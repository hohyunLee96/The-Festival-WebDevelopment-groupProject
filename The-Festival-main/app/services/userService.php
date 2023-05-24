<?php
require_once __DIR__ . '/../repositories/userRepository.php';
require __DIR__ . '/../PHPMailer/Exception.php';
require __DIR__ . '/../PHPMailer/SMTP.php';
require __DIR__ . '/../PHPMailer/PHPMailer.php';
require_once __DIR__ . '/../models/Exceptions/uploadFileFailedException.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class UserService
{
    private $repository;

    public function __construct()
    {
        $this->repository = new UserRepository();
    }

    // public function setReviewId(int $reviewId): self
    public function checkLogin(string $userName, string $password)
    {
        $user = $this->repository->login($userName, $password);
        if (isset($user) && $user != null) {
            return $user;
        }
        return null;
    }

    public function getUserById(int $userId)
    {
        return $this->repository->getUserById($userId);
    }

    public function getAllUsers()
    {
        return $this->repository->getAllUsers();
    }

    public function getUsersBySearchQuery($searchingTerm)
    {
        return $this->repository->getUsersBySearchQuery($searchingTerm);
    }

    public function getUserBySortingFirstNameByAscOrDescOrders($order)
    {
        return $this->repository->getUserBySortingFirstNameByAscOrDescOrders($order);
    }

    public function getUserBySortingFirstNameByAscendingOrder()
    {
        return $this->getUserBySortingFirstNameByAscOrDescOrders("ASC");
    }

    public function getUserBySortingFirstNameByDescendingOrder()
    {
        return $this->getUserBySortingFirstNameByAscOrDescOrders("DESC");
    }

    public function getUsersByRoles($roles)
    {
        return $this->repository->getUsersByRoles($roles);
    }

    public function getUsersBySearchAndSpecificRoles($searchingTerm, $criteria)
    {
        return $this->repository->getUsersBySearchAndSpecificRoles($searchingTerm, $criteria);
    }

    public function deleteUserById($userId): bool
    {
        $dbStoredName= $this->repository->getUserPictureById($userId); // first getting and storing the name of the image from db
        if ($this->repository->deleteUserById($userId)) {
            return $this->deleteUserImage($dbStoredName); // then deleting the image from the server
        }
        return false;
    }
    public function getUserPictureById($id){
        return $this->repository->getUserPictureById($id);
    }

    public function registerUser($newUser,$orderId)
    {
        $plainPassword = $newUser['password'];
        $newUser['password'] = $this->hashPassword($plainPassword);
        $image = $newUser['picture'];
        if (!empty($image['name'])) {
            $newUser['picture'] = $this->storeImage($image);
        } else {
            $newUser['picture'] = DEFAULT_AVATAR; // default image
        }
        $this->repository->registerUser($newUser,$orderId);
    }

    public function checkUserExistenceByEmail($email)
    {
        return $this->repository->checkUserExistenceByEmail($email);
    }

    public function updatePassword($userId, $newPassword): void
    {
        $repository = new UserRepository();
        if ($repository->updatePassword($userId, $this->hashPassword($newPassword))) {
            date_default_timezone_set('Europe/Amsterdam');
            $tokenExpiration = date('Y-m-d H:i:s');

            $this->deleteDataForgotPassword($userId, $tokenExpiration);
        }
    }

    public function deleteDataForgotPassword($email, $tokenExpiration): void
    {
        $this->repository->deleteDataForgotPassword($email, $tokenExpiration);
    }
    public function captchaVerification(&$systemMessage)
    {
        $secret = "6LelT5MkAAAAAP3xY6DkyRryMLG9Wxe2Xt48gz7t";
        $response = $_POST['g-recaptcha-response'];
        $remoteip = $_SERVER['REMOTE_ADDR'];
        $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$response&remoteip=$remoteip";
        $data = file_get_contents($url);
        $row = json_decode($data);
        if ($row->success== "true") {
            return true;
//            $this->registerValidUser($systemMessage);
        } else {
            $systemMessage = "you are a robot";
            return false;
        }
    }

    /**
     * @throws Exception
     */
    public function sendEmail($email): void
    {
        $token = bin2hex(random_bytes(16));
        date_default_timezone_set('Europe/Amsterdam');
        $expiration_time = date('Y-m-d H:i:s', time() + (60 * 20));

        $mail = new PHPMailer(true);

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'thefestivalinholland@gmail.com';                 // SMTP username
        $mail->Password = 'wwfixdhlyjwhjruh';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to

        $mail->setFrom('thefestivalinholland@gmail.com', 'The Festival Team');
        $mail->addAddress($email);     // Add a recipient

        $mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject = 'Verification Code';
//        $mail->Body = 'This is Verification code for changing password ' . $token .' Do not share it!';
        $mail->Body = 'http://localhost/login/updatePassword?token=' . $token;;

        if (!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            echo 'Message has been sent';
            $id = $this->checkUserExistenceByEmail($email);

            $this->repository->putRandomTokenForNewPassword($token, $expiration_time, $id);
        }
    }

    public function isTokenValid($token)
    {
        return $this->repository->isTokenValid($token);
    }

    public function hashPassword($password)
    {
        try {
            return password_hash($password, PASSWORD_DEFAULT);
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }


    /**
     * @throws uploadFileFailedException
     */
    public function storeImage($image)
    {
        try {
            $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
            $newImageName = uniqid() . '.' . $ext;
            $upload_dir = __DIR__ . '/../public/image/';
            if (!move_uploaded_file($image['tmp_name'], $upload_dir . $newImageName)) {
                throw new uploadFileFailedException();
            }
            return $newImageName;

        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }



    public function updateUserV2($updatingUser, $picture): ?bool
    {
        if (!empty($updatingUser->getHashedPassword())) {
            $updatingUser->setHashedPassword($this->hashPassword($updatingUser->getHashedPassword()));  // when password is empty it will not be hashed sent as null
        }
        $imageName = $this->processUpdatingUserImage($picture, $this->repository->getUserPictureById($updatingUser->getId()));
        $updatingUser->setPicture($imageName);
        if($this->repository->isUpdatingUserDetailsSame($updatingUser)){
            return true; // no need to update
        }
        return $this->repository->updateUserV2($updatingUser);
    }
    



    function processUpdatingUserImage($image, $oldImageName)
    {
        try {
            // Check if a new image was provided
            if (!empty($image['name']) ) {
                // Delete the old image from the server
                $this->deleteUserImage($oldImageName);  // delete methods prevent to delete default pic
                // If the old image was the default image, don't delete it
                if ($image['name'] !== DEFAULT_AVATAR) {
                    // Store the new image on the server and return its filename
                    return $this->storeImage($image);
                }else{
                    return DEFAULT_AVATAR;
                }
            } else {
                // If no new image was provided or the image is "Default", return the filename of the old image
                return $oldImageName;
            }
        } catch (uploadFileFailedException $e) {
            echo $e->getMessage();
        }
    }
    
    
 
    function checkEditingUserEmailExistence($email, $userID): bool
    {
        return $this->repository->checkEditingUserEmailExistence($email, $userID);
    }

    function deleteUserImage($ImageName)
    { // using constant DEFAULT_AVATAR
        if ($ImageName != DEFAULT_AVATAR) {
            try {
                $filePath = __DIR__ . '/../public/image/' . $ImageName;
                if (is_file($filePath) && file_exists($filePath)) {
                    return unlink($filePath);

                } else {
                    return false;
                }
            } catch (Exception $e) {
                return false;
            }
        }
        return true;
    }
    
    
      public function retrieveUserByIdWithUrl($id)
    {
        $repository = new UserRepository();
        return $repository->retrieveUserByIdWithUrl($id);
    }
    
     public function retrieveUserPermissionsWithUrl($id){

        $repository = new UserRepository();
        return $repository->retrieveUserPermissionsWithUrl($id);
    }

    public function checkUserExistenceByEmailWithUrl($email)
    {
        $repository = new UserRepository();
        return $repository->checkUserExistenceByEmailWithUrl($email);
    }
    
    public function updateUserAccount($updatedUser)
    {
        $repository = new UserRepository();
        return $repository->updateUserV2($updatedUser);
    }


}
