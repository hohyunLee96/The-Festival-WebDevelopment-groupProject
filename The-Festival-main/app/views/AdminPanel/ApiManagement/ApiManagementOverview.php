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
                <a class="btn btn-primary" href= "/admin/ApiManagement/addApiKey" >Add New APi Key</a>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="table-responsive-sm">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Key</th>
                    <th>Used By</th>
                    <th>Purpose</th>
                    <th>Created on</th>
                </tr>
                </thead>
                <tbody id="tableDataDisplay">
                <?php if (isset($errorMessage['apiKeys'])) : ?>
                    <div class="alert alert-warning" role="alert">
                        <strong>Not found: </strong> <?= $errorMessage['apiKeys'] ?>
                    </div>
                <?php else:
                    foreach ($apiKeys as $apiKey) { ?>
                        <tr>
                            <td><?= $apiKey->getApiKeyId() ?></td>
                            <td><?= $apiKey->getKey() ?></td>
                            <td><?= $apiKey->getUsedBy() ?></td>
                            <td><?= $apiKey->getPurpose() ?></td>
                            <td><?= $apiKey->getCreatedOn()->format('Y-m-d H:i ') ?></td>
                        </tr>
                    <?php } ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</section>

