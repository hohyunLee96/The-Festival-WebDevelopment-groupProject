<?php
require __DIR__ . '/../../Services/OrderTicketService.php';
require __DIR__ . '/ApiController.php';

class OrderTicketsController extends ApiController
{
    private $orderTicketService;

    public function __construct()
    {
        $this->orderTicketService = new orderTicketService();
    }

    public function retrievePreviousTicketId(){
        try {
            $this->sendHeaders();
            $ticketId=NULL;

            $ticketId = $this->orderTicketService->retrievePreviousTicketId();
            
            echo Json_encode($ticketId);
        }
        catch (InvalidArgumentException|Exception $e) {
            http_response_code(500); // sending bad request error to APi request if something goes wrong
            echo $e->getMessage();
        }
    }
    
    
    
    public function addTicket()
    {
        try {
            $this->orderTicketService->addTicket($_POST['orderTicketId'], $_POST['availableEventId'], $_POST['ticketOptions'], $_POST['orderId'], ); //$ticketData[0], $ticketData[1], $ticketData[2], $ticketData[3]);

        } catch (InvalidArgumentException | Exception $e) {
            http_response_code(500);
        }
    }



}


?>
