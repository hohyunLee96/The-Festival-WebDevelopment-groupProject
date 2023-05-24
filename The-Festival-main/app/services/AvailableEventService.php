<?php
require_once __DIR__ . '/../repositories/AvailableEventRepository.php';


class AvailableEventService
{
    private $availableEventRepository;

    public function __construct()
    {
        $this->availableEventRepository = new AvailableEventRepository();

    }
    public function getAvailableHistoryEvents()
    {
        return $this->availableEventRepository->getAvailableHistoryEvents();
    }

    public function getAvailableMusicEvents()
    {
        return $this->availableEventRepository->getAvailableMusicEvents();
    }
    
       public function  getAvailableEventByIdWithUrl($id)
    {
        return $this->availableEventRepository->getAvailableEventByIdWithUrl($id);

    }

    public function  retrieveParticipatingArtistsDataWithUrl($id)
    {
        return $this->availableEventRepository->retrieveParticipatingArtistsDataWithUrl($id);

    }


    public function getEventNameByEventTypeIdWithUrl($id){
        return $this->availableEventRepository->getEventNameByEventTypeIdWithUrl($id);
    }
    
    
    
    public function getParticipatingArtistNameByArtistId($id)
    {
        return $this->availableEventRepository->getParticipatingArtistNameByArtistId($id);
    }
    
     public function  getParticipatingArtistByIdWithUrl($id)
    {
        return $this->availableEventRepository->getParticipatingArtistByIdWithUrl($id);
    }


    public function getAvailableMusicEventsData()
    {
        $newList = array();
        $availableEvents = $this->getAvailableMusicEvents();
        foreach ($availableEvents as $availableEvent) {
            $participatingArtistId = $availableEvent->getParticipatingArtistId();
            $participatingArtist = $this->availableEventRepository->getParticipatingArtistNameByArtistId($participatingArtistId);

            array_push($newList, array('AvailableEvent' => $availableEvent, 'ParticipatingArtist' => $participatingArtist));

        }
        return $newList;
    }


    public function getAvailableHistoryEventsByDate($availableEvents, $date)
    {
        $newList = array();
        foreach ($availableEvents as $availableEvent) {
            if ($availableEvent->getEventDate() == $date) {
                array_push($newList, array('AvailableEvent' => $availableEvent));
            }
        }
        return $newList;
    }

    public function getAvailableMusicEventsByDate($availableEvents, $date)
    {
        $newList = array();
        foreach ($availableEvents as $availableEvent) {
            if ($availableEvent['AvailableEvent']->getEventDate() == $date) {
                array_push($newList, $availableEvent);
            }
        }
        return $newList;
    }

}
