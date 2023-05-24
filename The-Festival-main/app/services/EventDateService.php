<?php
require_once __DIR__ . '/../repositories/EventDateRepository.php';
class EventDateService
{
    private $eventDateRepository;
    public function __construct()
    {
        $this->eventDateRepository = new eventDateRepository();
    }


    public function getEventDateById($id)
    {
        return $this->eventDateRepository->getEventDateById($id);
    }
    
     public function getEventDateByIdWithUrl($id)
    {
        return $this->eventDateRepository->getEventDateByIdWithUrl($id);
    }
}
