<?php
require_once __DIR__ . '/EventRepository.php';
require_once __DIR__ . '/../models/OrderItem.php';
require_once __DIR__ . '/../models/restaurantOrderItem.php';
require_once __DIR__ . '/../models/historyTourOrderItem.php';
require_once __DIR__ . '/../models/performanceOrderItem.php';


class ShoppingCartRepository extends EventRepository
{
    public function getOrderByUserId($userId)
    {
        try {
            $stmt = $this->connection->prepare("SELECT orderId FROM `order` WHERE user_id = :user_id and orderStatus = 'open';");
            $stmt->bindValue(':user_id', $userId);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
//            return $result['orderId'];
            if ($result !== false) {
                return $result['orderId'];
            } else {
                return null;
            }

        } catch (PDOException $e) {
            // Handle the exception here
            // For example, you could log the error message and return null
            error_log("Error fetching order for user ID $userId: " . $e->getMessage());
            return null;
        }
    }

    public function getOrderByOrderId($orderId)
    {
        try {
            $stmt = $this->connection->prepare("SELECT orderId FROM `order` WHERE orderId = :orderId and orderStatus = 'open';");
            $stmt->bindValue(':orderId', $orderId);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['orderId'];

        } catch (PDOException $e) {
            // Handle the exception here
            // For example, you could log the error message and return null
            error_log("Error fetching order for user ID $userId: " . $e->getMessage());
            return null;
        }
    }

    public function createOrder($userId)
    {
        try {
            $stmt = $this->connection->prepare("INSERT INTO `order` (user_id, order_date, orderStatus) VALUES (:user_id, NOW(), 'open');");
            $stmt->bindValue(':user_id', $userId);
            $stmt->execute();
            $lastInsertedId = $this->connection->lastInsertId();
            return $lastInsertedId;
        } catch (PDOException $e) {
            // handle the error here, for example:
            echo "Error creating order: " . $e->getMessage();
            // or redirect to an error page
        }
    }
    public function decreasePerformanceTicketQuantityByOrderId($orderId){
        try {
            $stmt = $this->connection->prepare("UPDATE performance 
                                                        SET availableTickets = availableTickets - (
                                                        SELECT COUNT(orderitem.performanceTicketId)
                                                        FROM performanceticket
                                                        JOIN orderitem ON orderitem.performanceTicketId = performanceticket.performanceTicketId
                                                        WHERE performanceticket.performanceId = performance.performanceId
                                                        AND orderitem.order_id = :order_id)
                                                        WHERE performance.performanceId = (
                                                        SELECT performanceticket.performanceId
                                                        FROM performanceticket
                                                        JOIN orderitem ON orderitem.performanceTicketId = performanceticket.performanceTicketId
                                                        WHERE orderitem.order_id = :order_id
                                                        LIMIT 1);");
            $stmt->bindValue(':order_id', $orderId);
            $stmt->bindValue(':order_id', $orderId);
            $stmt->execute();
        } catch (PDOException $e) {
            // handle the error here, for example:
            echo "Error creating order: " . $e->getMessage();
            // or redirect to an error page
        }
    }
    public function decreaseHistoryTourTicketQuantityByOrderId($orderId){
        try {
            $stmt = $this->connection->prepare("UPDATE historytour 
                                                    SET availableHistoryTour = availableHistoryTour - (
                                                        SELECT 
                                                        CASE
                                                        WHEN historytourticket.ticket_type = 'family' THEN COUNT(orderitem.historyTourTicketId) * 4
                                                        ELSE COUNT(orderitem.historyTourTicketId)
                                                        END
                                                        FROM historytourticket
                                                        JOIN orderitem ON orderitem.historyTourTicketId = historytourticket.id
                                                        WHERE historytourticket.historyTourId = historytour.historyTourId
                                                        AND orderitem.order_id = :order_id
                                                        GROUP BY historytourticket.ticket_type)
                                                        WHERE historytour.historyTourId IN (
                                                        SELECT historytourticket.historyTourId
                                                        FROM historytourticket
                                                        JOIN orderitem ON orderitem.historyTourTicketId = historytourticket.id
                                                        WHERE orderitem.order_id = :order_id);");
            $stmt->bindValue(':order_id', $orderId);
            $stmt->bindValue(':order_id', $orderId);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            // Handle the exception here
            // For example, you could log the error message and return null
            error_log("Error fetching ticket ID for ticket name $ticketName: " . $e->getMessage());
            return null;
        }
    }


    public function getTicketId($newOrderItem)
    {
        $stmt = $this->connection->prepare("SELECT historytourticket.id, historytourticket.price
                                        FROM historytour
                                        JOIN language ON language.languageId = historytour.languageId
                                        JOIN timetable ON timetable.timeTableId = historytour.timeTableId
                                        JOIN historytourticket ON historytourticket.historyTourId = historytour.historyTourId
                                        JOIN eventdate ON eventdate.eventDateId = timetable.eventDateId
                                        WHERE language.name = :name
                                        AND historytourticket.ticket_type = :ticket_type
                                        AND eventdate.date = :date
                                        AND timetable.time = :time");

        $stmt->bindParam(':name', $newOrderItem['TourLanguage'], PDO::PARAM_STR);
        $stmt->bindParam(':ticket_type', $newOrderItem['tourTicketType'], PDO::PARAM_STR);
        $tourTicketDate = date('Y-m-d', strtotime($newOrderItem['tourTicketDate']));
        $stmt->bindParam(':date', $tourTicketDate, PDO::PARAM_STR);
        $stmt->bindParam(':time', $newOrderItem['tourTicketTime'], PDO::PARAM_STR);

        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result !== false) {
            return $result['id'];
        } else {
            return null;
        }
    }

    public function createTourOrderItem($orderId, $ticketId, $quantity)
    {
                // Check if there are enough available tickets
        $availableTickets = $this->checkTourAvailableTicket($ticketId);
        if ($availableTickets === null || $availableTickets < $quantity) {
            throw new Exception('Not enough available tickets. Only ' . $availableTickets . ' tickets available.');
        }
        $stmt = $this->connection->prepare("INSERT INTO orderitem (order_id, historyTourTicketId, quantity) VALUES (:order_id, :historyTourTicketId, :quantity)");
        $stmt->bindParam(':order_id', $orderId);
        $stmt->bindParam(':historyTourTicketId', $ticketId, PDO::PARAM_INT);
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $this->updateTotalPrice($orderId);
            return $this->connection->lastInsertId();
        } else {
            return false;
        }
    }

    public function createPerformanceOrderItem($orderId, $ticketId, $quantity)
    {
        $stmt = $this->connection->prepare("INSERT INTO orderitem (order_id, performanceTicketId, quantity) VALUES (:order_id, :performanceTicketId, :quantity)");
        $stmt->bindParam(':order_id', $orderId);
        $stmt->bindParam(':performanceTicketId', $ticketId, PDO::PARAM_INT);
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $this->updateTotalPrice($orderId);
            return $this->connection->lastInsertId();
        } else {
            return false;
        }
    }

    public function getHistoryTourOrdersByUserId($userId)
    {
        try {
            $stmt = $this->connection->prepare("SELECT orderItem.orderItemId, orderitem.quantity, historytourticket.ticket_type, historytourticket.price, language.name
                                            FROM orderitem
                                            JOIN historytourticket ON historytourticket.id = orderitem.historyTourTicketId
                                            JOIN `order` ON `order`.orderId = orderitem.order_id
                                            JOIN historytour on historytour.historyTourId = historytourticket.historyTourId
                                            JOIN language on  language.languageId = historytour.languageId
                                            WHERE `order`.user_id = :user_id and `order`.orderStatus = 'open'");
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            $dbRow = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $historyOrderItem = array();
            foreach ($dbRow as $row) {
                $historyOrderItem[] = $this->createHistoryOrderItemObject($row);
            }
            return $historyOrderItem;

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    public function closeOrder($orderId){
        try {
            $stmt = $this->connection->prepare("UPDATE `order` SET orderStatus = 'closed' WHERE orderId = :orderId");
            $stmt->bindParam(':orderId', $orderId, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function getHistoryTourOrdersByOrderId($orderId)
    {
        try {
            $stmt = $this->connection->prepare("SELECT orderItem.orderItemId, orderitem.quantity, historytourticket.ticket_type, historytourticket.price, language.name
                                            FROM orderitem
                                            JOIN historytourticket ON historytourticket.id = orderitem.historyTourTicketId
                                            JOIN `order` ON `order`.orderId = orderitem.order_id
                                            JOIN historytour on historytour.historyTourId = historytourticket.historyTourId
                                            JOIN language on  language.languageId = historytour.languageId
                                            WHERE `order`.orderId = :orderId and `order`.orderStatus = 'open'");
            $stmt->bindParam(':orderId', $orderId, PDO::PARAM_INT);
            $stmt->execute();
            $dbRow = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $historyOrderItem = array();
            foreach ($dbRow as $row) {
                $historyOrderItem[] = $this->createHistoryOrderItemObject($row);
            }
            return $historyOrderItem;

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    public function getPerformanceOrdersByOrderId($orderId)
    {
        try {
            $stmt = $this->connection->prepare("SELECT orderItem.orderItemId, orderitem.quantity, location.locationName, artist.artistName, performance.totalPrice, performancesession.sessionName
                                                    FROM orderitem
                                                    JOIN performanceTicket pt1 ON pt1.performanceTicketId = orderitem.performanceTicketId
                                                    JOIN performance ON pt1.performanceId = performance.performanceId
                                                    JOIN participatingartist on participatingartist.performanceId = performance.performanceId
                                                    JOIN artist ON artist.artistId = participatingartist.artistId
                                                    JOIN `order` ON `order`.orderId = orderitem.order_id
                                                    JOIN location ON location.locationId = performance.venueId
                                                    JOIN performancesession on performancesession.performanceSessionId = performance.SessionId
                                                    WHERE `order`.orderId = :orderId and `order`.orderStatus = 'open';");
            $stmt->bindParam(':orderId', $orderId, PDO::PARAM_INT);
            $stmt->execute();
            $dbRow = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $performanceOrderItem = array();
            foreach ($dbRow as $row) {
                $performanceOrderItem[] = $this->createPerformanceOrderItemObject($row);
            }
            return $performanceOrderItem;

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    private function createHistoryOrderItemObject($row)
    {
        $historyOrderItem = new HistoryTourOrderItem();
        $historyOrderItem->setOrderItemId($row['orderItemId']);
        $historyOrderItem->setQuantity($row['quantity']);
        $historyOrderItem->setTicketType($row['ticket_type']);
        $historyOrderItem->setPrice($row['price']);
        $historyOrderItem->setLanguage($row['name']);
        return $historyOrderItem;
    }

    public function getRestaurantOrdersByUserId($userId)
    {
        try {
            $stmt = $this->connection->prepare("SELECT orderItem.orderItemId, orderitem.quantity, restaurant.name, restaurant.foodTypes, restaurantticket.ticketType, restaurantticket.price
                                            FROM orderitem
                                            JOIN restaurantticket ON restaurantticket.restaurantTicketId = orderitem.restaurantTicketId
                                            JOIN `order` ON `order`.orderId = orderitem.order_id
                                            JOIN restaurant on restaurant.id = restaurantticket.restaurantId
                                            WHERE `order`.user_id = :user_id and `order`.orderStatus = 'open' and `order`.orderStatus = 'open';");
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            $dbRow = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $restaurantOrderItem = array();
            foreach ($dbRow as $row) {
                $restaurantOrderItem[] = $this->createRestaurantOrderItemObject($row);
            }
            return $restaurantOrderItem;

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    private function createRestaurantOrderItemObject($row)
    {
        $restaurantOrderItem = new RestaurantOrderItem();
        $restaurantOrderItem->setOrderItemId($row['orderItemId']);
        $restaurantOrderItem->setQuantity($row['quantity']);
        $restaurantOrderItem->setTicketType($row['ticketType']);
        $restaurantOrderItem->setPrice($row['price']);
        $restaurantOrderItem->setRestaurantName($row['name']);
        $restaurantOrderItem->setFoodType($row['foodTypes']);
        return $restaurantOrderItem;
    }

    public function getPerformanceOrdersByUserId($userId)
    {
        try {
            $stmt = $this->connection->prepare("SELECT orderItem.orderItemId, orderitem.quantity, location.locationName, artist.artistName, performance.totalPrice, performancesession.sessionName
                                                    FROM orderitem
                                                    JOIN performanceTicket pt1 ON pt1.performanceTicketId = orderitem.performanceTicketId
                                                    JOIN performance ON pt1.performanceId = performance.performanceId
                                                    JOIN participatingartist on participatingartist.performanceId = performance.performanceId
                                                    JOIN artist ON artist.artistId = participatingartist.artistId
                                                    JOIN `order` ON `order`.orderId = orderitem.order_id
                                                    JOIN location ON location.locationId = performance.venueId
                                                    JOIN performancesession on performancesession.performanceSessionId = performance.SessionId
                                                    WHERE `order`.user_id = :user_id and `order`.orderStatus = 'open';");
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            $dbRow = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $performanceOrderItem = array();
            foreach ($dbRow as $row) {
                $performanceOrderItem[] = $this->createPerformanceOrderItemObject($row);
            }
            return $performanceOrderItem;

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    private function createPerformanceOrderItemObject($row)
    {
        $performanceOrderItem = new PerformanceOrderItem();
        $performanceOrderItem->setOrderItemId($row['orderItemId']);
        $performanceOrderItem->setQuantity($row['quantity']);
        $performanceOrderItem->setArtistName($row['artistName']);
        $performanceOrderItem->setPrice($row['totalPrice']);
        $performanceOrderItem->setVenue($row['locationName']);
        $performanceOrderItem->setGenre($row['sessionName']);
        return $performanceOrderItem;
    }

    public function getOrderItemIdByTicketId($ticketId, $order)
    {
        try {
            $stmt = $this->connection->prepare("SELECT orderItemId 
                                                    FROM orderitem 
                                                    JOIN `Order` ON `Order`.orderid = orderitem.order_id
                                                    WHERE historyTourTicketId = :historyTourTicketId AND `Order`.orderId = :orderId;
                                                ;");
            $stmt->bindValue(':historyTourTicketId', $ticketId);
            $stmt->bindValue(':orderId', $order);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result !== false) {
                return $result['orderItemId'];
            } else {
                return null;
            }

        } catch (PDOException $e) {
            // Handle the exception here
            // For example, you could log the error message and return null
            error_log("Error fetching order for user ID $userId: " . $e->getMessage());
            return null;
        }
    }

    public function getPerformanceOrderItemIdByTicketId($ticketId, $order)
    {
        try {
            $stmt = $this->connection->prepare("SELECT orderItemId 
                                                    FROM orderitem 
                                                    JOIN `Order` ON `Order`.orderid = orderitem.order_id
                                                    WHERE performanceTicketId = :performanceTicketId AND `Order`.orderId = :orderId;
                                                ");
            $stmt->bindValue(':performanceTicketId', $ticketId);
            $stmt->bindValue(':orderId', $order);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result !== false) {
                return $result['orderItemId'];
            } else {
                return null;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function getPerformanceTicketIdByPerformanceId($performanceId)
    {
        try {
            $stmt = $this->connection->prepare("SELECT performanceTicketId 
                                                    FROM performanceTicket 
                                                    WHERE performanceId = :performanceId;");
            $stmt->bindValue(':performanceId', $performanceId);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['performanceTicketId'];

        } catch (PDOException $e) {
            // Handle the exception here
            // For example, you could log the error message and return null
            error_log("Error fetching order for user ID $userId: " . $e->getMessage());
            return null;
        }
    }
//    public function getPerformanceTicketIdByPerformanceId($performanceId){
//        try {
//            $stmt = $this->connection->prepare("SELECT performanceTicketId
//                                                    FROM performanceTicket
//                                                    WHERE performanceId = :performanceId;");
//            $stmt->bindValue(':performanceId', $performanceId);
//            $stmt->execute();
//            $result = $stmt->fetch(PDO::FETCH_ASSOC);
//            return $result['performanceTicketId'];
//
//        } catch (PDOException $e) {
//            // Handle the exception here
//            // For example, you could log the error message and return null
//            error_log("Error fetching order for user ID $userId: " . $e->getMessage());
//            return null;
//        }
//    }

//    public function updateTourOrderItemByTicketId($ticketId, $quantity)
//    {
//        try {
//            $stmt = $this->connection->prepare("UPDATE orderItem SET quantity = quantity + :quantity WHERE historyTourTicketId = :historyTourTicketId");
//            $stmt->bindParam(':quantity', $quantity);
//            $stmt->bindParam(':historyTourTicketId', $ticketId);
//            $stmt->execute();
//        } catch (PDOException $e) {
//            // Handle the error
//            echo "Error updating order item: " . $e->getMessage();
//        }
//    }
    public function updateTourOrderItemByTicketId($ticketId, $quantity)
    {
        // Check if there are enough available tickets
        $availableTickets = $this->checkTourAvailableTicket($ticketId);
        if ($availableTickets === null || $availableTickets < $quantity) {
            throw new Exception('Not enough available tickets.');
        }

        try {
            $stmt = $this->connection->prepare("UPDATE orderItem SET quantity = quantity + :quantity WHERE historyTourTicketId = :historyTourTicketId");
            $stmt->bindParam(':quantity', $quantity);
            $stmt->bindParam(':historyTourTicketId', $ticketId);
            $stmt->execute();
        } catch (PDOException $e) {
            // Handle the error
            echo "Error updating order item: " . $e->getMessage();
        }
    }

//    public function updatePerformanceOrderItemByTicketId($ticketId, $quantity)
//    {
//        try {
//            $stmt = $this->connection->prepare("UPDATE orderItem SET quantity = quantity + :quantity WHERE performanceTicketId = :performanceTicketId");
//            $stmt->bindParam(':quantity', $quantity);
//            $stmt->bindParam(':performanceTicketId', $ticketId);
//            $stmt->execute();
//        } catch (PDOException $e) {
//            // Handle the error
//            echo "Error updating order item: " . $e->getMessage();
//        }
//    }
    /**
     * @throws Exception
     */
    public function updatePerformanceOrderItemByTicketId($ticketId, $quantity, $orderId)
    {
        // Check if there is enough stock available
        $availableStock = $this->checkPerformanceAvailableTicket($ticketId);
        if ($availableStock < $quantity) {
            // Throw an error or handle it in some way
            throw new Exception("Not enough stock available for this ticket. ticket is only left for $availableStock");
        }

        try {
            $stmt = $this->connection->prepare("UPDATE orderItem SET quantity = quantity + :quantity WHERE performanceTicketId = :performanceTicketId");
            $stmt->bindParam(':quantity', $quantity);
            $stmt->bindParam(':performanceTicketId', $ticketId);
            $stmt->execute();
        } catch (PDOException $e) {
            // Handle the error
            echo "Error updating order item: " . $e->getMessage();
        }
    }


    public function updateQuantity($orderItemId, $quantity)
    {
        try {
            $stmt = $this->connection->prepare("UPDATE orderitem SET quantity = :quantity WHERE orderItemId = :orderItemId");
            $stmt->bindParam(':quantity', $quantity);
            $stmt->bindParam(':orderItemId', $orderItemId);

            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            // Handle any exceptions or errors that occurred during the update
            error_log("Error updating quantity: " . $e->getMessage());
            return false;
        }
    }

    public function deleteOrderItem($orderItemId)
    {
        try {
            // Prepare the SQL query
            $stmt = $this->connection->prepare('DELETE FROM orderitem WHERE orderItemId = :orderItemId');

            // Bind the parameters
            $stmt->bindParam(':orderItemId', $orderItemId);

            // Execute the query
            $stmt->execute();

        } catch (PDOException $e) {
            // Handle the exception here
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function getOrderIdByOrderItemId($orderItemId)
    {
        try {
            // Prepare the SQL statement with a named parameter
            $stmt = $this->connection->prepare('SELECT order_id FROM orderitem WHERE orderItemId = :orderItemId');

            // Bind the parameter to a variable
            $stmt->bindParam(':orderItemId', $orderItemId);

            // Execute the statement
            $stmt->execute();

            // Fetch the result
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            // Output the order_id
            return $result['order_id'];

        } catch (PDOException $e) {
            // Handle the exception here
            echo "Error: " . $e->getMessage();
        }
    }

    public function updateTotalPrice($orderId)
    {
        try {

            // Prepare the SQL statement with named parameters
            $stmt = $this->connection->prepare('UPDATE `Order`
                          SET totalPrice = (
                            SELECT SUM(
                              COALESCE(historytourticket.price, 0) * orderItem.quantity +
                              COALESCE(restaurantticket.price, 0) * orderItem.quantity +
                              COALESCE(performance.totalPrice, 0) * orderItem.quantity
                            )
                            FROM orderItem
                            LEFT JOIN historytourticket ON orderItem.historyTourTicketId = historytourticket.id
                            LEFT JOIN restaurantticket ON orderItem.restaurantTicketId = restaurantticket.restaurantTicketId
                            LEFT JOIN performanceticket ON orderItem.performanceTicketId = performanceticket.performanceTicketId
                            LEFT JOIN performance on performance.performanceId = performanceticket.performanceId
                            WHERE orderItem.order_id = :orderId
                          )
                          WHERE `Order`.orderId = :orderId');

            $stmt->bindParam(':orderId', $orderId);

            // Execute the statement
            $stmt->execute();

        } catch (PDOException $e) {
            echo 'Database error: ' . $e->getMessage();
        }

    }

    public function insertPaymentDetail($userId, $orderId, $status, $paymentCode, $webhookURL)
    {
        try {
            $stmt = $this->connection->prepare("INSERT INTO payment (userId, orderId, paymentStatus, paymentCode, webhookURL, requestDate) VALUES (:userId, :orderId, :paymentStatus, :paymentCode, :webhookURL, :requestDate)");
            $stmt->bindParam(':userId', $userId);
            $stmt->bindParam(':orderId', $orderId);
            $stmt->bindParam(':paymentStatus', $status);
            $stmt->bindParam(':paymentCode', $paymentCode);
            $stmt->bindParam(':webhookURL', $webhookURL);
            date_default_timezone_set("Europe/Amsterdam");
            $today = date("Y-m-d H:i:s");
            $stmt->bindParam(':requestDate', $today);
            $stmt->execute();
            return $this->connection->lastInsertId();

        } catch (PDOException $e) {
            // Handle the error
            echo "Error updating order item: " . $e->getMessage();
        }
    }

    public function deletePayment()
    {
        try {
            $stmt = $this->connection->prepare("DELETE FROM payment
                                                    WHERE paymentStatus = 'open' AND requestDate < DATE_SUB(NOW(), INTERVAL 15 MINUTE);");
            $stmt->bindParam(':paymentId', $paymentId);
            $stmt->execute();
        } catch (PDOException $e) {
            // Handle the error
            echo "Error updating order item: " . $e->getMessage();
        }
    }

    public function getPaymentIdByOrderId($orderId)
    {
        try {
            $stmt = $this->connection->prepare('SELECT paymentId FROM payment WHERE orderId = :orderId and paymentStatus = "open"');
            $stmt->bindParam(':orderId', $orderId);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                return $row['paymentId'];
            } else {
                return null;
            }
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function getTotalPriceByUserId($userId)
    {
        try {
            $stmt = $this->connection->prepare('SELECT totalPrice FROM `Order` WHERE `Order`.user_Id = :userId and `Order`.orderStatus = "open"');
            $stmt->bindParam(':userId', $userId);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                return $row['totalPrice'];
            } else {
                return "error occurred";
            }
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
    public function checkTourAvailableTicket($ticketId){
        try {
            $stmt = $this->connection->prepare('select availablehistorytour
                                                    from historytour
                                                    JOIN historytourticket on historytourticket.historyTourId = historytour.historyTourId
                                                    where historytourticket.id = :id;');
            $stmt->bindParam(':id', $ticketId);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                return $row['availablehistorytour'];
            } else {
                return null;
            }
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
    public function checkPerformanceAvailableTicket($ticketId){
        try {
            $stmt = $this->connection->prepare('select availableTickets
                                                    from performance
                                                    JOIN performanceticket on performanceticket.performanceTicketId = performance.performanceId
                                                    WHERE performanceticket.performanceTicketId = :performanceTicketId;');
            $stmt->bindParam(':performanceTicketId', $ticketId);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                return $row['availableTickets'];
            } else {
                return null;
            }
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function getTotalPriceByOrderId($orderId)
    {
        try {
            $stmt = $this->connection->prepare('SELECT totalPrice FROM `Order` WHERE `Order`.orderId = :orderId and `Order`.orderStatus = "open"');
            $stmt->bindParam(':orderId', $orderId);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                return $row['totalPrice'];
            } else {
                return null;
            }
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    function updatePaymentStatus($paymentCode, $newPaymentStatus)
    {

        $stmt = $this->connection->prepare('UPDATE payment SET paymentStatus = :paymentStatus WHERE paymentCode = :paymentCode');
        $stmt->bindParam(':paymentStatus', $newPaymentStatus);
        $stmt->bindParam(':paymentCode', $paymentCode);
        $stmt->execute();
    }

    public function getOrderStatus($orderId)
    {
        try {
            $stmt = $this->connection->prepare('SELECT paymentStatus FROM payment WHERE orderId = :orderId');
            $stmt->bindParam(':orderId', $orderId);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                return $row['paymentStatus'];
            } else {
                return null;
            }
        } catch (PDOException $e) {
            throw new Exception('Error: ' . $e->getMessage());
        }
    }

    public function getCheckoutUrl($orderId)
    {
        try {
            $stmt = $this->connection->prepare('SELECT webhookURL FROM payment WHERE orderId = :orderId and paymentStatus = "open"');
            $stmt->bindParam(':orderId', $orderId);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                return $row['webhookURL'];
            } else {
                return null;
            }
        } catch (PDOException $e) {
            throw new Exception('Error: ' . $e->getMessage());
        }
    }

    public function getPaymentCode($orderId)
    {
        try {
            $stmt = $this->connection->prepare('SELECT paymentCode FROM payment WHERE orderId = :orderId');
            $stmt->bindParam(':orderId', $orderId);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                return $row['paymentCode'];
            } else {
                return null;
            }
        } catch (PDOException $e) {
            throw new Exception('Error: ' . $e->getMessage());
        }
    }


    public function updatePaymentMethod($orderID, $paymentMethod)
    {
        try {
            $stmt = $this->connection->prepare('UPDATE payment SET paymentMethod = :paymentMethod WHERE orderId = :orderId');
            $stmt->bindParam(':paymentMethod', $paymentMethod);
            $stmt->bindParam(':orderId', $orderID);
            $stmt->execute();
        } catch (PDOException $e) {

        }
    }

}

//SELECT orderItem.orderItemId, orderitem.quantity, restaurant.name, restaurant.foodTypes
//                                            FROM orderitem
//                                            JOIN restaurantticket ON restaurantticket.restaurantTicketId = orderitem.restaurantTicketId
//                                            JOIN `order` ON `order`.orderId = orderitem.order_id
//                                            JOIN restaurant on restaurant.id = restaurantticket.restaurantId
//                                            WHERE `order`.user_id = '104';

//SELECT orderItem.orderItemId, orderitem.quantity, location.locationName, artist.artistName
//FROM orderitem
//JOIN performanceTicket pt1 ON pt1.performanceTicket = orderitem.performanceTicketId
//JOIN performance ON pt1.performanceId = performance.performanceId
//JOIN participatingartist on participatingartist.performanceId = performance.performanceId
//JOIN artist ON artist.artistId = participatingartist.artistId
//JOIN `order` ON `order`.orderId = orderitem.order_id
//JOIN location ON location.locationId = performance.venueId
//WHERE `order`.user_id = 104;
