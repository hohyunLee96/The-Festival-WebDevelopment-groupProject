<?php
require_once __DIR__ . '/repository.php';
require_once __DIR__ . '/../models/EventImage.php';


class EventImageRepository extends repository
{
    private function checkEventImageExistence($stmt): bool
    {
        try {
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            $message = '[' . date("F j, Y, g:i a e O") . ']' . $e->getMessage() . $e->getCode() . $e->getFile() . ' Line ' . $e->getLine() . PHP_EOL;
            error_log("Database connection failed: " . $message, 3, __DIR__ . "/../Errors/error.log");
            http_response_code(500);
            exit();
        }
    }
    public function getEventImageIdByEventAndType($eventId, $specification)
    {
        try {
            $stmt = $this->connection->prepare("SELECT * from eventimage WHERE eventId=:eventId AND specification=:specification");
            $stmt->bindParam(':eventId', $eventId);
            $stmt->bindParam(':specification', $specification);

            if ($this->checkEventImageExistence($stmt)) {
            $stmt->execute();
            return current($stmt->fetchAll(PDO::FETCH_CLASS, 'EventImage'));}
            
        } catch (PDOException $e) {
            echo $e;
        }
    }


    

}
