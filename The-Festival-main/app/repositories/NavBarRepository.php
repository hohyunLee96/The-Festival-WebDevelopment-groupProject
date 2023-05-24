<?php
require __DIR__ . '/../models/NavBarItem.php';
require_once __DIR__ . '/repository.php';

class NavBarRepository extends Repository
{
    public function getAllNavBarItems()
    {
        try {
            $stmt = $this->connection->prepare("SELECT navBarItemId, pageUrl, name FROM navbar ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS, 'NavBarItem');
        } catch (PDOException $e) {
            echo $e;
        }
    }

    function updateNavBarName($navName, $navId)
    {
        try {
            $stmt = $this->connection->prepare("UPDATE navbar SET name = :name WHERE navBarItemID = :navBarItemId");

            $stmt->bindValue(':name', $navName);
            $stmt->bindValue(':navBarItemId', $navId);

            $stmt->execute();
            if ($stmt->rowcount() == 0) {
                return false;
            }
            return true;

        } catch (PDOException $e) {
            echo $e;
        }
    }
    function getNavBarItemById($id){
        try {
            $stmt = $this->connection->prepare("SELECT navBarItemId, pageUrl, name FROM navbar WHERE navBarItemId = :navBarItemId");
            $stmt->bindValue(':navBarItemId', $id);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS, 'NavBarItem');
            return $stmt->fetch();
        } catch (PDOException $e) {
            echo $e;
        }
    }

    /**
     * @throws InternalErrorException
     */
    public function addNavBarAndGetId($name): int
    {
        $query = "INSERT INTO navbar (name,pageUrl) VALUES (:name, :pageUrl)";
        $params = [
            ':name' => $name,
            ':pageUrl' => "/Home/".$name
        ];
        $dbResult = $this->executeQuery($query, $params, false,true);
        if(is_numeric($dbResult)){
           return (int)$dbResult;
        }
        throw  new  InternalErrorException("Something went wrong Internally while creating a new navbar item");
    }
    public function doesNavBarItemExist($name){
        $query = "SELECT navBarItemId FROM navbar WHERE name = :name";
        return !empty($this->executeQuery($query, [':name' => $name]));
    }
    public function deleteNavBarItem($id){
        $query = "DELETE FROM navbar WHERE navBarItemId = :id";
        $params = [
            ':id' => $id
        ];
       return $this->executeQuery($query, $params);
    }
    public function hasNavBarSameDetails($navBarId, $name){
        $query = "SELECT navBarItemId FROM navbar WHERE name = :name AND navBarItemId = :navBarItemId";
        return !empty($this->executeQuery($query, [':name' => $name, ':navBarItemId' => $navBarId]));
    }
    public function editNavBarItem($navBarId, $name){
        $query = "UPDATE navbar SET name = :name ,pageUrl= :pageUrl WHERE navBarItemId = :navBarItemId";
        $params = [
            ':name' => $name,
            ':navBarItemId' => $navBarId,
            ':pageUrl' => "/Home/".$name

        ];
        return $this->executeQuery($query, $params);
    }
    public function getNavBarIdByTitle($title){
        $query = "SELECT navBarItemId FROM navbar WHERE name = :name";
        $params = [
            ':name' => $title
        ];
        $dbResult = $this->executeQuery($query, $params);
        if(empty($dbResult)){
            return null;
        }
        return $dbResult[0]['navBarItemId'];
    }

}