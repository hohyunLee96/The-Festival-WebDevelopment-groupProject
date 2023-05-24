<?php

class HistoryTourImage
{
    private int $historyTourLocationId;
    private string $imageName;
    private string $tourLocationImage;

    /**
     * @return int
     */
    public function getHistoryTourLocationId(): int
    {
        return $this->historyTourLocationId;
    }

    /**
     * @param int $historyTourLocationId
     */
    public function setHistoryTourLocationId(int $historyTourLocationId): void
    {
        $this->historyTourLocationId = $historyTourLocationId;
    }

    /**
     * @return string
     */
    public function getImageName(): string
    {
        return $this->imageName;
    }

    /**
     * @param string $imageName
     */
    public function setImageName(string $imageName): void
    {
        $this->imageName = $imageName;
    }

    /**
     * @return string
     */
    public function getTourLocationImage(): string
    {
        return $this->tourLocationImage;
    }

    /**
     * @param string $tourLocationImage
     */
    public function setTourLocationImage(string $tourLocationImage): void
    {
        $this->tourLocationImage = $tourLocationImage;
    }
}