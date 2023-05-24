<?php
require_once __DIR__ . '/repository.php';
require_once __DIR__ . '/../models/AvailableEvent.php';


class AvailableEventRepository extends repository
{
    public function getAvailableHistoryEvents()
    {
        try {
            $stmt = $this->connection->prepare("SELECT  eventId, eventDetails, eventTypeId,  historyTourId, eventDate, eventHour, deliveryPossibilities, singleEvent, availableTickets FROM availableevent WHERE historyTourId IS NOT NULL");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS, 'AvailableEvent');
            
        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function getAvailableMusicEvents()
    {
        try {
            $stmt = $this->connection->prepare("SELECT  eventId, eventDetails, eventTypeId, performanceId, CASE WHEN participatingArtistId IS NULL THEN 0 ELSE participatingArtistId END AS participatingArtistId, eventDate, eventHour, singleEvent, availableTickets FROM availableevent WHERE performanceId IS NOT NULL");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS, 'AvailableEvent');
            
        } catch (PDOException $e) {
            echo $e;
        }
    }




  private function checkAvailableEventDataExistence($stmt): bool
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


    public function  getAvailableEventByIdWithUrl($id)
    {
        try {
            
            $stmt = $this->connection->prepare("SELECT * From availableevent WHERE eventId LIKE :id");
            $stmt->bindValue(':id', "%$id%");
             if ($this->checkAvailableEventDataExistence($stmt)) {
                $stmt->execute();
                $result = $stmt->fetch();
                return $result;}
            
        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function  retrieveParticipatingArtistsDataWithUrl($id)
    {
        try {
            
            $stmt = $this->connection->prepare("SELECT * From availableevent WHERE eventId LIKE :id");
            $stmt->bindValue(':id', "%$id%");
             if ($this->checkAvailableEventDataExistence($stmt)) {
                $stmt->execute();
                $result = $stmt->fetch();
                //echo $result[5];
                if ($result[5] != NULL){
                    return true;
                }
                return false;
            }
            
        } catch (PDOException $e) {
            echo $e;
        }
    }


    public function getEventNameByEventTypeIdWithUrl($id)
    {
        try {
            
            $stmt = $this->connection->prepare("SELECT eventName From event WHERE eventId LIKE :id");
            $stmt->bindValue(':id', "%$id%");
             if ($this->checkAvailableEventDataExistence($stmt)) {
                $stmt->execute();
                $result = $stmt->fetch();
                return $result[0];
                
            }
            
        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function getParticipatingArtistNameByArtistId($id)
    {
        try {
            $stmt = $this->connection->prepare("SELECT artistName FROM artist WHERE artistId=:artistId");
            $stmt->bindParam(':artistId', $id);
            $stmt->execute();
            $result = $stmt->fetch();
            if ($result != 0){
            return current($result);}
            
        } catch (PDOException $e) {
            echo $e;
        }
    }
    
    
    public function  getParticipatingArtistByIdWithUrl($id)
    {
        try {
            
            $stmt = $this->connection->prepare("SELECT * From artist WHERE artistId LIKE :id");
            $stmt->bindValue(':id', "%$id%");
             if ($this->checkAvailableEventDataExistence($stmt)) {
                $stmt->execute();
                $result = $stmt->fetch();
                return $result;}
            
        } catch (PDOException $e) {
            echo $e;
        }
    }

}

