<?php
require_once __DIR__ . '/../../services/ArtistService.php';
require_once __DIR__ . '/AdminPanelController.php';
require_once __DIR__ . '/../../services/PerformanceService.php';
require_once __DIR__ . '/../../models/Exceptions/DatabaseQueryException.php';
require_once __DIR__ . '/../../models/Exceptions/NotAvailableException.php';
require_once __DIR__ . '/../../models/Exceptions/InternalErrorException.php';

class AdminDanceController extends AdminPanelController
{
    private $artistService;
    private $danceEventService;
    private $performanceService;

    public function __construct()
    {
        parent::__construct();
        $this->artistService = new ArtistService();
        $this->danceEventService = new DanceEventService();
        $this->performanceService = new PerformanceService();
    }
    public function index()
    {
        $this->performances();
    }

    public function artists()
    {
        $title = 'Artists';
        $this->displaySideBar($title);
        $artists = $this->artistService->getAllArtists();
        if (empty($artists)) {
            $errorMessage['artists'] = "No Artists found in system";
        }
        require_once __DIR__ . '/../../views/AdminPanel/Dance/artistsOverview.php';
    }

    public function editArtist()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['artist'])) {
            $artist = $this->artistService->getArtistByArtistID($_GET['artist']);
            if (empty($artist)) {
                $this->display404PageNotFound();
                exit();
            }
            $styles = $this->artistService->getAllStyles();
            if (empty($styles)) {
                $errorMessage['styles'] = "No Styles found in system";
            }
            $title = 'Edit ' . $artist->getArtistName();
            $this->displaySideBar($title);
            require_once __DIR__ . '/../../views/AdminPanel/Dance/EditArtist.php';
        }
    }

    public function venues()
    {
        $title = 'Venues';
        $this->displaySideBar($title);
        $venues = $this->eventService->getAllLocations();
        if (empty($venues)) {
            $errorMessage['venues'] = "No Venues found in system";
        }
        require_once __DIR__ . '/../../views/AdminPanel/Dance/VenuesOverview.php';
    }

    public function editVenue()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['venueId'])) {
            $venueId = htmlspecialchars($_GET['venueId']);
            $venue = $this->danceEventService->getVenueById($venueId);
            if (empty($venue)) {
                $this->display404PageNotFound();
                exit();
            }
            $title = 'Edit ' . $venue->getLocationName();
            $this->displaySideBar($title);
            require_once __DIR__ . '/../../views/AdminPanel/Dance/EditVenue.php';
        }
    }

    public function addVenue()
    {
        $title = 'Add Venue';
        $errorMessage = $this->addVenueSubmitted();
        $this->displaySideBar($title);
        require_once __DIR__ . '/../../views/AdminPanel/Dance/AddVenue.php';
    }

    private function addVenueSubmitted()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['AddVenue'])) {
            $sanitizedInput = $this->checkFieldsFilledAndSantizeInput($_POST, ['AddVenue', 'houseNumberAdditional']); // additional house number can be empty
            // house number Additional is optional and button is of course
            if (is_string($sanitizedInput)) { // check if the controller sends some error message or not
                return $sanitizedInput;
            } else {
                if (!$this->checkPostCodeValid($sanitizedInput['postCode'])) {
                    return "THe entered Post Code is not valid {$sanitizedInput['postCode']}";
                }
                try {
                    $dbResult = $this->eventService->addLocation($sanitizedInput);
                    if ($dbResult) {
                        header("location: /admin/dance/venues");
                        exit();
                    } else {
                        return "Error while adding the venue,Please try again";
                    }
                } catch (DatabaseQueryException $e) {
                    return $e->getMessage();
                }
            }
        }
    }

    public function addArtist()
    {
        $errorMessage = array();
        $title = 'Add Artist';
        $errorMessage['Submit'] = $this->addArtistSubmitted();
        $this->displaySideBar($title);
        $styles = $this->artistService->getAllStyles();
        if (empty($styles)) {
            $errorMessage['styles'] = "No styles found in system";
        }
        require_once __DIR__ . '/../../views/AdminPanel/Dance/AddArtist.php';

    }

    private function addArtistSubmitted()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['AddArtist'])) {
            $sanitizedInput = $this->checkFieldsFilledAndSantizeInput($_POST, ['AddArtist'], ['artistStyles']);
            $validImages = $this->processImagesWithFiles($_FILES, ['others']);
            if (is_string($sanitizedInput)) { // check if the controller sends some error message or not
                return $sanitizedInput;
            } else if (is_string($validImages)) {
                return $validImages;
            } else {
                try {
                    $dbResult = $this->artistService->addArtist($sanitizedInput, $validImages);
                    if ($dbResult) {
                        header("location: /admin/dance/artists");
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

    public function performances()
    {
        $title = 'Performances';
        $errorMessage = array();
        $this->displaySideBar($title);
        $artistPerformances = $this->getDanceEvent()->getPerformances();
        if (empty($artistPerformances)) {
            $errorMessage['artistPerformances'] = "No Artist Performances found for {$this->getDanceEvent()->getEventName()} event";
        }
        require_once __DIR__ . '/../../views/AdminPanel/Dance/PerformancesOverview.php';
    }

    public function editPerformance()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET' || !isset($_GET['performanceId'])) {
            $this->display404PageNotFound();
            exit();
        }

        $editingPerformance = $this->performanceService->getPerformanceById(htmlspecialchars($_GET['performanceId']));
        if (empty($editingPerformance)) {
            $this->display404PageNotFound();
            exit();
        }

        $allArtists = $this->artistService->getAllArtists();
        $allVenues = $this->eventService->getAllLocations();
        $allSessions = $this->performanceService->getAllPerformanceSessions();
        $errorMessage = [];

        if (empty($allArtists)) {
            $errorMessage['artists'] = "No Artists found in system";
        }

        if (empty($allVenues)) {
            $errorMessage['venues'] = "No Venues found in system";
        }

        if (empty($allSessions)) {
            $errorMessage['sessions'] = "No Performance Sessions found in system";
        }

        $title = 'Edit Performance ' . $editingPerformance->getPerformanceId();
        $this->displaySideBar($title);
        require_once __DIR__ . '/../../views/AdminPanel/Dance/EditPerformance.php';
    }


    public function addPerformance()
    {
        $title = 'Add Performance';
        $errorMessage = $this->addPerformanceSubmitted();
        $this->displaySideBar($title);
        $allArtists = $this->artistService->getAllArtists();
        $allLocations = $this->eventService->getAllLocations();
        $performanceSessions = $this->performanceService->getAllPerformanceSessions();
        require_once __DIR__ . '/../../views/AdminPanel/Dance/AddPerformance.php';
    }

    private function addPerformanceSubmitted()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['AddArtistPerformance'])) {
            $sanitizedInput = $this->checkFieldsFilledAndSantizeInput($_POST, ['AddArtistPerformance'], ['artists']);
            if (is_string($sanitizedInput)) {
                return $sanitizedInput;
            } else {
                if ($this->checkDateIsInPast('' . $sanitizedInput['performanceDate'] . ' ' . $sanitizedInput['startTime'])) {
                    return "Entered Date and Time is in the past";
                }
                try {
                    $sanitizedInput['duration'] = $this->getDurationInMinutes($sanitizedInput['startTime'], $sanitizedInput['endTime']); // adding new key with value to array
                    $dbResult = $this->performanceService->addPerformanceWithEventId($this->getDanceEvent()->getEventId(), $sanitizedInput);
                    if ($dbResult) {
                        header("location: /admin/dance/performances");
                        exit();
                    } else {
                        return "Error while adding the performance";
                    }
                } catch (DatabaseQueryException|InternalErrorException|NotAvailableException $e) {
                    return $e->getMessage(); // will return the error message that got while adding the performance
                } catch (Exception $e) {
                    return "Something went wrong, Please try again";
                }
            }
        }
    }

    private function formatArtistName($artists)
    {
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

    private function formatArtistStylesToDisplay($artistStyles)
    {
        $styles = '';
        if (is_array($artistStyles)) {
            foreach ($artistStyles as $artistStyle) {
                $styles = $styles . $artistStyle->getStyleName() . ' | ';
            }
            // Remove the last '|' character
            $styles = substr($styles, 0, -2);
        } else {
            $styles = $artistStyles;
        }
        return $styles;
    }

    /**
     * @throws Exception
     */
    private function getDurationInMinutes($startTime, $endTime): float|int|string
    {
        $startTime = new DateTime($startTime);
        $endTime = new DateTime($endTime);
        $duration = $startTime->diff($endTime);
        return $duration->format('%h') * 60 + $duration->format('%i');

    }

    private function checkPostCodeValid($postCode): bool
    {
        $postCode = $this->sanitizeInput($postCode);
        if (preg_match('/^[0-9]{4}[a-zA-Z]{2}$/', $postCode)) {
            return true;
        }
        return false;
    }

}