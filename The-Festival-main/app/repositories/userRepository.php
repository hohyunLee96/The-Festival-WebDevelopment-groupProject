<?php
require_once __DIR__ . '/repository.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../Models/Roles.php';

class UserRepository extends Repository
{
    // returns all users in an array. It might return empty array if there is no users in the database.
    function getAllUsers()
    {
        try {
            $stmt = $this->connection->prepare("SELECT id, firstName, lastName, dateOfBirth, email, registrationDate, picture, role,password FROM User ");
            $stmt->execute();
            if ($stmt->rowCount() == 0) {
                return null;
            }
            $users = array();
            $result = $stmt->fetchAll();
            foreach ($result as $user) {
                $users[] = $this->createUserInstance($user);
            }
            return $users;
        } catch (PDOException|Exception $e) {
            echo $e;
        }
    }

    public function login(string $userName, string $password)
    {
        // error_log("Hashed Password: " . password_hash($password, PASSWORD_DEFAULT ) . "\n", 3, "log.txt");
        try {
            $stmt = $this->connection->prepare("SELECT * FROM User WHERE email = ?");
            $stmt->execute([$userName]);
            $rawUser = $stmt->fetch();
            // check if the username exists in the database.
            if ($rawUser != false) {
                $user = $this->createUserInstance($rawUser);
                // echo $user->getFirstName();
                // echo $user->getEmail();
                if (password_verify($password, $user->getHashedPassword())) {
                    // to increase the security, we delete the hashed password.
                    $user->setHashedPassword("");
                    return $user;
                }
            }
            // echo "no user found";
            return null;

        } catch (Exception|PDOException $e) {
            echo $e;
        }
    }

    private function createUserInstance($dbRow): User
    {
        try {
            $user = new User();
            $user->setId($dbRow['id']);
            $user->setEmail($dbRow['email']);
            $user->setHashedPassword($dbRow['password']);
            $user->setRegistrationDate(new DateTime($dbRow['registrationDate']));
            $user->setRole(Roles::fromString($dbRow['role']));
            $user->setDateOfBirth(new DateTime($dbRow['dateOfBirth']));
            $user->setFirstName($dbRow['firstName']);
            $user->setLastName($dbRow['lastName']);
            $user->setPicture($dbRow['picture']);
            return $user;
        } catch (Exception $e) {
            echo "Error while creating user instance: " . $e->getMessage();
        }

    }

    public function getUserById(int $userId)
    {
        try {
            $stmt = $this->connection->prepare("SELECT id, firstName, lastName, dateOfBirth, email, registrationDate, picture, role,password FROM User WHERE id = :id");
            $stmt->bindParam(':id', $userId);
            $stmt->execute();
            if ($stmt->rowCount() == 0) {
                return null;
            }
            $result = $stmt->fetch();
            return $this->createUserInstance($result);
        } catch (PDOException|Exception $e) {
            echo $e;
        }
    }

    public function getUsersBySearchQuery($searchingTerm)
    {
        try {
            $stmt = $this->connection->prepare("SELECT id, firstName, lastName, dateOfBirth, email, registrationDate, picture, role,password FROM User WHERE firstName LIKE ? OR lastName LIKE ? OR email LIKE ? OR id LIKE ?");
            $stmt->execute(["%$searchingTerm%", "%$searchingTerm%", "%$searchingTerm%", "%$searchingTerm%"]);
            if ($stmt->rowCount() == 0) {
                return null;
            }
            $users = array();
            $result = $stmt->fetchAll();
            foreach ($result as $user) {
                $users[] = $this->createUserInstance($user);
            }
            return $users;
        } catch (PDOException|Exception $e) {
            echo $e;
        }
    }

    public function getUserBySortingFirstNameByAscOrDescOrders($order)
    {
        try {
            $stmt = $this->connection->prepare("SELECT id, firstName, lastName, dateOfBirth, email, registrationDate, picture, role,password FROM User ORDER BY firstName $order , lastName $order ");
            $stmt->execute();
            $users = array();
            if ($stmt->rowCount() == 0) {
                return null;
            }
            $result = $stmt->fetchAll();
            foreach ($result as $user) {
                $users[] = $this->createUserInstance($user);
            }
            return $users;
        } catch (PDOException|Exception $e) {
            echo $e;
        }
    }

    public function getUsersByRoles($role)
    {
        try {
            $stmt = $this->connection->prepare("SELECT id, firstName, lastName, dateOfBirth, email, registrationDate, picture, role,password FROM User WHERE role = :role");
            $stmt->bindValue(':role', Roles::getLabel($role));
            $stmt->execute();
            if ($stmt->rowCount() == 0) {
                return null;
            }
            $users = array();
            $result = $stmt->fetchAll();
            foreach ($result as $user) {
                $users[] = $this->createUserInstance($user);
            }
            return $users;
        } catch (PDOException|Exception $e) {
            echo $e;
        }
    }

    public function getUsersBySearchAndSpecificRoles($searchingTerm, $criteria)
    {
        try {
            $stmt = $this->connection->prepare("SELECT id, firstName, lastName, dateOfBirth, email, registrationDate, picture, role,password FROM User WHERE (firstName LIKE :searchingTerm OR lastName LIKE :searchingTerm OR email LIKE :searchingTerm) AND role= :role");
            $stmt->bindValue(':searchingTerm', "%$searchingTerm%");
            $stmt->bindValue(':role', Roles::getLabel($criteria));
            $stmt->execute();
            if ($stmt->rowCount() == 0) {
                return null;
            }
            $users = array();
            $result = $stmt->fetchAll();
            foreach ($result as $user) {
                $users[] = $this->createUserInstance($user);
            }
            return $users;
        } catch (PDOException|Exception $e) {
            echo $e;
        }
    }

    public function deleteUserById($id)
    {
        try {
            $stmt = $this->connection->prepare("DELETE FROM User WHERE id = :id");
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            if ($stmt->rowCount() == 0) {
                return false;
            }
            return true;
        } catch (PDOException|Exception $e) {
            echo $e;
        }
    }

    public function registerUser($newUser, $orderId)
    {
        try {
            $stmt = $this->connection->prepare("INSERT into User (firstName, lastName, dateOfBirth, email, password, picture, role) VALUES (:firstName, :lastName, :dateOfBirth, :email, :password, :picture, :role)");
            $stmt->bindValue(':firstName', $newUser["firstName"]);
            $stmt->bindValue(':lastName', $newUser["lastName"]);
            $stmt->bindValue(':dateOfBirth', $newUser["dateOfBirth"]);
            $stmt->bindValue(':email', $newUser["email"]);
            $stmt->bindValue(':password', $newUser['password']);
            $stmt->bindValue(':picture', $newUser['picture']);
            $stmt->bindValue(':role', Roles::getLabel($newUser['role']));
            $stmt->execute();
            $this->updateOrderTableWithNewUserId($this->connection->lastInsertId(), $orderId);
        } catch (PDOException $e) {
            echo $e;
        }
    }
    public function updateOrderTableWithNewUserId($userId, $orderId){
        try {
            $stmt = $this->connection->prepare("UPDATE `Order` SET user_id = :user_id WHERE orderId = :orderId");
            $stmt->bindValue(':user_id', $userId);
            $stmt->bindValue(':orderId', $orderId);
            $stmt->execute();
            if ($stmt->rowCount() == 0) {
                return false;
            }
            else{
                unset($_SESSION['orderId']); // expire the session variable
                return true;
            }
        } catch (PDOException $e) {
            echo $e;
        }
    }

    private function checkUserExistence($stmt): bool
    {
        try {
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            echo $e;
            exit();
        }
    }

    public function checkUserExistenceByEmail($email)
    {
        try {
            $stmt = $this->connection->prepare("SELECT id From User WHERE email= :email");
            $stmt->bindValue(':email', $email);
            if ($this->checkUserExistence($stmt)) {
                $stmt->execute();
                $result = $stmt->fetch();
                return $result[0];
            }
        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function isTokenValid($token)
    {
        try {
            $stmt = $this->connection->prepare("SELECT User.id
                                                        FROM User
                                                        Inner JOIN forgotPassword
                                                        ON User.id = forgotPassword.userId
                                                        WHERE forgotPassword.randomToken = :randomToken");

            $stmt->bindValue(':randomToken', $token);
            $stmt->execute();
            // Fetch the result from the executed SQL statement
            $result = $stmt->fetch();

            // Return the email address from the result
            return $result[0];

        } catch (PDOException $e) {
            echo $e;
        }
    }

    function putRandomTokenForNewPassword($token, $expiration_time, $userId)
    {
        try {
            $stmt = $this->connection->prepare("INSERT into forgotPassword (tokenExpiration, randomToken, userId) VALUES (:tokenExpiration, :randomToken, :userId)");

            $stmt->bindValue(':randomToken', $token);
            $stmt->bindValue(':tokenExpiration', $expiration_time);
            $stmt->bindValue(':userId', $userId);

            $stmt->execute();
        } catch (PDOException $e) {
            echo $e;
        }
    }

    function updatePassword($userId, $newPassword)
    {
        try {
            $stmt = $this->connection->prepare("UPDATE User SET password = :password WHERE id = :id");

            $stmt->bindValue(':password', $newPassword);
            $stmt->bindValue(':id', $userId);

            $stmt->execute();
            if ($stmt->rowcount() == 0) {
                return false;
            }
            return true;

        } catch (PDOException $e) {
            echo $e;
        }
    }

    function deleteDataForgotPassword($userId, $tokenExpiration)
    {
        try {
            $stmt = $this->connection->prepare("DELETE FROM forgotPassword WHERE tokenExpiration < :tokenExpiration OR id = :id");

            $stmt->bindValue(':tokenExpiration', $tokenExpiration);
            $stmt->bindValue(':id', $userId);

            $stmt->execute();
        } catch (PDOException $e) {
            echo $e;
        }
    }
    function updateUserV2($updatedUser)
    {
        $query = "UPDATE User SET role=:role, firstName=:firstName, lastName=:lastName, dateOfBirth=:dateOfBirth, email=:email, picture=:picture";
        if (!empty($updatedUser->getHashedPassword())) {
            $query .= ", password=:password"; // adding password to the query when password will be changed
        }
        $query .= " WHERE id=:id";
        $parameters = array(
            ":id" => $updatedUser->getId(),
            ":role" => Roles::getLabel($updatedUser->getRole()),
            ":firstName" => $updatedUser->getFirstName(),
            ":lastName" => $updatedUser->getLastName(),
            ":dateOfBirth" => $updatedUser->getDateOfBirth()->format('Y-m-d'),
            ":email" => $updatedUser->getEmail(),
            ":picture" => $updatedUser->getPicture()
        );
        if (!empty($updatedUser->getHashedPassword())) {
            $parameters[":password"] = $updatedUser->getHashedPassword();
        }
        return $this->executeQuery($query, $parameters);
    }


    // used when user edit process is going on
    function checkEditingUserEmailExistence($email, $userID): bool
    {
        try {
            $stmt = $this->connection->prepare("SELECT COUNT(*) FROM User WHERE email = :email AND id != :userId");
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':userId', $userID);
            $stmt->execute();
            return $stmt->fetchColumn() > 0; // returns true if user exist with coming email expect then same user
        } catch (PDOException $e) {
            echo $e;
        }
    }


    function getUserPictureById($id)
    {
        try {
            $stmt = $this->connection->prepare("SELECT picture FROM User WHERE id = :id");
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            echo $e;
        }
    }


    public function retrieveUserByIdWithUrl($id)
    {
        try {

            $stmt = $this->connection->prepare("SELECT * From User WHERE id LIKE :id");
            $stmt->bindValue(':id', "%$id%");
            if ($this->checkUserExistence($stmt)) {
                $stmt->execute();
                $result = $stmt->fetch();
                return $result;
            }
        } catch (PDOException $e) {
            echo $e;
        }
    }


    public function retrieveUserPermissionsWithUrl($id)
    {
        try {

            $stmt = $this->connection->prepare("SELECT role From User WHERE id LIKE :id");
            $stmt->bindValue(':id', "%$id%");
            if ($this->checkUserExistence($stmt)) {
                $stmt->execute();
                $result = $stmt->fetch();
                return $result[0];
            }
        } catch (PDOException $e) {
            echo $e;
        }
    }


    public function checkUserExistenceByEmailWithUrl($email)
    {
        try {

            $stmt = $this->connection->prepare("SELECT id From User WHERE email LIKE :email");
            $stmt->bindValue(':email', "%$email%");
            if ($this->checkUserExistence($stmt)) {
                $stmt->execute();
                if ($stmt->rowCount() > 0) {
                    return true;
                }
                return false;
            }
        } catch (PDOException $e) {
            echo $e;
        }
    }
    public function isUpdatingUserDetailsSame($user){
        if(!empty($user->getHashedPassword())){
            return false; // if someone wants to change password then it  is already as not a same detail
        }
        $query="SELECT id FROM User WHERE id=:id AND role=:role AND firstName=:firstName AND lastName=:lastName 
                      AND dateOfBirth=:dateOfBirth AND email=:email AND picture=:picture";
        $parameters=array(
            ":id"=>$user->getId(),
            ":role"=>Roles::getLabel($user->getRole()),
            ":firstName"=>$user->getFirstName(),
            ":lastName"=>$user->getLastName(),
            ":dateOfBirth"=>$user->getDateOfBirth()->format('Y-m-d'),
            ":email"=>$user->getEmail(),
            ":picture"=>$user->getPicture()
        );
        $result=$this->executeQuery($query,$parameters);
        if(empty($result)){
            return false;
        }
        return true;
    }

}

