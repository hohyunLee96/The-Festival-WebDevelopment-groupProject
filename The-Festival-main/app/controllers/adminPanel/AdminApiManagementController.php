<?php
require_once __DIR__ . '/../../services/ApiKeyService.php';
require_once __DIR__ . '/AdminPanelController.php';
require_once __DIR__ . '/../../models/Exceptions/DatabaseQueryException.php';
class AdminApiManagementController extends AdminPanelController
{
   private  $apiKeyService;
    public function __construct()
    {
        $this->apiKeyService = new ApiKeyService();
    }
    public function index()
    {
        $title = 'Api Management';
        $errorMessage = array();
        $this->displaySideBar($title);
        $apiKeys = $this->apiKeyService->getAllApiKeys();
        if(empty($apiKeys)){
            $errorMessage['apiKeys']='No Api Keys Found In the system';
        }
        require __DIR__ . '/../../views/AdminPanel/ApiManagement/ApiManagementOverview.php';
    }
    public function addApiKey(){
        $title = 'Add Api Key';
        $errorMessage = array();
        $errorMessage['Submit']=$this->addApiKeySubmitted();
        $this->displaySideBar($title);
        require __DIR__ . '/../../views/AdminPanel/ApiManagement/AddApiKey.php';
    }
    private function addApiKeySubmitted(){
        if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['createApiKey'])){
            $sanitizedInput = $this->checkFieldsFilledAndSantizeInput($_POST,['createApiKey']);
            if (is_string($sanitizedInput)) {
                return $sanitizedInput;
            }
            try{
                if($this->apiKeyService->createApiKey($sanitizedInput)){
                    header('Location: /admin/ApiManagement/');
                    exit;
                }
                return "something went wrong, please try again";
            }
            catch (DatabaseQueryException $e){
                return $e->getMessage();
            }
        }
    }
}