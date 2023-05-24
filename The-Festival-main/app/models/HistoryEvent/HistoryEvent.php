<?php
require_once __DIR__.'/HistoryTour.php';

class HistoryEvent extends Event
{
    private array $historyTours;

    public function __construct()
    {
        $this->historyTours = array();
    }

    /**
     * @return array
     */
    public function getHistoryTours(): array
    {
        return $this->historyTours;
    }

    /**
     * @param array $historyTours
     */
    public function setHistoryTours(array $historyTours): void
    {
        $this->historyTours = $historyTours;
    }
}