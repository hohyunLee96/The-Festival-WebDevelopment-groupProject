<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=false"></script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD5yDEZxPchpFrGUOTavwzG92Nh6CrvZx0"></script>
<div class="historyMainImage" style="background: url('<?= $this->getImageFullPath($historyTourLocationObject->getTourImage()['banner'][0]) ?>')">
    <span class="position-absolute bottom-0 start-0 mb-3 ps-3 d-flex align-items-center"
          style="z-index: 99; padding-left: 20px;">
        </span>
</div>
<body onload="locate('<?php echo $location . ' ' . $locationPostCode; ?>')">
    <h1 class="detailHeader"><?= $historyTourLocationObject->getLocationName() ?></h1><br>
    <div class="detailMainContainer">
    <h2> About</h2>
    <div class="detailContainer">
            <div class="detailParagraph">
                <p><?= $historyTourLocationObject->getHistoryP1()?></p><br>
            </div>

        <div class="detailPicture">
            <img src="<?= $this->getImageFullPath($historyTourLocationObject->getTourImage()['other'][0]) ?>"
                 class="border hover-zoom" style="border-radius:20%;height: 300px;width:300px">
        </div>
    </div>
    <div class="detailContainer">
        <div class="detailPicture">
            <img src="<?= $this->getImageFullPath($historyTourLocationObject->getTourImage()['other'][1]) ?>"
                 class="border hover-zoom" style="border-radius:20%;height: 300px;width:300px">
        </div>
            <p class="detailParagraph"><?= $historyTourLocationObject->getHistoryP2() ?></p><br>
    </div>
</div>
<div class="detailMainContainer2">
    <h2> Location
    </h2>
    <div class="detailContainer2">
        <div class="detailMap" id="map_canvas"></div>
        <div class="detailPicture">
            <img src="<?= $this->getImageFullPath($historyTourLocationObject->getTourImage()['other'][2]) ?>"
                 class="border hover-zoom" style="border-radius:20%;height: 355px;width:350px">
        </div>
    </div>
</div>
</body>
<script type="text/javascript">
    function locate(address) {
        let geocoder = new google.maps.Geocoder();
        geocoder.geocode({address: address}, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                let lat = results[0].geometry.location.lat();
                let lng = results[0].geometry.location.lng();
                initMap(lat, lng);
            } else {
                alert("Geocode was not successful for the following reason: " + status);
            }
        });
    }

    function initMap(lat, lng) {
        var center = new google.maps.LatLng(lat, lng);
        var mapOptions = {
            zoom: 16,
            center: center,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }
        var map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
        var marker = new google.maps.Marker({
            map: map,
            position: center
        });
    }
</script>

