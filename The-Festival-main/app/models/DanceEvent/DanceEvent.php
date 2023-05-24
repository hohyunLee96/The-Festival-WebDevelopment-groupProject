<?php
require_once __DIR__ . '/../event.php';
require_once __DIR__ . '/Performance.php';
class DanceEvent extends Event
{
    private array $performances;
    private float $maximalPrice;
    private float $minimalPrice;

    /**
     * @return float
     */
    public function getMaximalPrice(): float
    {
        return $this->maximalPrice;
    }

    /**
     * @param float $maximalPrice
     */
    public function setMaximalPrice(float $maximalPrice): void
    {
        $this->maximalPrice = $maximalPrice;
    }

    /**
     * @return float
     */
    public function getMinimalPrice(): float
    {
        return $this->minimalPrice;
    }

    /**
     * @param float $minimalPrice
     */
    public function setMinimalPrice(float $minimalPrice): void
    {
        $this->minimalPrice = $minimalPrice;
    }
    public function __construct()
    {
        $this->performances = array();
    }

    /**
     * @return array
     */
    public function getPerformances(): array
    {
        return $this->performances;
    }

    /**
     * @param array $performances
     */
    public function setPerformances(array $performances): void
    {
        $this->performances = $performances;
    }


}