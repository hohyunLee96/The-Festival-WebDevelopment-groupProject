<?php
require_once __DIR__ . '/../models/DanceEvent/Performance.php';
require_once __DIR__ . '/../models/DanceEvent/PerformanceSession.php';
require_once __DIR__ . '/EventRepository.php';
require_once __DIR__ . '/../services/ArtistService.php';
require_once __DIR__ . '/../models/Exceptions/DatabaseQueryException.php';

class PerformanceRepository extends EventRepository
{
    private ArtistService $artistService;

    public function __construct()
    {
        parent::__construct();
        $this->artistService = new ArtistService();
    }

    public function getPerformancesByEventId($eventId): ?array
    {
        $query = "SELECT Performance.PerformanceId,Performance.venueId,timetable.time,eventDate.date,
                  performance.sessionId,Performance.duration,performance.totalTickets,performance.availableTickets,
                  performance.totalPrice
                FROM Performance
                join timetable on Performance.timetableId = timetable.timetableId
                join eventdate on timetable.eventDateId = eventdate.eventDateId
                WHERE performance.eventID = :eventId
                ORDER BY eventdate.date ASC,timetable.time ASC";
        $result = $this->executeQuery($query, array(':eventId' => $eventId));
        if (empty($result)) {
            return null;
        }
        $artistPerformances = array();
        foreach ($result as $row) {
            $artistPerformances[] = $this->createPerformanceInstance($row);
        }
        return $artistPerformances;

    }

    private function createPerformanceInstance($dbRow): Performance
    {
        try {
            $performance = new Performance();
            $performance->setPerformanceId($dbRow['PerformanceId']);
            $performance->setArtists($this->artistService->getAllParticipatingArtistsInPerformance($dbRow['PerformanceId']));
            $dateTime = $dbRow['date'] . '' . $dbRow['time'];
            $performance->setDate(new DateTime($dateTime));
            $performance->setSession($this->getPerformanceSessionById($dbRow['sessionId']));
            $performance->setVenue($this->getLocationById($dbRow['venueId']));
            $performance->setDuration($dbRow['duration']);
            $performance->setTotalTickets($dbRow['totalTickets']);
            $performance->setAvailableTickets($dbRow['availableTickets']);
            $performance->setTotalPrice($dbRow['totalPrice']);
            return $performance;
        } catch (Exception $e) {
            echo "Error while creating artist performance instance: " . $e->getMessage();
        }

    }

    public function getAllPerformancesDoneByArtistIdAtEvent($artistId, $eventName): ?array
    {

        $query = "SELECT Performance.PerformanceId,Performance.venueId,timetable.time,eventDate.date,
                    performance.sessionId,performance.duration,performance.totalTickets
                    ,performance.availableTickets, performance.totalPrice
            FROM Performance
            join timetable on Performance.timetableId = timetable.timetableId
            join eventdate on timetable.eventDateId = eventdate.eventDateId
            join participatingartist on participatingartist.PerformanceId = Performance.PerformanceId
            join event on event.eventId = Performance.eventId
            WHERE participatingartist.artistId = :artistId AND event.eventName = :eventName
            ORDER BY eventdate.date ASC,timetable.time ASC";
        $result = $this->executeQuery($query, array(':artistId' => $artistId, ':eventName' => $eventName));
        if (empty($result)) {
            return null;
        }
        $artistPerformances = array();
        foreach ($result as $row) {
            $artistPerformances[] = $this->createPerformanceInstance($row);
        }
        return $artistPerformances;
    }

    public function getPerformanceSessionById($performanceSessionId)
    {
        try {
            $stmt = $this->connection->prepare("SELECT performanceSessionId ,sessionName,sessionDescription FROM performancesession WHERE performanceSessionId = :performanceSessionId");
            $stmt->execute(array(':performanceSessionId' => $performanceSessionId));
            $stmt->setFetchMode(PDO::FETCH_CLASS, 'PerformanceSession');
            return $stmt->fetch();
        } catch (PDOException $e) {
            echo "Error while getting artist performance session by id: " . $e->getMessage();
        }
    }

    public function getAllPerformanceSessions()
    {
        try {
            $stmt = $this->connection->prepare("SELECT performanceSessionId ,sessionName,sessionDescription FROM performancesession");
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS, 'PerformanceSession');
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            echo "Error while getting all artist performance sessions: " . $e->getMessage();
        }
    }

    /**
     * @throws DatabaseQueryException
     */
    public function addPerformanceWithEventId($eventId, $data): bool
    {
        $query = "INSERT INTO Performance (eventId,venueId,timetableId,sessionId,duration,totalPrice,totalTickets,availableTickets) 
                  VALUES (:eventId,:venueId,:timetableId,:sessionId,:duration,:totalPrice,:totalTickets,ROUND(:totalTickets * 0.9))";
        // 90 percent of total tickets are available to sell single Ticket
        $timetableID = $this->getTimetableIdByDateAndTime($data['performanceDate'], $data['startTime']);
        $parameters = array(':eventId' => $eventId,
            ':venueId' => $data['Venue'],
            ':timetableId' => $timetableID,
            ':sessionId' => $data['performanceSession'],
            ':duration' => $data['duration'],
            ':totalPrice' => $data['totalPrice'],
            ':totalTickets' => $data['totalTickets']);
        $performanceID = $this->executeQuery($query, $parameters, false, true);
        if (!is_numeric($performanceID)) {
            throw new DatabaseQueryException("Error while inserting performance in Database");
        }
        foreach ($data['artists'] as $artistId) {
            if (!$this->insertParticipatingArtists($artistId, $performanceID)) {
                throw new DatabaseQueryException("Error while inserting participating artists, bUt you can always Edit thea participating artists later.");
            }
        }
        return true;
    }

    public function insertParticipatingArtists($artistId, $performanceId)
    {
        $query = "INSERT INTO participatingartist (artistId,performanceId) VALUES (:artistId,:performanceId)";
        $parameters = array(':artistId' => $artistId, ':performanceId' => $performanceId);
        return $this->executeQuery($query, $parameters); // it returns bool value
    }

    public function deletePerformance($performanceId)
    {
        $query = "DELETE FROM Performance WHERE performanceId = :performanceId";
        $parameters = array(':performanceId' => $performanceId);
        return $this->executeQuery($query, $parameters);
    }

    public function isLocationAvailableAtTime($locationId, $date, $time): bool
    {
        $query = "SELECT performance.performanceId
                  FROM performance
                  JOIN timetable on performance.timeTableId = timeTable.timeTableId
                  JOIN eventDate on timeTable.eventDateId = eventDate.eventDateId
                  WHERE performance.venueId = :locationId AND eventDate.date = :date AND
                  :startTime <= DATE_ADD(timetable.time, INTERVAL performance.duration MINUTE)";
        $parameters = array(':locationId' => $locationId, ':date' => $date, ':startTime' => $time);
        $result = $this->executeQuery($query, $parameters);

        if (empty($result)) {
            return true;
        }
        return false;
    }

    public function getVenueById($locationId): ?Location
    {
        return $this->getLocationById($locationId);
    }
    public function getPerformanceById($performanceId): ?Performance
    {
        $query = "SELECT Performance.PerformanceId,Performance.venueId,timetable.time,eventDate.date,
                performance.sessionId,performance.duration, performance.totalPrice,performance.totalTickets
                    ,performance.availableTickets
            FROM Performance
            join timetable on Performance.timetableId = timetable.timetableId
            join eventdate on timetable.eventDateId = eventdate.eventDateId
            WHERE Performance.PerformanceId = :performanceId";
        $result = $this->executeQuery($query, array(':performanceId' => $performanceId),false);
        if (empty($result)) {
            return null;
        }
        return $this->createPerformanceInstance($result);
    }
    public function isPerformanceDetailsSameInDb($performanceDetails): bool
    {
        $query = "SELECT performance.performanceId FROM performance 
                 WHERE  performance.venueId = :venueId 
                 AND performance.sessionId = :sessionId AND performance.duration = :duration
                 AND performance.totalPrice = :totalPrice AND performance.totalTickets = :totalTickets 
                  AND performance.availableTickets = :availableTickets ";
        $parameters = array(':venueId' => $performanceDetails->venueId, ':sessionId' => $performanceDetails->sessionId,
            ':duration' => $performanceDetails->duration, ':totalPrice' => $performanceDetails->totalPrice,
            ':totalTickets' => $performanceDetails->totalTickets, ':availableTickets' => $performanceDetails->availableTickets);
        if (empty($this->executeQuery($query, $parameters))) {
            return false;
        }
        return true;
    }

    public function isPerformanceVenueSame($performanceId, $venueId): bool
    {
        $query = "SELECT performance.performanceId FROM performance 
                 WHERE performance.performanceId = :performanceId AND  performance.venueId = :venueId";
        $parameters = array(':performanceId' => $performanceId, ':venueId' => $venueId);
        if (empty($this->executeQuery($query, $parameters))) {
            return false;
        }
        return true;
    }

    public function removeParticipatingArtist($performanceId, $artistId)
    {
        $query = "DELETE FROM participatingartist WHERE participatingartist.performanceId = :performanceId AND participatingartist.artistId = :artistId";
        $parameters = array(':performanceId' => $performanceId, ':artistId' => $artistId);
        return $this->executeQuery($query, $parameters);
    }

    public function addParticipatingArtist($performanceId, $artistId)
    {
        $query = "INSERT INTO participatingartist (artistId,performanceId) VALUES (:artistId,:performanceId)";
        $parameters = array(':artistId' => $artistId, ':performanceId' => $performanceId);
        return $this->executeQuery($query, $parameters);
    }

    public function isPerformanceDateTimeSame($performanceDate, $startTime, $performanceId): bool
    {
        $query = "SELECT performance.performanceId
                 From performance
                 Where performance.performanceId = :performanceId AND performance.timetableid=
                 (SELECT timeTableId FROM timetable
                  JOIN eventDate ON eventDate.eventDateId = timetable.EventDateId
                   WHERE eventDate.date = :date AND time = :time)";
        $parameters = array(':date' => $performanceDate, ':time' => $startTime, ':performanceId' => $performanceId);
        if (empty($this->executeQuery($query, $parameters))) {
            return false;
        }
        return true;
    }

    public function getParticipatingArtistsIds($performanceId): ?array
    {
        $query = "SELECT participatingartist.artistId FROM participatingartist WHERE participatingartist.performanceId = :performanceId";
        $parameters = array(':performanceId' => $performanceId);
        $result = $this->executeQuery($query, $parameters);
        $artistIds = array();
        foreach ($result as $row) {
            $artistIds[] = $row['artistId'];
        }
        return $artistIds;
    }

    public function getTimetableIdByDateAndTime($date, $time)
    {
        return parent::getTimetableIdByDateAndTime($date, $time);
    }

    public function updatePerformance($performance)
    {
        $query = "UPDATE performance SET performance.venueId = :venueId, performance.sessionId = :sessionId, 
                       performance.duration = :duration, performance.totalPrice = :totalPrice, 
                   performance.totalTickets = :totalTickets, performance.availableTickets = :availableTickets";
        $parameters = array(':venueId' => $performance->venueId, ':sessionId' => $performance->sessionId, ':duration' => $performance->duration, ':totalPrice' => $performance->totalPrice, ':totalTickets' => $performance->totalTickets, ':availableTickets' => $performance->availableTickets);

        if (!empty($performance->timetableId)) {
            $query .= ", performance.timetableId = :timetableId";
            $parameters[':timetableId'] = $performance->timetableId;
        }
        $query .= " WHERE performance.performanceId = :performanceId";
        $parameters[':performanceId'] = $performance->id;

        return $this->executeQuery($query, $parameters);
    }


}