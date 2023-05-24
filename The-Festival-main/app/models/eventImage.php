<?php

class EventImage
{
    private int $imageId;
    private int  $eventId;
    private string  $specification;


     /**
     * @return int
     */
    public function getImageId(): int
    {
        return $this->imageId;
    }

    /**
     * @param int $imageId
     */
    public function setImageId(int $imageId): void
    {
        $this->imageId = $imageId;
    }

    
      /**
     * @return int
     */
    public function getEventID(): int
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
    public function getSpecification(): string
    {
        return $this->specification;
    }

    /**
     * @param string $specification
     */
    public function setSpecification(string $specification): void
    {
        $this->specification=specification;
    }

    

    
}
