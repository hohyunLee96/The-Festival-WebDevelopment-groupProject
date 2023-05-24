<?php
require __DIR__ . '/controller.php';
require __DIR__ . '/../services/userService.php';
require_once __DIR__ . '/../models/User.php';

class LoginController extends Controller
{
    private $userService;

    // initialize services
    function __construct()
    {
        $this->userService = new UserService();
    }

    public function logout($query)
    {
        // session_start();
        session_unset();
        session_destroy();
        header("location: /login");
        exit();
    }

    public function index($query)
    {
        // check if the user is already logged in.
        // if $_SESSION["loggedUser"] is set, it means the user has already logged in.
        // if the user is already logged in, redirect her to /home.
        if (isset($_SESSION["loggedUser"])) {
            // echo "you are already logged in";
            header("location: /home");
            exit();
        }
        // if the user has submitted a login request,
        // the form calls login again, but this time, the $_POST parameters
        // are set. So, we enter the else if.
        else if (isset($_POST["signInSubmit"]) && isset($_POST["username"]) && isset($_POST["pwd"])) {
            $inputUserName = $_POST["username"];
            $inputPassword = $_POST["pwd"];
            // using html special chars function to clean up the input
            $inputUserName = htmlspecialchars($inputUserName);
            $inputPassword = htmlspecialchars($inputPassword);
            // checkLogin method in UserService class checks if the user with the given username and password exists in the database. If it exits, it returns the user object, if it does not exists or the password is wrong, it returns null.
            $user = $this->userService->checkLogin($inputUserName, $inputPassword);

            if (isset($user) && $user != null) {
                // if the user exists in the database, log it in.
                // to show the user is logged in, we set the loggeUser value in $_SESSION dictionary. Then, we redirect to home.
                $_SESSION['loggedUser'] = $user;
                $_SESSION['userId'] = $user->getId();

                header("location: /home");
            } // if the username or password is wrong, we are here
            else {
                // displayView shows the contents of views/login/index.php
                $this->displayView("Wrong Credentials. Try again.");
            }
        } // if the user is visiting the login page normally, show her the login page!
        else {
            // displayView shows the contents of views/login/index.php
            $this->displayView(null);
        }
    }

    public function registerUser()
    {
        $errorMessage = "";
        if (isset($_POST["registerBtn"])) {
            if (empty($_POST["firstName"])) {
                $errorMessage = "Please fill out your first name";
            } else if (empty($_POST["lastName"])) {
                $errorMessage = "Please fill out your last name";
            } else if (empty($_POST["email"])) {
                $errorMessage = "Please fill out your email";
            } else if (empty($_POST["password"])) {
                $errorMessage = "Please fill out your password";
            } else {
                if ($this->userService->captchaVerification($errorMessage)) {
                    $this->registerValidUser($errorMessage);
                }
            }
        }
        require __DIR__ . '/../views/login/register.php';
    }


    private function registerValidUser(&$systemMessage)
    {
        if ($this->userService->checkUserExistenceByEmail(htmlspecialchars($_POST["email"]))) {
            $systemMessage = "duplicated email";
        } else if ($_POST['password'] != $_POST['passwordConfirm']) {
            $systemMessage = "passwords are not matched";
        } else {
            $this->createNewUser($systemMessage);
        }
    }

    private function createNewUser(&$systemMessage)
    {
        $birthDate = htmlspecialchars($_POST["dateOfBirth"]);
        $parsedDate = $this->parseDateOfBirth($birthDate);
        if (is_string($parsedDate)) {
            $systemMessage = $parsedDate;
        } else {
            $newUser = array(
                "firstName" => htmlspecialchars($_POST["firstName"]),
                "lastName" => htmlspecialchars($_POST["lastName"]),
                "dateOfBirth" => htmlspecialchars($_POST["dateOfBirth"]),
                "email" => htmlspecialchars($_POST["email"]),
                "password" => htmlspecialchars($_POST["password"]),
                "picture" => $_FILES['createUserImage'],
                "role" => Roles::customer()
            );
            if (empty($_SESSION['orderId'])) {
                $orderId = null;
            } else {
                $orderId = $_SESSION['orderId'];
            }
            $this->userService->registerUser($newUser, $orderId);
            echo "<script>window.location.replace('/home');</script>";
        }
    }

    /**
     * @throws Exception
     */
    public
    function resetPasswordViaEmail()
    {
        if (isset($_POST["send-link"])) {
            $email = htmlspecialchars($_POST["forgotPasswordEmail"]);
            if ($this->userService->checkUserExistenceByEmail($email)) {
                $this->userService->sendEmail($email);
            } else {
                echo "<script>alert('We cannot find this email from our system')</script>";
            }
        }
        require __DIR__ . '/../views/login/sendEmailForgotPassword.php';
    }

    public
    function updatePassword()
    {
        if (isset($_POST["updatePassword"])) {
            $token = $_GET["token"];
            if ($this->userService->isTokenValid($token)) {

                $newPassword = $_POST['updateNewPassword1'];
                $userId = $this->userService->isTokenValid($token);

                $this->userService->updatePassword($userId, $newPassword);
            }
        }
        require __DIR__ . '/../views/login/updateForgotPassword.php';
    }
}