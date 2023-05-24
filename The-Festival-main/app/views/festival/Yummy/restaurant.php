<?php
// session_start()
if(isset($model) && $model != null && isset($model->restaurants) && $model->restaurants != null) {
  $restaurants = $model->restaurants;
}
else {
  $restaurants = null;
}
if(isset($model) && $model != null && isset($model->foodTypes) && $model->foodTypes != null) {
  $foodTypes = $model->foodTypes;
  // print_r($foodTypes);
} else {
  $foodTypes = null;
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Festival</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  </head>

  <body>
    <div class="restaurantCards container col-sm-12 col-md-9 col-lg-6">
    <form action="/festival/Yummy/restaurant" method="POST">
      <div class="form-floating mb-3">
        <select name="restaurantFoodTypesSelect" class="form-select" aria-label="Default select example">
          <option value="All" selected>All</option>
          <?php
          if($foodTypes != null && count($foodTypes) > 0) {
            foreach ($foodTypes as $foodType) {
              include("foodTypeOptionCreator.php");
            }
          }
          ?>
        </select>
        <label for="restaurantFoodTypesSelect">Food Types</label>
      </div>
      
      <div class="form-floating mb-3">
        <button class="btn mb-2" name="searchSubmit" type="submit">
          Search
        </button>
      </div>
    </form>
        
        <?php
        if($restaurants != null && count($restaurants) > 0) {
          foreach ($restaurants as $restaurant) {
            include("showSingleRestaurant.php");
          }
        }
        ?>
    </div>
  </body>
</html>
