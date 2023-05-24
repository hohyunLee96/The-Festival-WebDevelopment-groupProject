<?php
require_once __DIR__ . "/../repositories/OrderRepository.php";
class OrderService
{
    private OrderRepository $orderRepository;
    public function __construct()
    {
        $this->orderRepository = new OrderRepository();
    }
    public function getAllOrders()
    {
        return $this->orderRepository->getAllOrders();
    }
}