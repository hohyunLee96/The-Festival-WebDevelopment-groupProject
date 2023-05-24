<?php
require_once __DIR__ . '/../repositories/FooterRepository.php';

class FooterService
{
    private $footerRepository;

    public function __construct()
    {
        $this->footerRepository = new footerRepository();
    }

    public function getAllFooterItems()
    {
        return $this->footerRepository->getAllFooterItems();
    }

    public function updateFooterItemName($footerItemName, $id): bool
    {
        return $this->footerRepository->updateFooterItemName($footerItemName, $id);
    }
}?>