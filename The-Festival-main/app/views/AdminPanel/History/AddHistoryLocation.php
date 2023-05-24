<!--<section class="home-section">-->
<!--    <div class="container pb-3 pt-3">-->
<!--        <div class="row">-->
<!--            <div class="col-md-12">-->
<!--                <h1>--><?//= $title ?><!--</h1>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--    <div class="container">-->
<!--        <div class="panel panel-primary">-->
<!--            <div class="panel-heading">-->
<!--                <h2 class="panel-title">Add Tour Address</h2>-->
<!--            </div>-->
<!--            <div class="panel-body">-->
<!--                <form method="POST">-->
<!--                    <input id="autocomplete" placeholder="Enter Tour address" onFocus="geolocate()" type="text"-->
<!--                           class="form-control">-->
<!--                    <div id="address">-->
<!--                        <div class="row">-->
<!--                            <div class="col-md-6">-->
<!--                                <label class="control-label">Street Number</label>-->
<!--                                <input class="form-control" id="street_number" name="tourStreetNumber" disabled="true">-->
<!--                            </div>-->
<!--                            <div class="col-md-6">-->
<!--                                <label class="control-label">Street Name</label>-->
<!--                                <input class="form-control" id="route" name="tourStreetName" disabled="true">-->
<!--                            </div>-->
<!--                        </div>-->
<!--                        <div class="row">-->
<!--                            <div class="col-md-6">-->
<!--                                <label class="control-label">City</label>-->
<!--                                <input class="form-control field" id="locality" name="tourCity"disabled="true">-->
<!--                            </div>-->
<!--                            <div class="col-md-6">-->
<!--                                <label class="control-label">State</label>-->
<!--                                <input class="form-control" id="administrative_area_level_1" disabled="true">-->
<!--                            </div>-->
<!--                        </div>-->
<!--                        <div class="row">-->
<!--                            <div class="col-md-6">-->
<!--                                <label class="control-label">Post Code</label>-->
<!--                                <input class="form-control" id="postal_code" name="tourPostCode"disabled="true">-->
<!--                            </div>-->
<!--                            <div class="col-md-6">-->
<!--                                <label class="control-label">Country</label>-->
<!--                                <input class="form-control" id="country" name="tourCountry" disabled="true">-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <button type="submit" class="btn btn-primary mt-3" name="addNewTourLocation">Submit</button>-->
<!--                </form>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</section>-->
<!---->
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>-->
<!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"-->
<!--        integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS"-->
<!--        crossorigin="anonymous"></script>-->
<!--<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet"-->
<!--      integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">-->
<!--<script src="auto-complete.js"></script>-->
<!---->
<!--<script async defer-->
<!--        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD5yDEZxPchpFrGUOTavwzG92Nh6CrvZx0&libraries=places&callback=initAutocomplete">-->
<!---->
<!--</script>-->
<!--<script>-->
<!--    var placeSearch, autocomplete;-->
<!--    var componentForm = {-->
<!--        street_number: 'short_name',-->
<!--        route: 'long_name',-->
<!--        locality: 'long_name',-->
<!--        administrative_area_level_1: 'short_name',-->
<!--        country: 'long_name',-->
<!--        postal_code: 'short_name'-->
<!--    };-->
<!---->
<!--    function initAutocomplete() {-->
<!--        // Create the autocomplete object, restricting the search to Netherlands-->
<!--        autocomplete = new google.maps.places.Autocomplete(-->
<!--            document.getElementById('autocomplete'), {-->
<!--                types: ['geocode'],-->
<!--                componentRestrictions: {country: 'NL'}-->
<!--            });-->
<!---->
<!--        // When the user selects an address from the dropdown, populate the address-->
<!--        // fields in the form.-->
<!--        autocomplete.addListener('place_changed', fillInAddress);-->
<!--    }-->
<!---->
<!--    function fillInAddress() {-->
<!--// Get the place details from the autocomplete object.-->
<!--        var place = autocomplete.getPlace();-->
<!--0-->
<!--        for (var component in componentForm) {-->
<!--            document.getElementById(component).value = '';-->
<!--            document.getElementById(component).disabled = false;-->
<!--        }-->
<!---->
<!--// Get each component of the address from the place details-->
<!--// and fill the corresponding field on the form.-->
<!--        for (var i = 0; i < place.address_components.length; i++) {-->
<!--            var addressType = place.address_components[i].types[0];-->
<!--            if (componentForm[addressType]) {-->
<!--                var val = place.address_components[i][componentForm[addressType]];-->
<!--                document.getElementById(addressType).value = val;-->
<!--            }-->
<!--        }-->
<!--    }-->
<!---->
<!--    // Bias the autocomplete object to the user's geographical location,-->
<!--    // as supplied by the browser's 'navigator.geolocation' object.-->
<!--    function geolocate() {-->
<!--        if (navigator.geolocation) {-->
<!--            navigator.geolocation.getCurrentPosition(function (position) {-->
<!--                var geolocation = {-->
<!--                    lat: position.coords.latitude,-->
<!--                    lng: position.coords.longitude-->
<!--                };-->
<!--                var circle = new google.maps.Circle({-->
<!--                    center: geolocation,-->
<!--                    radius: position.coords.accuracy-->
<!--                });-->
<!--                autocomplete.setBounds(circle.getBounds());-->
<!--            });-->
<!--        }-->
<!--    }-->
<!--</script>-->

<section class="home-section">
    <div class="container pb-3 pt-3">
        <div class="row">
            <div class="col-md-12">
                <h1><?= $title ?></h1>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h2 class="panel-title">Add Tour Address</h2>
            </div>
            <div class="panel-body">
                <form method="POST" enctype="multipart/form-data" >
                    <input id="autocomplete" placeholder="Enter Tour address" onFocus="geolocate()" type="text"
                           class="form-control">
                    <div id="address">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="control-label">Street Number</label>
                                <input class="form-control" id="street_number" name="tourStreetNumber" disabled="true">
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">Street Name</label>
                                <input class="form-control" id="route" name="tourStreetName" disabled="true">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="control-label">City</label>
                                <input class="form-control field" id="locality" name="tourCity"disabled="true">
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">State</label>
                                <input class="form-control" id="administrative_area_level_1" disabled="true">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="control-label">Post Code</label>
                                <input class="form-control" id="postal_code" name="tourPostCode"disabled="true">
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">Country</label>
                                <input class="form-control" id="country" name="tourCountry" disabled="true">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="tourLocationName">Tour Location Name</label>
                            <textarea name="tourLocationName" id="tourLocationName" class="form-control"
                                      rows="5"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="locationInformation">Location Information</label>
                            <textarea name="locationInformation" id="locationInformation" class="form-control"
                                      rows="5"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="tourDescription">Tour Description 1</label>
                            <textarea name="tourDescription1" id="tourDescription1" class="form-control"
                                      rows="5"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="tourDescription">Tour Description 2</label>
                            <textarea name="tourDescription2" id="tourDescription2" class="form-control"
                                      rows="5"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="banner">Other</label><br>
                            <input type="file" name="banner" accept=".png, .jpg, .jpeg">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="other">Others <span style="font-size: 12px;">(At Least 4 files)</span></label><br>
                            <input type="file" name="others[]" accept=".png, .jpg, .jpeg" multiple>
                        </div>
                    </div>
                    <?php if (isset($errorMessage['Submit'])): ?>
                        <div class="alert alert-danger">
                           <?= $errorMessage['Submit']?>
                        </div>
                    <?php endif; ?>
                    <button type="submit" class="btn btn-primary mt-3" name="addNewTourLocation">Submit</button>
                </form>
            </div>
        </div>
    </div>
</section>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"
        integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS"
        crossorigin="anonymous"></script>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet"
      integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<script src="auto-complete.js"></script>

<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD5yDEZxPchpFrGUOTavwzG92Nh6CrvZx0&libraries=places&callback=initAutocomplete">

</script>
<script>
    var placeSearch, autocomplete;
    var componentForm = {
        street_number: 'short_name',
        route: 'long_name',
        locality: 'long_name',
        administrative_area_level_1: 'short_name',
        country: 'long_name',
        postal_code: 'short_name'
    };

    function initAutocomplete() {
        // Create the autocomplete object, restricting the search to Netherlands
        autocomplete = new google.maps.places.Autocomplete(
            document.getElementById('autocomplete'), {
                types: ['geocode'],
                componentRestrictions: {country: 'NL'}
            });

        // When the user selects an address from the dropdown, populate the address
        // fields in the form.
        autocomplete.addListener('place_changed', fillInAddress);
    }

    function fillInAddress() {
// Get the place details from the autocomplete object.
        var place = autocomplete.getPlace();
0
        for (var component in componentForm) {
            document.getElementById(component).value = '';
            document.getElementById(component).disabled = false;
        }

// Get each component of the address from the place details
// and fill the corresponding field on the form.
        for (var i = 0; i < place.address_components.length; i++) {
            var addressType = place.address_components[i].types[0];
            if (componentForm[addressType]) {
                var val = place.address_components[i][componentForm[addressType]];
                document.getElementById(addressType).value = val;
            }
        }
    }

    // Bias the autocomplete object to the user's geographical location,
    // as supplied by the browser's 'navigator.geolocation' object.
    function geolocate() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                var geolocation = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                var circle = new google.maps.Circle({
                    center: geolocation,
                    radius: position.coords.accuracy
                });
                autocomplete.setBounds(circle.getBounds());
            });
        }
    }
</script>


<!--<section class="home-section">-->
<!--    <div class="container pb-3 pt-3">-->
<!--        <div class="row">-->
<!--            <div class="col-md-12">-->
<!--                <h1> --><?//= $title ?><!--</h1>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--    <div class="container">-->
<!--        <div class="row">-->
<!--            <div class="col-md-6 col-lg-6 col-xl-5">-->
<!--                <form id="AddPerformance" class="mx-1 mx-md-4" method="POST" enctype="multipart/form-data">-->
<!--                    <input id="autocomplete" placeholder="Enter Tour address" onFocus="geolocate()" type="text"-->
<!--                           class="form-control">-->
<!--                    <div id="address">-->
<!--                        <div class="row">-->
<!--                            <div class="col-md-6">-->
<!--                                <label class="control-label">Street Number</label>-->
<!--                                <input class="form-control" id="street_number" name="tourStreetNumber" disabled="true">-->
<!--                            </div>-->
<!--                            <div class="col-md-6">-->
<!--                                <label class="control-label">Street Name</label>-->
<!--                                <input class="form-control" id="route" name="tourStreetName" disabled="true">-->
<!--                            </div>-->
<!--                        </div>-->
<!--                        <div class="row">-->
<!--                            <div class="col-md-6">-->
<!--                                <label class="control-label">City</label>-->
<!--                                <input class="form-control field" id="locality" name="tourCity"disabled="true">-->
<!--                            </div>-->
<!--                            <div class="col-md-6">-->
<!--                                <label class="control-label">State</label>-->
<!--                                <input class="form-control" id="administrative_area_level_1" disabled="true">-->
<!--                            </div>-->
<!--                        </div>-->
<!--                        <div class="row">-->
<!--                            <div class="col-md-6">-->
<!--                                <label class="control-label">Post Code</label>-->
<!--                                <input class="form-control" id="postal_code" name="tourPostCode"disabled="true">-->
<!--                            </div>-->
<!--                            <div class="col-md-6">-->
<!--                                <label class="control-label">Country</label>-->
<!--                                <input class="form-control" id="country" name="tourCountry" disabled="true">-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="mb-3">-->
<!--                        <label class="form-label" for="ArtistName">Name</label>-->
<!--                        <input type="text" name="artistName" id="artistName" class="form-control"/>-->
<!--                    </div>-->
<!--                    <div class="mb-3">-->
<!--                        <label class="form-label" for="artistDescription">Description</label>-->
<!--                        <textarea name="artistDescription" id="artistDescription" class="form-control"-->
<!--                                  rows="5"></textarea>-->
<!--                    </div>-->
<!--                    <div class="mb-3">-->
<!--                        <div class="logo-container">-->
<!--                            <label class="form-label" for="artistLogo">Logo</label><br>-->
<!--                            <input type="file" name="artistLogo" alt="Artist Logo" accept=".png, .jpg, .jpeg">-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="mb-3">-->
<!--                        <label for="artistStyles" class="form-label">Artist Styles</label>-->
<!--                        --><?php //if (!empty($errorMessage['styles'])): ?>
<!--                            <div class="alert alert-info" role="alert">-->
<!--                                --><?//= $errorMessage['styles'] ?>
<!--                            </div>-->
<!--                        --><?php //else: ?>
<!--                        --><?php //endif; ?>
<!--                    </div>-->
<!--                    <div class="mb-3">-->
<!--                        <label class="form-label" for="portrait">Portrait</label><br>-->
<!--                        <input type="file" name="portrait" alt="Artist Portrait" accept=".png, .jpg, .jpeg">-->
<!--                    </div>-->
<!--                    <div class="mb-3">-->
<!--                        <label class="form-label" for="banner">Banner</label><br>-->
<!--                        <input type="file" name="banner" accept=".png, .jpg, .jpeg">-->
<!--                    </div>-->
<!--                    <div class="mb-3">-->
<!--                        <label class="form-label" for="portrait">Others <span style="font-size: 12px;">(At Least  3 files)</span></label><br>-->
<!--                        <input type="file" name="others[]" accept=".png, .jpg, .jpeg" multiple>-->
<!--                    </div>-->
<!--                    --><?php //if (!empty($errorMessage['Submit'])): ?>
<!--                        <div class="alert alert-danger" role="alert">-->
<!--                            --><?//= $errorMessage['Submit'] ?>
<!--                        </div>-->
<!--                    --><?php //endif; ?>
<!--                    <div class="row justify-content-center">-->
<!--                        <div class="pb-3">-->
<!--                            <button type="submit" name="AddArtist" class="btn btn-primary w-100">Add Artist</button>-->
<!--                        </div>-->
<!--                        <div class="pb-2">-->
<!--                            <button type="reset" class="btn btn-secondary w-100"-->
<!--                                    onclick="history.back()">-->
<!--                                Cancel-->
<!--                            </button>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </form>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</section>-->
<!--<script>-->
<!--    var placeSearch, autocomplete;-->
<!--    var componentForm = {-->
<!--        street_number: 'short_name',-->
<!--        route: 'long_name',-->
<!--        locality: 'long_name',-->
<!--        administrative_area_level_1: 'short_name',-->
<!--        country: 'long_name',-->
<!--        postal_code: 'short_name'-->
<!--    };-->
<!---->
<!--    function initAutocomplete() {-->
<!--        // Create the autocomplete object, restricting the search to Netherlands-->
<!--        autocomplete = new google.maps.places.Autocomplete(-->
<!--            document.getElementById('autocomplete'), {-->
<!--                types: ['geocode'],-->
<!--                componentRestrictions: {country: 'NL'}-->
<!--            });-->
<!---->
<!--        // When the user selects an address from the dropdown, populate the address-->
<!--        // fields in the form.-->
<!--        autocomplete.addListener('place_changed', fillInAddress);-->
<!--    }-->
<!---->
<!--    function fillInAddress() {-->
<!--// Get the place details from the autocomplete object.-->
<!--        var place = autocomplete.getPlace();-->
<!--        0-->
<!--        for (var component in componentForm) {-->
<!--            document.getElementById(component).value = '';-->
<!--            document.getElementById(component).disabled = false;-->
<!--        }-->
<!---->
<!--// Get each component of the address from the place details-->
<!--// and fill the corresponding field on the form.-->
<!--        for (var i = 0; i < place.address_components.length; i++) {-->
<!--            var addressType = place.address_components[i].types[0];-->
<!--            if (componentForm[addressType]) {-->
<!--                var val = place.address_components[i][componentForm[addressType]];-->
<!--                document.getElementById(addressType).value = val;-->
<!--            }-->
<!--        }-->
<!--    }-->
<!---->
<!--    // Bias the autocomplete object to the user's geographical location,-->
<!--    // as supplied by the browser's 'navigator.geolocation' object.-->
<!--    function geolocate() {-->
<!--        if (navigator.geolocation) {-->
<!--            navigator.geolocation.getCurrentPosition(function (position) {-->
<!--                var geolocation = {-->
<!--                    lat: position.coords.latitude,-->
<!--                    lng: position.coords.longitude-->
<!--                };-->
<!--                var circle = new google.maps.Circle({-->
<!--                    center: geolocation,-->
<!--                    radius: position.coords.accuracy-->
<!--                });-->
<!--                autocomplete.setBounds(circle.getBounds());-->
<!--            });-->
<!--        }-->
<!--    }-->
<!---->
<!--    function previewImage(event, previewId) {-->
<!--        const file = event.target.files[0];-->
<!--        const reader = new FileReader();-->
<!---->
<!--        reader.onload = function (event) {-->
<!--            const image = event.target.result;-->
<!--            document.getElementById(previewId).setAttribute('src', image);-->
<!--            document.querySelector(`#${previewId} + .empty-image-preview`).style.display = 'none';-->
<!--        };-->
<!---->
<!--        reader.readAsDataURL(file);-->
<!--    }-->
<!--</script>-->

