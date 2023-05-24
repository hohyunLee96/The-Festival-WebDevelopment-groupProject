<?php
require_once __DIR__ . '/../../Services/userService.php';
require_once __DIR__ . '/ApiController.php';
class UsersController extends ApiController
{
    private $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    public function searchUsers(): void
    {
        try {
            $this->sendHeaders();
            if ($_SERVER['REQUEST_METlHOD'] === 'GET') {
                $users = null;
                if (empty($_GET['SearchTerm'])) {
                    if (empty($_GET['sortSelectedOption'])) {
                        $users = $this->userService->getAllUsers();
                    } else {
                        $sortSelectedOption = htmlspecialchars($_GET['sortSelectedOption']);
                        $users = $this->getUsersBySortingOptionSelected($sortSelectedOption);
                    }
                } else {
                    $searchingTerm = htmlspecialchars($_GET['SearchTerm']);
                    if (!empty($_GET['sortSelectedOption'])) {
                        $sortSelectedOption = htmlspecialchars($_GET['sortSelectedOption']);
                        $users = $this->userService->getUsersBySearchAndSpecificRoles($searchingTerm, Roles::fromString($sortSelectedOption));
                    } else {
                        $users = $this->userService->getUsersBySearchQuery($searchingTerm);
                    }
                }
                echo JSon_encode($users);
            }
        } catch (InvalidArgumentException|Exception $e) {
            http_response_code(500); // sending bad request error to APi request if something goes wrong
            echo $e->getMessage();
        }
    }


    public function retrieveUserById()
    {
        try {
            $this->sendHeaders();
            $user = NULL;

            if (!empty($_GET['id'])) {
                $id = htmlspecialchars($_GET['id']);
                $user = $this->userService->retrieveUserByIdWithUrl($id);
            }
            echo JSon_encode($user);
        } catch (InvalidArgumentException|Exception $e) {
            http_response_code(500); // sending bad request error to APi request if something goes wrong
            echo $e->getMessage();
        }

    }


    public function retrieveUserPermissions()
    {
        try {
            $this->sendHeaders();
            $userWithEmail = false;

            if (!empty($_GET['id'])) {
                $id = htmlspecialchars($_GET['id']);
                $userWithEmail = $this->userService->retrieveUserPermissionsWithUrl($id);
            }
            echo JSon_encode($userWithEmail);
        } catch (InvalidArgumentException|Exception $e) {
            http_response_code(500); // sending bad request error to APi request if something goes wrong
            echo $e->getMessage();
        }

    }


    public function checkEmailAddress()
    {
        try {
            $this->sendHeaders();
            $userWithEmail = false;

            if (!empty($_GET['email'])) {
                $emailAddress = htmlspecialchars($_GET['email']);
                $userWithEmail = $this->userService->checkUserExistenceByEmailWithUrl($emailAddress);
            }
            echo JSon_encode($userWithEmail);
        } catch (InvalidArgumentException|Exception $e) {
            http_response_code(500); // sending bad request error to APi request if something goes wrong
            echo $e->getMessage();
        }

    }


    public function deleteUser(): void
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $this->sendHeaders();
                $responseData = "";
                $responseData = array(
                    "Success" => false,
                    "Message" => "Sorry, Something went wrong while deleting user"
                );
                $body = file_get_contents('php://input');
                $data = json_decode($body);
                $checkDeleted = $this->userService->deleteUserById(htmlspecialchars($data->userID));
                if ($checkDeleted) {
                    $sortingOption = htmlspecialchars($data->SortingCondition);
                    $users = $this->getUsersBySortingOptionSelected($sortingOption); // sending data as it is in ui
                    $responseData = array(
                        "Success" => true,
                        "users" => json_encode($users)
                    );
                }
                echo json_encode($responseData);
            }

        } catch (InvalidArgumentException|PDOException|Exception $e) {
            http_response_code(500); // sending bad request error to APi request if something goes wrong
            echo $e->getMessage();
        }
    }


    public function sortUsers(): void
    {
        try {
            $this->sendHeaders();
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $users = null;
                if (empty($_GET['selectedOption'])) {
                    $users = $this->userService->getAllUsers();
                } else {
                    $sortBy = htmlspecialchars($_GET['selectedOption']);
                    $users = $this->getUsersBySortingOptionSelected($sortBy);
                }
            }
            echo Json_encode($users);
        } catch (InvalidArgumentException|Exception $e) {
            http_response_code(500); // sending bad request error to APi request if something goes wrong
            echo $e->getMessage();
        }
    }

    private function getUsersBySortingOptionSelected($selectedOption): ?array
    {
        $users = null;
        switch ($selectedOption) {
            case "A-z":
                $users = $this->userService->getUserBySortingFirstNameByAscendingOrder();
                break;
            case "Z-A":
                $users = $this->userService->getUserBySortingFirstNameByDescendingOrder();
                break;
            case in_array($selectedOption, Roles::getEnumValues()): // makings enums dependent with class
                $users = $this->userService->getUsersByRoles(Roles::fromString($selectedOption));
                break;
            case "All Users":
                $users = $this->userService->getAllUsers();
                break;
        }
        return $users;
    }

    public function editUserDetails()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $this->sendHeaders();
                $responseData = array(
                    'success' => false,
                    'message' => "Something went wrong while updating user details,please Try Again!!"
                ); //making default

                // parsing input data
                $userDetails = json_decode($_POST['details']);
                $profilePicture = $_FILES['profilePicture'];
                $userID = htmlspecialchars($userDetails->id);
                $userFirstName = htmlspecialchars($userDetails->firstName);
                $userLastName = htmlspecialchars($userDetails->lastName);
                $userEmail = htmlspecialchars($userDetails->email);
                $userDateOfBirth = htmlspecialchars($userDetails->dateOfBirth);
                $userRole = htmlspecialchars($userDetails->role);
                $userPassword = isset($userDetails->password) ? htmlspecialchars($userDetails->password) : ''; // checking if password is sent or not if not setting default value

                if ($this->userService->checkEditingUserEmailExistence($userEmail, $userID)) {
                    $responseData = array(
                        'success' => false,
                        'message' => " This email address: $userEmail already exist, please use another email"
                    );
                } else {
                    $updatingUser = $this->createUserInstance($userID, $userFirstName, $userLastName, $userEmail, $userDateOfBirth, $userRole, $userPassword);
                    $success = $this->userService->updateUserV2($updatingUser, $profilePicture);
                    if ($success) {
                        $responseData = array(
                            'success' => $success,
                            'message' => ""
                        );
                    }
                }
            }
            echo json_encode($responseData);
        } catch (Exception $e) {
            http_response_code(500); // sending bad request error to APi request if something goes wrong
            echo $e->getMessage();
        }
    }

    private function createUserInstance($id, $firstName, $lastName, $email, $dateOfBirth, $role, $password)
    {
        try {
            $user = new User();
            $user->setId($id);
            $user->setFirstName($firstName);
            $user->setLastName($lastName);
            $user->setEmail($email);
            $user->setDateOfBirth(new DateTime($dateOfBirth));
            $user->setRole(Roles::fromString($role));
            $user->setHashedPassword($password);

            return $user;
        } catch (InvalidArgumentException|Exception $e) { // whenever something goes wrong while parsing
            http_response_code(500); // sending bad request error to APi request if something goes wrong
        }
    }
}
