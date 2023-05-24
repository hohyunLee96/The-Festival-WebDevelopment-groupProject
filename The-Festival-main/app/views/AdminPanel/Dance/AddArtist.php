<section class="home-section">
    <div class="container pb-3 pt-3">
        <div class="row">
            <div class="col-md-12">
                <h1> <?= $title ?></h1>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-lg-6 col-xl-5">
                <form id="AddPerformance" class="mx-1 mx-md-4" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label" for="ArtistName">Name</label>
                        <input type="text" name="artistName" id="artistName" class="form-control"/>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="artistDescription">Description</label>
                        <textarea name="artistDescription" id="artistDescription" class="form-control"
                                  rows="5"></textarea>
                    </div>
                    <div class="mb-3">
                        <div class="logo-container">
                            <label class="form-label" for="artistLogo">Logo</label><br>
                            <input type="file" name="artistLogo" alt="Artist Logo" accept=".png, .jpg, .jpeg">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="artistStyles" class="form-label">Artist Styles</label>
                        <?php if (!empty($errorMessage['styles'])): ?>
                            <div class="alert alert-info" role="alert">
                                <?= $errorMessage['styles'] ?>
                            </div>
                        <?php else: ?>
                            <?php foreach ($styles as $style): ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="artistStyles[]"
                                           id="style-<?= $style->getStyleId() ?>" value="<?= $style->getStyleId() ?>">
                                    <label class="form-check-label" for="style-<?= $style->getStyleId() ?>">
                                        <?= $style->getStyleName() ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="portrait">Portrait</label><br>
                        <input type="file" name="portrait" alt="Artist Portrait" accept=".png, .jpg, .jpeg">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="banner">Banner</label><br>
                        <input type="file" name="banner" accept=".png, .jpg, .jpeg">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="portrait">Others <span style="font-size: 12px;">(At Least  3 files)</span></label><br>
                        <input type="file" name="others[]" accept=".png, .jpg, .jpeg" multiple>
                    </div>
                    <?php if (!empty($errorMessage['Submit'])): ?>
                        <div class="alert alert-danger" role="alert">
                            <?= $errorMessage['Submit'] ?>
                        </div>
                    <?php endif; ?>
                    <div class="row justify-content-center">
                        <div class="pb-3">
                            <button type="submit" name="AddArtist" class="btn btn-primary w-100">Add Artist</button>
                        </div>
                        <div class="pb-2">
                            <button type="reset" class="btn btn-secondary w-100"
                                    onclick="history.back()">
                                Cancel
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<script>
    function previewImage(event, previewId) {
        const file = event.target.files[0];
        const reader = new FileReader();

        reader.onload = function (event) {
            const image = event.target.result;
            document.getElementById(previewId).setAttribute('src', image);
            document.querySelector(`#${previewId} + .empty-image-preview`).style.display = 'none';
        };

        reader.readAsDataURL(file);
    }
</script>
