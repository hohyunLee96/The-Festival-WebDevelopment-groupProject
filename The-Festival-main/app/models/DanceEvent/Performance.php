<?php
require_once __DIR__ . '/../Location.php';
require_once __DIR__ . '/PerformanceSession.php';

class Performance implements JsonSerializable
{
    private int $performanceId;
    private array $artists;
    private DateTime $date;
    private Location $venue;
    private int $duration;
    private PerformanceSession $session;
    private float $totalPrice; // this is included with Vat
    private int $totalTickets;
    private int $availableTickets;

    /**
     * @return float
     */
    public function getTotalPrice(): float
    {
        return $this->totalPrice;
    }

    /**
     * @param float $totalPrice
     */
    public function setTotalPrice(float $totalPrice): void
    {
        $this->totalPrice = $totalPrice;
    }

    /**
     * @return int
     */
    public function getTotalTickets(): int
    {
        return $this->totalTickets;
    }

    /**
     * @param int $totalTickets
     */
    public function setTotalTickets(int $totalTickets): void
    {
        $this->totalTickets = $totalTickets;
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

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->performanceId,
            'artists' => $this->artists,
            'date' => $this->date->format('Y-m-d'),
            'venue' => $this->venue->getLocationName(),
            'session' => $this->session->getSessionName(),
            'price' => $this->totalPrice,
            'startTime' => $this->getDate()->format('H:i'),
            'endTime' => $this->getEndDateTime()->format('H:i'),
        ];
    }

    public function __construct()
    {
        $this->artists = array();
    }

    /**
     * @return PerformanceSession
     */
    public function getSession(): PerformanceSession
    {
        return $this->session;
    }

    /**
     * @param PerformanceSession $session
     */
    public function setSession(PerformanceSession $session): void
    {
        $this->session = $session;
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
     * @return array
     */
    public function getArtists(): array
    {
        return $this->artists;
    }

    /**
     * @param array $artists
     */
    public function setArtists(array $artists): void
    {
        $this->artists = $artists;
    }

    /**
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * @param DateTime $date
     */
    public function setDate(DateTime $date): void
    {
        $this->date = $date;
    }

    /**
     * @return Location
     */
    public function getVenue(): Location
    {
        return $this->venue;
    }

    /**
     * @param Location $venue
     */
    public function setVenue(Location $venue): void
    {
        $this->venue = $venue;
    }

    /**
     * @return int
     */
    public function getDuration(): int
    {
        return $this->duration;
    }

    /**
     * @param int $duration
     */
    public function setDuration(int $duration): void
    {
        $this->duration = $duration;
    }


    public function getEndDateTime(): DateTime // returns EndDateTime of the performance
    {
        $endTime = clone $this->date;
        $endTime->add(new DateInterval('PT' . $this->duration . 'M'));
        return $endTime;
    }

    function getFormattedArtistName()
    {
        $artists = $this->getArtists();
        $name = '';
        if (is_array($artists)) {
            foreach ($artists as $artist) {
                $name = $name . $artist->getArtistName() . ' | ';
            }
            // Remove the last '|' character
            $name = substr($name, 0, -2);
        } else {
            $name = $artists->getArtistName();
        }
        return $name;
    }

}