<?php
require_once __DIR__ . '/EventController.php';
require_once __DIR__ . '/../../services/HistoryService.php';
require_once __DIR__ . '/../../services/ShoppingCartService.php';

class HistoryController extends EventController
{
    protected HistoryService $historyService;
    protected ShoppingCartService $shoppingCartService;

    public function __construct()
    {
        parent::__construct();
        $this->historyService = new HistoryService();
        $this->shoppingCartService = new ShoppingCartService();
    }

    public function index()
    {
        $allTourLocations = $this->historyService->getAllHistoryTourLocation();
        $this->displayNavBar("A stroll Through History", '/css/festival/history.css');
        $historyEvent = $this->eventService->getEventByName('A Stroll Through History');
        $historyTours = $historyEvent->getHistoryTours();
        $timetable = $this->shoppingCartService->getarrayAccordingToDate($historyTours);
//        $startingTourLocation = $this->historyService->getHistoryTourLocationsByHistoryTourId(1);

        require __DIR__ . '/../../views/festival/History/index.php';
    }

    public function detail()
    {

        $locationId = $_GET["locationId"];
        $location = $_GET["location"];
        $locationPostCode = $_GET["locationPostCode"];
        $historyTourLocationObject = $this->historyService->getHistoryTourLocationByLocationId($locationId);

        $this->displayNavBar("A stroll Through History", '/css/festival/history.css');

        require __DIR__ . '/../../views/festival/History/detail.php';
    }

    public function ticketSelection()
    {
        try {
            if (isset($_POST["addTourToCart"]) && empty($_SESSION['userId']) && empty($_SESSION['orderId'])) {
                // Create a new order and add tour to the order
                $orderId = $this->createOrder();
                $this->addTourToOrder($orderId);
            } else if (isset($_POST["addTourToCart"]) && !empty($_SESSION['orderId']) && empty($_SESSION['userId'])) {
                // Get order by order ID and add tour to the order
                $orderId = $this->getOrderById($_SESSION['orderId']);
                $this->addTourToOrder($orderId);
                $this->shoppingCartService->updateTotalPrice($_SESSION['orderId']);
            } else if (isset($_POST["addTourToCart"]) && !empty($_SESSION['userId'])) {
                // Get order by user ID and add tour to the order
                $orderId = $this->getOrderByUserId($_SESSION['userId']);
                if (!$orderId) {
                    $orderId = $this->createOrder($_SESSION['userId']);
                }
                $this->addTourToOrder($orderId);
                $this->shoppingCartService->updateTotalPrice($orderId);
            }
        } catch (Exception $error) {
            $errorMessage = $error->getMessage();
        }
        require __DIR__ . '/../../views/festival/History/ticketSelection.php';
    }

    private function createOrder($userId = null)
    {
        $orderId = $this->shoppingCartService->createOrder($userId);
        $_SESSION['orderId'] = $orderId;
        return $orderId;
    }

    private function addTourToOrder($orderId)
    {
        if (isset($_POST["tourFamilyTicket"])) {
            $tourType = "family";
            $quantity = 1;
        } else {
            $tourType = "single";
            $quantity = $_POST["tourSingleTicket"];
        }

        $newOrderItem = array(
            "orderId" => $orderId,
            "tourTicketDate" => htmlspecialchars($_POST["tourTicketDate"]),
            "tourTicketTime" => htmlspecialchars($_POST["tourTicketTime"]),
            "tourTicketType" => $tourType,
            "TourLanguage" => htmlspecialchars($_POST["TourLanguage"]),
        );

        $ticketId = $this->shoppingCartService->getTicketId($newOrderItem);
        $orderItem = $this->shoppingCartService->getOrderItemIdByTicketId($ticketId, $orderId);
        if (!$orderItem) {
            $this->shoppingCartService->createTourOrderItem($orderId, $ticketId, $quantity);
            header('Location: /festival/shoppingCart');
        } else {
            $this->shoppingCartService->updateTourOrderItemByTicketId($ticketId, $quantity);
            header('Location: /festival/shoppingCart');
        }
    }


    private function getOrderById($orderId)
    {
        return $this->shoppingCartService->getOrderByOrderId($orderId);
    }

    private function getOrderByUserId($userId)
    {
        return $this->shoppingCartService->getOrderByUserId($userId);
    }

    public function getAllHistoryTourLocation()
    {
        $allTourLocation = $this->historyService->getAllHistoryTourLocation();
    }
}
