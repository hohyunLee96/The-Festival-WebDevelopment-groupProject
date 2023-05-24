<?php

require_once __DIR__ . '/../controller.php';
require_once __DIR__ . '/../../services/EventService.php';
require_once __DIR__ . '/../../models/User.php';

abstract class AdminPanelController extends Controller
{
    protected $eventService;

    public function __construct()
    {
        parent::__construct();
        $this->checkLoggedInUserIsAdminstrator(); // checking if the logged user or not show that this page can be logged in if the user is not logged in or
        // if the user is not an administrator, it will redirect to the not allowed page.
        $this->eventService = new EventService();
    }

    protected function displaySideBar($title, $pathToCss = null): void
    {
        // if user is logged in  then only this method get called
         $loggedUser = unserialize(serialize($_SESSION["loggedUser"]));
        $imagePath ="/image/".$loggedUser->getPicture();
        if(isset($_POST['btnLogout'] ) && $_SERVER['REQUEST_METHOD'] === 'POST'){
            $this->logoutUser();
        }
        require_once __DIR__ . '/../../views/AdminPanel/adminPanelSideBar.php';
    }

    private function checkLoggedInUserIsAdminstrator(): void
    {
        if (isset($_SESSION["loggedUser"])) {
            if (unserialize(serialize($_SESSION["loggedUser"]))->getRole() == Roles::Administrator()) {

            } else {
                $this->displayUnauthorisedAccess();
            }
        } else {
            header("location: /login");
            exit();
        }
    }

    protected function checkDateIsInPast(string $dateTimeString)
    {
        $date = new DateTime($dateTimeString);
        $currentDateTime = new DateTime();
        if ($date < $currentDateTime) {
            return true;
        }
    }

    protected function getImageFullPath($imageName): string // overwritting this Method from controller class
    {
        $directory = strtolower(substr(get_class($this), 5, -10));
        return "/image/Festival/$directory/" . $imageName;
    }

    protected function displayUnauthorisedAccess(): void
    {
        require_once __DIR__ . "/../../views/AdminPanel/NotAllowedPage.php";
        exit();  // exit the controller if user is not admin
    }

    protected function getDanceEvent()
    {
        return $this->eventService->getEventByName('Dance'); //TODO: HardCoded
    }
}