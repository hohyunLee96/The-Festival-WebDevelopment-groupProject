
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Festival</title>
  <script src="/Javascripts/tinymce/js/tinymce/tinymce.min.js" referrerpolicy="origin"></script>

</head>

<body>
  <main>
    <span id="userId" style="display:none"><?php echo $currentUserId; ?></span>
    <span id="pageId" style="display:none"><?php echo $pageId; ?></span>


    <?php if (isset($model) && $model != null)  echo $model;?>

    <script src="/Javascripts/HomePage.js"></script>

  </main>
</body>

</html>
