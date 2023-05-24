<?php
require_once __DIR__ . '/AdminPanelController.php';
require_once __DIR__ . '/../../services/InfoPagesService.php';
require_once __DIR__ . '/../../models/Exceptions/InternalErrorException.php';
require_once __DIR__ . '/../../models/Exceptions/NotAvailableException.php';

class AdminInfoPagesController extends AdminPanelController
{
    private $infoPageService;

    public function __construct()
    {
        parent::__construct();
        $this->infoPageService = new InfoPagesService();
    }

    public function index()
    {
        $title = 'Info Pages';
        $this->displaySideBar($title);
        $this->deleteRequest();
        $infoPages = $this->infoPageService->getAllInfoPages();
        $errorMessage = array();
        if (empty($infoPages)) {
            $errorMessage['NoPagesFound'] = "NO PAGES FOUND";
        }
        $selectedInfoPage = $this->getSelectedPage();
        require_once __DIR__ . '/../../views/AdminPanel/InfoPages/index.php';
    }

    private function deleteRequest()
    {
        if (isset($_POST['btnDelete']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->infoPageService->deleteInfoPage(htmlspecialchars($_POST['btnDelete']));
        }
    }
    private function getSelectedPage()
    {
        if (isset($_POST['btnPageLoad']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $infoPageId = htmlspecialchars($_POST['pageSelect']);
            return $this->infoPageService->getInfoPageById($infoPageId);
        }
    }

    public function addNewPage()
    {
        $title = 'Add New Page';
        $errorMessage['Submit'] = $this->addNewPageSubmitted();
        $this->displaySideBar($title);
        require_once __DIR__ . '/../../views/AdminPanel/InfoPages/addNewPage.php';
    }

    private function addNewPageSubmitted()
    {
        if (isset($_POST['AddNewPageSubmitted']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
            $sanitizedInput = $this->checkFieldsFilledAndSantizeInput($_POST, ['AddNewPageSubmitted']);
            if (is_string($sanitizedInput)) {
                return $sanitizedInput;
            }
            try {
                $executedResult = $this->infoPageService->addNewPage($sanitizedInput);
                if ($executedResult) {
                    header('Location: /admin/infoPages');
                    exit();
                } else {
                    return 'Something went wrong While adding new page please try again';
                }
            } catch (NotAvailableException|InternalErrorException $e) {
                return $e->getMessage();
            }
        }
    }
}