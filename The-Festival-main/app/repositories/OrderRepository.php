<?php
require_once __DIR__ . '/EventRepository.php';
require_once __DIR__ . '/../services/UserService.php';
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../services/OrderItemService.php';

class OrderRepository extends EventRepository
{
    private $userServices;
    private $orderItemService;

    public function __construct()
    {
        parent::__construct();
        $this->userServices = new UserService();
        $this->orderItemService = new OrderItemService();
    }

    public function getAllOrders()
        // this will retrieve all orders Paid from the database
    {
        $query = "SELECT ord.orderId, ord.user_id, ord.order_date, ord.totalPrice,payment.paymentMethod
                   FROM `order` as ord
                   JOIN payment ON ord.orderId = payment.orderId
                    WHERE payment.paymentStatus = 'Paid'";
        $dbResult = $this->executeQuery($query);
        if (empty($dbResult)) {
            return null;
        }
        $orders = array();
        foreach ($dbResult as $dbRow) {
            $orders[] = $this->createOrderInstance($dbRow);
        }
        return $orders;
    }

    private function createOrderInstance($dbRow)
    {
        try {
            $orderingCustomer = $this->userServices->getUserById($dbRow['user_id']);
            $order = new Order();
            $order->setOrderId($dbRow['orderId']);
            $order->setCustomer($orderingCustomer);
            $order->setOrderDate(new DateTime($dbRow['order_date']));
            $order->setPaymentMethod($dbRow['paymentMethod']);
            $orderItems = $this->orderItemService->getOrderItemsByOrderId($dbRow['orderId']);
            $order->setOrderItems($orderItems);
            $order->setTotalPrice($dbRow['totalPrice']);
            return $order;
        } catch (Exception $e) {
            throw new InternalErrorException("Order Objection Failed:". $e->getMessage());
        }


    }


}