<?php
require_once __DIR__ . '/../repositories/EventPageRepository.php';

class EventPageService
{
    private $eventPageRepository;

    public function __construct()
    {
        $this->eventPageRepository = new EventPageRepository();
    }

    public function getEventPageByName($name)
    {
        return $this->eventPageRepository->getEventPageByName($name);
    }
}