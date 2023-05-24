<section class="home-section">
    <div class="container pb-3 pt-5">
        <div class="row">
            <div class="col-md-12">
                <h1><?=$title?></h1>
            </div>
        </div>
    </div>
    <div class="Container">
        <div class="d-flex flex-column flex-md-row justify-content-md-between">
            <div class="float-start"></div>
            <div class="container-fluid float-end text-end">
                <a class="btn btn-primary" href= "/admin/dance/Addvenue" >Add new Venue</a>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="table-responsive-sm">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Street Name</th>
                    <th> House Number</th>
                    <th>Additional</th>
                    <th>Post Code</th>
                    <th>City</th>
                    <th>Country</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody id="tableDataDisplay">
                <?php if (isset($errorMessage['venues'])) : ?>
                    <div class="alert alert-warning" role="alert">
                        <strong>Error:</strong> <?= $errorMessage['venues'] ?>
                    </div>
                <?php else:
                    foreach ($venues as $venue) { ?>
                        <tr>
                            <td><?= $venue->getLocationId() ?></td>
                            <td><?= $venue->getLocationName() ?></td>
                            <td><?= $venue->getAddress()->getStreetName() ?></td>
                            <td><?= $venue->getAddress()->getHouseNumber() ?></td>
                            <td><?php  if(is_null($venue->getAddress()->getHouseNumberAdditional())) :
                                    echo "N/A";
                                else: echo $venue->getAddress()->getHouseNumberAdditional();
                                endif;?>

                                    </td>
                            <td><?= $venue->getAddress()->getPostCode() ?></td>
                            <td><?= $venue->getAddress()->getCity() ?></td>
                            <td><?= $venue->getAddress()->getCountry() ?></td>
                            <td>
                                <div class="d-inline-flex">
                                    <form action="/admin/dance/editVenue">
                                        <button name="venueId" class="btn btn-primary" value="<?= $venue->getLocationId() ?>"><i
                                                class="fa-solid fa-file-pen"></i></button>
                                    </form>
                                        <button type="button" class="btn btn-danger ms-3" onclick="deleteButtonClicked('<?= $venue->getLocationId()?>')"><i
                                                class="fa-solid fa-trash"></i></button>
                                </div>

                            </td>
                        </tr>
                    <?php } ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<script src="/Javascripts/CustomDialogueBox.js" type="text/javascript"></script>
<script src="/Javascripts/AdminPanel/Dance/VenuesOverview.js" type="text/javascript"></script>
