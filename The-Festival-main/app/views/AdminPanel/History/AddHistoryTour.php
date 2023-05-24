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
                <h2 class="panel-title">Add History Tour</h2>
            </div>
            <div class="panel-body">
                <form method="POST">
                    <div id="address">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="control-label">Tour Language</label>
                                <select class="form-control" id="newTourLanguage" name="newTourLanguage">
                                    <option value="english">English</option>
                                    <option value="dutch">Dutch</option>
                                    <option value="chinese">Chinese</option>
                                    <option value="custom">Custom</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">Custom Language</label>
                                <input class="form-control" id="customLanguage" name="customLanguage" placeholder="Enter custom language" disabled>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label" for="tourDate">Tour Date</label>
                                <input type="date" name="newTourDate" id="newTourDate" class="form-control"/>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="tourTime">Tour Time</label>
                                <input type="time" name="newTourTime" id="newTourTime" class="form-control" onchange="updateDuration()">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3" name="addNewHistoryTour">Submit</button>
                </form>
            </div>
        </div>
    </div>
</section>
<script>
    // Enable the "Custom Language" input field when the "Custom" option is selected
    const tourLanguageSelect = document.querySelector('#newTourLanguage');
    const customLanguageInput = document.querySelector('#customLanguage');

    tourLanguageSelect.addEventListener('change', () => {
        const selectedOption = tourLanguageSelect.options[tourLanguageSelect.selectedIndex];
        if (selectedOption.value === 'custom') {
            customLanguageInput.disabled = false;
        } else {
            customLanguageInput.disabled = true;
        }
    });
</script>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"
        integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS"
        crossorigin="anonymous"></script>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet"
      integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
