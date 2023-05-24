<?php

class OrderItem implements \JsonSerializable
{
    private int $orderItemId;
    private int $quantity;
    private string $price;
    private object $event; // so it can store any object of Events like performance /A stroll Through History etc
    private float $vatPercentage;
    private ?string $eventTicketType;

    /**
     * @return string|null
     */
    public function getEventTicketType(): ?string
    {
        return $this->eventTicketType;
    }

    /**
     * @param string|null eventTicketType
     */
    public function setEventTicketType(?string $eventTicketType): void
    {
        $this->eventTicketType = $eventTicketType;
    }

    /**
     * @return float
     */
    public function getVatPercentage(): float
    {
        return $this->vatPercentage;
    }

    /**
     * @param float $vatPercentage
     */
    public function setVatPercentage(float $vatPercentage): void
    {
        $this->vatPercentage = $vatPercentage;
    }

    /**
     * @return int
     */
    public function getOrderItemId(): int
    {
        return $this->orderItemId;
    }

    /**
     * @param int $orderItemId
     */
    public function setOrderItemId(int $orderItemId): void
    {
        $this->orderItemId = $orderItemId;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    /**
     * @return object
     */
    public function getEvent(): object
    {
        return $this->event;
    }

    /**
     * @param object $event
     */
    public function setEvent(object $event): void
    {
        $this->event = $event;
    }

    /**
     * @return string
     */
    public function getPrice(): string
    {
        return $this->price;
    }

    /**
     * @param string $price
     */
    public function setPrice(string $price): void
    {
        $this->price = $price;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'quantity' => $this->getQuantity(),
            'FestivalEventName' => $this->getFestivalEventName(),
            'VatAmount' => $this->getVatAmount(),
            'vatPercentage' => $this->getVatPercentage(),
            'EventDetails' => $this->getShowingDetailsByEventType(),
        ];
    }

    private function getShowingDetailsByEventType()
    {
        if ($this->getEvent() instanceof Performance) {

            return array(
                'artists' => $this->getEvent()->getFormattedArtistName(),
                'date' => $this->getEvent()->getDate()->format('Y-m-d'),
                'venue' => $this->getEvent()->getVenue()->getLocationName(),
                'session' => $this->getEvent()->getSession()->getSessionName(),
                'price' => $this->getEvent()->getTotalPrice(),
                'startTime' => $this->getEvent()->getDate()->format('H:i'),
                'endTime' => $this->getEvent()->getEndDateTime()->format('H:i'),
            );

        }
        if ($this->getEvent() instanceof HistoryTour) {
            return array(
                'tourLanguage' => $this->getEvent()->getTourLanguage(),
                'tourDate' => $this->getEvent()->getTourDate()->format('Y-m-d'),
                'time' => $this->getEvent()->getTime()->format('H:i:s'),
                'price' => $this->price,
                'TicketType' => $this->getEventTicketType(),
            );
        }
    }

    public function getVatAmount(): float|int
    {
        return (floatval($this->getPrice()) * $this->getVatPercentage()) / 100;
    }

    public function getEventType(): string
    {
        return get_class($this->getEvent());
    }

    public function getFestivalEventName()
    {
        if ($this->getEventType() == 'Performance') {
            return 'Dance';
        }
        if ($this->getEventType() == 'HistoryTour') {
            return 'A Stroll Through History';
        }
    }
}
