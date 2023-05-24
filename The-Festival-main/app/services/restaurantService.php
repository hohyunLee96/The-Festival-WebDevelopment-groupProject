<?php
require __DIR__ . '/../repositories/restaurantRepository.php';
require_once __DIR__ . '/../models/restaurant.php';

class RestaurantService
{
    
    public function deleteRestaurantById(int $restaurantID)
    {
        $repository = new RestaurantRepository();
        return $repository->deleteRestaurantById($restaurantID);
    }
    public function getRestaurantByName(string $restaurantName)
    {
        $repository = new RestaurantRepository();
        return $repository->getRestaurantByName($restaurantName);
    }
    public function getRestaurantById(int $restaurantId)
    {
        $repository = new RestaurantRepository();
        return $repository->getRestaurantById($restaurantId);
    }

    public function getRestaurants()
    {
        $repository = new RestaurantRepository();
        return $repository->getRestaurants();
    }

    public function getAllFoodTypes() {
        $repository = new RestaurantRepository();
        return $repository->getAllFoodTypes();
    }

    public function createNewRestaurant($newRestaurant)
    {
        $repository = new RestaurantRepository();
        return $repository->createNewRestaurant($newRestaurant);
    }

    public function updateRestaurantById($restaurantID, $newRestaurant): void 
    {
        $repository = new RestaurantRepository();
        $repository->updateRestaurantById($restaurantID, $newRestaurant);
    }

    public function deletePageById($pageID): void 
    {
        $repository = new PageRepository();
        $repository->deletePageById($pageID);
    }
}
