<?php
require_once __DIR__ . '/../models/HistoryEvent/HistoryEvent.php';
require_once __DIR__ . '/EventRepository.php';

class HistoryEventRepository extends EventRepository
{
    public function getHistoryEventByEventId($eventId)
    {
        try {
            $stmt = $this->connection->prepare("SELECT event.eventId,event.eventName FROM event WHERE eventId = :eventId");
            $stmt->bindParam(':eventId', $eventId);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $dbRow = $stmt->fetch();
            return $this->createHistoryEventInstance($dbRow);
        } catch (PDOException $e) {
            echo $e;
        }
    }

    private function createHistoryEventInstance($dbRow)
    {
        try {
            $historyEvent = new HistoryEvent();
            $historyEvent->setEventId($dbRow['eventId']);
            $historyEvent->setEventName($dbRow['eventName']);
            $historyEvent->setHistoryTours($this->getToursByEventId($dbRow['eventId']));
            $historyEvent->setEventParagraphs($this->getEventParagraphsByEventId($dbRow['eventId']));
            $historyEvent->setEventImages($this->getEventImagesByEventId($dbRow['eventId']));
            return $historyEvent;
        } catch (Exception $e) {
            echo $e;
        }

    }

    public function getToursByEventId($eventId)
    {
        try {
            $stmt = $this->connection->prepare("SELECT eventDate.date, language.name, timetable.time, historytour.eventId, historytour.historyTourId
                                                    FROM eventdate
                                                    INNER JOIN timetable ON eventdate.eventDateId = timetable.eventDateId
                                                    INNER JOIN historytour ON historytour.timeTableId = timetable.timeTableId
                                                    INNER JOIN language ON language.languageId = historytour.languageId
                                                    WHERE historytour.eventId = :eventId
                                                    ORDER BY eventDate.date ASC, timetable.time ASC;");
            $stmt->bindParam(':eventId', $eventId);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result = $stmt->fetchAll();
            $historyTours = array();
            foreach ($result as $row) {
                $historyTours[] = $this->createHistoryTourInstance($row);
            }
            return $historyTours;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function getHistoryTourById($historyTourId)
    {
        try {
            $stmt = $this->connection->prepare("SELECT eventDate.date, language.name, timetable.time, historytour.eventId, historytour.historyTourId
FROM eventdate
INNER JOIN timetable ON eventdate.eventDateId = timetable.eventDateId
INNER JOIN historytour ON historytour.timeTableId = timetable.timeTableId
INNER JOIN language ON language.languageId = historytour.languageId
WHERE historytour.historyTourId = :historyTourId;");
            $stmt->bindParam(':historyTourId', $historyTourId);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result = $stmt->fetch();
            if (!$result) {
                return null; // history tour with the given ID was not found
            }
            return $this->createHistoryTourInstance($result);
        } catch (PDOException $e) {
            echo $e;
        }
    }

    /**
     * @throws Exception
     */
    private function createHistoryTourInstance($dbRow)
    {
        $historyTours = new HistoryTour();
        $historyTours->setHistoryTourId($dbRow['historyTourId']);
        $historyTours->setTourLanguage($dbRow['name']);
        $dateTime = $dbRow['date'] . '' . $dbRow['time'];
        $historyTours->setTourDate(new DateTime($dateTime));
        $timeString = $dbRow['time'];
        $time = DateTime::createFromFormat('H:i:s', $timeString);
        $historyTours->setTime($time);
        $historyTours->setHistoryTourLocations((array)$this->getHistoryTourLocationsByHistoryTourId($dbRow['historyTourId']));
        $historyTours->setDuration(90.00); //ToDO: get duration from database
        return $historyTours;
    }

    public function getHistoryTourLocationsByHistoryTourId($historyTourId)
    {
        try {
            $stmt = $this->connection->prepare("SELECT historyTourLocation.historyTourLocationId, historyTourLocation.locationId, historyTourLocation.locationInformation, historyTourLocation.historyP1, historyTourLocation.historyP2, location.locationName 
        FROM historyTourLocation 
        INNER JOIN location on location.locationId = historyTourLocation.locationId
        where historytourlocation.historytourId = :historyTourId");
            $stmt->bindParam(':historyTourId', $historyTourId);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result = $stmt->fetch();
            if (!empty($result)) {
                return $this->createHistoryTourLocations($result);
            }
            return null;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function getHistoryTourLocationByLocationId($locationId)
    {
        try {
            $stmt = $this->connection->prepare("SELECT historyTourLocation.historyTourLocationId, historyTourLocation.locationId, historyTourLocation.locationInformation, historyTourLocation.historyP1, historyTourLocation.historyP2, location.locationName 
        FROM historyTourLocation 
        INNER JOIN location on location.locationId = historyTourLocation.locationId
        where location.locationId = :locationId");
            $stmt->bindParam(':locationId', $locationId);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result = $stmt->fetch();
            if (!empty($result)) {
                return $this->createHistoryTourLocations($result);
            }
            return null;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    private function createHistoryTourLocations($row)
    {
        $historyTourLocations = new HistoryTourLocation();
        $historyTourLocations->setLocationName($row['locationName']);
        $historyTourLocations->setHistoryTourLocationId($row['historyTourLocationId']);
        $historyTourLocations->setTourLocation($this->getLocationById($row['historyTourLocationId']));
        $historyTourLocations->setLocationInfo($row['locationInformation']);
        $historyTourLocations->setHistoryP1($row['historyP1']);
        $historyTourLocations->setHistoryP2($row['historyP2']);
        $historyTourLocations->setTourImage($this->getHistoryTourLocationImagesByHistoryTourLocationId($row['historyTourLocationId']));
        return $historyTourLocations;
    }

    public function test($historyTourLocationId)
    {
        try {
            $stmt = $this->connection->prepare("SELECT historytourimage.historyTourLocationId, historytourimage.imageId, historytourimage.tourLocationImage, image.imageName
                                                    FROM historytourimage
                                                    JOIN image ON image.imageId = historytourimage.imageId where  historytourimage.historyTourLocationId = :historyTourLocationId;");
            $stmt->bindParam(':historyTourLocationId', $historyTourLocationId);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result = $stmt->fetchAll();
            if (!empty($result)) {
                return $this->getImagesWithKeyValue($result);
            }
            return null;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function getHistoryTourLocationImagesByHistoryTourLocationId($historyTourLocationId)
    {
        try {
            $stmt = $this->connection->prepare("SELECT historytourimage.historyTourLocationId, historytourimage.imageId, historytourimage.tourLocationImage, image.imageName
                                                    FROM historytourimage
                                                    JOIN image ON image.imageId = historytourimage.imageId where  historytourimage.historyTourLocationId = :historyTourLocationId;");
            $stmt->bindParam(':historyTourLocationId', $historyTourLocationId);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result = $stmt->fetchAll();
            if (!empty($result)) {
                return $this->getImagesWithKeyValue($result);
            }
            return null;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    private function getImagesWithKeyValue($result): array
    {
        $images = array();
        foreach ($result as $imageRow) {
            $imageName = $imageRow['imageName'];
            $imageSpec = $imageRow['tourLocationImage'];
            if (isset($images[$imageSpec])) { // storing images as key value pair in array
                $images[$imageSpec][] = $imageName;
            } else {
                $images[$imageSpec] = array($imageName);
            }
        }
        return $images;
    }
    public function deleteHistoryTourLocation($selectedLocationId){
        try {
            $stmt = $this->connection->prepare("DELETE FROM historytourlocation WHERE historyTourLocationId = :historyTourLocationId");
            $stmt->bindParam(':historyTourLocationId', $selectedLocationId);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function insertNewTourLocation($newTourLocation)
    {
        $address_id = $this->insertAddress($newTourLocation);
        $location_id = $this->insertLocation($newTourLocation, $address_id);
        $historyLocationId = $this->insertHistoryTourLocation($newTourLocation, $location_id);
        $this->insertHistoryTourImages($newTourLocation, $historyLocationId);
        return true;
    }

    private function insertAddress($newTourLocation)
    {
        $stmt = $this->connection->prepare("INSERT INTO address (streetName, country, houseNumber, postCode, city) VALUES (:streetName, :country, :houseNumber, :postCode, :city)");
        $stmt->bindValue(':streetName', $newTourLocation["tourStreetName"]);
        $stmt->bindValue(':country', $newTourLocation['tourCountry']);
        $stmt->bindValue(':houseNumber', $newTourLocation["tourStreetNumber"]);
        $stmt->bindValue(':postCode', $newTourLocation["tourPostCode"]);
        $stmt->bindValue(':city', $newTourLocation["tourCity"]);
        $stmt->execute();
        return $this->connection->lastInsertId();
    }

    private function insertLocation($newTourLocation, $address_id)
    {
        $stmt = $this->connection->prepare("INSERT INTO location (locationName, addressId) VALUES (:locationName, :addressId)");
        $stmt->bindValue(':locationName', $newTourLocation["tourLocationName"]);
        $stmt->bindValue(':addressId', $address_id);
        $stmt->execute();
        return $this->connection->lastInsertId();
    }

    private function insertHistoryTourLocation($newTourLocation, $location_id)
    {
        $query = "INSERT INTO historyTourLocation (locationId,locationInformation,historyP1,historyP2) VALUES ( :locationId, :locationInformation,:historyP1,:historyP2)";
        $historyLocationId = $this->executeQuery($query, array(':locationId' =>$location_id, ':locationInformation' => $newTourLocation['locationInformation'], ':historyP1' => $newTourLocation['tourDescription1'], ':historyP2' => $newTourLocation['tourDescription2']), false, true);
        if (!is_numeric($historyLocationId)) {
            throw new DatabaseQueryException("Error while inserting tour location");
        }
        return $historyLocationId;
    }

    private function insertHistoryTourImages($newTourLocation, $historyLocationId)
    {
        foreach ($newTourLocation['others'] as $key => $imageId) {
            $this->insertHistoryTourImageWithArtistIdAndImageId($historyLocationId, $imageId, 'Other');
        }
        $this->insertHistoryTourImageWithArtistIdAndImageId($historyLocationId, $newTourLocation['banner'], 'banner');
    }

    private function insertHistoryTourImageWithArtistIdAndImageId($historyTourLocationId, $imageId, $tourLocationImage)
    {
        $query = "INSERT INTO historyTourImage (historyTourLocationId,imageId,tourLocationImage) VALUES (:historyTourLocationId,:imageId,:tourLocationImage)";
        if (!$this->executeQuery($query, array(':historyTourLocationId' => $historyTourLocationId, ':imageId' => $imageId, ':tourLocationImage' => $tourLocationImage))) {
            // since it is an insert query so execute Query will return false if it was not inserted successFully
            throw new DatabaseQueryException("Error while inserting history location image");
        } // if it is false we have an error
    }

    public function checkEventDateExistence($eventDate)
    {
        try {
            $stmt = $this->connection->prepare("SELECT eventDateId, date FROM eventDate WHERE date = :date");
            $stmt->bindParam(':date', $eventDate);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                return $result['eventDateId'];
            }
            return false;

        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function checkLanguageExistence($language)
    {
        try {
            $stmt = $this->connection->prepare("SELECT languageId, name FROM language WHERE name = :name");
            $stmt->bindParam(':name', $language);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                return $result['languageId'];
            }
            return false;

        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function checkTourTimeTableExistence($eventDateId, $timeTable)
    {
        try {
            $stmt = $this->connection->prepare("SELECT eventDateId, timeTableId, time FROM timetable WHERE time = :time AND eventDateId = :eventDateId;");
            $stmt->bindParam(':time', $timeTable);
            $stmt->bindParam(':eventDateId', $eventDateId);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                return $result['timeTableId'];
            }
            return null;

        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function insertNewHistoryTour($newHistoryTour)
    {
// Prepare and execute first query
        $stmt1 = $this->connection->prepare("INSERT INTO eventDate (date) VALUES (:date)");

        $stmt1->bindValue(':date', $newHistoryTour["newTourDate"]);

        $stmt1->execute();

        // Get the last inserted ID from the previous query
        $eventDateId = $this->connection->lastInsertId();

        // Prepare and execute second query
        $stmt2 = $this->connection->prepare("INSERT INTO timetable (eventDateId, time) VALUES (:eventDateId, :time)");

        $stmt2->bindValue(':eventDateId', $eventDateId);
        $stmt2->bindValue(':time', $newHistoryTour["newTourTime"]);

        $stmt2->execute();

        // Get the last inserted ID from the previous query
        $timeTableId = $this->connection->lastInsertId();

        // Prepare and execute third query
        $stmt3 = $this->connection->prepare("INSERT INTO language (name) VALUES (:name)");
        $stmt3->bindValue(':name', $newHistoryTour["newTourLanguage"]);

        $stmt3->execute();

        // Get the last inserted ID from the previous query
        $languageId = $this->connection->lastInsertId();

        // Prepare and execute third query
        $stmt4 = $this->connection->prepare("INSERT INTO historytour (eventId, languageId, timeTableId) VALUES (:eventId, :languageId, :timeTableId)");
        $stmt4->bindValue(':eventId', 1);
        $stmt4->bindValue(':languageId', $languageId);
        $stmt4->bindValue(':timeTableId', $timeTableId);

        $stmt4->execute();
    }

    public function insertNewEventDate($eventDate)
    {
        $stmt = $this->connection->prepare("INSERT INTO eventDate (date) VALUES (:date)");
        $stmt->bindValue(':date', $eventDate);
        $stmt->execute();
    }

    public function insertNewTimeTable($eventDateId, $time)
    {
        $stmt = $this->connection->prepare("INSERT INTO timetable (eventDateId, time) VALUES (:eventDateId, :time)");
        $stmt->bindValue(':eventDateId', $eventDateId);
        $stmt->bindValue(':time', $time);
        $stmt->execute();
    }

    public function getEventDateId($eventDate)
    {
        $stmt = $this->connection->prepare("SELECT id FROM eventDate WHERE date = :date LIMIT 1");
        $stmt->bindValue(':date', $eventDate);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? $result['id'] : null;
    }

    public function insertNewLanguage($language)
    {
        $stmt = $this->connection->prepare("INSERT INTO language (name) VALUES (:name)");
        $stmt->bindValue(':name', $language);
        $stmt->execute();
    }

    public function getLanguageId($language)
    {
        $stmt = $this->connection->prepare("SELECT id FROM language WHERE name = :name LIMIT 1");
        $stmt->bindValue(':name', $language);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? $result['id'] : null;
    }

    private function checkDataExistence($stmt): bool
    {
        try {
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            $message = '[' . date("F j, Y, g:i a e O") . ']' . $e->getMessage() . $e->getCode() . $e->getFile() . ' Line ' . $e->getLine() . PHP_EOL;
            error_log("Database connection failed: " . $message, 3, __DIR__ . "/../Errors/error.log");
            http_response_code(500);
            exit();
        }
    }

    public function insertNewTourTest($languageId, $timeTableId)
    {
        try{
            $stmt = $this->connection->prepare("INSERT INTO historytour (eventId, languageId, timeTableId) VALUES (:eventId, :languageId, :timeTableId)");
            $stmt->bindValue(':eventId', 1);
            $stmt->bindValue(':languageId', $languageId);
            $stmt->bindValue(':timeTableId', $timeTableId);
            $stmt->execute();
        }
        catch (PDOException $e) {
            echo $e;
        }

    }

    public function deleteHistoryTour($selectedTourId)
    {
        try{
            $stmt = $this->connection->prepare("DELETE FROM historytour WHERE historyTourId = :historyTourId;");
            $stmt->bindValue(':historyTourId', $selectedTourId);
            $stmt->execute();
        }
        catch (PDOException $e) {
            echo $e;
        }

    }

    public function getSelectedTourById($selectedTourId)
    {
        $stmt = $this->connection->prepare("DELETE FROM historytour WHERE historyTourId = :historyTourId;");
        $stmt->bindValue(':historyTourId', $selectedTourId);
        $stmt->execute();
    }

    public function updateHistoryTourByTourId($selectedTourId, $updateHistoryTour)
    {
        $stmt = $this->connection->prepare("UPDATE historytour
                                                    INNER JOIN timetable ON historytour.timeTableId = timetable.timeTableId
                                                    INNER JOIN eventdate ON timetable.eventDateId = eventdate.eventDateId
                                                    INNER JOIN language ON historytour.languageId = language.languageId
                                                    SET eventdate.date = :date, language.name = :name, timetable.time = :time
                                                    WHERE historytour.historyTourId = :historyTourId");

        $stmt->bindValue(':date', $updateHistoryTour["updateTourDate"]);
        $stmt->bindValue(':name', $updateHistoryTour["updateTourLanguage"]);
        $stmt->bindValue(':time', $updateHistoryTour["updateTourTime"]);

        $stmt->bindParam(':historyTourId', $selectedTourId);
        $stmt->execute();
    }

}

