<?php
require_once __DIR__ . '/../services/NavBarService.php';
require_once __DIR__ . '/../models/InfoPage.php';
require_once __DIR__.'/../models/Exceptions/InternalErrorException.php';
class InfoPagesRepository extends Repository
{
    private $navBarItemService;
    public function __construct()
    {
        parent::__construct();
        $this->navBarItemService = new NavBarService();
    }
    public function getAllInfoPages(): ?array
    {
        $query="SELECT infoPageId, navBarItemId,pageContent FROM infoPage";
        $dbResult = $this->executeQuery($query);
        if(empty($dbResult)){
            return null;
        }
        $infoPages = array();
        foreach ($dbResult as $dbRow){
            $navBarItem = $this->navBarItemService->getNavBarItemById($dbRow['navBarItemId']);
            $infoPages[] = new InfoPage($dbRow['infoPageId'], $navBarItem, $dbRow['pageContent']);
        }
        return $infoPages;
    }
    public function getInfoPageById($id): ?InfoPage
    {
        $query="SELECT infoPageId, navBarItemId,pageContent FROM infoPage WHERE infoPageId = :id";
        $params = [
            ':id' => $id
        ];
        $dbResult = $this->executeQuery($query, $params);
        if(empty($dbResult)){
            return null;
        }
        $dbRow = $dbResult[0];
        $navBarItem = $this->navBarItemService->getNavBarItemById($dbRow['navBarItemId']);
        return new InfoPage($dbRow['infoPageId'], $navBarItem, $dbRow['pageContent']);
    }

    /**
     * @throws InternalErrorException
     */
    public function addNewPage($details){
        $navBarItemId = $this->navBarItemService->addNavBarAndGetId($details['title']);
        $query = "INSERT INTO infoPage (navBarItemId, pageContent) VALUES (:navBarItemId, :pageContent)";
        $params = [
            ':navBarItemId' => $navBarItemId,
            ':pageContent' => $details['PageContent']
        ];
         return $this->executeQuery($query, $params ); //since it is ans
    }
    public function deleteInfoPage($id)
    {
        $query = "DELETE FROM infoPage WHERE infoPageId = :id";
        $params = [
            ':id' => $id
        ];
         $this->executeQuery($query, $params);
    }
    public function getNavBarIdByInfoPageId($id){
        $query = "SELECT navBarItemId FROM infoPage WHERE infoPageId = :id";
        $params = [
            ':id' => $id
        ];
        $dbResult = $this->executeQuery($query, $params,false); // since db contains unique data
        if(empty($dbResult)){
            return null;
        }
        return $dbResult['navBarItemId'];
    }
    public function hasInfoPageSameDetails($id,$content){
        $query = "SELECT infoPageId FROM infoPage WHERE infoPageId = :id AND pageContent = :content";
        $params = [
            ':id' => $id,
            ':content' => $content
        ];
        $dbResult = $this->executeQuery($query, $params); // since db contains unique data
        return !empty($dbResult);
    }
    public function editInfoPage($id,$content){
        $query = "UPDATE infoPage SET pageContent = :content WHERE infoPageId = :id";
        $params = [
            ':id' => $id,
            ':content' => $content
        ];
        $this->executeQuery($query, $params);
    }
    public function getInfoPageByTitle($title): ?InfoPage
    {
        $navBarId = $this->navBarItemService->getNavBarIdByTitle($title);
        $query="SELECT infoPageId, navBarItemId,pageContent FROM infoPage WHERE navBarItemId = :navBarId";
        $params = [
            ':navBarId' => $navBarId
        ];
        $dbResult = $this->executeQuery($query, $params);
        if(empty($dbResult)){
            return null;
        }
        $dbRow = $dbResult[0];
        $navBarItem = $this->navBarItemService->getNavBarItemById($dbRow['navBarItemId']);
        return new InfoPage($dbRow['infoPageId'], $navBarItem, $dbRow['pageContent']);
    }

}