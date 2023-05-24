<section class="home-section">
    <div class="container pb-3 pt-3">
        <div class="row">
            <div class="col-md-12">
<!--                <h1>--><?//= $title ?><!--</h1>-->
            </div>
        </div>
    </div>
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h2 class="panel-title">Update Tour Address</h2>
            </div>
            <div class="panel-body">
                <form method="POST" action="/admin/history/update">
                    <div id="updateTour">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="control-label">Tour Language</label>
                                <input class="form-control" id="updateTourLanguage" name="updateTourLanguage" value="<?= $getSelectedTourById->getTourLanguage(); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">Tour Date</label>
                                <input type="date" class="form-control" id="updateTourDate" name="updateTourDate" value="<?= $getSelectedTourById->getTourDate()->format('Y-m-d'); ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="control-label">Tour Time</label>
                                <input type="time" class="form-control field" id="updateTourTime" name="updateTourTime" value="<?= $getSelectedTourById->getTime()->format('H:i:s'); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">Tour Duration</label>
                                <input class="form-control" id="administrative_area_level_1" value="<?= $getSelectedTourById->getDuration(); ?>">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3" name="updateTourLocation" value="<?= $getSelectedTourById->getHistoryTourId() ?>" >Submit</button>
                </form>
            </div>
        </div>
    </div>
</section>