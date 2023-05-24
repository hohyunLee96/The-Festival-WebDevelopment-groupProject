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
            <a class="btn btn-primary" href="/admin/history/addHistoryTour">Add New Tour</a>
        </div>
        <div class="table-responsive-sm">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Tour Language</th>
                    <th>Tour Date</th>
                    <th>Tour Time</th>
                    <th>Tour Duration</th>
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
                    foreach ($historyTours as $historyTour) :
                        ?>
                        <tr>
                            <td><?= $historyTour->getTourLanguage(); ?></td>
                            <td><?= $historyTour->getTourDate()->format('Y-m-d'); ?></td>
                            <td><?= $historyTour->getTime()->format('H:i:s'); ?></td>
                            <td><?= $historyTour->getDuration(); ?></td>
                            <td>
                                <div class="d-inline-flex">
                                    <form method="POST">
                                        <input type="hidden" name="deleteTourId" value="<?= $historyTour->getHistoryTourId() ?>">
                                        <button type="submit" name="deleteHistoryTour" class="btn btn-danger"><i class="fa-solid fa-trash"></i></button>

                                        <input type="hidden" name="updateTourId" value="<?= $historyTour->getHistoryTourId() ?>">
                                        <button type="submit" name="updateHistoryTour" class="btn btn-primary"><i class="fa-solid fa-file-pen"></i></button>
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
<!--<script src="/Javascripts/ManageUsers.js"></script>-->
</body>
</html>



