<?php
require_once __DIR__ . '/../../services/HistoryService.php';
require_once __DIR__ . '/AdminPanelController.php';

class AdminHistoryController extends AdminPanelController
{
    private $historyService;
    private $event;

    public function __construct()
    {
        parent::__construct();
        $this->historyService = new HistoryService();
        $this->event = $this->eventService->getEventByName('A Stroll Through History'); //TODO: HardCoded
    }

    public function tourLocations()
    {
        $title = 'Tour Location';
        $this->displaySideBar($title);
        $historyEvent = $this->eventService->getEventByName('A Stroll Through History');
        $tourLocations = $this->historyService->getAllHistoryTourLocation();
        if (empty($tourLocations)) {
            $errorMessage['tourLocation'] = "No location found in system";
        }
        if (isset($_POST['deleteHistoryLocation'])) {
            $selectedLocationId = $_POST['deleteTourLocationId'];
            $this->historyService->deleteHistoryTourLocation($selectedLocationId);
        }
        require_once __DIR__ . '/../../views/AdminPanel/History/HistoryLocationOverview.php';
    }

    public function historyTours()
    {
        $title = 'History Tour';
        $this->displaySideBar($title);

        $historyEvent = $this->eventService->getEventByName('A Stroll Through History');
        $historyTours = $historyEvent->getHistoryTours();

        if (empty($historyTours)) {
            $errorMessage['tourLocation'] = "No location found in system";
        }
        if (isset($_POST['deleteHistoryTour'])) {
            $selectedTourId = $_POST['deleteTourId'];
            $this->historyService->deleteHistoryTour($selectedTourId);

        } elseif (isset($_POST['updateHistoryTour'])) {
            $selectedTourId = $_POST['updateTourId'];
            $this->updateHistoryTourByTourId($selectedTourId);

        }
        require_once __DIR__ . '/../../views/AdminPanel/History/HistoryTourOverview.php';
    }

    public function updateHistoryTourByTourId($selectedTourId)
    {
        $title = 'Update History Tour';
        $this->displaySideBar($title);

        $getSelectedTourById = $this->historyService->getHistoryTourById($selectedTourId);

        require_once __DIR__ . '/../../views/AdminPanel/History/UpdateHistoryTour.php';
    }

    public function editTourLocation()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['tourLocation'])) {
            $tourLocation = $this->historyService->getHistoryTourLocationByLocationId($_GET['tourLocation']);
            if (empty($tourLocation)) {
                $this->display404PageNotFound();
                exit();
            }

            $title = 'Edit ' . $tourLocation->getLocationName();
            $this->displaySideBar($title);
            require_once __DIR__ . '/../../views/AdminPanel/History/EditHistoryLocation.php.php';
        }
    }

    public function update()
    {
        if (isset($_POST['updateTourLocation'])) {
            $selectedTourId = $_POST['updateTourLocation'];
            $updateHistoryTour = array(
                'updateTourLanguage' => htmlspecialchars($_POST['updateTourLanguage']),
                'updateTourDate' => date('Y-m-d', strtotime($_POST['updateTourDate'])),
                'updateTourTime' => date('H:i:s', strtotime($_POST['updateTourTime'])),
            );
            $this->historyService->updateHistoryTourByTourId($selectedTourId, $updateHistoryTour);
            header('Location: /admin/historyTours');
        }
    }

    public function addHistoryTourLocation()
    {
        $title = 'Add Tour Location';
        //  $this->displaySideBar($title);
        $errorMessage['Submit'] = $this->addHistoryTourSubmitted();

//        if (isset($_POST['addNewTourLocation'])) {
//            $this->insertNewTourLocation();
//        }
        require_once __DIR__ . '/../../views/AdminPanel/History/AddHistoryLocation.php';
    }

    function insertNewTourLocation()
    {
        // File has been successfully uploaded and moved to the desired folder
        $newTourLocation = array(
            'tourStreetName' => htmlspecialchars($_POST['tourStreetName']),
            'tourCountry' => htmlspecialchars($_POST['tourCountry']),
            'tourStreetNumber' => htmlspecialchars($_POST['tourStreetNumber']),
            'tourPostCode' => htmlspecialchars($_POST['tourPostCode']),
            'tourCity' => htmlspecialchars($_POST['tourCity']),
            'tourLocationName' => htmlspecialchars($_POST['tourLocationName']),
        );
        $this->historyService->insertNewTourLocation($newTourLocation);
    }

    private function addHistoryTourSubmitted()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addNewTourLocation'])) {
            $sanitizedInput = $this->checkFieldsFilledAndSantizeInput($_POST, ['addNewTourLocation']);
            $validImages = $this->processImagesWithFiles($_FILES, ['others']);
            if (is_string($sanitizedInput)) { // check if the controller sends some error message or not
                return $sanitizedInput;
            } else if (is_string($validImages)) {
                return $validImages;
            } else {
                try {
                    $dbResult = $this->historyService->insertNewTourLocation($sanitizedInput, $validImages);
                    if ($dbResult) {
                        header("location: /admin/history/tourLocations");
                        exit();
                    } else {
                        return "Error while adding the artist,Please try again";
                    }
                } catch (DatabaseQueryException|uploadFileFailedException $e) {
                    return $e->getMessage();
                }
            }
        }
    }

    public function addHistoryTour()
    {
        $title = 'Add History Tour';
        $this->displaySideBar($title);

        if (isset($_POST['addNewHistoryTour'])) {
            $this->checkUserInputs();
            $eventDate = DateTime::createFromFormat('Y-m-d', $_POST['newTourDate'])->format('Y-m-d');
            $language = $_POST['newTourLanguage'];
            $timeTable = $_POST['newTourTime'];

            // Check if custom language is selected
            if ($language == 'custom') {
                $customLanguage = $_POST['customLanguage'];
                $language = $customLanguage;
            }

            $this->historyService->newTourData($eventDate, $language, $timeTable);
        }

        require_once __DIR__ . '/../../views/AdminPanel/History/AddHistoryTour.php';
    }

    public function checkUserInputs()
    {
        if (empty($_POST['newTourDate'])) {

            return "Please enter a date";
        }
        if (empty($_POST['newTourLanguage'])) {
            return "Please select a language";
        }
        if (empty($_POST['newTourTime'])) {
            return "Please select a time";
        }
        return true;
    }
}
