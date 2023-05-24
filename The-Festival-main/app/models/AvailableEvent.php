<?php


class AvailableEvent
{
    protected int $eventId;
    protected string $eventDetails;
    protected int $eventTypeId;
    protected int $historyTourId;
    protected int $performanceId;
    protected int $participatingArtistId;
    protected int $eventDate;
    protected string $eventHour;
    protected string $deliveryPossibilities;
    protected string $singleEvent;
    protected int $availableTickets;



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
    public function getEventDetails(): string
    {
        return $this->eventDetails;
    }

    /**
     * @param string $eventDetails
     */
    public function setEventDetails(string $eventDetails): void
    {
        $this->eventDetails = $eventDetails;
    }


    /**
     * @return int
     */
    public function getEventTypeId(): int
    {
        return $this->eventTypeId;
    }

    /**
     * @param int $eventTypeId
     */
    public function setEventTypeId(int $eventTypeId): void
    {
        $this->eventTypeId = $eventTypeId;
    }

    /**
     * @return int
     */

    public function gethistoryTourId(): int
    {
        return $this->historyTourId;
    }

    /**
     * @param int $historyTourId
     */
    public function setHistoryTourId(int $historyTourId): void
    {
        $this->historyTourId = $historyTourId;
    }


    /**
     * @return int
     */

    public function getPerformanceId(): int
    {
        return $this->performanceId;
    }

    /**
     * @param int $performanceId
     */
    public function setPerformanceId(int $performanceId): void
    {
        $this->performanceId = $performanceId;
    }

    /**
     * @return int
     */
    public function getParticipatingArtistId(): int
    {
        return $this->participatingArtistId;
    }

    /**
     * @param int $participatingArtistId
     */
    public function setParticipatingArtistId(int $participatingArtistId): void
    {
        $this->participatingArtistId = $participatingArtistId;
    }

    /**
     * @return int
     */
    public function getEventDate(): int
    {
        return $this->eventDate;
    }

    /**
     * @param int $eventDate
     */
    public function setEventDate(int $eventDate): void
    {
        $this->eventDate = $eventDate;
    }

    /**
     * @return string
     */
    public function getEventHour(): string
    {
        return $this->eventHour;
    }

    /**
     * @param string $eventHour
     */
    public function setEventHour(string $eventHour): void
    {
        $this->eventHour = $eventHour;
    }

    /**
     * @return string
     */
    public function getDeliveryPossibilities(): string
    {
        return $this->deliveryPossibilities;
    }

    /**
     * @param string $deliveryPossibilities
     */
    public function setEventPossibilities(string $deliveryPossibilities): void
    {
        $this->deliveryPossibilities = $deliveryPossibilities;
    }

    /**
     * @return string
     */
    public function getSingleEvent(): string
    {
        return $this->singleEvent;
    }

    /**
     * @param string $singleEvent
     */
    public function setSingleEvent(string $singleEvent): string
    {
        $this->singleEvent = $singleEvent;
    }



    /**
     * @return int
     */
    public function getAvailableTickets(): int
    {
        return $this->availableTickets;
    }

    /**
     * @param int $availableTickets
     */
    public function setAvailableTickets(int $availableTickets): void
    {
        $this->availableTickets = $availableTickets;
    }




}
