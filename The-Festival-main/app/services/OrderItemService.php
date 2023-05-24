<?php
require_once __DIR__.'/../repositories/OrderItemRepository.php';
class OrderItemService
{
    private $orderItemRepository;
    public function __construct()
    {
        $this->orderItemRepository = new OrderItemRepository();
    }
    public function getOrderItemsByOrderId($orderId)
    {
        return $this->orderItemRepository->getAllOrderItemsByOrder($orderId);
    }
}