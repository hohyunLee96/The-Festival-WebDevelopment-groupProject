<?php
require_once __DIR__ . '/../controller.php';
require_once __DIR__ . '/../../services/EventPageService.php';
require_once __DIR__. '/../../services/EventService.php';
 abstract class EventController extends controller
{
    protected eventPageService $eventPageService;
    protected EventService $eventService;
    protected function __construct()
    {
        parent::__construct();
        $this->eventPageService = new EventPageService();
        $this->eventService = new EventService();
    }
    protected function getImageFullPath($imageName): string
    {
        $directory = strtolower(substr(get_class($this), 0, -10));
        return "/image/Festival/$directory/".$imageName;
    }
    protected function display404PageNotFound() : void // overwritting the controller method
    {
        require_once __DIR__ . '/../../views/PageNotFound.html';
    }
    protected function getGoogleDirectionsLink( Address $address): string
    {
        $addressString=$address->getHouseNumber()." ". $address->getStreetName() . " " . $address->getPostCode() . " "  . $address->getCity() . " " . $address->getCountry();
        return "https://www.google.com/maps/dir/?api=1&destination=$addressString";
    }
}