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
                    <label class="form-label" for="tourLocation">Name</label>
                    <input type="text" name="tourLocation" id="tourLocation" class="form-control"
                           value="<?= $tourLocation->getLocationName() ?>"/>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="tourH1">Description</label>
                    <textarea name="tourH1" id="tourH1" class="form-control"
                              rows="8"><?= $tourLocation->getHistoryP1() ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="tourH2">Description</label>
                    <textarea name="tourH1" id="tourH1" class="form-control"
                              rows="8"><?= $tourLocation->getHistoryP2() ?></textarea>
                </div>
                <div class="mb-3">
                    <div class="logo-container">
                        <label class="form-label" for="tourBanner">Banner</label><br>
                        <input type="file" id="artistLogo" alt="<?= $tourLocation->getLocationName() ?> Logo"
                               accept=".png, .jpg, .jpeg"
                               title="change Picture">
                        <?php if (!empty($tourLocation->getArtistLogo())): ?>
                            <img src="<?= $this->getImageFullPath($artist->getArtistLogo()) ?>" alt="Artist Logo"
                                 style="width: 100px; height: 100px;">
                        <?php else: ?>
                            <div class="alert alert-info" role="alert">
                                There is no Logo for <?= $tourLocation->getLocationName() ?> ,please upload Logo which will
                                be viewed in the artist page.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="artistPortrait">Portrait</label><br>
                                <input type="file" id="artistPortrait" alt="Artist Portrait" accept=".png, .jpg, .jpeg"
                                       class="pb-3" >
                                <div class="image-container">
                                    <?php if (!empty($tourLocation->getArtistImages()['other'][0])): ?>
                                        <img src="<?= $this->getImageFullPath($tourLocation->getArtistImages()['other'][0]) ?>"
                                             alt="Artist Portrait"  style="width: 200px; height: 200px;">
                                    <?php else: ?>
                                        <div class="alert alert-info" role="alert">
                                            There is no Portrait for <?= $tourLocation->getLocationName() ?>. Please upload one image which will be viewed in the artist page.
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="locationBanner">Banner</label><br>
                                <input type="file" id="locationBanner" accept=".png, .jpg, .jpeg" class="pb-3">
                                <?php if (!empty($tourLocation->getArtistImages()['Banner'][0])): ?>
                                    <img src="<?= $this->getImageFullPath($tourLocation->getArtistImages()['Banner'][0]) ?>"
                                         alt="Artist Banner" style="width: 200px; height: 200px;">
                                <?php else: ?>
                                    <div class="alert alert-info" role="alert">
                                        There is no Banner for <?= $tourLocation->getLocationName() ?>. Please upload one which
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
                    <?php if (!empty($tourLocation->getArtistImages()['Other'])): ?>
                        <?php foreach ($tourLocation->getArtistImages()['Other'] as $image): ?>
                            <img src="<?= $this->getImageFullPath($image) ?>" alt="Artist Gallery"
                                 style="width: 200px; height: 200px;" class="pb-3">
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="alert alert-info" role="alert">
                            There is no Others image for <?= $tourLocation->getLocationName() ?>. Please upload three images
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
                                onclick="location.href='/admin/History/TourLocation'">
                            Cancel
                        </button>
                        <button type="button"
                                class="btn btn-primary ms-3"
                                onclick="btnSaveChangesClicked('<?= $tourLocation->getHistoryTourLocationId() ?>')">
                            Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    function btnSaveChangesClicked(artistId) {
        let tourLocation = document.getElementById("tourLocation").value;
        let tourH1 = document.getElementById("tourH1").value;
        let tourH2 = document.getElementById("tourH2").value;

        //all Images
        let locationBanner = document.getElementById("locationBanner").files[0];
        let OtherLocationImages = document.getElementById("OtherArtistImages").files;
        if (artistName === "" || artistDescription === "" || selectedArtistStyles.length === 0) {
            displayError("Please fill in all fields");
            return;
        }

        let historyTour = {
            tourDetails: {
                id: $historyTourLocationId,
                name: $locationName,
                p1: $historyP1,
                p2: $historyP2,
            },
            locationBanner: locationBanner,
            OtherLocationImages: OtherLocationImages

        }
        if(historyTour.OtherLocationImages.length !== 0){
            if(!checkArtistOtherImagesMinimum(historyTour.OtherLocationImages)){
                displayError("Please upload at least 3 other images for artist");
                return;
            }
        }
        sendUpdateRequest(historyTour);
    }


    function displayError(error) {
        let errorDiv = document.getElementById("errors");
        errorDiv.innerHTML = error;
        errorDiv.hidden = false;
    }

    function sendUpdateRequest(historyTour) {
        let formData = getFormData(historyTour);
        fetch('http://localhost/api/danceApi/artists?artistId=' + historyTour.id, {
            method: 'POST',
            body: formData
        }).then(response => {
            response.json()
                .then(data => {
                    if (data.success) {
                        location.href = "/admin/dance/artists";
                    } else {
                        displayError(data.message)
                    }
                });
        }).catch(error => {
            console.log(error)
        });
    }

    function getFormData(tourLocation) {
        const formData = new FormData();
        formData.append('locationP1', JSON.stringify(tourLocation.locationP1));
        formData.append('locationP2', JSON.stringify(tourLocation.locationP2));
        formData.append('locationBanner', tourLocation.locationBanner);
        if (tourLocation.OtherLocationImages.length !== 0) {
            for (let i = 0; i < tourLocation.OtherLocationImages.length; i++) {
                formData.append('OtherLocationImages[' + i + ']', tourLocation.OtherLocationImages[i]);
            }
        } else {
            formData.append('OtherArtistImages', '');
        }
        return formData;
    }

    function checkArtistOtherImagesMinimum(locationOtherImages) {
        if ( locationOtherImages.length < 3) {
            return false;
        }
        return true;
    }



</script>