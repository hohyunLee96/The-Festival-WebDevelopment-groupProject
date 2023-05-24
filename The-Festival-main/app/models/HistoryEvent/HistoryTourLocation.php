<?php
require_once __DIR__ . '/../../models/Location.php';
class HistoryTourLocation
{
    private ?int $historyTourLocationId;
    private ?Location $tourLocation;
    private ?string $locationInfo;
    private ?string $historyP1;
    private ?string $historyP2;
    private ?string $locationName;
    private ?array $tourImage;


    /**
     * @return int|null
     */
    public function getHistoryTourLocationId(): ?int
    {
        return $this->historyTourLocationId;
    }

    /**
     * @param int|null $historyTourLocationId
     */
    public function setHistoryTourLocationId(?int $historyTourLocationId): void
    {
        $this->historyTourLocationId = $historyTourLocationId;
    }

    /**
     * @return Location|null
     */
    public function getTourLocation(): ?Location
    {
        return $this->tourLocation;
    }

    /**
     * @param Location|null $tourLocation
     */
    public function setTourLocation(?Location $tourLocation): void
    {
        $this->tourLocation = $tourLocation;
    }

    /**
     * @return string|null
     */
    public function getLocationInfo(): ?string
    {
        return $this->locationInfo;
    }

    /**
     * @param string|null $locationInfo
     */
    public function setLocationInfo(?string $locationInfo): void
    {
        $this->locationInfo = $locationInfo;
    }

    /**
     * @return string|null
     */
    public function getHistoryP1(): ?string
    {
        return $this->historyP1;
    }

    /**
     * @param string|null $historyP1
     */
    public function setHistoryP1(?string $historyP1): void
    {
        $this->historyP1 = $historyP1;
    }

    /**
     * @return string|null
     */
    public function getHistoryP2(): ?string
    {
        return $this->historyP2;
    }

    /**
     * @param string|null $historyP2
     */
    public function setHistoryP2(?string $historyP2): void
    {
        $this->historyP2 = $historyP2;
    }

    /**
     * @return string|null
     */
    public function getLocationName(): ?string
    {
        return $this->locationName;
    }

    /**
     * @param string|null $locationName
     */
    public function setLocationName(?string $locationName): void
    {
        $this->locationName = $locationName;
    }

    /**
     * @return array|null
     */
    public function getTourImage(): ?array
    {
        return $this->tourImage;
    }

    /**
     * @param array|null $tourImage
     */
    public function setTourImage(?array $tourImage): void
    {
        $this->tourImage = $tourImage;
    }



}
