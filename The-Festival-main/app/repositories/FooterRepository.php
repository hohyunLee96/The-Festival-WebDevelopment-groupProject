<?php
require __DIR__ . '/../models/FooterItem.php';
require __DIR__ . '/repository.php';

class FooterRepository extends Repository
{
    public function getAllFooterItems()
    {
        try {
            $stmt = $this->connection->prepare("SELECT  footerItemId, pageUrl, name, paragraph, icon FROM footer ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS, 'FooterItem');
        } catch (PDOException $e) {
            echo $e;
        }
    }

    function updateFooterItemName($footerItemName, $footerItemId)
    {
        try {
            $stmt = $this->connection->prepare("UPDATE footer SET name = :name WHERE footerItemId = :footerItemId");

            $stmt->bindValue(':name', $footerItemName);
            $stmt->bindValue(':footerItemId', $footerItemId);

            $stmt->execute();
            if ($stmt->rowcount() == 0) {
                return false;
            }
            return true;

        } catch (PDOException $e) {
            echo $e;
        }
    }

}?>