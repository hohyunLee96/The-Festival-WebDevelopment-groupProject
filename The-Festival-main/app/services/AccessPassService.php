<?php
require_once __DIR__ . '/../repositories/AccessPassRepository.php';
class AccessPassService
{
    private $accessPassRepository;
    public function __construct()
    {
        $this->accessPassRepository = new AccessPassRepository();
    }


    public function getAccessPassByIdAndDate($id, $eventDate)
    {
        return $this->accessPassRepository->getAccessPassByIdAndDate($id, $eventDate);
    }

}
