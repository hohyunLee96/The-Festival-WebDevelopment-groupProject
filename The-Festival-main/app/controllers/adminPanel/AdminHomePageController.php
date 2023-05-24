<?php
require_once __DIR__ . '/AdminPanelController.php';
require_once __DIR__ . '/../../models/Exceptions/DatabaseQueryException.php';
require_once __DIR__ . '/../../services/restaurantService.php';
require_once __DIR__ . '/../../models/restaurant.php';
require_once __DIR__ . '/../controller.php';
require_once __DIR__ . '/../../services/pageService.php';
require_once __DIR__ . '/../../models/page.php';

class AdminHomePageController extends AdminPanelController
{
    private $pageService;

    // initialize services
    function __construct()
    {
        $this->pageService = new PageService();
    }
    public function editor($query) {
        // first, we check for title in the query.
        parse_str($query, $parsedQuery);
        if(isset($parsedQuery["title"])) {
            $page = $this->pageService->getPageByTitle($parsedQuery["title"]);
            if($page != null) {
                $this->displayView($page);
            }
            else {
                // page not found
                $this->displayView(null);
            }
        }
        else {
            $this->displayView(null);
        }
    }
    public function editorSubmitted() {
        // $content = null;
        // submitting new page or updating an existing page
        if(isset($_POST["formSubmit"])) {
            // save to database
            $page = new Page();
            // comment: correct URI later.
            $page->setURI("home/page");
            $page->setTitle($_POST['pageTitle']);
            $page->setBodyContentHTML($_POST["tinyMCEform"]);
            // comment: creation time is set now by database
            // comment: correct userId later.
            $page->setCreatorUserId(1);
            // check if the page is a new page or updating existing page
            // if the pageID value exists in the submitted form, we are updating that page. If it does not exist, we are creating a new page.
            if(isset($_POST['pageID'])) {
                $this->pageService->updatePageById($_POST['pageID'], $page);
            }
            else {
                $this->pageService->createNewPage($page);
            }

            // show the user result.
            $this->displayView($_POST["tinyMCEform"]);
        }
        // if the user has clicked the delete button
        else if(isset($_POST["formDelete"])) {
            $this->pageService->deletePageById($_POST['pageID']);
            echo "deleted page with id " . $_POST['pageID'];   
        }
    }
    public function show($query) {
        // echo $query;
        // parse_str turns the query string into a dictionary
        // title=Test1 becomes {title: Test1}
        // title=Test101&id=10 becomes {title: Test101, id: 10}
        parse_str($query, $parsedQuery);
        // first, we check if the user has provided a title
        if(isset($parsedQuery["title"])) {
            // getPageByTitle searchs the database for the given title, if the given title is found, we return it. If it is not found, we return null.
            $page = $this->pageService->getPageByTitle($parsedQuery["title"]);
            if($page != null) {
                 $args = func_get_args();
                if (count($args) == 1){
                $this->displayView($page->getBodyContentHTML());}
                 else if ($args > 1) {
                $user = $args[1];
                $pageId= $page->getId();
                $this->displayViewUsingPermissions($page->getBodyContentHTML(), $user, $pageId);}
            }
            // if the title is not found in the database, we show the following error.
            else {
                echo "title not found";
            }
        }
        // if the user has not provided a title, give the following error.
        else {
            echo "title should be set";
        }
    }


    public function editHomePage($query) {
        if (!isset($_SESSION["loggedUser"])) {
            // if user is not logged in, she cannot edit restaurants.
            header("location: /home");
            exit();
        }
        if (!unserialize(serialize($_SESSION["loggedUser"]))->getRole() == Roles::Administrator()) {
            // if user is not administrator, she cannot edit restaurants either
            header("location: /home");
            exit();
        }

        $title = 'Edit Home Page';
        $pageName = "newtest";
        $this->displaySideBar($title);

        // first, we check for title in the query.
        $page = null;
        $page = $this->pageService->getPageByTitle($pageName);
        require_once __DIR__ . '/../../views/AdminPanel/HomePage/editHomePage.php';
    }

}