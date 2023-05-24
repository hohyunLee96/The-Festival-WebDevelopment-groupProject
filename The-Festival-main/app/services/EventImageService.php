<?php
require_once __DIR__ . '/../repositories/EventImageRepository.php';
require_once __DIR__ . '/../services/ImageService.php';

class EventImageService
{
    private $eventImageRepository;

    private $imageService;

    public function __construct()
    {
        $this->eventImageRepository = new EventImageRepository();
        $this->imageService = new ImageService();

    }
     
    function getEventImageIdByEventAndType($eventId, $specification){

        return $this->eventImageRepository->getEventImageIdByEventAndType($eventId, $specification);
    }
    
    function getEventImagePath($eventId, $specification){
           
     $imageId = $this->eventImageRepository->getEventImageIdByEventAndType($eventId, $specification)->getImageId();
     //echo $imageId;
     $imagePath = $this->imageService->getImageById($imageId);
    // echo $imagePath;
     return $imagePath;

    }

 
}
