<?php
 require_once __DIR__ . '/controller.php';

require_once '../services/userService.php';


class ManageAccountController extends Controller
{
    private $userService;
    private $currentUser;
    private $currentUserId;

    function __construct()
    {
        $this->userService = new UserService();
        $this->currentUser = unserialize(serialize($_SESSION["loggedUser"]));
        $this->currentUserId = $this->currentUser->getId();
    }

    public function index()
    {   
        $currentUser = $this->currentUser;
       
        $this->displayNavBar("ManageAccount",'/css/manageAccountStyle.css');

        require_once __DIR__ . '/../views/manageAccount/index.php';
     
        $this->displayFooter();

    }


    function getInputBirthDate()
{
    $dateInput = strtotime($_POST['dateOfBirth']);
    if ($dateInput) {
        $dateOfBirth = date('Y-m-d', $dateInput);
        return $dateOfBirth;
    }
}

function  setProfileImagePath(){

    $newPicture = $_FILES["file"]["name"];
    $imagePath = "";
    if ($newPicture != ""){
    move_uploaded_file($_FILES["file"]["tmp_name"], "./image/" . $newPicture );
    $imagePath = "./image/" . $newPicture;}
    else {
        $initialPicture  = $this->currentUser->getPicture();

        $imagePath =  $initialPicture;
    }

    return $imagePath;

}


function setUserPassword(){

    $password = NULL;
    $newPassword = $_POST["newPassword"];
    $confirmedPassword = $_POST["confirmPassword"];

    if ($newPassword == $confirmedPassword){
        $password=$newPassword;
    }

    return $password;
}


private function createUserInstance($id, $role, $firstName, $lastName, $email, $picture, $dateOfBirth, $password)
{
    try {
        $user = new User();
        $user->setId($id);
        $user->setRole(Roles::fromString($role));
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setEmail($email);
        $user->setPicture($picture);
        $user->setDateOfBirth(new DateTime($dateOfBirth));
        $user->setHashedPassword($password);

        return $user;
    } catch (InvalidArgumentException|Exception $e) { 
        http_response_code(500);
    }

}

  public  function updateProfile($currentUserId)
{
   

    if (isset($_POST["updateProfile"])) {

        $firstName = $_POST["firstName"];
        $lastName = $_POST["lastName"];
        if (isset($_POST["userRole"])) {
            $role = $_POST["userRole"];
        } else {
            $role = 'Customer';
        }
        $email = $_POST["email"];
        $birthDate = $this->getInputBirthDate();
        $imagePath = $this->setProfileImagePath();
        
        $userId = $this->currentUserId;


        if(!empty($_POST['newPassword']) && !empty($_POST['confirmPassword'])){
            $password = $this->setUserPassword();
            if(!is_null($password)){
                $password = $this->userService->hashPassword();
                $updatedUser = $this->createUserInstance($userId, $role, $firstName, $lastName, $email, $imagePath, $birthDate,  $password);
               $this->userService->updateUserAccount($updatedUser);
           }
           }
           else {
               $password = $this->currentUser->getHashedPassword();
               $updatedUser = $this->createUserInstance($userId, $role, $firstName, $lastName, $email, $imagePath, $birthDate,  $password);
               $this->userService->updateUserAccount($updatedUser);
           }
       
        header("location: /manageaccount");
    }
}

    public function updateAccountData(){
        $userId = $this->currentUserId;
        $this->updateProfile($userId);
    }
}


?>
