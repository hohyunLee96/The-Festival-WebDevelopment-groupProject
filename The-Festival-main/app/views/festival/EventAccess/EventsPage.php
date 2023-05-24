<?php function DisplayData($eventList, $day)
{ ?>

  <div id="schedule" class="border m-2 mt-5">

    <p class="text-center display-5">
      <?= $day->getEventDay() . " " . $day->getFormattedEventDate() ?>
    </p>

    <div class="grid text-center">
      <?php
      foreach ($eventList as $count => $row) {
        ?>

        <?php
        if ($row['AvailableEvent']->getSingleEvent() == 'True') {

          ?>
          <div class="g-col-11 grid text-center">

            <div class="border m-2 p-2 ">
              <?= $row['AvailableEvent']->getEventHour() ?>
            </div>

            <?php
            if ($row['AvailableEvent']->getEventTypeId() == 1) {

              ?>
              <div class="g-col-9 border m-2 p-2">
                <?= $row['AvailableEvent']->getEventDetails() ?>

              </div>
              <div class="g-col-2 border m-2 p-2">
                <?= $row['AvailableEvent']->getDeliveryPossibilities() ?>
              </div>

            <?php } else {
              if ($row['ParticipatingArtist'] != 0) { ?>
                <div class="g-col-11 border m-2 p-2">
                  <?= $row['ParticipatingArtist'] ?>

                </div>
              <?php } else { ?>
                <div class="g-col-11 border m-2 p-2">
                  <?= $row['AvailableEvent']->getEventDetails() ?>

                </div>

              <?php } ?>

            <?php } ?>


          </div>

        <?php } else { ?>
          <div class="g-col-5 grid text-center">

            <div class="border m-2 p-2">
              <?= $row['AvailableEvent']->getEventHour() ?>
            </div>

            <?php
            if ($row['AvailableEvent']->getEventTypeId() == 1) {

              ?>
              <div class="g-col-11 border m-2 p-2">
                <?= $row['AvailableEvent']->getEventDetails() ?>

              </div>
              <div class="g-col-4 border m-2 p-2">
                <?= $row['AvailableEvent']->getDeliveryPossibilities() ?>

              </div>
            <?php } else {
              if ($row['ParticipatingArtist'] != 0) { ?>

                <div class="g-col-11 border  m-2 p-2">
                  <?= $row['ParticipatingArtist'] ?>

                </div>

              <?php } else { ?>
                <div class="g-col-11 border m-2 p-2">
                  <?= $row['AvailableEvent']->getEventDetails() ?>

                </div>

              <?php } ?>
            <?php } ?>

          </div>
        <?php } ?>
        <div class="g-col-1 m-auto">

          <button type="button" class="btn btn-light m-1 mt-2 w-100" id="buyTicket"><img class="buyTicket w-100"
              src="../image/addTicket.png" alt=""><span id="availableEventId" style="display:none">
              <?php echo $row['AvailableEvent']->getEventId(); ?>
            </span></button>
        </div>


        <div id="ticketData" class="modal" style="display:none">
          <div id="ticket" class="p-2 grid text-center">

            <div id="data" class="g-col-10 my-5 d-flex flex-row">

              <img class="eventPageImg g-col-2 w-25" src="../image/Festival/EventAccess/imgPlaceholder.png" alt="">
              <div class="d-flex flex-column m-4" id="textData">
                <p class="display-5" id="eventType" class="g-col-8 m-4">
                </p>
                <p><span id="day"></span>&nbsp;<span id="dateTime"></span> </p>

                <label for="translationOptionsList" id="chooseTranslationOption">Choose language:</label>
                <div class="d-flex flex-row" id="translationOptions" style="display:none">
                </div>

                <label for="ticketType" id="chooseTicketType">Select ticket type:</label>

                <select id="ticketTypes" class="form-select">
                  <option value="single">Single</option>
                  <option value="nonSingle">Non-Single</option>
                </select>

                <div class="btn-group" id="ticketOptionsControls">

                  <button type="button" class="btn btn-light " id="addToShoppingBasket"> Add to basket</button>
                  <button type="button" class="btn btn-light " data-dismiss="modal" id="cancelAddingNewTicket">
                    Cancel</button>
                </div>

              </div>
            </div>

          </div>
        </div>
      <?php } ?>
    </div>

  </div>


<?php } ?>


<main>

  <span id="userId" style="display:none">
    <?php echo $currentUserId; ?>
  </span>


  <div class="position-relative container" style="height: 135vh;">
    <p><video muted autoplay>

        <source src="../video/Festival/EventAccess/VeerplasVideo.mp4" class="col-xl h-100 mx-0 position-absolute start-0"
          type="video/mp4">
      </video></p>

    <div class="carousel-caption">
      <div class="display-2 d-inline p-2 align-text-bottom">
        <? echo $bodyHead->getH1() ?>
      </div>
      <div class="display-1 d-inline p-2 align-text-bottom">
        <? echo $bodyHead->getH2() ?>
      </div>
    </div>
  </div>

  <div class="px-4 my-5 text-center" id="mainParagraph">

    <p>
      <?= $paragraphs[0]->getText() ?>
    </p>
    <?php ?>
  </div>


  <div id="eventInfoPanel" class="grid text-center m-5" style="row-gap: 0;">
    <?php
    $imagePath = '';
    foreach ($paragraphs as $count => $paragraph) {
      if ($count > 0) {

        if ($count == 1) {
          $imagePath = $danceEventImageData;
        } else if ($count == 2) {
          $imagePath = $foodEventImageData;
        } else if ($count == 3) {
          $imagePath = $historyEventImageData;
        }
        $src = "../image/Festival/EventAccess/" . $imagePath ?>


        <div id="eventInfoRow" class="g-col-12 border rounded d-flex flex-row"><img class="eventPageImg w-50 border rounded"
            src=<?php echo $src ?> alt="">
          <p id="eventInfo" class="rounded py-5 m-4 display">
            <?= $paragraph->getText() ?>
          </p>
        </div>
      <?php }
    } ?>
  </div>

  <?php DisplayData($availableEventsList1, $firstEventsDay);
  DisplayData($availableEventsList2, $secondEventsDay);
  DisplayData($availableEventsList3, $thirdEventsDay);
  DisplayData($availableEventsList4, $fourthEventsDay);
  ?>





  <div id="eventAccessPageMap" class="grid text-center">
    <div class="g-col-12 g-col-md-12">
      <iframe class="eventsPageMapPlaceholder"
        src="https://www.google.com/maps/embed?pb=!1m70!1m12!1m3!1d9741.18943482697!2d4.632876232243762!3d52.383159573961166!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m55!3e0!4m5!1s0x47c5ef64a9023e29%3A0x5cae25c78aaf6285!2sEnergieplein%2074%2C%202031%20TC%20Haarlem!3m2!1d52.386646999999996!2d4.652472899999999!4m5!1s0x47c5ef6b636c315d%3A0x82cc852c36be783!2sKromme%20Elleboogsteeg%2012%2C%202011%20TS%20Haarlem!3m2!1d52.3822462!2d4.6344671!4m5!1s0x47c5ef14eba2659d%3A0xb4de55f24270ed81!2sGedempte%20Voldersgracht%202%2C%202011%20WP%20Haarlem!3m2!1d52.381006!2d4.6298927999999995!4m5!1s0x47c5ef6b88e5c609%3A0x79ebd2779fcbb6b8!2sSmedestraat%2033%2C%20Smedestraat%2031%2C%202011%20RE%20Haarlem!3m2!1d52.382222!2d4.6363889!4m5!1s0x47c5ef1386b1b391%3A0x18af44a874eeb1a0!2sZijlsingel%202%2C%202013%20DN%20Haarlem!3m2!1d52.382991999999994!2d4.6286359!4m5!1s0x47c5ef6a238b396d%3A0xc936a8d9bd126667!2sSpaarne%2096%2C%20Haarlem!3m2!1d52.3787011!2d4.6376547!4m5!1s0x47c5ef6bdefce0cf%3A0x66447fecda420328!2sKlokhuisplein%209%2C%20Haarlem!3m2!1d52.3808273!2d4.638281699999999!4m5!1s0x47c5ef6bbb5c4bdd%3A0x21b5d308777e39e6!2sSpekstraat%204%2C%202011%20HM%20Haarlem!3m2!1d52.380750899999995!2d4.6361004!4m5!1s0x47c5ef6b906d38cd%3A0x6eb703e41c73e034!2sGrote%20Markt%2013%2C%202011%20RC%20Haarlem!3m2!1d52.3816728!2d4.6362421!5e0!3m2!1sen!2snl!4v1679943801053!5m2!1sen!2snl"
        width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
        referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
  </div>


  <div id="InstagramData" class="container object-fit-fill  mb-4">
    <div class="row object-fit-fill  ">
      <div class="col col-3 m-0 p-0">
        <div class="border rounded px-2 ml-0 mt-1 py-1 "><img class="smallImage p-3 mb-1 "
            src="../image/Festival/EventAccess/instagramScanCode.png">
          <div class="display-7 mt-3 mb-4 px-2  text-center">Placeholder text</div>
        </div>
      </div>
      <div class="col-9 m-0 p-0">
        <div id="carouselExample" class="carousel slide  ">
          <div class="carousel-inner">
            <div class="carousel-item active">
              <div class="grid text-center ">
                <div class="g-col-md-4 row object-fit-fill border rounded mx-1"><img class="d-block w-100 mt-4 mb-4 "
                    src="../image/Festival/EventAccess/InstagramImg1.png"></div>
                <div class="g-col-md-4 row object-fit-fill border rounded mx-1"><img class="d-block w-100 mt-4 mb-4"
                    src="../image/Festival/EventAccess/InstagramImg2.png"></div>
                <div class="g-col-md-4 row object-fit-fill border rounded mx-1"><img class="d-block w-100 mt-4 mb-4"
                    src="../image/Festival/EventAccess/InstagramImg3.png"></div>
              </div>
            </div>
            <div class="carousel-item">
              <div class="grid text-center ">
                <div class="g-col-md-4 row object-fit-fill border rounded mx-1 "><img class="d-block w-100 mt-4 mb-4 "
                    src="../image/Festival/EventAccess/InstagramImg4.png"></div>
                <div class="g-col-md-4 row object-fit-fill border rounded mx-1"><img class="d-block w-100 mt-4 mb-4"
                    src="../image/Festival/EventAccess/InstagramImg5.png"></div>
                <div class="g-col-md-4 row object-fit-fill border rounded mx-1"><img class="d-block w-100 mt-4 mb-4"
                    src="../image/Festival/EventAccess/InstagramImg6.png"></div>
              </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
              <span class="visually-hidden">Previous</span> </button> <button class="carousel-control-next"
              type="button" data-bs-target="#carouselExample" data-bs-slide="next"> <span
                class="visually-hidden">Next</span> </button>
          </div>

</main>
</body>
<script src="/Javascripts/Festival/EventAccess/EventAccess.js"></script>

</html>
