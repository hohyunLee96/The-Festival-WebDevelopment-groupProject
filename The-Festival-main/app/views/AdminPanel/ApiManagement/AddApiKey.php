<section class="home-section">
    <div class="container pb-3 pt-5">
        <div class="row">
            <div class="col-md-12">
                <h1><?= $title ?></h1>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-lg-6 col-xl-5">
                <form class="mx-1 mx-md-4" method="POST">
                    <div class="mb-3">
                        <label class="form-label" for="Api Key Used For ">Used By</label>
                        <input type="text" name="usedBy" id="Api Key Used For " class="form-control"/>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="UsedFor">Purpose </label>
                        <input type="text" name="purpose" id="UsedFor" class="form-control"/>
                    </div>
                    <?php if (!empty($errorMessage['Submit'])): ?>
                        <div class="alert alert-danger" role="alert">
                            <?= $errorMessage['Submit'] ?>
                        </div>
                    <?php endif; ?>
                    <div class="row justify-content-center">
                        <div class="pb-3">
                            <button type="submit" name="createApiKey" class="btn btn-primary w-100"> Create Api Key</button>
                        </div>
                        <div class="pb-2">
                            <button type="reset" class="btn btn-secondary w-100"
                                    onclick="location.href='/admin/ApiManagement'">
                                Cancel
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
