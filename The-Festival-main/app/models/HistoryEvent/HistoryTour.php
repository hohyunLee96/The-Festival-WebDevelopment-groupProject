<?php

class HistoryTour implements JsonSerializable
{

    private int $historyTourId;
    private string $tourLanguage;
    private array $historyTourLocations;
    private DateTime $tourDate;
    private float $duration;
    private DateTime $time;

    public function jsonSerialize() : mixed
    {
        return [
            'historyTourId' => $this->historyTourId,
            'tourLanguage' => $this->tourLanguage,
            'historyTourLocations' => $this->historyTourLocations,
            'tourDate' => $this->tourDate->format('Y-m-d'),
            'duration' => $this->duration,
            'time' => $this->time->format('H:i:s')
        ];
    }

    /**
     * @return float
     */
    public function getDuration(): float
    {
        return $this->duration;
    }

    /**
     * @param float $duration
     */
    public function setDuration(float $duration): void
    {
        $this->duration = $duration;
    }

    /**
     * @return int
     */
    public function getHistoryTourId(): int
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


    public function __construct()
    {
        $this->historyTourLocations= array();
    }


    /**
     * @return string
     */
    public function getTourLanguage(): string
    {
        return $this->tourLanguage;
    }

    /**
     * @param string $tourLanguage
     */
    public function setTourLanguage(string $tourLanguage): void
    {
        $this->tourLanguage = $tourLanguage;
    }

    /**
     * @return DateTime
     */
    public function getTourDate(): DateTime
    {
        return $this->tourDate;
    }

    /**
     * @param DateTime $tourDate
     */
    public function setTourDate(DateTime $tourDate): void
    {
        $this->tourDate = $tourDate;
    }


    /**
     * @return array
     */
    public function getHistoryTourLocations(): array
    {
        return $this->historyTourLocations;
    }

    /**
     * @param array $historyTourLocations
     */
    public function setHistoryTourLocations(array $historyTourLocations): void
    {
        $this->historyTourLocations = $historyTourLocations;
    }

    /**
     * @return DateTime
     */
    public function getTime(): DateTime
    {
        return $this->time;
    }

    /**
     * @param DateTime $time
     */
    public function setTime(DateTime $time): void
    {
        $this->time = $time;
    }





}