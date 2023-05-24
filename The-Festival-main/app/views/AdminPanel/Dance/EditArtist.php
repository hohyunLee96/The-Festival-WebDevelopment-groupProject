<section class="home-section">
    <div class="container-fluid pb-3 pt-3 ps-5">
        <div class="row ps-3">
            <div class="col-md-12" class="ps-5">
                <h1><?= $title ?></h1>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-lg-6 col-xl-5">
                <div class="mb-3">
                    <label class="form-label" for="artistName">Name</label>
                    <input type="text" name="artistName" id="artistName" class="form-control"
                           value="<?= $artist->getArtistName() ?>"/>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="artistDescription">Description</label>
                    <textarea name="artistDescription" id="artistDescription" class="form-control"
                              rows="8"><?= $artist->getArtistDescription() ?></textarea>
                </div>
                <div class="mb-3">
                    <div class="logo-container">
                        <label class="form-label" for="artistLogo">Logo</label><br>
                        <input type="file" id="artistLogo" alt="<?= $artist->getArtistName() ?> Logo"
                               accept=".png, .jpg, .jpeg"
                               title="change Picture">
                        <?php if (!empty($artist->getArtistLogo())): ?>
                            <img src="<?= $this->getImageFullPath($artist->getArtistLogo()) ?>" alt="Artist Logo"
                                 style="width: 100px; height: 100px;">
                        <?php else: ?>
                            <div class="alert alert-info" role="alert">
                                There is no Logo for <?= $artist->getArtistName() ?> ,please upload Logo which will
                                be viewed in the artist page.
                            </div>
                        <?php endif; ?>
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
                                <input class="form-check-input artist-style-checkbox" type="checkbox"
                                       value="<?= $style->getStyleId() ?>"
                                    <?php if (in_array($style, $artist->getArtistStyles())): ?>
                                       checked
                                <?php endif; ?>
                                <label class="form-check-label" for="style-<?= $style->getStyleId() ?>">
                                    <?= $style->getStyleName() ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="artistPortrait">Portrait</label><br>
                                <input type="file" id="artistPortrait" alt="Artist Portrait" accept=".png, .jpg, .jpeg"
                                       class="pb-3" >
                                <div class="image-container">
                                    <?php if (!empty($artist->getArtistImages()['Portrait'][0])): ?>
                                        <img src="<?= $this->getImageFullPath($artist->getArtistImages()['Portrait'][0]) ?>"
                                             alt="Artist Portrait"  style="width: 200px; height: 200px;">
                                    <?php else: ?>
                                        <div class="alert alert-info" role="alert">
                                            There is no Portrait for <?= $artist->getArtistName() ?>. Please upload one image which will be viewed in the artist page.
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="artistBanner">Banner</label><br>
                                <input type="file" id="artistBanner" accept=".png, .jpg, .jpeg" class="pb-3">
                                <?php if (!empty($artist->getArtistImages()['Banner'][0])): ?>
                                    <img src="<?= $this->getImageFullPath($artist->getArtistImages()['Banner'][0]) ?>"
                                         alt="Artist Banner" style="width: 200px; height: 200px;">
                                <?php else: ?>
                                    <div class="alert alert-info" role="alert">
                                        There is no Banner for <?= $artist->getArtistName() ?>. Please upload one which
                                        will be viewed in the artist page.
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="OtherArtistImages">Other Images</label><br>
                    <input type="file" id="OtherArtistImages" multiple accept=".png, .jpg, .jpeg" class="pb-3">
                    <?php if (!empty($artist->getArtistImages()['Other'])): ?>
                        <?php foreach ($artist->getArtistImages()['Other'] as $image): ?>
                            <img src="<?= $this->getImageFullPath($image) ?>" alt="Artist Gallery"
                                 style="width: 200px; height: 200px;" class="pb-3">
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="alert alert-info" role="alert">
                            There is no Others image for <?= $artist->getArtistName() ?>. Please upload three images
                            which will
                            be viewed in the artist page.
                        </div>
                    <?php endif; ?>
                </div>
                <div class="alert alert-danger" role="alert" id="errors" hidden>
                </div>
                <div class="col-md-6 pb-5">
                    <div class="pt-3 d-flex justify-content-between">
                        <button type="reset" class="btn btn-secondary me-3"
                                onclick="location.href='/admin/dance/artists'">
                            Cancel
                        </button>
                        <button type="button"
                                class="btn btn-primary ms-3"
                                onclick="btnSaveChangesClicked('<?= $artist->getArtistId() ?>')">
                            Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="/Javascripts/AdminPanel/Dance/EditArtist.js" type="text/javascript"></script>
