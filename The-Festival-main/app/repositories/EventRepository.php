<?php
require_once __DIR__ . '/repository.php';
require_once __DIR__ . '/../services/DanceEventService.php';
require_once __DIR__ . '/../services/HistoryService.php';
require_once __DIR__ . '/HistoryEventRepository.php';
require_once __DIR__ . '/../models/Address.php';
require_once __DIR__ . '/../models/Exceptions/DatabaseQueryException.php';

class EventRepository extends repository
{
    public function getEventByName($name)
    {
        try {
            $stmt = $this->connection->prepare("SELECT eventId,eventName FROM event WHERE eventName = :name");
            $stmt->bindParam(':name', $name);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result = $stmt->fetch();
            return $this->getEventObjectAccordingToEventName($result['eventName'], $result['eventId']);
        } catch (PDOException $e) {
            echo $e;
        }
    }

    private function getEventObjectAccordingToEventName($name, $eventId)
    {
        //Todo: make it dynamic withe the help of the database
        $service = null;
        switch ($name) {
            case 'Dance':
                $service = new DanceEventService();
                return $service->getDanceEventByEventId($eventId);

            case 'A Stroll Through History':
                $service = new HistoryService();
                return $service->getHistoryEventByEventId($eventId);
        }
    }

    private function getAllEventsName()
    {
        try {
            $stmt = $this->connection->prepare("SELECT eventName FROM event");
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            echo $e;
        }
    }

    protected function getLocationById($locationId)
    {
        try {
            $query = "SELECT location.locationId,location.locationName,location.addressId
                                                    FROM location WHERE location.locationId = :locationId";
            $result = $this->executeQuery($query, [':locationId' => $locationId], false);
            if (!empty($result)) {
                return $this->createLocationInstance($result);
            }
            return null;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    protected function createLocationInstance($dbRow): Location
    {
        $location = new Location();
        $location->setLocationId($dbRow['locationId']);
        $location->setLocationName($dbRow['locationName']);
        $location->setAddress($this->getAddressById($dbRow['addressId']));
        return $location;
    }

    private function getAddressById($addressId)
    {
        try {
            $query = "SELECT address.addressId, address.postCode, address.streetName, address.houseNumber, address.houseNumberAdditional,
                       address.city, address.country
                        FROM address WHERE address.addressId = :addressId";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':addressId', $addressId);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Address');
            return $stmt->fetch();

        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function getAllLocations(): ?array
    {
        $query = "SELECT location.locationId,location.locationName, location.addressId FROM location";
        $result = $this->executeQuery($query);
        if (!empty($result)) {
            $locations = [];
            foreach ($result as $row) {
                $locations[] = $this->createLocationInstance($row);
            }
            return $locations;
        }
        return null;
    }

    //checks if the date exist or not if exi

    /**
     * @throws DatabaseQueryException
     */
    private function getEventDateIdByInsertingDate($date)
    {
        $query = "SELECT eventDateId FROM EventDate WHERE date = :date";
        $result = $this->executeQuery($query, array(':date' => $date), false); // date exist it is going to return us date
        if (empty($result)) {
            $query = "INSERT INTO EventDate (date) VALUES (:date)";
            $executedResult = $this->executeQuery($query, array(':date' => $date), false, true);
            if (is_bool($executedResult)) { // if it is bools means that it was not inserted into the database
                throw new DatabaseQueryException("Error while inserting date into database");
            }
            return $executedResult; // it is going to return us the id of the date that we just inserted
        }
        return $result['eventDateId']; // returns the id of the Event Date
    }

    /**
     * @throws DatabaseQueryException
     */
    private function getTimetableIDByInsertingTimeWithDateId($time, $eventDateId)
    {
        $query = "SELECT timeTableId FROM timetable WHERE time = :time AND eventDateId = :eventDateId";
        $result = $this->executeQuery($query, array(':time' => $time, ':eventDateId' => $eventDateId), false);
        if (empty($result)) {
            $query = "INSERT INTO timetable (time,eventDateId) VALUES (:time,:eventDateId)";
            $executedResult = $this->executeQuery($query, array(':time' => $time, ':eventDateId' => $eventDateId), false, true);
            if (is_bool($executedResult)) { // if it is bool means that it was not inserted into the database
                throw new DatabaseQueryException("Error while inserting time into database");
            }
            return $executedResult; // it is going to return us the id of the date that we just inserted
        }
        return $result['timeTableId']; // returns the id of the timetable
    }

    /**
     * @throws DatabaseQueryException
     */
    protected function getTimetableIdByDateAndTime($date, $time)
    { // this function will give us timetable Id by Date and time
        $eventDateId = $this->getEventDateIdByInsertingDate($date);
        return $this->getTimetableIDByInsertingTimeWithDateId($time, $eventDateId);
    }

    protected function getEventParagraphsByEventID($eventID)
    {
        $query = "SELECT paragraph.paragraphId,paragraph.title, paragraph.text 
                FROM paragraph 
                JOIN eventparagraph ON paragraph.paragraphId = eventparagraph.paragraphId
                WHERE eventId = :eventId";
        $result = $this->executeQuery($query, array(':eventId' => $eventID));
        if (!empty($result)) {
            $paragraphs = [];
            foreach ($result as $row) {
                $paragraphs[] = new Paragraph($row['paragraphId'], $row['title'], $row['text']);
            }
            return $paragraphs;
        }
        return null;
    }

    protected function getEventImagesByEventID($eventID): ?array
    {
        $query = "SELECT image.imageName,eventimage.specification as imageSpecification
                FROM image
                JOIN eventimage ON eventimage.imageId = image.imageId
                where eventimage.eventId = :eventId";
        $result = $this->executeQuery($query, array(':eventId' => $eventID));
        if (!empty($result)) {
            return $this->getImagesWithKeyValue($result);
        }
        return null;
    }

    private function getImagesWithKeyValue($result): array
    {
        $images = array();
        foreach ($result as $imageRow) {
            $imageName = $imageRow['imageName'];
            $imageSpec = $imageRow['imageSpecification'];
            if (isset($images[$imageSpec])) { // storing images as key value pair in array
                $images[$imageSpec][] = $imageName;
            } else {
                $images[$imageSpec] = array($imageName);
            }
        }
        return $images;
    }

    public function getLocationIdByAddressId($addressId)
    {
        $query = "SELECT locationId FROM location WHERE addressId = :addressId";
        $result = $this->executeQuery($query, array(':addressId' => $addressId), false);
        if (!empty($result)) {
            return $result['locationId'];
        }
        return null;
    }

    /**
     * @throws DatabaseQueryException
     */
    public function getOrCreateAddressId($postcode, $streetName, $houseNumber, $city, $country, $houseNumberAdditional = null)
    {
        $query = "SELECT addressId FROM address WHERE postCode = :postCode AND streetName = :streetName AND houseNumber = :houseNumber  
                                AND city = :city AND country = :country";
        $params = array(':postCode' => $postcode, ':streetName' => $streetName, ':houseNumber' => $houseNumber,
            ':city' => $city, ':country' => $country);
        if (!empty($houseNumberAdditional)) {
            $query .= " AND houseNumberAdditional = :houseNumberAdditional";
            $params[':houseNumberAdditional'] = $houseNumberAdditional;
        }
        $result = $this->executeQuery($query, $params, false);
        if (empty($result)) {
            return $this->insertAddressAndGetId($postcode, $streetName, $houseNumber, $city, $country, $houseNumberAdditional);
        }
        return $result['addressId'];
    }

    /**
     * @throws DatabaseQueryException
     */

    protected function insertAddressAndGetId($postcode, $streetName, $houseNumber, $city, $country, $houseNumberAdditional = null)
    {
        $query = "INSERT INTO address (postCode,streetName,houseNumber,city,country) VALUES (:postCode,:streetName,:houseNumber,:city,:country)";
        $parameters = array(':postCode' => $postcode, ':streetName' => $streetName, ':houseNumber' => $houseNumber, ':city' => $city, ':country' =>
            $country);
        if (!empty($houseNumberAdditional)) {
            $query = "INSERT INTO address (postCode,streetName,houseNumber,houseNumberAdditional,city,country) 
                          VALUES (:postCode,:streetName,:houseNumber,:houseNumberAdditional,:city,:country)";
            $parameters[':houseNumberAdditional'] = $houseNumberAdditional;
        }
        $executedResult = $this->executeQuery($query, $parameters, false, true);
        if (is_numeric($executedResult)) {
            return $executedResult; // it is going to return us the id of the date that we just inserted
        }
        throw new DatabaseQueryException("Error while inserting address into database"); // if nothing is returned means something went wrong
    }

    public function addLocation($locationName, $addressId)
    {
        $query = "INSERT INTO location (locationName,addressId) VALUES (:locationName,:addressId)";
        return $this->executeQuery($query, array(':locationName' => $locationName, ':addressId' => $addressId));
        // since it is an insert query it will return us bool if it was inserted or not
    }

    public function checkLocationExistence($locationName): bool
    {
        $query = "SELECT locationId FROM location WHERE locationName = :locationName"; // since location should be Unique According to db design
        $result = $this->executeQuery($query, array(':locationName' => $locationName));
        if (empty($result)) {
            return false;
        }
        return true;
    }

    protected function deleteLocation($locationId): bool
    {
        $query = "DELETE FROM location WHERE locationId = :locationId";
        return $this->executeQuery($query, [':locationId' => $locationId]); // since it is an delete query it will return us bool if it was deleted or not
    }

    protected function isUpdatingLocationDetailSame(Location $location): bool
    {
        $query = "SELECT locationId FROM location WHERE locationName = :locationName AND locationId = :locationId";
        $result = $this->executeQuery($query, array(':locationName' => $location->getLocationName(), ':locationId' => $location->getLocationId()));
        if (!empty($result)) {
            return true;
        }
        return false;
    }

    protected function isUpdatingAddressDetailSame(Address $address): bool
    {
        $query = "SELECT addressId FROM address WHERE postCode = :postCode AND streetName = :streetName AND houseNumber = :houseNumber  
                            AND city = :city AND country = :country AND addressId = :addressId";
        $params = array(':postCode' => $address->getPostCode(), ':streetName' => $address->getStreetName(), ':houseNumber' => $address->getHouseNumber(),
            ':city' => $address->getCity(), ':country' => $address->getCountry(), ':addressId' => $address->getAddressId());
        if (empty($address->getHouseNumberAdditional())) {
            $query .= " AND (houseNumberAdditional IS NULL OR houseNumberAdditional = '')";
        } else {
            $query .= " AND houseNumberAdditional = :houseNumberAdditional";
            $params[':houseNumberAdditional'] = $address->getHouseNumberAdditional();
        }
        $result = $this->executeQuery($query, $params);
        if (empty($result)) {
            return false;
        }
        return true;
    }

    protected function updateLocation(Location $location)
    {
        $query = "UPDATE location SET locationName = :locationName WHERE locationId = :locationId";
        return $this->executeQuery($query, [':locationName' => $location->getLocationName(), ':locationId' => $location->getLocationId()]);
    }

    protected function updateAddress(Address $address)
    {
        $query = "UPDATE address SET postCode = :postCode, streetName = :streetName, houseNumber = :houseNumber, 
              city = :city, country = :country, houseNumberAdditional = NULLIF(:houseNumberAdditional, '') 
              WHERE addressId = :addressId";
        $params = array(':postCode' => $address->getPostCode(), ':streetName' => $address->getStreetName(),
            ':houseNumber' => $address->getHouseNumber(),
            ':city' => $address->getCity(), ':country' => $address->getCountry(), ':addressId' => $address->getAddressId(),
            ':houseNumberAdditional' => $address->getHouseNumberAdditional());
        return $this->executeQuery($query, $params);
    }

    protected function getVatPercentageByEventName($eventName)
    {
        $query = "SELECT vat.vatPercentage FROM event 
                     join vat on event.vatId = event.vatId
                     WHERE eventName = :eventName";
        $result = $this->executeQuery($query, array(':eventName' => $eventName), false);
        if (!empty($result)) {
            return $result['vatPercentage'];
        }
        return null;
    }
}