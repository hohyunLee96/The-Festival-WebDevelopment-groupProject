<?php
require __DIR__ . '/../../Services/ShoppingBasketService.php';
require __DIR__ . '/ApiController.php';

class ShoppingBasketsController extends ApiController
{
    private $shoppingBasketService;

    public function __construct()
    {
        $this->shoppingBasketService = new ShoppingBasketService();
    }
    

      public function retrievePreviousShoppingBasket()
    {
        try {
            $this->sendHeaders();
            $shoppingBasketId = NULL;

            $shoppingBasketId = $this->shoppingBasketService->retrievePreviousShoppingBasket();

            echo Json_encode($shoppingBasketId);
        } catch (InvalidArgumentException | Exception $e) {
            http_response_code(500); // sending bad request error to APi request if something goes wrong
            echo $e->getMessage();
        }
    }
    
    
      public function retrievePreviousShoppingBasketId()
    {
        try {
            $this->sendHeaders();
            $shoppingBasketId = NULL;

            $shoppingBasketId = $this->shoppingBasketService->retrieveIdOfPreviousShoppingBasket();

            echo Json_encode($shoppingBasketId);
        } catch (InvalidArgumentException | Exception $e) {
            http_response_code(500); // sending bad request error to APi request if something goes wrong
            echo $e->getMessage();
        }
    }
    
    


    public function checkExistenceOfBasketForUser()
    {
        try {
            $this->sendHeaders();
            $shoppingBasketExists = NULL;

            if (!empty($_GET['id'])) {
                $userId= htmlspecialchars($_GET['id']);
                $shoppingBasketExists = $this->shoppingBasketService->checkExistenceOfBasketForUser($userId);

            }

            echo Json_encode($shoppingBasketExists);
        } catch (InvalidArgumentException | Exception $e) {
            http_response_code(500); // sending bad request error to APi request if something goes wrong
            echo $e->getMessage();
        }
    }


    public function retrieveBasketOfUser()
    {
        try {
            $this->sendHeaders();
            $shoppingBasket= NULL;

            if (!empty($_GET['id'])) {
                $userId= htmlspecialchars($_GET['id']);
                $shoppingBasket= $this->shoppingBasketService->retrieveBasketOfUser($userId);

            }

            echo Json_encode($shoppingBasket);
        } catch (InvalidArgumentException | Exception $e) {
            http_response_code(500); // sending bad request error to APi request if something goes wrong
            echo $e->getMessage();
        }
    }




    public function addShoppingBasket()
    {
        try {
            $this->shoppingBasketService->addShoppingBasket($_POST['shoppingBasketId'], $_POST['userId'], $_POST['billId']);

        } catch (InvalidArgumentException | Exception $e) {
            http_response_code(500);
        }
    }


}


?>
