<?php
require_once __DIR__ . '/repository.php';
require_once __DIR__ . '/../models/AccessPass.php';

class AccessPassRepository extends repository
{
    private function checkAccessPassExistence($stmt): bool
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
    public function getAccessPassByIdAndDate($id, $eventDate)
    {
        try {
            $stmt = $this->connection->prepare("SELECT * FROM accesspass WHERE accessPassId=:accessPassId AND eventDate=:eventDate");
            $stmt->bindParam(':accessPassId', $id);
            $stmt->bindParam(':eventDate', $eventDate);
            if ($this->checkAccessPassExistence($stmt)) {
            $stmt->execute();
            return current($stmt->fetchAll(PDO::FETCH_CLASS, 'AccessPass'));}
            
        } catch (PDOException $e) {
            echo $e;
        }
    }

}
