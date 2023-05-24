
<div class="compartment-1 d-flex align-items-end">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 overflow-hidden p-0">
                <img src="<?= $this->getImageFullPath($selectedArtist->getArtistImages()['Banner'][0]) ?>"
                     class="img-fluid h-100 position-absolute top-0 start-0" alt="Cover Image" style="width: 100vw">
                <span class="position-absolute bottom-0 start-0 mb-3 d-flex align-items-center"
                      style="z-index: 99; padding-left: 20px;">
               <h2 class="text-white fw-bold mb-0 ps-3 DanceFont">Dance</h2>
               <h5 class="text-white fw-bold mb-0 ms-3 artistNameFont"><?= $selectedArtist->getArtistName() ?></h5>
                </span>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid pt-3">
    <span class="float-left ps-3">
        <button class="BackButton" onclick="history.back()">
        <i class="fa-solid fa-arrow-left fa-xl"></i>
    </button>
    </span>
</div>
<div class="container-fluid px-md-5 ">
    <div class="container-fluid pt-3 ps-3 pb-3">
        <div class="row ps-3">
            <div class="col-6 ps-3">
                <p class="text-center" style="font-size: 24px;">
                    <?= nl2br($selectedArtist->getArtistDescription()) ?>
                </p>
            </div>
            <div class="col-6 justify-content-center">
                <svg class="pr-3">
                    <img class="image-fluid" src="<?= $this->getImageFullPath($selectedArtist->getArtistLogo()) ?>"
                         alt="<?= $selectedArtist->getArtistName() ?>" style="width: 250px; height: 250px;">
                </svg>
            </div>
        </div>
    </div>
    <div class="container-fluid pb-4 px-md-5">
        <div class="d-flex align-items-center justify-content-center">
            <img src="<?= $this->getImageFullPath($selectedArtist->getArtistImages()['Other'][0]) ?>"
                 style="width: 100%; height: 100%; border-radius: 49px;">
        </div>
        <div class="container-fluid pt-5">
            <div class="row">
                <div class="col">
              <span class="float-start ps-1">
            <h5 style="font-weight: bold; font-size: 40px; margin-bottom: -25px;">Albums and Singles</h5><br>
             </span>
                </div>
            </div>
            <div class="row">
                <?php if (isset($errorMessage['connectionToSpotify'])) : ?>
                    <div class="alert alert-warning" role="alert">
                        <strong>Error:</strong> <?= $errorMessage['connectionToSpotify'] ?>
                    </div>
                <?php else: ?>
                    <?php if (isset($errorMessage['artistAlbums'])) : ?>
                        <div class="alert alert-warning" role="alert">
                            <strong>Error:</strong> <?= $errorMessage['artistAlbums'] ?>
                        </div>
                    <?php else : ?>
                        <?php foreach ($artistAlbums->items as $album) : ?>
                            <div class="col-2">
                                <a href="<?= $album->external_urls->spotify ?>" class="album-link text-decoration-none">
                                    <div class="podcast-card">
                                        <div class="frame-117">
                                            <img src="<?= $album->images[0]->url ?>" class="img-fluid"
                                                 alt="<?= $album->name ?>">
                                        </div>
                                        <div class="text ps-3">
                                            <p class="albumName"><?= mb_strimwidth($album->name, 0, 7, '...') ?></p>
                                            <p class="detail-Text"><?= date('Y', strtotime($album->release_date)) ?> <i
                                                        class="fa-sharp fa-solid fa-circle fa-2xs"></i> <?= $album->album_type ?>
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="container-fluid ps-5 pt-3">
        <div class="row">
            <div class="col-6">
                <div class="card ps-3" style="border: 2px solid #000000;border-radius: 49px;">
                    <div class="card-header text-center pb-4 pt-4"
                         style="background-color: transparent; border-bottom: none;">
                        <h5 style="font-weight: bold; font-size: 40px; margin-bottom: -25px;">Show times</h5>
                    </div>
                    <div class="card-body d-flex flex-column justify-content-center text-center">
                        <?php foreach ($filteredArtistPerformances as $date => $performances) { ?>
                            <div class="container pb-5">
                                <button class="DateShowButton mx-auto"
                                        disabled><?= $this->getDayByDateString($date) ?></button>
                                <br>
                                <?php foreach ($performances as $performance) { ?>
                                    <label class="timeLabel"><?= $performance->getDate()->format('H:i') ?>
                                        - <?= $performance->getEndDateTime()->format('H:i') ?>
                                        <a class="link" style="text-decoration: underline; color: black;"
                                           target=”_blank”
                                           href="<?= $this->getGoogleDirectionsLink($performance->getVenue()->getAddress()) ?>"><?= $performance->getVenue()->getLocationName() ?>
                                        </a></label>
                                    <br>
                                    <button type="button"
                                            onclick='showTicketModal("<?= addslashes(json_encode($performance)) ?>")'
                                            class="BookBtn mx-auto" data-bs-toggle="modal"
                                            data-bs-target="#performanceModal">Book Ticket <i
                                                class="fa-solid fa-circle-arrow-right"></i>
                                    </button>
                                    <br>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="container-fluid">
                    <p style="font-style: normal;font-weight: 700;font-size: 40px;line-height: 64px;text-align: center;">
                        Check out tracks</p>
                </div>
                <div class="card" style="background-color:#121D22; border-radius: 30px; border: none">
                    <div class="container-fluid">

                        <div class="col">
                            <?php if (isset($errorMessage['connectionToSpotify'])) : ?>
                                <div class="alert alert-warning" role="alert">
                                    <strong>Error:</strong> <?= $errorMessage['connectionToSpotify'] ?>
                                </div>
                            <?php else: ?>
                            <?php if (isset($errorMessage['artistTopTracks'])) : ?>
                                <div class="alert alert-warning" role="alert">
                                    <strong>Error:</strong> <?= $errorMessage['artistTopTracks'] ?>
                                </div>
                            <?php else : ?>
                            <div class="hoverable">
                                <?php foreach ($artistTopTracks as $track) { ?>
                                    <div class="row-1">
                                        <div class="container text-light">
                                            <div class="container" style="margin-left:-20px">
                                                 <span class="float-start pt-2">
                                                <button id="playPauseButton<?= $track->id ?>" class="btn"
                                                        onclick="togglePlayPause('<?= $track->preview_url ?>', '<?= $track->id ?>')"
                                                        style="background-color:#121D22;color: white; border: none;padding:0;">
                                                  <i class="fa-solid fa-play fa-2xl"></i>
                                                </button>
                                                 </span>
                                            </div>
                                            <div class="row align-items-center">
                                                <div class="col-1">
                                                    <img src="<?= $track->album->images[0]->url ?>"
                                                         alt="<?= $track->name ?>"
                                                         class="img-fluid" style="width: 40px; height:38px;">
                                                </div>
                                                <div class="col-6 " style="margin-left: -15px">
                                                    <h2 class="pt-2 "><?= mb_strimwidth($track->name, 0, 20, '...') ?></h2>
                                                </div>
                                                <div class="col-4">
                                                </div>
                                                <div class="col-1 ps-3">
                                                    <span class="float-end pt-1 ps-5 text-right">
                                                    <p class="ps-2 pt-3"
                                                       style="font-size:20px;color: #606161"><?= gmdate("i:s", round($track->duration_ms / 1000)) ?></p>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid pt-5 pb-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-8" style="border-radius: 49px;">
                    <img src="<?= $this->getImageFullPath($selectedArtist->getArtistImages()['Other'][1]) ?>"
                         class="img-fluid" alt="<?= $selectedArtist->getArtistName() ?>"
                         style="height:613px; width: 963px; border-radius: 49px;">
                </div>
                <div class="col-4" style="border-radius: 49px;">
                    <img src="<?= $this->getImageFullPath($selectedArtist->getArtistImages()['Other'][2]) ?>"
                         class="img-fluid" alt="<?= $selectedArtist->getArtistName() ?>"
                         style="height: 613px; width: 924px; border-radius: 49px;">
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/TicketModal.html' ?>
<script>
    function showTicketModal(data) {
        performance = JSON.parse(data.replace(/\\/g, ''));
        fillTicketModal(performance);
    }
</script>
<script src="/Javascripts/festival/Dance/TicketModalPerformance.js" type="text/javascript"></script>
<script src="/Javascripts/festival/Dance/Audioplayer.js" type="text/javascript"></script>
</body>