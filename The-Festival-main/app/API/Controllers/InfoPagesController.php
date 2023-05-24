<?php
require_once __DIR__ . '/ApiController.php';
require_once __DIR__ . '/../../models/ImageManager.php';
require_once __DIR__ . '/../../services/InfoPagesService.php';
require_once __DIR__ . '/../../models/Exceptions/NotAvailableException.php';
require_once __DIR__ . '/../../models/Exceptions/uploadFileFailedException.php';

class InfoPagesController extends ApiController
{
    private $infoPagesService;
    use ImageManager;

    /**
     * @param $infoPagesService
     */
    public function __construct()
    {
        $this->infoPagesService = new InfoPagesService();
    }


    function uploadImage()
    {
        $infoImagesDir = __DIR__ . '/../../public/image/InfoPages/';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $uploadingImage = $_FILES['image'];
            $baseurl = "http://" . $_SERVER['HTTP_HOST'] . "/image/InfoPages/";
            try {
                $this->checkValidImageOrNot($uploadingImage);
                $imageName = $this->getUniqueImageNameByImageName($uploadingImage);
                $this->moveImageToSpecifiedDirectory($uploadingImage, $infoImagesDir . $imageName);
            } catch (uploadFileFailedException $e) {
                $this->respondWithError(500, $e->getMessage());
                return;
            }
            $this->respond(array('location' => $baseurl . $imageName));
        }
    }

    public function editInfoPage()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->sendHeaders();
            $data = $this->getSanitizedData();
            try {
                $this->infoPagesService->editInfoPage($data->infoPageId, $data->infoPageTitle, $data->infoPageContent);
                $this->respond(true);
            } catch (NotAvailableException $e) {
                $this->respondWithError(409, $e->getMessage());
                return;
            } catch (Exception $e) {
                $this->respondWithError(500, $e->getMessage());
                return;
            }
        }
    }


}