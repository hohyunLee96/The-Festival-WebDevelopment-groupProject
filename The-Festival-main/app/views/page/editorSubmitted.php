<?php
// session_start();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Festival</title>
  </head>

  <body>
    <?php
        if(isset($model) && $model != null) echo $model;
    ?>
  </body>
</html>
