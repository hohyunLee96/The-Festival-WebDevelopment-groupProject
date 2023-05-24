<?php
require_once __DIR__ . '/OrderItem.php';

class restaurantOrderItem extends OrderItem
{
    private string $foodType;
    private string $restaurantName;
    private string $ticketType;

    /**
     * @return string
     */
    public function getFoodType(): string
    {
        return $this->foodType;
    }

    /**
     * @param string $foodType
     */
    public function setFoodType(string $foodType): void
    {
        $this->foodType = $foodType;
    }

    /**
     * @return string
     */
    public function getRestaurantName(): string
    {
        return $this->restaurantName;
    }

    /**
     * @param string $restaurantName
     */
    public function setRestaurantName(string $restaurantName): void
    {
        $this->restaurantName = $restaurantName;
    }

    /**
     * @return string
     */
    public function getTicketType(): string
    {
        return $this->ticketType;
    }

    /**
     * @param string $ticketType
     */
    public function setTicketType(string $ticketType): void
    {
        $this->ticketType = $ticketType;
    }

}