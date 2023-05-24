<?php
require_once __DIR__ . '/EventRepository.php';
require_once __DIR__ . '/../models/OrderItem.php';
require_once __DIR__ . '/../services/PerformanceService.php';
require_once __DIR__ . '/../services/HistoryService.php';

class OrderItemRepository extends EventRepository
{
    private PerformanceService $performanceService;
    private HistoryService $historyService;

    public function __construct()
    {
        parent::__construct();
        $this->performanceService = new PerformanceService();
        $this->historyService = new HistoryService();
    }

    public function getAllOrderItemsByOrder($orderId)
    {
        $query = "SELECT orderItemId,quantity,historyTourTicketId,performanceTicketId 
                  FROM orderItem WHERE order_id = :orderId";
        $dbResult = $this->executeQuery($query, [":orderId" => $orderId]);
        if (empty($dbResult)) {
            return null;
        }
        $orderItems = array();
        foreach ($dbResult as $dbRow) {
            $orderItems[] = $this->createOrderInstance($dbRow);
        }
        return $orderItems;
    }
    private function getVatForOrderItem($event)
    {
        $vat = 0;
        if ($event instanceof Performance) {
            $vat = $this->getVatPercentageByEventName('Dance');
        }
        if ($event instanceof HistoryTour) {
            $vat = $this->getVatPercentageByEventName('A Stroll Through History');
        }
        return $vat;

    }

    /**
     * @throws InternalErrorException
     */
    private function createOrderInstance($dbRow)
    {
        $orderItem = new OrderItem();
        $orderItem->setOrderItemId($dbRow['orderItemId']);
        $orderItem->setQuantity($dbRow['quantity']);
        $orderItem->setEvent($this->getEventAccordingToOrderItem($dbRow)); // cannot be null
        $ticketType = $this->getTicketType($dbRow);
        if (is_array($ticketType)) { // only if History Tour
            $orderItem->setEventTicketType($ticketType['ticketType']);
            $orderItem->setPrice($ticketType['ticketType']);
        }
        else{
            $orderItem->setPrice($orderItem->getEvent()->getTotalPrice()); // no price difference for performance
        }
        $orderItem->setVatPercentage($this->getVatForOrderItem($orderItem->getEvent()));
        return $orderItem;
    }

    private function getTicketType($dbRow)
    {
        if (!empty($dbRow['historyTourTicketId'])) {
            return $this->getHistoryTicketTypeAndPrice($dbRow['historyTourTicketId']);
        }
        return '';
    }

    private function getHistoryTicketTypeAndPrice($historyTourTicketId)
    {
        $query = "SELECT ticket_type,price FROM historyTourTicket WHERE id = :historyTourTicketId";
        $dbResult = $this->executeQuery($query, [":historyTourTicketId" => $historyTourTicketId]);
        if (empty($dbResult)) {
            return '';
        }
        return array(
            'ticketType' => $dbResult[0]['ticket_type'],
            'price' => $dbResult[0]['price']
        );
    }

    /**
     * @throws InternalErrorException
     */
    private function getEventAccordingToOrderItem($dbResult): HistoryTour|Performance|null
    {
        if (!empty($dbResult['performanceTicketId'])) {
            $performanceId = $this->getPerformanceIdAccordingToTicketId($dbResult['performanceTicketId']);
            return $this->performanceService->getPerformanceById($performanceId);
        }
        if (!empty($dbResult['historyTourTicketId'])) {
            $tourId = $this->getHistoryTourIdAccordingToTicketId($dbResult['historyTourTicketId']);
            return $this->historyService->getHistoryTourById($tourId);
        }
        throw new InternalErrorException("No event found for this order item");

    }


    private function getPerformanceIdAccordingToTicketId($ticketId)
    {
        $query = "SELECT performanceId FROM performanceTicket WHERE performanceTicketId = :ticketId";
        $dbResult = $this->executeQuery($query, [":ticketId" => $ticketId]);
        if (empty($dbResult)) {
            return null;
        }
        return $dbResult[0]['performanceId'];
    }

    private
    function getHistoryTourIdAccordingToTicketId($ticketId)
    {
        $query = "SELECT historyTourId FROM historyTourTicket WHERE id = :ticketId";
        $dbResult = $this->executeQuery($query, [":ticketId" => $ticketId]);
        if (empty($dbResult)) {
            return null;
        }
        return $dbResult[0]['historyTourId'];
    }
// No yummy Event or /Ticket implemented

}