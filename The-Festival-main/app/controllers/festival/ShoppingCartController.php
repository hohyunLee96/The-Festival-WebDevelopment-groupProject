<?php
require_once __DIR__ . '/EventController.php';
require_once __DIR__ . '/../../Services/ShoppingCartService.php';

class ShoppingCartController extends EventController
{
    private $shoppingCartService;

    public function __construct()
    {
        parent::__construct();
        $this->shoppingCartService = new ShoppingCartService();
    }

    public function index()
    {
        $allItemsInShoppingCarts = "";
        $allRestaurantItems = "";
        $allPerformanceItems = "";
        $totalPrice = 0;
        if(empty($orderId) && !empty($_SESSION['userId'])){
            $orderId = $this->shoppingCartService->getOrderByUserId($_SESSION['userId']);
        }

        if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['orderId'])) {
            $orderId = htmlspecialchars($_GET['orderId']);

            $allItemsInShoppingCarts = $this->shoppingCartService->getHistoryTourOrdersByOrderId($orderId);
            $allRestaurantItems = $this->shoppingCartService->getRestaurantOrdersByUserId($orderId);
            $allPerformanceItems = $this->shoppingCartService->getPerformanceOrdersByOrderId($orderId);
        } else {
            if (!empty($_SESSION['userId'])) {
                $userId = $_SESSION['userId'];

                $allItemsInShoppingCarts = $this->shoppingCartService->getHistoryTourOrdersByUserId($userId);
                $allRestaurantItems = $this->shoppingCartService->getRestaurantOrdersByUserId($userId);
                $allPerformanceItems = $this->shoppingCartService->getPerformanceOrdersByUserId($userId);

            } else {
                $orderId = $_SESSION['orderId'] ?? '';
                $allItemsInShoppingCarts = $this->shoppingCartService->getHistoryTourOrdersByOrderId($orderId);
                $allRestaurantItems = $this->shoppingCartService->getRestaurantOrdersByUserId($orderId);
                $allPerformanceItems = $this->shoppingCartService->getPerformanceOrdersByOrderId($orderId);

            }
            $sharingUrl=$this->getSharingUrl($orderId);
        }


        if (isset($_POST['payNow']) && !empty($_SESSION['userId'])) {
            $userId = $_SESSION['userId'];
            $orderId = $this->shoppingCartService->getOrderByUserId($userId);

            // Get payment parameters from form submission
            $amount = number_format($_POST["amount"], 2, '.', '');
            $description = $_POST["description"];
            $redirectUrl = $_POST["redirectUrl"];
            $webhookUrl = $_POST["webhookUrl"];

            //delete expired payment
            $this->shoppingCartService->deletePayment();

            // Create Mollie payment
            $payment = $this->shoppingCartService->createPayment($userId, $orderId, $amount, $description, $redirectUrl, $webhookUrl);
        } else if (isset($_POST['payNow']) && empty($_SESSION['userId'])) {
            include __DIR__ . '/../../views/ShoppingCart/shoppingCartModal.php';
        }
        require_once __DIR__ . '/../../views/ShoppingCart/shoppingCart.php';
    }


    public function paymentRedirect()
    {
        if (isset($_GET['orderID'])) {
            $orderId = htmlspecialchars($_GET['orderID']);
            $paymentCode = $this->shoppingCartService->getPaymentCodeByOrderId($orderId);
            $paymentStatus = $this->shoppingCartService->getPaymentStatusFromMollie($paymentCode);
            if ($paymentStatus == "paid") {
                $this->shoppingCartService->changePaymentToPaid($paymentCode, $orderId);
                include __DIR__ . '/../../views/ShoppingCart/paymentSuccess.php';
            } else {
                include __DIR__ . '/../../views/ShoppingCart/paymentError.php.php';
            }
        }
    }

    public function updateQuantity()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderItemId = $_POST['orderItemId'];
            $orderId = $this->shoppingCartService->getOrderIdByOrderItemId($orderItemId);
            $quantity = $_POST['quantity'];
            $this->shoppingCartService->updateQuantity($orderItemId, $quantity);
            $this->shoppingCartService->updateTotalPrice($orderId);
        }
    }

    public function deleteOrderItem()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderItemId = $_POST['orderItemId'];
            $orderId = $this->shoppingCartService->getOrderIdByOrderItemId($orderItemId);
            $this->shoppingCartService->deleteOrderItem($orderItemId);
            $this->shoppingCartService->updateTotalPrice($orderId);

        }
    }

    public function getTotalPrice()
    {
        if (!empty($_SESSION['userId'])) {
            $userId = $_SESSION['userId'];
            $totalPrice = $this->shoppingCartService->getTotalPriceByUserId($userId);
        } else if (!empty($_SESSION['orderId'])) {
            $orderId = $_SESSION['orderId'];
            $totalPrice = $this->shoppingCartService->getTotalPriceByOrderId($orderId);
        } else if (empty($totalPrice)) {
            $totalPrice = 0;
        }
        echo $totalPrice;
    }

    private function getSharingUrl($orderId)
    {
        return "http://localhost/festival/shoppingCart?orderId=$orderId";
    }
}