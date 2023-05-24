<?php
require_once __DIR__ . '/EventController.php';
require_once __DIR__. '/../../services/AvailableEventService.php';
require_once __DIR__. '/../../services/EventDateService.php';
require_once __DIR__ . '/../../services/userService.php';
require_once __DIR__ . '/../../services/eventImageService.php';



class EventAccessController extends eventController
{
    private $availableEventService;
    private $eventDateService;
    private $eventImageService;
    private $userService;
    private $currentUserId;


    public function __construct()
    {
        parent::__construct();
        $this->availableEventService = new AvailableEventService();
        $this->eventDateService = new EventDateService();
        $this->eventImageService = new EventImageService();

        if(isset($_SESSION["loggedUser"])){
        $this->currentUserId = unserialize(serialize($_SESSION["loggedUser"]))->getId();
        }

    }

    public function index()
    {
        $currentUserId = $this->currentUserId;
        $this->displayNavBar("EventAccess",'/css/festival/eventAccess.css');

        $eventAccesPage = $this->eventPageService->getEventPageByName('EventAccess');
        $bodyHead =  $eventAccesPage->getContent()->getBodyHead();
        $sectionText =  $eventAccesPage->getContent()->getSectionText();
        $paragraphs = $sectionText->getParagraphs();
        $availableHistoryEvents = $this->availableEventService->getAvailableHistoryEvents();
        $availableMusicEvents = $this->availableEventService->getAvailableMusicEventsData();         
        $danceEventImageData = $this->eventImageService->getEventImagePath(2, 'other');
        $historyEventImageData= $this->eventImageService->getEventImagePath(1, 'other');
        $foodEventImageData = $this->eventImageService->getEventImagePath(3, 'other');



        $firstEventsDay = $this->eventDateService->getEventDateById(1);
        $secondEventsDay = $this->eventDateService->getEventDateById(2);
        $thirdEventsDay = $this->eventDateService->getEventDateById(3);
        $fourthEventsDay = $this->eventDateService->getEventDateById(4);


        $availableHistoryEventsList1 = $this->availableEventService->getAvailableHistoryEventsByDate($availableHistoryEvents, 1);
        $availableConcertEventsList1 = $this->availableEventService->getAvailableMusicEventsByDate($availableMusicEvents, 1);
        $availableEventsList1 = array_merge($availableHistoryEventsList1,  $availableConcertEventsList1);

        $availableHistoryEventsList2 = $this->availableEventService->getAvailableHistoryEventsByDate($availableHistoryEvents, 2);
        $availableConcertEventsList2 = $this->availableEventService->getAvailableMusicEventsByDate($availableMusicEvents, 2);
        $availableEventsList2 = array_merge($availableHistoryEventsList2,  $availableConcertEventsList2);


        $availableHistoryEventsList3 = $this->availableEventService->getAvailableHistoryEventsByDate($availableHistoryEvents, 3);
        $availableConcertEventsList3 = $this->availableEventService->getAvailableMusicEventsByDate($availableMusicEvents, 3);
        $availableEventsList3 = array_merge($availableHistoryEventsList3,  $availableConcertEventsList3);


        $availableHistoryEventsList4 = $this->availableEventService->getAvailableHistoryEventsByDate($availableHistoryEvents, 4);
        $availableConcertEventsList4 = $this->availableEventService->getAvailableMusicEventsByDate($availableMusicEvents, 4);
        $availableEventsList4 = array_merge($availableHistoryEventsList4,  $availableConcertEventsList4);


       

        require __DIR__ . '/../../views/festival/EventAccess/eventsPage.php';
        
        $this->displayFooter();

    }}
?>

