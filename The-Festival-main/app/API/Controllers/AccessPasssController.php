<?php
require __DIR__ . '/../../Services/AccessPassService.php';
require __DIR__ . '/ApiController.php';

class AccessPasssController extends ApiController
{
    private $accessPassService;

    public function __construct()
    {
        $this->accessPassService = new AccessPassService();
    }

    
    public function retrieveAccessPassByIdAndEventDate()
    {
        try {
            $this->sendHeaders();
            $accessPass= NULL;

            if (!empty($_GET['id']) && !empty($_GET['eventdate'])) {
                $id = htmlspecialchars($_GET['id']);
                $eventDate=htmlspecialchars($_GET['eventdate']);

                $accessPass= $this->accessPassService->getAccessPassByIdAndDate($id, $eventDate);
            }
            echo Json_encode($accessPass);
        } catch (InvalidArgumentException | Exception $e) {
            http_response_code(500); // sending bad request error to APi request if something goes wrong
            echo $e->getMessage();
        }
    }

    

}


?>
