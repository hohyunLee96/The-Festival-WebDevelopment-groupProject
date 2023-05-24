<?php
require_once __DIR__ . '/EventController.php';
require_once __DIR__ . '/../../services/SpotifyService.php';
require_once __DIR__ . '/../../services/ArtistService.php';
require_once __DIR__ . '/../../services/ShoppingCartService.php';


class DanceController extends eventController
{
    private $spotifyService;
    private $artistService;
    private $danceEventService;
    private $performanceService;
    private $shoppingCartService;

    public function __construct()
    {
        parent::__construct();
        $this->spotifyService = new SpotifyService();
        $this->artistService = new ArtistService();
        $this->danceEventService = new DanceEventService();
        $this->performanceService = new PerformanceService();
        $this->shoppingCartService = new ShoppingCartService();
    }

    public function index()
    {
        $this->displayNavBar('Dance', "/css/festival/Dance/IndexPage.css");
        $participatingArtists = $this->artistService->getAllArtistsParticipatingInEvent();
        $danceEvent = $this->eventService->getEventByName('Dance');
        $artistPerformances = $danceEvent->getPerformances();
        if (empty($artistPerformances)) {
            $this->display404PageNotFound(); // every artisst should have at least one performance
        }
        $groupedPerformances = $this->performanceService->groupPerformancesWithDate($artistPerformances);
        require __DIR__ . '/../../views/festival/Dance/index.php';
    }

    public function artistDetails()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['artist'])) {
            try {
                $errorMessage = array();
                $artistId = $this->sanitizeInput($_GET['artist']);
                $selectedArtist = $this->artistService->getArtistByArtistID($artistId);
                if (empty($selectedArtist)) {
                    $this->display404PageNotFound();
                }
                $this->displayNavBar($selectedArtist->getArtistName(),"/css/festival/Dance/ArtistPage.css");
                try {
                    $artistAlbums = $this->spotifyService->getArtistAlbumsWithLimit($artistId, 6);
                    if (empty($artistAlbums)) {
                        $errorMessage['artistAlbums'] = 'No albums found for this artist';
                    }
                    $artistTopTracks = $this->spotifyService->getArtistTopTracksWithLimit($artistId, 10);
                    if (empty($artistTopTracks)) {
                        $errorMessage['artistTopTracks'] = 'No tracks found for this artist';
                    }
                } catch (\SpotifyWebAPI\SpotifyWebAPIException $e) {
                    $errorMessage['connectionToSpotify'] = $e->getMessage();
                }
                $artistPerformances = $this->performanceService->getAllPerformancesDoneByArtistIdAtEvent($selectedArtist->getArtistId(), 'Dance');
                $filteredArtistPerformances = $this->performanceService->groupPerformancesWithDate($artistPerformances);
                require __DIR__ . '/../../views/festival/Dance/artist.php';
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        } else {
            $this->display404PageNotFound();
        }
    }

    private function getDayByDateString($dateString): string
    {
        try {
            $date = new DateTime($dateString); // Create a DateTime object from the date string
            return $date->format('l');
        } catch (Exception $e) {
            return "Unknown";
        }
    }

    private function formatArtistName($artists)
    {
        $name = '';
        if (is_array($artists)) {
            foreach ($artists as $artist) {
                $name = $name . $artist->getArtistName() . ' | ';
            }
            // Remove the last '|' character
            $name = substr($name, 0, -2);
        } else {
            $name = $artists->getArtistName();
        }
        return $name;
    }
    public function ticketSelection()
    {
        if(isset($_POST['addPerformanceToCart']) && empty($_SESSION['userId']) && empty($_SESSION['orderId'])){
            $newOrderId = $this->shoppingCartService->createOrder(null);
            $_SESSION['orderId'] = $newOrderId;

            $performanceId = $_POST['performanceId']; // passed performanceId from ticket selection
            $performanceTicketId = $this->shoppingCartService->getPerformanceTicketIdByPerformanceId($performanceId); // get performanceTicketId having performance Id
            $orderItem = $this->shoppingCartService->getPerformanceOrderItemIdByTicketId($performanceTicketId, $newOrderId); // check if user already has same ticket in the shopping cart
            $quantity = $_POST['NoOfTickets'];
            if (!$orderItem) {
                // if user does not have ticket that user puts into shopping cart then create new orderItem with performanceTicketId
                $this->shoppingCartService->createPerformanceOrderItem($newOrderId, $performanceTicketId, $quantity);
            } else {
                // if user already has same performance ticket in the shopping cart, just update quantity
                $this->shoppingCartService->updatePerformanceOrderItemByTicketId($performanceTicketId, $quantity,$newOrderId);
            }

            header('Location: /festival/shoppingCart');
        }
        else if(isset($_POST['addPerformanceToCart']) && !empty($_SESSION['orderId']) && empty($_SESSION['userId'])){
            $orderId = $this->shoppingCartService->getOrderByOrderId($_SESSION['orderId']);
            $performanceId = $_POST['performanceId']; // passed performanceId from ticket selection
            $performanceTicketId = $this->shoppingCartService->getPerformanceTicketIdByPerformanceId($performanceId); // get performanceTicketId having performance Id
            $orderItem = $this->shoppingCartService->getPerformanceOrderItemIdByTicketId($performanceTicketId, $orderId); // check if user already has same ticket in the shopping cart
            $quantity = $_POST['NoOfTickets'];

            if (!$orderItem) {
                // if user does not have ticket that user puts into shopping cart then create new orderItem with performanceTicketId
                $this->shoppingCartService->createPerformanceOrderItem($orderId, $performanceTicketId, $quantity);
            } else {
                // if user already has same performance ticket in the shopping cart, just update quantity
                $this->shoppingCartService->updatePerformanceOrderItemByTicketId($performanceTicketId, $quantity,$orderId);
            }
            header('Location: /festival/shoppingCart');
        }
        else if (isset($_POST['addPerformanceToCart']) && !empty($_SESSION['userId'])) { // check if button pressed and user has an account
            $userId = $_SESSION['userId'];
            $orderId = $this->shoppingCartService->getOrderByUserId($userId); // get orderId by user Id
            // Check if there is an existing order for the user
            if (!$orderId) {
                // Create a new order for the user if user does not have an order yet
                $orderId = $this->shoppingCartService->createOrder($userId);
//                $orderId = $this->shoppingCartService->getOrderByUserId($userId);
            }
            $performanceId = $_POST['performanceId']; // passed performanceId from ticket selection
            $performanceTicketId = $this->shoppingCartService->getPerformanceTicketIdByPerformanceId($performanceId); // get performanceTicketId having performance Id
            $orderItem = $this->shoppingCartService->getPerformanceOrderItemIdByTicketId($performanceTicketId, $orderId); // check if user already has same ticket in the shopping cart
//            $this->shoppingCartService->updateTotalPrice($_SESSION['orderId']);
            $quantity = $_POST['NoOfTickets'];

            if (!$orderItem) {
                // if user does not have ticket that user puts into shopping cart then create new orderItem with performanceTicketId
                $this->shoppingCartService->createPerformanceOrderItem($orderId, $performanceTicketId, $quantity);
            } else {
                // if user already has same performance ticket in the shopping cart, just update quantity
                $this->shoppingCartService->updatePerformanceOrderItemByTicketId($performanceTicketId, $quantity,$orderId);
            }
            header('Location: /festival/shoppingCart');
        }
    }
    
}