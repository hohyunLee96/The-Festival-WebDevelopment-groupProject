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
    <?php
    if($page == null) {
      echo "<h1>Create a new page with TinyCME editor</h1>";
    }
    else {
      echo "<h1>Edit an existing page with TinyCME editor</h1>";
    }

    ?>

    <form action="/page/editorSubmitted" method="POST">
      <div class="form-floating mb-3">
        <textarea id="mytextarea" name="tinyMCEform">
          <?php
          // if the user is creating a new page, show the following message
          if($page == null) echo "Create your new page.";
          else {
            // if the user is editing an existing page, show the content of that page.
            echo $page->getBodyContentHTML();
          }
          ?>
        </textarea>
      </div>
      <div class="form-floating mb-3">
        <label for="floatingInput">Page Title</label>
        <input type="text"
        class="form-control"
        name="pageTitle"
        id="pageTitle"
        placeholder="Page Title"
        <?php 
        // if we are showing a page for editing, fill the page tile field.
        if($page !=null) echo "value=".$page->getTitle();
        ?>
        >
      </div>
      <?php
      // if we are updating an existing page, we create a hidden input and give its value te pageId. So, when the form is submitted to the server, we know which pageId we are updating or deleting.
      if($page != null) {
        $pageId = $page->getId();
      ?>
        <div class="form-floating mb-3">
        <input type="hidden" class="form-control" name="pageID" id="pageID" value= <?php echo $pageId; ?> >
        </div>
      <?php
      }
      ?>
      <div class="form-floating mb-3">
        <button class="btn mb-2" name="formSubmit" type="submit">
        <?php 
        // if we are creating a new page, use Submit as the button text. But if we are updating an existing page, use Update as the button text.
        if($page == null) echo "Submit";
        else echo "Update";
        ?>
        </button>
      </div>
      <?php 
      if($page != null) {
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
