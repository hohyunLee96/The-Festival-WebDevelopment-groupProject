<?php
require_once __DIR__ . '/Paragraph.php';
 abstract class Event
{
    protected string $eventName;
    protected int $eventId;
    protected ?array $eventParagraphs; // every event does not need to have a paragraph
    protected ?array $eventImages; // every event does not need to have  images
     protected float $eventVatPercentage; // each event has a different vat percentage but

     /**
      * @return float
      */
     public function getEventVatPercentage(): float
     {
         return $this->eventVatPercentage;
     }

     /**
      * @param float $eventVatPercentage
      */
     public function setEventVatPercentage(float $eventVatPercentage): void
     {
         $this->eventVatPercentage = $eventVatPercentage;
     }


     public function __construct()
     {
         $this->eventParagraphs = array();
         $this->eventImages = array();
     }

     /**
      * @return array|null
      */
     public function getEventParagraphs(): ?array
     {
         return $this->eventParagraphs;
     }

     /**
      * @param array|null $eventParagraphs
      */
     public function setEventParagraphs(?array $eventParagraphs): void
     {
         $this->eventParagraphs = $eventParagraphs;
     }

     /**
      * @return array|null
      */
     public function getEventImages(): ?array
     {
         return $this->eventImages;
     }

     /**
      * @param array|null $eventImages
      */
     public function setEventImages(?array $eventImages): void
     {
         $this->eventImages = $eventImages;
     }


     /**
     * @return string
     */
    public function getEventName(): string
    {
        return $this->eventName;
    }

    /**
     * @param string $eventName
     */
    public function setEventName(string $eventName): void
    {
        $this->eventName = $eventName;
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




}