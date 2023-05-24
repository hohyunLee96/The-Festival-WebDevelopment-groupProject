<?php
require_once __DIR__ . '/../models/DanceEvent/DanceEvent.php';
require_once __DIR__ . '/../services/ArtistService.php';
require_once __DIR__ . '/../services/PerformanceService.php';
require_once __DIR__ . '/EventRepository.php';

class DanceEventRepository extends EventRepository
{
    private ArtistService $artistService;
    private PerformanceService $performanceService;

    public function __construct()
    {
        parent::__construct();
        $this->artistService = new ArtistService();
        $this->performanceService = new PerformanceService();
    }

    public function getDanceEventByEventId($eventId): ?DanceEvent
    {
        $query = "SELECT event.eventId,event.eventName,vat.vatPercentage FROM event
                  JOIN vat on event.vatId = vat.vatId
                  WHERE eventId = :eventId";
        $result = $this->executeQuery($query, array(':eventId' => $eventId), false);
        if (!empty($result)) {
            return $this->createDanceEventInstance($result);
        }
        return null;
    }

    private function createDanceEventInstance($dbRow): DanceEvent
    {
        try {
            $danceEvent = new DanceEvent();
            $danceEvent->setEventId($dbRow['eventId']);
            $danceEvent->setEventName($dbRow['eventName']);
            $danceEvent->setPerformances($this->performanceService->getPerformancesByEventId($dbRow['eventId']));
            $danceEvent->setEventParagraphs($this->getEventParagraphsByEventID($dbRow['eventId']));
            $danceEvent->setEventImages($this->getEventImagesByEventId($dbRow['eventId']));
            $danceEvent->setEventVatPercentage($dbRow['vatPercentage']);
            $danceEventPriceRange = $this->getMinimalAndMaximumPrice($dbRow['eventId']);
            $danceEvent->setMinimalPrice($danceEventPriceRange['minimumPrice']);
            $danceEvent->setMaximalPrice($danceEventPriceRange['maximumPrice']);
            return $danceEvent;
        } catch (Exception $e) {
            echo "Error while creating dance event instance: " . $e->getMessage();
        }
    }
    private function getMinimalAndMaximumPrice($eventId): array
    {
        $query = "SELECT MIN(performance.totalPrice) as minimumPrice, MAX(performance.totalPrice) as maximumPrice FROM performance WHERE performance.eventId = :eventId";
        $result = $this->executeQuery($query, array(':eventId' => $eventId), false);
        if (!empty($result)) {
            return array('minimumPrice'=>$result['minimumPrice'],'maximumPrice'=>$result['maximumPrice']);
        }
        return array('minimumPrice'=>0, 'maximumPrice'=>0); // if there are no price only
    }

    public function deleteVenue($venueId): bool
    {
        return $this->deleteLocation($venueId);
    }

    public function isPerformanceVenue($venueId): bool
    {
        $query = "SELECT performance.performanceId FROM performance WHERE performance.venueId = :venueId";
        $result = $this->executeQuery($query, array(':venueId' => $venueId));
        if (empty($result)) {
            return false;
        }
        return true;
    }

    public function getVenueById($venueId): ?Location
    {
        return $this->getLocationById($venueId);
    }

    public function isUpdatingVenueDetailSame($venue): bool
    {
        return $this->isUpdatingLocationDetailSame($venue);
    }

    public function isUpdatingVenueAddressSame($address): bool
    {
        return $this->isUpdatingAddressDetailSame($address);
    }

    public function updateVenueDetail($venue): bool
    {
        return $this->updateLocation($venue);
    }

    public function updateVenueAddress(Address $address): bool
    {
        return $this->updateAddress($address);
    }
}
