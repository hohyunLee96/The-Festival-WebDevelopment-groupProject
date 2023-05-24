<?php
require_once __DIR__ . '/../repositories/repository.php';

class ImageRepository extends Repository
{
    /**
     * @throws DatabaseQueryException
     */
    public function insertImageAndGetId($imageName)
    {
        $query = "INSERT INTO image (imageName) VALUES (:imageName)";
        $executedResult = $this->executeQuery($query, array(':imageName' => $imageName), false, true);
        if (!is_numeric($executedResult)) { // if it is bools means that it was not inserted into the database
            throw new DatabaseQueryException("Error while inserting image into database");
        }
        return $executedResult; // it is going to return us the id of the date that we just inserted
    }


    private function checkImageExistence($stmt): bool
    {
        try {
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            exit();
        }
    }


    public function getImageById($id)
    {
        try {
            $stmt = $this->connection->prepare("SELECT imageName from image WHERE imageId=:imageId");
            $stmt->bindParam(':imageId', $id);

            $stmt->execute();
            $result = $stmt->fetch();
            return current($result);

        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function deleteImage($id)
    {
        $query = "DELETE FROM image WHERE imageId=:imageId";
        return $this->executeQuery($query, [':imageId' => $id]);
    }

}
