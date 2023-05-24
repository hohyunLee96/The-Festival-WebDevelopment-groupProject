<?php
require_once __DIR__ . '/../repositories/ImageRepository.php';
class ImageService
{
    private $imageRepository;
    public function __construct()
    {
        $this->imageRepository = new ImageRepository();
    }

    /**
     * @throws DatabaseQueryException
     */
    public function insertImageAndGetId($imageName){
        return$this->imageRepository->insertImageAndGetId($imageName);
    }
    
    public function getImageById($id)
    {
        return $this->imageRepository->getImageById($id);
    }
    public function deleteImage($id)
    {
        return $this->imageRepository->deleteImage($id);
    }

}
