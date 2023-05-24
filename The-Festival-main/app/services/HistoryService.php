<?php
require_once __DIR__ . '/../repositories/HistoryPageRepository.php';
require_once __DIR__ . '/../models/ImageManager.php';
require_once __DIR__ . '/../services/EventService.php';
require_once __DIR__ . '/../services/ImageService.php';

class HistoryService
{
    use ImageManager;

    private $repository;
    private $repository2;
    private $imageuploadDirectory = __DIR__ . "/../public/image/Festival/History/";
    private $imageService;

    public function __construct()
    {
        $this->repository = new HistoryPageRepository();
        $this->repository2 = new HistoryEventRepository();
        $this->imageService = new ImageService();

    }

    public function getGoogleMarkerByLocationName(string $locationName, $locationPostCode)
    {
        return $this->repository->getGoogleMarkerByLocationName($locationName, $locationPostCode);
    }

    public function getAllHistoryTourLocation()
    {
        return $this->repository->getAllHistoryTourLocation();
    }

    public function getHistoryTourTimeTable()
    {
        return $this->repository->getHistoryTourTimeTable();
    }

    public function getHistoryTourLocationsByHistoryTourId($historyTourId)
    {
        return $this->repository2->getHistoryTourLocationsByHistoryTourId($historyTourId);
    }

    public function getHistoryTourImageByHistoryTourId($historyTourLocationId)
    {
        return $this->repository2->getHistoryTourImageByHistoryTourId($historyTourLocationId);
    }

    public function getHistoryEventByEventId($eventId)
    {
        return $this->repository2->getHistoryEventByEventId($eventId);

    }

    public function getHistoryTourLocationByLocationId($locationId)
    {
        return $this->repository2->getHistoryTourLocationByLocationId($locationId);

    }

//    public function insertNewTourLocation($newTourLocation, $validImages)
//    {
//        return $this->repository2->insertNewTourLocation($newTourLocation, $validImages);
//    }

    public function insertNewTourLocation($newTourLocation, $validImages): bool
    {
        // checking if the artist already exists in the database or not
//        if ($this->repository2->artistExistenceInDatabase($newTourLocation['artistName'], $newTourLocation['artistDescription'])) {
//            throw new DatabaseQueryException("Artist With same name and description already exists");
//        }
        $newImagesNamesOthers['others'] = $this->getImagesNameByMovingToDirectory($validImages['others'], $this->imageuploadDirectory);
        unset($validImages['others']); // removing others array images after updating
        $newImagesNames = $this->getImagesNameByMovingToDirectory($validImages, $this->imageuploadDirectory);
        $allImagesNames = array_merge($newImagesNames, $newImagesNamesOthers); // merging  arrays
        $imagesWithId = $this->insertImagesreturnID($allImagesNames);
        return $this->repository2->insertNewTourLocation(array_merge($newTourLocation, $imagesWithId));
    }
    public function insertImagesReturnID($images): array
    {
        $imagesID = [];
        foreach ($images as $key => $image) {
            if (is_array($image)) {
                foreach ($image as $key2 => $image2) {
                    $imagesID[$key][$key2] = $this->imageService->insertImageAndGetId($image2);
                }
            } else {
                $insertedID = $this->imageService->insertImageAndGetId($image);
                $imagesID[$key] = $insertedID;
            }
        }
        return $imagesID;
    }


    public function insertNewHistoryTour($newHistoryTour)
    {
        return $this->repository2->insertNewHistoryTour($newHistoryTour);
    }

    public function checkEventDateExistence($eventDate)
    {
        $eventDateId = $this->repository2->checkEventDateExistence($eventDate);
        if (!$eventDateId) {
            // Event date does not exist, insert new data and get the new ID
            $this->repository2->insertNewEventDate($eventDate);
            $eventDateId = $this->repository2->checkEventDateExistence($eventDate);
        }
        return $eventDateId;
    }
//    public function checkLanguageExistence($newTourLanguage){
//        return $this->repository2->checkLanguageExistence($newTourLanguage);
//    }
//    public function insertNewEventDate($eventDate)
//    {
//        return $this->repository2->insertNewEventDate($eventDate);
//    }
//    public function insertNewLanguage($language){
//        return $this->repository2->insertNewLanguage($language);
//    }
    public function getEventDateId($eventDate)
    {
        return $this->repository2->getEventDateId($eventDate);
    }

    public function checkTourTimeTableExistence($eventDateId, $timeTable)
    {

        $newTourTimeTableID = $this->repository2->checkTourTimeTableExistence($eventDateId, $timeTable);
        if (!$newTourTimeTableID) {
            // Event date does not exist, insert new data and get the new ID
            $this->repository2->insertNewTimeTable($eventDateId, $timeTable);
            $newTourTimeTableID = $this->repository2->checkTourTimeTableExistence($eventDateId, $timeTable);
        }
        return $newTourTimeTableID;
    }
//    public function insertNewTimeTable($eventDateId,$time){
//        return $this->repository2->insertNewTimeTable($eventDateId,$time);
//    }
//    public function insertNewTourTest($languageId,$timeTableId){
//        return $this->repository2->insertNewTourTest($languageId,$timeTableId);
//    }

    public function newTourData($eventDate, $language, $timeTable)
    {
        $eventDateId = $this->checkEventDateExistence($eventDate);
        $timeTableId = $this->checkTourTimeTableExistence($eventDateId, $timeTable);
        $languageId = $this->checkLanguageExistence($language);

        return $this->repository2->insertNewTourTest($languageId, $timeTableId);
    }

    public function getHistoryTourById($selectedTourId)
    {
        return $this->repository2->getHistoryTourById($selectedTourId);
    }

    public function checkLanguageExistence($newTourLanguage)
    {
        $languageId = $this->repository2->checkLanguageExistence($newTourLanguage);
        if (!$languageId) {
            // Language does not exist, insert new data and get the new ID
            $this->repository2->insertNewLanguage($newTourLanguage);
            $languageId = $this->repository2->checkLanguageExistence($newTourLanguage);
        }
        return $languageId;
    }

    public function deleteHistoryTour($selectedTourId)
    {
        $this->repository2->deleteHistoryTour($selectedTourId);
    }

    public function updateHistoryTourByTourId($selectedTourId, $updateHistoryTour)
    {
        $this->repository2->updateHistoryTourByTourId($selectedTourId,$updateHistoryTour);
    }
    public function deleteHistoryTourLocation($selectedLocationId){
        $this->repository2->deleteHistoryTourLocation($selectedLocationId);
    }
}