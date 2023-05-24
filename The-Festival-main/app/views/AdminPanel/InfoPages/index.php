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
        <div class="row my-3">
            <div class="col-md-6">
                <?php if (!empty($errorMessage['NoPagesFound'])): ?>
                    <div class="alert alert-error" role="alert">
                        <?= $errorMessage['NoPagesFound'] ?>
                    </div>
                <?php else: ?>
                    <form method="POST">
                        <div class="form-group">
                            <label for="pageSelect">Select a Page to Load:</label>
                            <select class="form-control" id="pageSelect" name="pageSelect">
                                <?php foreach ($infoPages as $infoPage): ?>
                                    <option value="<?= $infoPage->getInfoPageId() ?>"><?= $infoPage->getNavBarItem()->getNavName() ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" class="btn btn-primary mt-3" name="btnPageLoad">Load</button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
            <div class="col-md-6 d-flex justify-content-end align-items-center">
                <button class="btn btn-success" onclick="location.href='/admin/infopages/addNewPage'">Add New Page
                </button>
            </div>
        </div>
    </div>
    <?php if (!empty($selectedInfoPage)) : ?>

        <div class="container pb-5">
            <div class="mb-3">
                <label for="pageName">Page Title/ Nav Bar Title</label>
                <input type="text" id="pageTitle" name="pageName" class="form-control"
                       value="<?= $selectedInfoPage->getNavBarItem()->getNavName() ?>">
            </div>
            <div class="mb-3">
            <textarea rows="35" id="pageEditor">
                <?php echo $selectedInfoPage->getContent() ?>
            </textarea>
            </div>
            <div class="alert alert-danger" role="alert" id="errors" hidden>
            </div>

            <div class="d-flex justify-content-between mb-3">
                <form method="POST">
                    <button type="submit" class="btn btn-lg btn-danger" name="btnDelete"
                            value="<?= $selectedInfoPage->getInfoPageId() ?>">Delete This Page
                    </button>
                    <button type="reset" class="btn btn-secondary btn-lg">Cancel</button>
                </form>
                <div>
                    <button type="button" class="flot-start btn btn-primary btn-lg"
                            onclick="btnSaveChangesClicked('<?= $selectedInfoPage->getInfoPageId() ?>')">Save changes
                    </button>
                </div>
            </div>

        </div>
    <?php endif; ?>
    <script type="text/javascript" src="/Javascripts/InfoEditor.js">
    </script>
</section>

