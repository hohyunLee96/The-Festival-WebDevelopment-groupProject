<?php
require_once __DIR__ . '/../repositories/PerformanceRepository.php';
require_once __DIR__ . '/ArtistService.php';
require_once __DIR__ . '/../models/Exceptions/NotAvailableException.php';
require_once __DIR__ . '/../models/Exceptions/InternalErrorException.php';

class PerformanceService
{
    private $performanceRepository;
    private $artistService;

    public function __construct()
    {
        $this->performanceRepository = new PerformanceRepository();
        $this->artistService = new ArtistService();
    }

    public function getPerformancesByEventId($eventId): ?array
    {
        return $this->performanceRepository->getPerformancesByEventId($eventId);
    }

    public function getAllPerformancesDoneByArtistIdAtEvent($artistId, $eventName): ?array
    {
        return $this->performanceRepository->getAllPerformancesDoneByArtistIdAtEvent($artistId, $eventName);
    }

    public function getAllPerformanceSessions(): ?array
    {
        return $this->performanceRepository->getAllPerformanceSessions();
    }

    public function groupPerformancesWithDate($performances): ?array
    {
        $groupedPerformances = array();
        foreach ($performances as $performance) {
            $date = $performance->getDate()->format('Y-m-d'); // Get the date of the artist performance and grouping with the date ask
            if (!isset($groupedPerformances[$date])) {
                $groupedPerformances[$date] = array(); // check if the date is already in the array, if not create a new array for this date
            }
            $groupedPerformances[$date][] = $performance; // Adding the artist performance to the array for this date
        }
        return $groupedPerformances;
    }

    /**
     * @throws NotAvailableException
     * @throws DatabaseQueryException
     * @throws InternalErrorException
     */
    public function addPerformanceWithEventId($eventId, $data): bool
    { // check if the artist is available at the time
        $this->checkVenueAvailabilityForPerformance($data['Venue'], $data['performanceDate'], $data['startTime']);
        $this->checkArtistAvailabilityForPerformance($data['artists'], $data['performanceDate'], $data['startTime']);
        return $this->performanceRepository->addPerformanceWithEventId($eventId, $data);
    }

    public function deletePerformanceById($performanceId): bool
    {
        return $this->performanceRepository->deletePerformance($performanceId);
    }

    /**
     * @throws NotAvailableException
     * @throws InternalErrorException
     */
    private function checkArtistAvailabilityForPerformance($performingArtists, $performanceDate, $performanceStartTime): void
    { //checking if artists is array or not
        if (!is_array($performingArtists)) {
            $performingArtists = [$performingArtists]; // if it is not array, making it array
        }
        foreach ($performingArtists as $artist) {
            if (!$this->artistService->isArtistAvailableAtTime($artist, $performanceDate, $performanceStartTime)) {
                $notAvailableArtist = $this->artistService->getArtistByArtistID($artist);
                if (empty($notAvailableArtist)) {
                    throw new InternalErrorException("Something went wrong while checking the artist 
                    availability ,Internally");
                }
                throw new NotAvailableException(sprintf(" %s is not available in 
                %s at %s", $notAvailableArtist->getArtistName(), $performanceDate, $performanceStartTime));
            }
        }
    }

    /**
     * @throws NotAvailableException
     * @throws InternalErrorException
     */
    private function checkVenueAvailabilityForPerformance($locationId, $performanceDate, $performanceStartTime): void
    {
        if (!$this->performanceRepository->isLocationAvailableAtTime($locationId, $performanceDate, $performanceStartTime)) {
            $venue = $this->performanceRepository->getVenueById($locationId);
            if (empty($venue)) {
                throw new InternalErrorException("Something went wrong while checking the venue 
                availability ,Internally");
            }
            throw new NotAvailableException(sprintf("Location %s is not available in 
            %s at %s", $venue->getLocationName(), $performanceDate, $performanceStartTime));
        }

    }
    public function getPerformanceById($performanceId): ?Performance
    {
        return $this->performanceRepository->getPerformanceById($performanceId);
    }

    /**
     * @throws NotAvailableException
     * @throws InternalErrorException
     * @throws DatabaseQueryException
     */
    public function updatePerformance($performanceDetails): bool
    {
        $isPerformanceDateTimeSame = $this->performanceRepository->isPerformanceDateTimeSame(
            $performanceDetails->date,
            $performanceDetails->startTime,
            $performanceDetails->id);

        $isPerformanceDetailsSameInDb = $this->performanceRepository->isPerformanceDetailsSameInDb($performanceDetails);

        $isPerformanceVenuesSame = $this->performanceRepository->isPerformanceVenueSame(
            $performanceDetails->id,
            $performanceDetails->venueId);

        $isParticipatingArtistSame = $this->isParticipatingArtistSame(
            $performanceDetails->id,
            $performanceDetails->participatingArtists);

        if ($isPerformanceDateTimeSame && $isPerformanceDetailsSameInDb && $isParticipatingArtistSame) {
            return true; //everything same nothing to be changed
        }
        if (!$isPerformanceVenuesSame) {
            $this->checkVenueAvailabilityForPerformance($performanceDetails->venueId, $performanceDetails->date,
                $performanceDetails->startTime); // if it is not available then throw exceptions
        }
        if (!$isParticipatingArtistSame) {
            $this->updateParticipatingArtist($performanceDetails->id, $performanceDetails->participatingArtists,
                $performanceDetails->date, $performanceDetails->startTime); // it also checks if the artist is available or not
            if ($isPerformanceDateTimeSame && $isPerformanceDetailsSameInDb){
                return true; // if there is no change in the performance details then return true
            }
        }
        if (!$isPerformanceDateTimeSame) {
            $performanceDetails->timetableId = $this->performanceRepository->getTimetableIdByDateAndTime($performanceDetails->date,
                $performanceDetails->startTime);
        }
        return $this->performanceRepository->updatePerformance($performanceDetails);
    }

    /**
     * @throws NotAvailableException
     * @throws InternalErrorException
     */
    private function updateParticipatingArtist($performanceId, $artists, $date, $startTime): void
    {
        // Get the current participating artists for the performance
        $presentParticipatingArtists = $this->performanceRepository->getParticipatingArtistsIds($performanceId);
        $artistsToAdd = array_diff($artists, $presentParticipatingArtists);
        if (!empty($artistsToAdd)) { // if there are artists to be added
            $this->checkArtistAvailabilityForPerformance($artistsToAdd, $date,
                $startTime); // first checking
            foreach ($artistsToAdd as $artistId) {
                $this->performanceRepository->addParticipatingArtist($performanceId, $artistId);
            } // then adding
        }
        $artistsToRemove = array_diff($presentParticipatingArtists, $artists); // artists to be removed
        if (!empty($artistsToRemove)) {
            foreach ($artistsToRemove as $artistId) {
                $this->performanceRepository->removeParticipatingArtist($performanceId, $artistId);
            }
        }

    }

    private function isParticipatingArtistSame($performanceId, $artists): bool
    {
        $presentParticipatingArtists = $this->performanceRepository->getParticipatingArtistsIds($performanceId);
        if (empty(array_diff($artists, $presentParticipatingArtists)) && empty(array_diff($presentParticipatingArtists, $artists))) {
            return true;
        }
        return false;
    }


}