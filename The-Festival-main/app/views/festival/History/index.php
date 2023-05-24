<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<div class="historyMainImage"
     style="background: url('<?= $this->getImageFullPath($historyEvent->getEventImages()['banner'][0]) ?>')">
    <span class="position-absolute bottom-0 start-0 mb-3 ps-3 d-flex align-items-center"
          style="z-index: 99; padding-left: 20px;">
          <h2 class="text-white fw-bold mb-0 ms-3"> <?= $historyEvent->getEventName() ?></h2>
        </span>
</div>
<h1 class="historyHeader"><?= $historyEvent->getEventName() ?></h1>
<div class="historyContainer">
    <?php if (!empty($historyEvent->getEventParagraphs())):
        ?>
        <div class="historyInformation">
            <?php
            foreach ($historyEvent->getEventParagraphs() as $paragraph) {
                ?>
                <h2><?= $paragraph->getTitle() ?></h2>
                <p><?= nl2br($paragraph->getText()) ?></p><br>
            <?php } ?>
        </div>
    <?php endif; ?>
    <div class="historyWalkingRoute">
    <span class="fw-bold fs-4">
    Location information
</span>
        <span>Take some time to read up on the locations you will visit on our history tours.</span><br>
        <p>The locations that will be visited are: </p>
        <ul>
            <?php
            foreach ($allTourLocations as $allTourLocation) {
                ?>
                <li>
                    <form method="GET" action="/festival/history/detail">
                        <input type="hidden" name="locationPostCode"
                               value="<?= $allTourLocation->getTourLocation()->getAddress()->getPostCode() ?>">
                        <input type="hidden" name="location"
                               value="<?= $allTourLocation->getTourLocation()->getLocationName() ?>">
                        <input type="hidden" name="locationId"
                               value="<?= $allTourLocation->getTourLocation()->getLocationId() ?>">
                        <button class="btn btn-link btn-sm" type="submit"><?= $allTourLocation->getTourLocation()->getLocationName() ?></button>
                        <i><?= $allTourLocation->getLocationInfo() ?></i>
                    </form>
                </li>
            <?php } ?>
        </ul>
    </div>
</div>
<h1 class="historyHeader">Showing all English tours</h1>


<div class="container">
    <div class="container-fluid pt-5 ps-5">
        <div class="row">
            <?php foreach ($timetable as $date => $toursByTime) { ?>
                <div class="col-md-3 my-3 mx-auto">
                    <button class="circleLine"><?= $date ?></button>
                    <?php foreach ($toursByTime as $time => $tours) { ?>
                        <div class="tourContainer position-relative">
                            <div class="d-flex align-items-center justify-content-center h-100">
                                <span class="align-self-center text-center text-lg tourTime"><?= $time ?></span>
                            </div>
                            <?php foreach ($tours as $tour) { ?>
                                <div class="container-fluid d-flex align-items-center justify-content-center tourLanguage"
                                     data-tour='<?php echo json_encode($tour); ?>'>
                                    <?= $tour->getTourLanguage() ?>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<script src="/Javascripts/festival/History/TicketHovering.js" type="text/javascript"></script>








