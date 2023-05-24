<?php
require_once __DIR__ . '/repository.php';
require_once __DIR__ . '/../models/restaurant.php';

class RestaurantRepository extends Repository
{
    private function createRestaurantInstance($dbRow): Restaurant
    {
        try {
            $restaurant = new Restaurant();
            $restaurant->setId($dbRow['id']);
            $restaurant->setName($dbRow['name']);
            $restaurant->setLocation($dbRow['location']);
            $restaurant->setDescriptionHTML($dbRow['descriptionHTML']);
            $numSeats = $dbRow['numberOfSeats'];
            if(is_int($numSeats)) {
                $restaurant->setNumberOfSeats($numSeats);
            }
            else {
                $restaurant->setNumberOfSeats(0);
            }
            $score = $dbRow['score'];
            if(is_int($score)) {
                $restaurant->setScore($score);
            }
            else {
                $restaurant->setScore(0);
            }
            $foodTypes = $dbRow['foodTypes'];
            if(is_string($foodTypes)) {
                $restaurant->setFoodTypes($foodTypes);
            }
            else {
                $restaurant->setFoodTypes("");
            }

            return $restaurant;
        } catch (Exception $e) {
            echo "Error while creating user instance: " . $e->getMessage();
        }
    }

    public function getPageById(int $pageId)
    {
        try {
            $stmt = $this->connection->prepare("SELECT * FROM Page WHERE id = :id");
            $stmt->bindParam(':id', $pageId);
            $stmt->execute();
            if ($stmt->rowCount() == 0) {
                return null;
            }
            $result = $stmt->fetch();
            return $this->createPageInstance($result);
        } catch (PDOException|Exception $e) {
            echo $e;
        }
    }


    public function getRestaurants()
    {
        try {
            // we make a query to database, to find all restaurants.
            $stmt = $this->connection->prepare("SELECT * FROM Restaurant");
            // execute the query.
            $stmt->execute();
            $restaurants = array();
            $results = $stmt->fetchAll();
            foreach ($results as $row) {
                $restaurant = $this->createRestaurantInstance($row);
                array_push($restaurants, $restaurant);
            }
            return $restaurants;
        } catch (PDOException|Exception $e) {
            echo $e;
        }
    }
    public function getAllFoodTypes() {
        try {
            // we make a query to database, to find all restaurants.
            $stmt = $this->connection->prepare("SELECT * FROM RestaurantType");
            // execute the query.
            $stmt->execute();
            $foodTypeNames = array();
            $results = $stmt->fetchAll();
            foreach ($results as $row) {
                // print_r($row);
                $foodTypeName = $row['name'];
                array_push($foodTypeNames, $foodTypeName);
            }
            return $foodTypeNames;
        } catch (PDOException|Exception $e) {
            echo $e;
        }
    }

    public function getRestaurantByName(string $restaurantName)
    {
        try {
            // we make a query to database, to find the page with the given title.
            $stmt = $this->connection->prepare("SELECT * FROM Restaurant WHERE name = :name");
            // to increase security, we use bindParam.
            $stmt->bindParam(':name', $restaurantName);
            // then execute the query.
            $stmt->execute();
            // if the number of records found with the given title is zero, then that title does not exist in the database.
            if ($stmt->rowCount() == 0) {
                return null;
            }
            $result = $stmt->fetch();
            return $this->createRestaurantInstance($result);
        } catch (PDOException|Exception $e) {
            echo $e;
        }
    }
    public function getRestaurantById(int $restaurantId)
    {
        try {
            // we make a query to database, to find the page with the given title.
            $stmt = $this->connection->prepare("SELECT * FROM Restaurant WHERE id = :id");
            // to increase security, we use bindParam.
            $stmt->bindParam(':id', $restaurantId);
            // then execute the query.
            $stmt->execute();
            // if the number of records found with the given title is zero, then that title does not exist in the database.
            if ($stmt->rowCount() == 0) {
                return null;
            }
            $result = $stmt->fetch();
            return $this->createRestaurantInstance($result);
        } catch (PDOException|Exception $e) {
            echo $e;
        }
    }
    public function createNewRestaurant($newRestaurant)
    {
        try {
            $stmt = $this->connection->prepare("INSERT into Restaurant (name, location, descriptionHTML, numberOfSeats, score, foodTypes) VALUES (:name, :location, :descriptionHTML, :numberOfSeats, :score, :foodTypes)");

            $stmt->bindValue(':name', $newRestaurant->getName());
            $stmt->bindValue(':location', $newRestaurant->getLocation());
            $stmt->bindValue(':descriptionHTML', $newRestaurant->getDescriptionHTML());
            $stmt->bindValue(':numberOfSeats', $newRestaurant->getNumberOfSeats());
            $stmt->bindValue(':score', $newRestaurant->getScore());
            $stmt->bindValue(':foodTypes', $newRestaurant->getFoodTypes());
            $stmt->execute();

            $newRestaurant->setId($this->connection->lastInsertId());
            return $newRestaurant;

        } catch (PDOException|Exception $e) {
            echo $e;
        }
    }

    public function updateRestaurantById($restaurantID, $newRestaurant): void 
    {
        try {
            $stmt = $this->connection->prepare("UPDATE Restaurant SET name = :name, location = :location, descriptionHTML = :descriptionHTML, numberOfSeats = :numberOfSeats, score = :score, foodTypes = :foodTypes WHERE id = :id;");
            // we use bindValue to increase security and prevent SQL injection.
            $stmt->bindValue(':name', $newRestaurant->getName());
            $stmt->bindValue(':location', $newRestaurant->getLocation());
            $stmt->bindValue(':descriptionHTML', $newRestaurant->getDescriptionHTML());
            $stmt->bindValue(':numberOfSeats', $newRestaurant->getNumberOfSeats());
            $stmt->bindValue(':score', $newRestaurant->getScore());
            $stmt->bindValue(':foodTypes', $newRestaurant->getFoodTypes());
            $stmt->bindValue(':id',$restaurantID);

            $stmt->execute();
        } catch (PDOException|Exception $e) {
            echo $e;
        }
    }
    public function deleteRestaurantById($restaurantID): void
    {
        try {
            $stmt = $this->connection->prepare("DELETE FROM Restaurant WHERE id = :id;");
            $stmt->bindValue(':id',$restaurantID);
            $stmt->execute();
        } catch (PDOException|Exception $e) {
            echo $e;
        }
    }

    function updatePageContentById($id, $bodyContentHTML)
    {
        try {
            $stmt = $this->connection->prepare("UPDATE User SET bodyContentHTML = :bodyContentHTML WHERE id = :id");

            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':bodyContentHTML', $bodyContentHTML);

            $stmt->execute();
            if($stmt->rowcount() == 0){
                return false;
            }
            return true;

        } catch (PDOException $e) {
            echo $e;
        }
    }
}

