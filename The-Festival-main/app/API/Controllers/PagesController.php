<?php
require __DIR__ . '/../../Services/pageService.php';
require __DIR__ . '/ApiController.php';

class PagesController extends Controller
{
    private $pageService;

    public function __construct()
    {
        $this->pageService = new PageService();
    }

    public function retrievePageData(){
        try {
            $this->sendHeaders();
            $page=NULL;

            if (!empty($_GET['id'])) {
                $id = htmlspecialchars($_GET['id']);
                $page = $this->pageService->getPageByIdWithUrl($id);
            }
            echo Json_encode($page);
        }
        catch (InvalidArgumentException|Exception $e) {
            http_response_code(500); // sending bad request error to APi request if something goes wrong
            echo $e->getMessage();
        }
    }
}


?>
