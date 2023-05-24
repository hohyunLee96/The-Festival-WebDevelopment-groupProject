<?php
require_once __DIR__ . '/../repositories/EventRepository.php';
class EventService
{
    private $eventRepository;
//    private $eventPageRepository;
    public function __construct()
    {
        $this->eventRepository = new EventRepository();
//        $this->eventPageRepository = new EventPageRepository();

    }

    public function getEventByName($name)
    {
        return $this->eventRepository->getEventByName($name);
    }

    public function getAllLocations()
    {
        return $this->eventRepository->getAllLocations();
    }

    public function getParagraphByEventId($eventId)
    {
        return $this->eventPageRepository->getParagraphByEventId($eventId);
    }

    /**
     * @throws DatabaseQueryException
     */
    public function addLocation($data)
    {
        if($this->eventRepository->checkLocationExistence($data['locationName'])){
            throw new DatabaseQueryException("Location already exists with {$data['locationName']}");
        }
        if (!empty($data['houseNumberAdditional'])) {
            $addressId = $this->eventRepository->getOrCreateAddressId($data['postCode'], $data['streetName'], $data['houseNumber'], $data['city'], $data['country'], $data['houseNumberAdditional']);
        } else {
            $addressId = $this->eventRepository->getOrCreateAddressId($data['postCode'], $data['streetName'], $data['houseNumber'], $data['city'], $data['country']);
        }
        return $this->eventRepository->addLocation($data['locationName'], $addressId);
    }
}