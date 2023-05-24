<?php
require_once __DIR__ . '/../repositories/InfoPagesRepository.php';
require_once __DIR__ . '/../models/Exceptions/NotAvailableException.php';

class InfoPagesService
{
    private $infoPagesRepository;
    private $navBarService;

    public function __construct()
    {
        $this->infoPagesRepository = new InfoPagesRepository();
        $this->navBarService = new NavBarService();
    }

    public function getAllInfoPages(): ?array
    {
        return $this->infoPagesRepository->getAllInfoPages();
    }

    /**
     * @throws InternalErrorException
     * @throws NotAvailableException
     */
    public function addNewPage($details)
    {
        if ($this->navBarService->doesNavBarItemExist($details['title'])) {
            throw new NotAvailableException("Page with this title already exists");
        }
        return $this->infoPagesRepository->addNewPage($details);
    }

    public function getInfoPageById($id): ?InfoPage
    {
        return $this->infoPagesRepository->getInfoPageById($id);
    }

    public function deleteInfoPage($infoPageId)
    {
        $navBarItemId = $this->infoPagesRepository->getNavBarIdByInfoPageId($infoPageId);
        $this->navBarService->deleteNavBarItem($navBarItemId);
        return $this->infoPagesRepository->deleteInfoPage($infoPageId);
    }

    /**
     * @throws NotAvailableException
     */
    public function editInfoPage($infoPageId, $infoPageTitle, $infoPageContent){
        $navBarId=$this->infoPagesRepository->getNavBarIdByInfoPageId($infoPageId);
        $isNavbarDetailsSame= $this->navBarService->hasNavBarSameDetails($navBarId,$infoPageTitle);
        $isPageDetailsSame= $this->infoPagesRepository->hasInfoPageSameDetails($infoPageId,$infoPageContent);
        if($isNavbarDetailsSame && $isPageDetailsSame){
            return true; // nothing to be changed in the database
        }
        if(!$isPageDetailsSame){
            $this->infoPagesRepository->editInfoPage($infoPageId,$infoPageContent);
        }
        if(!$isNavbarDetailsSame){
            if($this->navBarService->doesNavBarItemExist($infoPageTitle)){
                throw new NotAvailableException("Page with this title {$infoPageTitle} already exists");
            }
            $this->navBarService->editNavBarItem($navBarId,$infoPageTitle);
        }
        return true;
    }
    public function getInfoPageByTitle($title): ?InfoPage
    {
        return $this->infoPagesRepository->getInfoPageByTitle($title);
    }

}