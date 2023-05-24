<?php
require __DIR__ . '/../repositories/pageRepository.php';
require_once __DIR__ . '/../models/page.php';

class PageService
{
    
    public function getPageById(int $pageId)
    {
        $repository = new PageRepository();
        return $repository->getPageById($pageId);
    }
    public function getPageByTitle(string $pageTitle)
    {
        $repository = new PageRepository();
        return $repository->getPageByTitle($pageTitle);
    }


    public function createNewPage($newPage): void
    {
        $repository = new PageRepository();
        $repository->createNewPage($newPage);
    }

    public function updatePageById($pageID, $newPage): void 
    {
        $repository = new PageRepository();
        $repository->updatePageById($pageID, $newPage);
    }

    public function deletePageById($pageID): void 
    {
        $repository = new PageRepository();
        $repository->deletePageById($pageID);
    }
    
       public function getPageByIdWithUrl(int $pageId)
    {
        $repository = new PageRepository();
        return $repository->getPageByIdWithUrl($pageId);
    }
}
