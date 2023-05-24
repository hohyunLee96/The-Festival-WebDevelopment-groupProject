<?php
require_once __DIR__ . '/AdminPanelController.php';
require_once __DIR__ . '/../../Services/userService.php';
require_once __DIR__ . '/../../models/User.php';

class AdminManageUsersController extends AdminPanelController
{
    private $userService;

    public function __construct()
    {
        parent::__construct();
        $this->userService = new UserService();
    }

    public function index()
    {
        $users = $this->userService->getAllUsers();
        $this->displaySideBar("User Management");
        require __DIR__ . '/../../views/AdminPanel/ManageUsers/OverviewManageUsers.php';
    }

    public function editUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['btnEditUser']) && isset($_POST['hiddenUserId'])) {
            $userId = $this->sanitizeInput($_POST['hiddenUserId']);
            $editingUser = $this->userService->getUserById($userId);
            if (!is_null($editingUser)) {
                $this->displaySideBar("Edit User", '/css/registerStyle.css');
                require __DIR__ . '/../../views/AdminPanel/ManageUsers/EditUser.php';
            } else {
                $this->display404PageNotFound();
            }
        } else {
            http_response_code(401); // Unauthorised Request
            exit();
        }
    }

    public function registerNewUser()
    {
        $message = $this->registerNewUserSubmit();
        $this->displaySideBar("RegisterNewUser", '/css/registerStyle.css');
        require __DIR__ . '/../../views/AdminPanel/ManageUsers/RegisterNewUser.php';
    }


    private function registerNewUserSubmit()
    {
        if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['btnRegister'])) {// initialize message variable
            $userDetails = $this->checkFieldsFilledAndSantizeInput($_POST, ['btnRegister']);
            if (is_string($userDetails)) {
                return $userDetails;
            }
            $dateParseResult = $this->parseDateOfBirth($this->sanitizeInput($userDetails['dateOfBirth'])); //TODO: check if the date is valid
            if (is_string($dateParseResult)) { // checking if the controller sends some error message or not
                return $dateParseResult;
            }
            if ($this->userService->checkUserExistenceByEmail($userDetails['email'])) {
                return "User with this email already exists";
            }
            if ($userDetails['password'] == $userDetails['passwordConfirm']) {
                $userDetails['role']=Roles::fromString($userDetails['role']);
                $userDetails['picture']=$_FILES['profilePicUpload'];
                if ($this->userService->registerUser($userDetails)) {
                    header("Location: /admin/manageusers");
                    exit();
                } else {
                    return "Something went wrong while creating an account please try again later";
                }

            } else {
                return "Password and Confirm Password does not match";
            }
        }
    }


}