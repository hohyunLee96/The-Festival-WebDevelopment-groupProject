<section class="home-section">
    <div class="container pb-3">
        <div class="container pb-3 pt-3">
            <div class="row">
                <div class="col-md-12">
                    <h1> <?= $title ?></h1>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <form id="AddPerformance" class="mx-1 mx-md-4" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6 col-lg-6 col-xl-5">
                    <div class="col-md-6">
                        <label for="input-venueName" class="form-label">Venue Name<label>
                                <input type="text" class="form-control" id="input-Venue Name"
                                       name="locationName">
                    </div>
                    <div class="card">
                        <div class="card-header bg-transparent border-0">
                            <h4>Address</h4>
                        </div>
                        <div class="container-fluid">
                            <div class="row g-2">
                                <div class="col-md-6 col-lg-4 pr-3">
                                    <label for="input-houseNumber" class="form-label">House Number</label>
                                    <input type="text" class="form-control" id="input-houseNumber" name="houseNumber">
                                </div>
                                <div class="col-md-6 col-lg-2 ps-4">
                                    <label for="input-additionalHouseNumber" class="form-label">Additional</label>
                                    <input type="text" class="form-control" id="input-additionalHouseNumber"
                                           name="houseNumberAdditional">
                                </div>
                            </div>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label for="input-postalCode" class="form-label">Post Code</label>
                                    <input type="text"  placeholder="1234TX" class="form-control" pattern="[0-9]{4}[a-zA-Z]{2}"
                                           title="Please enter valid post code" name="postCode">
                                </div>
                                <div class="col-md-6 ">
                                    <label for="input-streetName" class="form-label"> Street Name</label>
                                    <input type="text" class="form-control" id="input-streetName" name="streetName">
                                </div>

                            </div>
                            <div class="row g-2 mb-3">
                                <div class="col-md-6">
                                    <label for="input-city" class="form-label">City</label>
                                    <input type="text" class="form-control" id="input-city" name="city">
                                </div>
                                <div class="col-md-6">
                                    <label for="input-country" class="form-label">Country</label>
                                    <input type="text" class="form-control" id="input-country" name="country">
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if (!empty($errorMessage)): ?>
                        <div class="pt-3">
                            <div class="alert alert-danger pt-3" role="alert">
                                <?= $errorMessage ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="col-md-6">
                        <div class="pt-3">
                            <button type="reset" class="btn btn-secondary"
                                    onclick="location.href='/admin/dance/venues'">
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-primary" name="AddVenue">Add Venue</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

