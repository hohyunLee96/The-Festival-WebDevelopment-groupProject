<?php
require_once __DIR__ . '/repository.php';

class EventDateRepository extends repository
{
    private function checkEventDateExistence($stmt): bool
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
    public function getEventDateById($id)
    {
        try {
            $stmt = $this->connection->prepare("SELECT eventDateId, date FROM eventdate WHERE eventDateId=:eventDateId");
            $stmt->bindParam(':eventDateId', $id);
            if ($this->checkEventDateExistence($stmt)) {
            $stmt->execute();
            return current($stmt->fetchAll(PDO::FETCH_CLASS, 'EventDate'));}
            
        } catch (PDOException $e) {
            echo $e;
        }
    }


    public function getEventDateByIdWithUrl($id)
    {
        try {
            $stmt = $this->connection->prepare("SELECT eventDateId, date FROM eventdate WHERE eventDateId LIKE :id");
            $stmt->bindValue(':id', "%$id%");
             if ($this->checkEventDateExistence($stmt)) {
                $stmt->execute();
                $result = $stmt->fetch();
                return $result;
            }
            
        } catch (PDOException $e) {
            echo $e;
        }
    }
}
