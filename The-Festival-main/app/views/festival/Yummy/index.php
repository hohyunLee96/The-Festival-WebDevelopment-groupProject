<?php
// session_start()
if(isset($model) && $model != null) {
  $page = $model;
}
else {
  $page = null;
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Festival</title>
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
    
    
    <h1>Restaurants</h1>
    

    </div>
    </div>
    </div>
    </div>
  </body>
</html>
