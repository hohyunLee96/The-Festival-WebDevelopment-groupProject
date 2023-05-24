<?php
 class AccessPass implements \JsonSerializable
{
    protected int $accessPassId;
    protected int $eventId;
    protected string $eventDate;
    protected string $accessPassType;
    protected int $price;

    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }

    /**
     * @return int
     */
    public function getAccessPassId(): int
    {
        return $this->accessPassId;
    }

    /**
     * @param int $accessPassId
     */
    public function setAccessPassId(int $accessPassId): void
    {
        $this->accessPassId = $accessPassId;
    }

    /**
     * @return int
     */
    public function getEventId(): int
    {
        return $this->eventId;
    }

    /**
     * @param int $eventId
     */
    public function setEventId(int $eventId): void
    {
        $this->eventId = $eventId;
    }

   
 /**
     * @return string
     */
    public function getEventDate(): string
    {
        return $this->eventDate;
    }

    /**
     * @param string $eventDate
     */
    public function setEventDate(string $eventDate): void
    {
        $this->eventDate = $eventDate;
    }


    
   
 /**
     * @return string
     */
    public function getAccessPassType(): string
    {
        return $this->accessPassType;
    }

    /**
     * @param string $accessPassType
     */
    public function setAccessPassType(string $accessPassType): void
    {
        $this->accessPassType = $accessPassType;
    }


     /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }


}
