<?php
require __DIR__ . '/../../Services/ShopOrderService.php';
require __DIR__ . '/ApiController.php';

class ShopOrdersController extends ApiController
{
    private $shopOrderService;

    public function __construct()
    {
        $this->shopOrderService = new ShopOrderService();
    }

    public function retrievePreviousOrderId(){
        try {
            $this->sendHeaders();
            $orderId=NULL;

            $orderId = $this->shopOrderService->retrievePreviousOrderId();
            
            echo Json_encode($orderId);
        }
        catch (InvalidArgumentException|Exception $e) {
            http_response_code(500); // sending bad request error to APi request if something goes wrong
            echo $e->getMessage();
        }
    }
    
    
        
    public function addOrder()
    {
        try {
            $this->shopOrderService->addOrder($_POST['orderId'], $_POST['userId'], $_POST['orderDate'], $_POST['billId']); 

        } catch (InvalidArgumentException | Exception $e) {
            http_response_code(500);
        }
    }
    
    
}


?>
