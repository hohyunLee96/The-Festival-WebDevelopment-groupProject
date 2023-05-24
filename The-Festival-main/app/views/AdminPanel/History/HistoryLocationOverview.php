<section class="home-section">
    <div class="container-fluid pb-3">
        <div class="row">
            <div class="col-md-12">
                <h1><?= $title ?></h1>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-md-between">
            <h4 class="pb-2">Tour Location Overview</h4>
            <a class="btn btn-primary" href="/admin/history/addHistoryTourLocation">Add New Tour Location</a>
        </div>
        <div class="table-responsive-sm">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Street Name</th>
                    <th>House Number</th>
                    <th>Additional</th>
                    <th>Post Code</th>
                    <th>City</th>
                    <th>Country</th>
                </tr>
                </thead>
                <Style>.round-image {
                        width: 35px;
                        height: 35px;
                        border-radius: 50%;
                        object-fit: cover;
                    }
                </Style>
                <tbody id="tableDataDisplay">
                <?php if (isset($errorMessage['tourLocation'])) : ?>
                    <div class="alert alert-warning" role="alert">
                        <strong>Error:</strong> <?= $errorMessage['tourLocation'] ?>
                    </div>
                <?php else:
                    foreach ($tourLocations as $tourLocation) :
                        ?>
                        <tr>
                            <td><?= $tourLocation->getTourLocation()->getLocationName(); ?></td>
                            <td><?= $tourLocation->getTourLocation()->getAddress()->getStreetName(); ?></td>
                            <td><?= $tourLocation->getTourLocation()->getAddress()->getHouseNumber(); ?></td>
                            <td><?php if (is_null($tourLocation->getTourLocation()->getAddress()->getHouseNumberAdditional())) :
                                    echo "N/A";
                                else: echo $tourLocation->getTourLocation()->getAddress()->getHouseNumberAdditional();
                                endif; ?>
                            </td>
                            <td><?= $tourLocation->getTourLocation()->getAddress()->getPostCode(); ?></td>
                            <td><?= $tourLocation->getTourLocation()->getAddress()->getCity(); ?></td>
                            <td><?= $tourLocation->getTourLocation()->getAddress()->getCountry(); ?></td>
                            <td>
                                <div class="d-inline-flex">
                                    <form method="POST">
                                        <input type="hidden" name="deleteTourLocationId" value="<?= $tourLocation->getHistoryTourLocationId() ?>">
                                        <button type="submit" name="deleteHistoryLocation" class="btn btn-danger"><i class="fa-solid fa-trash"></i></button>

                                        <input type="hidden" name="updateTourLocationId" value="<?= $tourLocation->getHistoryTourLocationId() ?>">
                                        <button type="submit" name="updateHistoryLocation" class="btn btn-primary"><i class="fa-solid fa-file-pen"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<script src="/Javascripts/ManageUsers.js"></script>
</body>
</html>



