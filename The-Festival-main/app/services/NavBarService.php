<?php
require_once __DIR__ . '/../repositories/NavBarRepository.php';

class NavBarService
{
    private $navBarRepository;

    public function __construct()
    {
        $this->navBarRepository = new NavBarRepository();
    }

    public function getAllNavBarItems()
    {
        return $this->navBarRepository->getAllNavBarItems();
    }

    public function updateNavBarName($navBarName, $id): bool
    {
        return $this->navBarRepository->updateNavBarName($navBarName, $id);
    }
    public function getNavBarItemById($id){
        return $this->navBarRepository->getNavBarItemById($id);
    }

    /**
     * @throws InternalErrorException
     */
    public function addNavBarAndGetId($navBarName): int
    {
        return $this->navBarRepository->addNavBarAndGetId($navBarName);
    }
    public function doesNavBarItemExist($navBarName): bool
    {
        return $this->navBarRepository->doesNavBarItemExist($navBarName);
    }
    public function deleteNavBarItem($id): bool
    {
        return $this->navBarRepository->deleteNavBarItem($id);
    }
    public function hasNavBarSameDetails( $id,$navBarName): bool
    {
        return $this->navBarRepository->hasNavBarSameDetails( $id,$navBarName);
    }
    public function editNavBarItem($id,$navBarName): bool
    {
        return $this->navBarRepository->editNavBarItem($id, $navBarName);
    }
    public function getNavBarIdByTitle($infoPageId){
        return $this->navBarRepository->getNavBarIdByTitle($infoPageId);
    }
}