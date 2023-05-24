<?php
require_once __DIR__ . '/OrderItem.php';
require_once __DIR__ . '/User.php';
class Order implements JsonSerializable
{
    private int $orderId;
    private User $customer;
    private float $totalPrice;
    private DateTime $orderDate;
    private array $orderItems;
    private string $paymentMethod;

    public function __construct()
    {
        $this->customer = new User();
        $this->orderItems = array();
    }

    /**
     * @return int
     */
    public function getOrderId(): int
    {
        return $this->orderId;
    }

    /**
     * @param int $orderId
     */
    public function setOrderId(int $orderId): void
    {
        $this->orderId = $orderId;
    }

    /**
     * @return User
     */
    public function getCustomer(): User
    {
        return $this->customer;
    }

    /**
     * @param User $customer
     */
    public function setCustomer(User $customer): void
    {
        $this->customer = $customer;
    }

    /**
     * @return float
     */
    public function getTotalPrice(): float
    {
        return $this->totalPrice;
    }

    /**
     * @param float $totalPrice
     */
    public function setTotalPrice(float $totalPrice): void
    {
        $this->totalPrice = $totalPrice;
    }

    /**
     * @return DateTime
     */
    public function getOrderDate(): DateTime
    {
        return $this->orderDate;
    }

    /**
     * @param DateTime $orderDate
     */
    public function setOrderDate(DateTime $orderDate): void
    {
        $this->orderDate = $orderDate;
    }

    /**
     * @return array
     */
    public function getOrderItems(): array
    {
        return $this->orderItems;
    }

    /**
     * @param array $orderItems
     */
    public function setOrderItems(array $orderItems): void
    {
        $this->orderItems = $orderItems;
    }

    /**
     * @return string
     */
    public function getPaymentMethod(): string
    {
        return $this->paymentMethod;
    }

    /**
     * @param string $paymentMethod
     */
    public function setPaymentMethod(string $paymentMethod): void
    {
        $this->paymentMethod = $paymentMethod;
    }

    public function jsonSerialize() :mixed
    {
        return array(
            'orderId' => $this->orderId,
            'customer' => $this->customer->getFullName(),
            'totalPrice' => $this->totalPrice,
            'totalVatAmount' => $this->getTotalVatAmount(),
            'paymentMethod' => $this->paymentMethod,
            'orderDate' => $this->orderDate->format('Y-m-d'),
            'orderItems' => $this->orderItems
        );
    }
    private function getTotalVatAmount(): float
    {
        $totalVatAmount = 0;
        foreach ($this->orderItems as $orderItem) {
            $totalVatAmount += $orderItem->getVatAmount();
        }
        return $totalVatAmount;
    }
}