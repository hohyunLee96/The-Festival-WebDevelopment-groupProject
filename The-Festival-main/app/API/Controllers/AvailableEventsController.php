<?php
require __DIR__ . '/../../Services/eventDateService.php';
require __DIR__ . '/../../Services/availableEventService.php';
require __DIR__ . '/ApiController.php';
class AvailableEventsController extends ApiController
{
    private $eventDateService;

    private $availableEventService;

    public function __construct()
    {
        $this->eventDateService = new EventDateService();
        $this->availableEventService = new AvailableEventService();
    }

    public function retrieveAvailableEventData()
    {
        try {
            $this->sendHeaders();
            $availableEvent = NULL;

            if (!empty($_GET['id'])) {
                $id = htmlspecialchars($_GET['id']);
                $availableEvent = $this->availableEventService->getAvailableEventByIdWithUrl($id);
            }
            echo Json_encode($availableEvent);
        } catch (InvalidArgumentException | Exception $e) {
            http_response_code(500); // sending bad request error to APi request if something goes wrong
            echo $e->getMessage();
        }
    }


    public function retrieveParticipatingArtistsData()
    {
        try {
            $this->sendHeaders();
            $participatingArtists = NULL;

            if (!empty($_GET['id'])) {
                $id = htmlspecialchars($_GET['id']);
                $participatingArtists = $this->availableEventService->retrieveParticipatingArtistsDataWithUrl($id);
            }
            echo Json_encode($participatingArtists);
        } catch (InvalidArgumentException | Exception $e) {
            http_response_code(500); // sending bad request error to APi request if something goes wrong
            echo $e->getMessage();
        }
    }


    public function retrieveEventNameByEventTypeId()
    {
        try {
            $this->sendHeaders();
            $eventName = NULL;

            if (!empty($_GET['id'])) {
                $id = htmlspecialchars($_GET['id']);
                $eventName = $this->availableEventService->getEventNameByEventTypeIdWithUrl($id);
            }
            echo Json_encode($eventName);
        } catch (InvalidArgumentException | Exception $e) {
            http_response_code(500); // sending bad request error to APi request if something goes wrong
            echo $e->getMessage();
        }
    }

    public function getEventDateById()
    {
        try {
            $this->sendHeaders();
            $eventDate = NULL;

            if (!empty($_GET['id'])) {
                $id = htmlspecialchars($_GET['id']);
                $eventDate = $this->eventDateService->getEventDateByIdWithUrl($id);
            }
            echo Json_encode($eventDate);
        } catch (InvalidArgumentException | Exception $e) {
            http_response_code(500); // sending bad request error to APi request if something goes wrong
            echo $e->getMessage();
        }
    }
    
    
       public function retrieveArtistData(){
        try {
            $this->sendHeaders();
            $artist=NULL;

            if (!empty($_GET['id'])) {
                $id = htmlspecialchars($_GET['id']);
                $artist = $this->availableEventService->getParticipatingArtistByIdWithUrl($id);
            }
            echo Json_encode($artist);
        }
        catch (InvalidArgumentException|Exception $e) {
            http_response_code(500); // sending bad request error to APi request if something goes wrong
            echo $e->getMessage();
        }
    }
}


?>
