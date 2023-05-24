<?php
require_once __DIR__ . '/controller.php';
require_once __DIR__ . '/pagecontroller.php';
require_once __DIR__ . '/../services/userService.php';
require_once __DIR__ . '/../services/InfoPagesService.php';

class HomeController extends Controller
{

    private $userService;
    private $currentUserId;
    private $pageController;
    private $infoPageService;

    // initialize services
    function __construct()
    {
        parent::__construct();
        $this->userService = new UserService();
        if (isset($_SESSION["loggedUser"])) {
            $this->currentUserId = unserialize(serialize($_SESSION["loggedUser"]))->getId();
        }
        $this->pageController = new PageController();
        $this->infoPageService = new InfoPagesService();

    }

    public function index()
    {
        $this->displayNavBar("HomePage", '/css/homePageStyle.css');

        if (isset($_SESSION["loggedUser"]) && unserialize(serialize($_SESSION["loggedUser"]))->getRole() == Roles::Administrator()) {
            require_once __DIR__ . '/../views/home/editHomeSection.php';
        }
        $currentUserId = $this->currentUserId;
        $this->pageController->show("title=newtest", $currentUserId);
        $this->displayFooter();
    }

    public function loadInfoPages($infoPage)
    {
        $this->displayNavBar($infoPage);
        $selectedInfoPage = $this->infoPageService->getInfoPageByTitle($infoPage);
        if(empty($selectedInfoPage)){
            $this->display404PageNotFound();
            die();
        }
        require_once __DIR__ . '/../views/home/infoPage.php';
    }
}

