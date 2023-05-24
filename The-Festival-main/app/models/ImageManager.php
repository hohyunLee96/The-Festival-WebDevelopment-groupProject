<?php
require_once __DIR__ . '/../models/Exceptions/uploadFileFailedException.php';

trait ImageManager
{
    /**
     * @throws uploadFileFailedException
     */
    function moveImageToSpecifiedDirectory($image, $directory): void
    {
        if (!move_uploaded_file($image['tmp_name'], $directory)) {
//            throw new uploadFileFailedException("File upload Failed");
        }
    }

    function getUniqueImageNameByImageName($image): string
    {
        $imageExtension = pathinfo($image['name'], PATHINFO_EXTENSION);
        return uniqid() . '.' . $imageExtension;
    }

    /**
     * @throws uploadFileFailedException
     */
    function getImagesNameByMovingToDirectory($images, $pathToDir): array
    {
        try {
            $imageNames = [];
            foreach ($images as $key => $image) {
                $imageName = $this->getUniqueImageNameByImageName($image);
                $this->moveImageToSpecifiedDirectory($image, $pathToDir . $imageName);
                // Check if the key already exists in $imageNames
                if (isset($imageNames[$key])) {
                    // If the key exists, append the new value to the existing value in the array
                    if (is_array($imageNames[$key])) {
                        $imageNames[$key][] = $imageName;
                    } else {
                        $imageNames[$key] = [$imageNames[$key], $imageName];
                    }
                } else {
                    // If the key doesn't exist, add a new key-value pair to the array
                    $imageNames[$key] = $imageName;
                }
            }
            return $imageNames;
        } catch (Exception $exception) {
            throw new uploadFileFailedException($exception->getMessage());
        }

    }

    /**
     * @throws uploadFileFailedException
     */
    function deleteImageFromDirectory($imagePath): void
    {
        if (file_exists($imagePath)) {
            if (!unlink($imagePath)) {
                throw new uploadFileFailedException("File deletion Failed:");
            }
        } else {
            throw new uploadFileFailedException("File deletion Failed: File does not exist");
        }
    }

    /**
     * @throws uploadFileFailedException
     */
    function deleteImagesFromDirectory($images, $directory): void
    {
        foreach ($images as $imageName) {
            $this->deleteImageFromDirectory($directory . $imageName);
        }
    }
     function checkValidImageOrNot($image): bool
    {
        $imageFileType = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            return false;
        }
        return true;
    }

    /**
     * @throws uploadFileFailedException
     */
    function renameImageFromDirectory($oldImageName, $newImageName, $directory): void
    {
        if(!rename($directory . $oldImageName, $directory . $newImageName)){
            throw new uploadFileFailedException("File rename Failed:");
        }
    }

    /**
     * @throws uploadFileFailedException
     */
    function editNewImageAccordanceToCurrent($currentImageName, $newImage, $directory): void
    { //delete existing Image and rename previous One
        $this->deleteImageFromDirectory($directory . $currentImageName);
        $this->moveImageToSpecifiedDirectory($newImage, $directory . $currentImageName);
    }

}