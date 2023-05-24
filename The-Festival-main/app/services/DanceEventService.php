<?php
require_once __DIR__ . '/../repositories/DanceEventRepository.php';
require_once __DIR__ . '/../models/Exceptions/DatabaseQueryException.php';

class DanceEventService
{
    private $danceRepo;

    public function __construct()
    {
        $this->danceRepo = new DanceEventRepository();
    }

    public function getDanceEventByEventId($eventId): ?Event
    {
        return $this->danceRepo->getDanceEventByEventId($eventId);
    }

    /**
     * @throws DatabaseQueryException
     */
    public function deleteVenue($venueId): bool
    { // checking if the venue is used by a performance or not if yes it cannot be deleted
        if ($this->danceRepo->isPerformanceVenue($venueId)) {
            throw new DatabaseQueryException("Venue is used by a performance so it cannot be deleted at the moment");
        }
        return $this->danceRepo->deleteVenue($venueId);
    }

    public function getVenueById($venueId): ?Location
    {
        return $this->danceRepo->getVenueById($venueId);
    }

    public function updateVenue($venueToUpdate): bool
    {
        $isVenueDetailsSame = $this->danceRepo->isUpdatingVenueDetailSame($venueToUpdate);
        $isAddressSame = $this->danceRepo->isUpdatingVenueAddressSame($venueToUpdate->getAddress());

        if ($isVenueDetailsSame && $isAddressSame) {
            // No need to update if both detail and address are the same
            return true;
        } elseif ($isVenueDetailsSame) {
            // Update only the address if the detail is the same
            return $this->danceRepo->updateVenueAddress($venueToUpdate->getAddress());
        } elseif ($isAddressSame) {
            // Update only the detail if the address is the same
            return $this->danceRepo->updateVenueDetail($venueToUpdate);
        } else {
            // Update both detail and address if they are different
            if($this->danceRepo->updateVenueAddress($venueToUpdate->getAddress()) && $this->danceRepo->updateVenueDetail($venueToUpdate)){
                return true;
            }
        }
        return false;
    }


}