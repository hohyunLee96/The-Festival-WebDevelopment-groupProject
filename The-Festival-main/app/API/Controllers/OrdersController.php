<?php
require_once __DIR__ . '/ApiController.php';
require_once __DIR__ . '/../../Services/ApiKeyService.php';
require_once __DIR__ . '/../../services/OrderService.php';
class OrdersController extends ApiController
{
    private $apiKeyService;
    private $orderService;

    public function __construct()
    {
        $this->apiKeyService = new ApiKeyService();
        $this->orderService = new OrderService();
    }

    public function index()
    {
        $apiKey = $this->getAPiKeyFromHeader();
        if (empty($apiKey)) {
            $this->respondWithError(401, 'API key is required In order to access this resource');
            return;
        }
        if (!$this->apiKeyService->isApiKeyValid($apiKey)) {
            $this->respondWithError(401, 'API key is invalid');
            return;
        }
        try{
            $orders = $this->orderService->getAllOrders();
            if(empty($orders)){
                $this->respondWithError(204, 'No orders  in the db');
                return;
            }
            $this->respond($orders);
        }
        catch (InternalErrorException $e){
            $this->respondWithError(500, $e->getMessage());
            return;
        }


    }

    private function getAPiKeyFromHeader()
    {
        return $_SERVER['HTTP_APIKEY'] ?? null;
    }
}