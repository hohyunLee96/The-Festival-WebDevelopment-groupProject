<?php
require_once __DIR__ . '/repository.php';
require_once __DIR__ . '/../models/page.php';

class PageRepository extends Repository
{
    private function createPageInstance($dbRow): Page
    {
        try {
            $page = new Page();
            $page->setId($dbRow['id']);
            $page->setURI($dbRow['URI']);
            $page->setTitle($dbRow['title']);
            $page->setBodyContentHTML($dbRow['bodyContentHTML']);
            $page->setCreationTime(new DateTime($dbRow['creationTime']));
            $page->setCreatorUserId($dbRow['creatorUserId']);
            return $page;
        } catch (Exception $e) {
            echo "Error while creating user instance: " . $e->getMessage();
        }
    }

    public function getPageById(int $pageId)
    {
        try {
            $stmt = $this->connection->prepare("SELECT * FROM Page WHERE id = :id");
            $stmt->bindParam(':id', $pageId);
            $stmt->execute();
            if ($stmt->rowCount() == 0) {
                return null;
            }
            $result = $stmt->fetch();
            return $this->createPageInstance($result);
        } catch (PDOException|Exception $e) {
            echo $e;
        }
    }

    public function getPageByTitle(string $pageTitle)
    {
        try {
            // we make a query to database, to find the page with the given title.
            $stmt = $this->connection->prepare("SELECT * FROM Page WHERE title = :title");
            // to increase security, we use bindParam.
            $stmt->bindParam(':title', $pageTitle);
            // then execute the query.
            $stmt->execute();
            // if the number of records found with the given title is zero, then that title does not exist in the database.
            if ($stmt->rowCount() == 0) {
                return null;
            }
            $result = $stmt->fetch();
            return $this->createPageInstance($result);
        } catch (PDOException|Exception $e) {
            echo $e;
        }
    }

    public function createNewPage($newPage)
    {
        try {
            $stmt = $this->connection->prepare("INSERT into Page (URI, title, bodyContentHTML, creatorUserId) VALUES (:URI, :title, :bodyContentHTML, :creatorUserId)");

            $stmt->bindValue(':URI', $newPage->getURI());
            $stmt->bindValue(':title', $newPage->getTitle());
            $stmt->bindValue(':bodyContentHTML', $newPage->getBodyContentHTML());
            $stmt->bindValue(':creatorUserId', $newPage->getCreatorUserId());

            $stmt->execute();
        } catch (PDOException|Exception $e) {
            echo $e;
        }
    }

    public function updatePageById($pageID, $newPage)
    {
        try {
            $stmt = $this->connection->prepare("UPDATE Page SET title = :title, URI = :URI, creatorUserId = :creatorUserId, bodyContentHTML = :bodyContentHTML WHERE id = :id;");
            // we use bindValue to increase security and prevent SQL injection.
            $stmt->bindValue(':URI', $newPage->getURI());
            $stmt->bindValue(':title', $newPage->getTitle());
            $stmt->bindValue(':bodyContentHTML', $newPage->getBodyContentHTML());
            $stmt->bindValue(':creatorUserId', $newPage->getCreatorUserId());
            $stmt->bindValue(':id',$pageID);

            $stmt->execute();
        } catch (PDOException|Exception $e) {
            echo $e;
        }
    }
    public function deletePageById($pageID): void
    {
        try {
            $stmt = $this->connection->prepare("DELETE FROM Page WHERE id = :id;");
            $stmt->bindValue(':id',$pageID);
            $stmt->execute();
        } catch (PDOException|Exception $e) {
            echo $e;
        }
    }

    function updatePageContentById($id, $bodyContentHTML)
    {
        try {
            $stmt = $this->connection->prepare("UPDATE User SET bodyContentHTML = :bodyContentHTML WHERE id = :id");

            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':bodyContentHTML', $bodyContentHTML);

            $stmt->execute();
            if($stmt->rowcount() == 0){
                return false;
            }
            return true;

        } catch (PDOException $e) {
            echo $e;
        }
    }
    
    
        private function checkPageExistence($stmt): bool
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

    public function  getPageByIdWithUrl($id)
    {
        try {
            
            $stmt = $this->connection->prepare("SELECT * From Page WHERE id LIKE :id");
            $stmt->bindValue(':id', "%$id%");
             if ($this->checkPageExistence($stmt)) {
                $stmt->execute();
                $result = $stmt->fetch();
                return $result;}
            
        } catch (PDOException $e) {
            echo $e;
        }
    }
}

