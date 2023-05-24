<?php
// session_start()
if(isset($model) && $model != null) {
  $restaurant = $model;
}
else {
  $restaurant = null;
}

$dates = array();
array_push($dates, "Thursday 27 July");
array_push($dates, "Friday 28 July");
array_push($dates, "Saturday 29 July");
array_push($dates, "Sunday 30 July");
$sessions = array();
array_push($sessions, "17:00 till 19:00");
array_push($sessions, "19:00 till 21:00");
array_push($sessions, "21:00 till 23:00");

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Festival</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  </head>

  <body>
    <div class="container">
    <div class="col-sm-12 col-md-6 col-lg-4 mx-auto">
    <h1> Book Ticket at Restaurant <?php echo $restaurant->getName(); ?>
    <form action="/festival/Yummy/bookTicketSubmitted" method="POST"  enctype="multipart/form-data" >
        <div class="form-floating mb-3">
        <input type="hidden" class="form-control" name="restaurantID" id="restaurantID" value= <?php
        $restaurantId = $restaurant->getId();
            echo $restaurantId;
        ?> >
        </div>

    <div>
    <label for="bookTicketDateSelect">Choose date:</label>
    </div>
    <div class="form-floating mb-3">
    <select name="restaurantFoodTypesSelect" class="form-select" aria-label="Default select example">
        <?php
        if($dates != null && count($dates) > 0) {
        foreach ($dates as $date) {
            include("dateOptionCreator.php");
        }
        }
        ?>
    </select>
    </div>
    <div>
    <label for="bookTicketSessionSelect">Choose session:</label>
    </div>
    <div class="form-floating mb-3">
    <select name="restaurantFoodTypesSelect" class="form-select" aria-label="Default select example">
        <?php
        if($sessions != null && count($sessions) > 0) {
            foreach ($sessions as $session) {
                include("sessionOptionCreator.php");
            }
        }
        ?>
    </select>
    </div>

    <div> 
    <label for="numberOfAdults">Adults</label>
    </div>
    <div class="form-floating mb-3">
    <input type="number"
    class="form-control"
    name="numberOfAdults"
    id="numberOfAdults"
    placeholder="Number of adults"
    >
    </div>

    <div> 
    <label for="numberOfChildren">Children:</label>
    </div>
    <div class="form-floating mb-3">
    <input type="number"
    class="form-control"
    name="numberOfChildren"
    id="numberOfChildren"
    placeholder="Number of children"
    >
    </div>


    <div class="form-floating mb-3">
    <button class="btn mb-2" name="formSubmit" type="submit">
        Book
    </button>
    </div>
    </form>
    </div>
    </div>
  </body>
</html>
