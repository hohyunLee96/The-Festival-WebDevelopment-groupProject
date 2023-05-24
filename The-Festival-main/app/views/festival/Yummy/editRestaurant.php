<?php
// session_start()
if(isset($model) && $model != null) {
  $restaurant = $model;
}
else {
  $restaurant = null;
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Festival</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    
    <script src="/Javascripts/tinymce/js/tinymce/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
      tinymce.init({
        /* replace textarea having class .tinymce with tinymce editor */
        selector: "#mytextarea",
        plugins: 'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking image save table contextmenu directionality emoticons template paste textcolor',
    });
    </script>
  </head>

  <body>
  <div class="row g-0">
            <div class="container">
                <div class="row d-flex justify-content-center">
                    <div class="col-sm-12 col-md-6 col-lg-4 mx-auto">
    <?php
    if($restaurant == null) {
      echo "<h1>Create a new restaurant</h1>";
    }
    else {
      echo "<h1>Edit an existing restaurant</h1>";
    }

    ?>

    <form action="/festival/Yummy/editRestaurantSubmitted" method="POST"  enctype="multipart/form-data" >
    <div class="form-floating mb-3">
        <input type="text"
        class="form-control"
        name="restaurantName"
        id="restaurantName"
        placeholder="Restaurant Name"
        value="<?php 
        // if we are showing a page for editing, fill the page tile field.
        if($restaurant !=null) echo $restaurant->getName();
        ?>"
        >
        <label for="restaurantName">Restaurant Name</label>
      </div>
      <?php
      // if we are updating an existing page, we create a hidden input and give its value te pageId. So, when the form is submitted to the server, we know which pageId we are updating or deleting.
      if($restaurant != null) {
        $restaurantId = $restaurant->getId();
      ?>
        <div class="form-floating mb-3">
        <input type="hidden" class="form-control" name="restaurantID" id="restaurantID" value= <?php echo $restaurantId; ?> >
        </div>
      <?php
      }
      ?>
      <div class="form-floating mb-3">
        <input type="text"
        class="form-control"
        name="restaurantLocation"
        id="restaurantLocation"
        placeholder="Restaurant Location"
        value="<?php 
        // if we are showing a page for editing, fill the page tile field.
        if($restaurant !=null) echo $restaurant->getLocation();
        ?>"
        >
        <label for="restaurantLocation">Restaurant Location</label>
      </div>
      <div class="form-floating mb-3">
        <input type="number"
        class="form-control"
        name="restaurantNumberOfSeats"
        id="restaurantNumberOfSeats"
        placeholder="Restaurant Number Of Seats"
        value="<?php 
        // if we are showing a page for editing, fill the page tile field.
        if($restaurant !=null ) echo $restaurant->getNumberOfSeats();
        ?>"
        >
        <label for="restaurantNumberOfSeats">Restaurant Number Of Seats</label>
      </div>

      <div class="form-floating mb-3">
        <input type="number"
        class="form-control"
        name="restaurantScore"
        id="restaurantScore"
        placeholder="Restaurant Number Of Seats"
        value="<?php 
        // if we are showing a page for editing, fill the page tile field.
        if($restaurant !=null ) echo $restaurant->getScore();
        ?>"
        >
        <label for="restaurantScore">Restaurant Score (1 to 5)</label>
      </div>

      <div class="form-floating mb-3">
        <input type="text"
        class="form-control"
        name="restaurantFoodTypes"
        id="restaurantFoodTypes"
        placeholder="Food Types. Separate by comma"
        value="<?php 
        // if we are showing a page for editing, fill the page tile field.
        if($restaurant !=null ) echo $restaurant->getFoodTypes();
        ?>"
        >
        <label for="restaurantNumberOfSeats">Food Types. Separate by comma.</label>
      </div>
      <div class="form-floating mb-3 restaurantCardImage-upload">
        <input type='file' id="cardRestaurantPicUpload" name="cardRestaurantPicUpload" accept=".png, .jpg, .jpeg"/>
          <label for="cardRestaurantPicUpload"><i class="fas fa-edit"></i></label>
      </div>

      <div class="form-floating mb-3 restaurantCardImageDivShow">
        <img 
        class="img-fluid h-200 restaurantCardImageImgShow"
        alt="Cover Image"
        src="<?= $restaurant->getRestaurantCardImageFullPath() ?>">
      </div>

      <div class="form-floating mb-3">
        <textarea id="mytextarea" name="tinyMCEform">
          <?php 
          // if the user is creating a new page, show the following message
          if($restaurant == null) echo "Restaurant Description (HTML)";
          else {
            // if the user is editing an existing page, show the content of that page.
            echo $restaurant->getDescriptionHTML();
          }
          ?>
        </textarea>
      </div>
      
      <div class="form-floating mb-3">
        <button class="btn mb-2" name="formSubmit" type="submit">
        <?php 
        // if we are creating a new page, use Submit as the button text. But if we are updating an existing page, use Update as the button text.
        if($restaurant == null) echo "Submit";
        else echo "Update";
        ?>
        </button>
      </div>
      <?php 
      if($restaurant != null) {
        // If we are updating an existing page, add a detele Button too.
      ?>
      <div class="form-floating mb-3">
        <button class="btn mb-2" name="formDelete" type="submit">
        Delete      
        </button>
      </div>
      <?php 
            }
      ?>
    </form>
    </div>
    </div>
    </div>
    </div>
  </body>
</html>
