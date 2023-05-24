<?php
class Repository
{
    protected $connection;

    /**
     * @throws InternalErrorException
     */
    function __construct()
    {
        require __DIR__ . '/../config/dbconfig.php';
        try {
            $this->connection = new PDO("$type:host=$servername;port=$portNumber;dbname=$database", $username, $password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw  new InternalErrorException("Connection failed: " . $e->getMessage());
        }
    }

    protected function executeQuery($query, $params = array(), $fetchAll = true,$returnId = false)
    {
        try {
            $stmt = $this->connection->prepare($query);
            $this->bindValuesToQuery($stmt, $params);
            $stmt->execute();
            $rowCount = $stmt->rowCount();
            if ($rowCount == 0) {
                return $this->handleZeroRowCount($query);
            }
            if ($rowCount > 0) {
                return $this->handlePositiveRowCount($query, $stmt, $fetchAll,$returnId);
            }
        } catch (PDOException $e) {
            throw new DatabaseQueryException("Something went wrong with the query: " . $e->getMessage());
        }
    }
    private function bindValuesToQuery($stmt, $params): void
    {
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
    }

    private function handleZeroRowCount($query): ?bool
    {
        if (stripos($query, 'insert') !== false || stripos($query, 'delete') !== false || stripos($query, 'update') !== false) {
            return false;
        }
        return null;
    }

    private function handlePositiveRowCount($query, $stmt, $fetchAll,$returnId)
    {
        if (stripos($query, 'insert') !== false || stripos($query, 'delete') !== false || stripos($query, 'update') !== false) {
            if($returnId){
                return $this->connection->lastInsertId();
            }
            return true;
        } else if(stripos($query, 'select') !== false) { // even if fetch is provided we check if it select then we only check for fetchAll
            if ($fetchAll) {
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
            }
            return $result;
        }
    }
}