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
               <h4 class="pb-2" >Artists Overview</h4>
            <a class="btn btn-primary" href="/admin/dance/addArtist">Add New Artist</a>
        </div>
        <div class="table-responsive-sm">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Artist Id</th>
                    <th>Profile Pic</th>
                    <th>Artist Name</th>
                    <th>styles</th>
                    <th>Artist Description</th>
                    <th>Actions</th>
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
                <?php if (isset($errorMessage['artists'])) : ?>
                    <div class="alert alert-warning" role="alert">
                        <strong>Error:</strong> <?= $errorMessage['artists'] ?>
                    </div>
                <?php else:
                foreach ($artists as $artist) :
                    ?>
                    <tr>
                        <td><?= $artist->getArtistId() ?></td>
                        <td><img src="<?=$this->getImageFullPath($artist->getArtistImages()['Portrait'][0]) ?>" alt="Profile Picture"
                                 class="round-image"></td>
                        <td><?= $artist->getArtistName(); ?></td>
                        <td><?= $this->formatArtistStylesToDisplay($artist->getArtistStyles()) ?></td>
                        <td><?= mb_strimwidth($artist->getArtistDescription(), 0, 35, '...') ?></td>
                        <td>
                            <div class="d-inline-flex">
                                    <a type="button" href="/admin/dance/editArtist?artist=<?= $artist->getArtistId() ?>" class="btn btn-primary"><i
                                                class="fa-solid fa-file-pen"></i></a>
                                    <button type="button" onclick="deleteButtonClicked('<?= $artist->getArtistId() ?>')" class="btn btn-danger ms-3"><i
                                                class="fa-solid fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php endif;?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<script src="/Javascripts/CustomDialogueBox.js" type="text/javascript"></script>
<script src="/Javascripts/AdminPanel/Dance/ArtistOverview.js" type="text/javascript"></script>



