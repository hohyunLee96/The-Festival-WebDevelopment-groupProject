<?php
require_once __DIR__ . '/EventController.php';
require_once __DIR__ . '/../../models/user.php';
require_once __DIR__ . '/../../models/restaurant.php';
require_once __DIR__ . '/../../services/restaurantService.php';

class YummyController extends eventController {
    private $restaurantService;
    public function __construct()
    {
        parent::__construct();
        $this->restaurantService = new RestaurantService();
    }

    public function index(){
        $this->displayViewFestival(null);
    }

    private function filterRestaurants($restaurants, $foodTypesArr) {
        foreach($foodTypesArr as $fValue) {
            $fValue = trim($fValue);
            foreach($restaurants as $rKey => $restaurant) {
                // print_r($rKey);
                // print_r($restaurant);
                // if it does not contain it, remove it
                if (strpos(" ".$restaurant->getFoodTypes()." ", $fValue) == false) {
                    // echo "removing ". $fValue . "from ". $restaurant->getFoodTypes();
                    // print_r($restaurant);
                    // echo $restaurant->getName() . " - ";
                    // echo $restaurant->getFoodTypes(). " - ";
                    // echo $fValue;
                    // echo "---";
                    unset($restaurants[$rKey]);
                }
            }
        }
        return $restaurants;
    }

    public function restaurant($query) {
        // get all restaurants
        $model = new stdClass;
        $restaurants = $this->restaurantService->getRestaurants();
        $model->restaurants = $restaurants;
        $foodTypes = $this->restaurantService->getAllFoodTypes();
        $model->foodTypes = $foodTypes;
        if(isset($_POST["searchSubmit"]) && isset($_POST["restaurantFoodTypesSearch"]) && $_POST["restaurantFoodTypesSearch"]!="") {
            $sFT = $_POST["restaurantFoodTypesSearch"];
            print_r($sFT);
            $foodTypesArr = explode( ',', $sFT);
            print_r($foodTypesArr);
            $restaurants = $this->filterRestaurants($restaurants, $foodTypesArr);
            $model->restaurants = $restaurants;
            // filter restaurants
            // for each food type, remove the restaurants not having that type
        }
        if(isset($_POST["searchSubmit"]) && isset($_POST["restaurantFoodTypesSelect"]) && $_POST["restaurantFoodTypesSelect"]!="") {
            $sFT = $_POST["restaurantFoodTypesSelect"];
            // print_r($sFT);
            $foodTypesArr = explode( ',', $sFT);
            // print_r($foodTypesArr);
            $restaurants = $this->filterRestaurants($restaurants, $foodTypesArr);
            $model->restaurants = $restaurants;
            // filter restaurants
            // for each food type, remove the restaurants not having that type
        }
        
        // print_r($restaurants);
        // $c = count($restaurants);
        // echo "Number of restaurants is {$c}";       
        $this->displayNavBar("Yummy",'/css/festival/yummy.css');
        // require __DIR__ . '/../../views/festival/History/index.php';
        $this->displayViewFestival($model);
    }

    private function deleteFestivalYummyImage($ImageName)
    {
        try {
            $filePath = '/image/Festival/Yummy/' . $ImageName;
            if (is_file($filePath) && file_exists($filePath)) {
                return unlink($filePath);
            } else {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
        return true;
    }

    private function processUpdatingRestaurantCardImage($image, $restaurantId)
    {
        $oldImageName = "RestaurantCardImage-" . $restaurantId;
        try {
            // Check if a new image was provided
            if (!empty($image['name']) ) {
                // Delete the old image from the server
                $this->deleteFestivalYummyImage($oldImageName);
                // Store the new image on the server and return its filename
                $newImageName = "RestaurantCardImage-" . $restaurantId . "." . pathinfo($image['name'], PATHINFO_EXTENSION);
                $this->storeImage($image, $newImageName);
                return $newImageName;
                
            } else {
                // If no new image was provided or the image is "Default", return the filename of the old image
                return $oldImageName;
            }
        } catch (uploadFileFailedException $e) {
            echo $e->getMessage();
        }
    }

    private function storeImage($image, $newImageName)
    {
        try {
            $upload_dir = __DIR__ . '/../../public/image/Festival/Yummy/';
            $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
            if (!move_uploaded_file($image['tmp_name'], $upload_dir . $newImageName)) {
                throw new Exception("error in move uploaded file");
            }
            return;
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }

    public function editRestaurantSubmitted() {
        // submitting new page or updating an existing page
        if(isset($_POST["formSubmit"])) {
            // save to database
            $restaurant = new Restaurant();
            // comment: correct URI later.
            $restaurant->setName($_POST['restaurantName']);
            $restaurant->setLocation($_POST['restaurantLocation']);
            $restaurant->setNumberOfSeats($_POST['restaurantNumberOfSeats']);
            $restaurant->setScore($_POST['restaurantScore']);
            $restaurant->setFoodTypes($_POST['restaurantFoodTypes']);
            $restaurant->setDescriptionHTML($_POST["tinyMCEform"]);

            
            // check if the page is a new page or updating existing page
            // if the pageID value exists in the submitted form, we are updating that page. If it does not exist, we are creating a new page.
            if(isset($_POST['restaurantID'])) {
                $this->restaurantService->updateRestaurantById($_POST['restaurantID'], $restaurant);

                // handle restaurant card image
                $restaurantCardPicture = $_FILES['cardRestaurantPicUpload'];
                $imageName = $this->processUpdatingRestaurantCardImage($restaurantCardPicture, $_POST['restaurantID']);

                $this->displayNavBar("Yummy",'/css/festival/yummy.css');

                echo "existing restaurant updated!";
            }
            else {
                $createdRest = $this->restaurantService->createNewRestaurant($restaurant);
                $restaurantCardPicture = $_FILES['cardRestaurantPicUpload'];
                $imageName = $this->processUpdatingRestaurantCardImage($restaurantCardPicture, $createdRest->getId());

                $this->displayNavBar("Yummy",'/css/festival/yummy.css');

                echo "new restaurant added!";
            }

            // show the user result.
            
            // $this->displayView($_POST["tinyMCEform"]);
        }
        // if the user has clicked the delete button
        else if(isset($_POST["formDelete"])) {
            $this->restaurantService->deleteRestaurantById($_POST['restaurantID']);
            echo "deleted restaurant with id " . $_POST['restaurantID'];   
        }
    }

    public function editRestaurant($query) {
        if (!isset($_SESSION["loggedUser"])) {
            // if user is not logged in, she cannot edit restaurants.
            header("location: /home");
            exit();
        }

        if (!unserialize(serialize($_SESSION["loggedUser"]))->getRole() == Roles::Administrator()) {
            // if user is not administrator, she cannot edit restaurants either
            header("location: /home");
            exit();
        }
        // first, we check for title in the query.
        parse_str($query, $parsedQuery);
        if(isset($parsedQuery["name"])) {
            // echo $parsedQuery["name"];
            $restaurant = $this->restaurantService->getRestaurantByName($parsedQuery["name"]);
            // print_r($restaurant);
            if($restaurant != null) {
                $this->displayNavBar("Yummy",'/css/festival/yummy.css');
                $this->displayViewFestival($restaurant);
            }
            else {
                // page not found
                $this->displayNavBar("Yummy",'/css/festival/yummy.css');
                $this->displayViewFestival(null);
            }
        }
        else {
            $this->displayNavBar("Yummy",'/css/festival/yummy.css');
            $this->displayViewFestival(null);
        }
    }

    public function bookTicket() {
        if (!isset($_SESSION["loggedUser"])) {
            // if user is not logged in, she cannot edit restaurants.
            header("location: /home");
            exit();
        }
        if(isset($_POST["formBookReservationSubmit"]) && isset($_POST["restaurantID"])) {
            // echo $_POST["restaurantID"];
            $this->displayNavBar("Yummy",'/css/festival/yummy.css');
            $restaurant = $this->restaurantService->getRestaurantById($_POST["restaurantID"]);
            $this->displayViewFestival($restaurant);
        }
        else {
            header("location: /home");
            exit();
        }
    }
}