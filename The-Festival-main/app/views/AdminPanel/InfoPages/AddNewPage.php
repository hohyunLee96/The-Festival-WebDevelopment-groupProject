<section class="home-section">
    <script src="https://cdn.tiny.cloud/1/8q7yq04ylnzkpg8qr9ypmn6p2cmtaq2l0qe3spmlawy8b4qn/tinymce/6/tinymce.min.js"
            referrerpolicy="origin"></script>
    <div class="container pb-3 pt-3">
        <div class="row">
            <div class="col-md-12">
                <h1> <?= $title ?></h1>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <form method="POST">
                <div class="container pb-5">
                    <div class="mb-3">
                        <label for="pageName">Title<span
                                    style="font-size: 11px">(this will be in Nab Bar Heading too)</span></label>
                        <input type="text" id="pageName" name="title" class="form-control">
                    </div>
                    <div class="mb-3">
            <textarea rows="35" id="pageEditor" name="PageContent">
            </textarea>
                    </div>
                    <?php if (!empty($errorMessage['Submit'])): ?>
                        <div class="alert alert-danger" role="alert">
                            <?= $errorMessage['Submit'] ?>
                        </div>
                    <?php endif; ?>
                    <div class="mb-3">
                        <button type="reset" class="btn btn-secondary btn-lg">Cancel</button>
                        <button type="submit" class="flot-start btn btn-primary  btn-lg" name="AddNewPageSubmitted"> Add
                            Page
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script type="text/javascript" src="/Javascripts/InfoEditor.js">
    </script>
</section>
