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
                <div class="col-md-6">
                    <label for="input-venueName" class="form-label">Venue Name<label>
                            <input type="text" class="form-control" id="input-Venue Name"
                                   name="locationName" value="<?= $venue->getLocationName() ?>">
                </div>
                <div class="card">
                    <div class="card-header bg-transparent border-0">
                        <h4>Address</h4>
                    </div>
                    <div class="container-fluid">
                        <div class="row g-2">
                            <div class="col-md-6 col-lg-4 pr-3">
                                <label for="input-houseNumber" class="form-label">House Number</label>
                                <input type="text" class="form-control" id="input-houseNumber" name="houseNumber"
                                       value="<?= $venue->getAddress()->getHouseNumber() ?>">
                            </div>
                            <div class="col-md-6 col-lg-2 ps-4">
                                <label for="input-additionalHouseNumber" class="form-label">Additional</label>
                                <input type="text" class="form-control" id="input-additionalHouseNumber"
                                       name="houseNumberAdditional"
                                       value="<?= $venue->getAddress()->getHouseNumberAdditional() ?>">
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label for="input-postalCode" class="form-label">Post Code</label>
                                <input type="text" placeholder="1234TX" class="form-control"
                                       pattern="[0-9]{4}[a-zA-Z]{2}"
                                       id="input-postCode" title="Please enter valid post code" name="postCode"
                                       value="<?= $venue->getAddress()->getPostCode() ?>">
                            </div>
                            <div class="col-md-6 ">
                                <label for="input-streetName" class="form-label"> Street Name</label>
                                <input type="text" class="form-control" id="input-streetName" name="streetName"
                                       value="<?= $venue->getAddress()->getStreetName() ?>">
                            </div>

                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <label for="input-city" class="form-label">City</label>
                                <input type="text" class="form-control" id="input-city" name="city"
                                       value="<?= $venue->getAddress()->getCity() ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="input-country" class="form-label">Country</label>
                                <input type="text" class="form-control" id="input-country" name="country"
                                       value="<?= $venue->getAddress()->getCountry() ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pt-3">
                    <div class="alert alert-danger pt-3" role="alert" id="errors" hidden>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="pt-3">
                        <button type="reset" class="btn btn-secondary"
                                onclick="location.href='/admin/dance/venues'">
                            Cancel
                        </button>
                        <button type="button" class="btn btn-primary" id="AddVenue"
                                onclick="btnSaveChangesClicked('<?= $venue->getLocationId() ?>','<?= $venue->getAddress()->getAddressId() ?>')">Save changes
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="/Javascripts/AdminPanel/Dance/EditVenue.js" type="text/javascript"></script>
