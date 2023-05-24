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
               <h4 class="pb-2" >Restaurants Overview</h4>
            <a class="btn btn-primary" href="/admin/yummy/editRestaurant">Add A New Restaurant</a>
        </div>
        <div class="table-responsive-sm">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Location</th>
                    <th>Desc (HTML)</th>
                    <th># of Seats</th>
                    <th>Score</th>
                    <th>Food Types</th>
                    <th>Pic</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <Style>.roundImageRestaurantCardImage {
                        width: 40px;
                        height: 40px;
                        border-radius: 50%;
                        object-fit: cover;
                    }
                </Style>
                <tbody id="tableDataDisplay">
                <?php if (isset($errorMessage['restaurants'])) : ?>
                    <div class="alert alert-warning" role="alert">
                        <strong>Error:</strong> <?= $errorMessage['restaurants'] ?>
                    </div>
                <?php else:
                foreach ($restaurants as $restaurant) :
                    ?>
                    <tr>
                        <td><?= $restaurant->getId(); ?></td>
                        <td><?= mb_strimwidth($restaurant->getName(), 0, 35, "..."); ?></td>
                        <td><?= mb_strimwidth($restaurant->getLocation(), 0, 35, "..."); ?></td>
                        <td><?= mb_strimwidth($restaurant->getDescriptionHTML(), 0, 35, "..."); ?></td>
                        <td><?= $restaurant->getNumberOfSeats(); ?></td>
                        <td><?= $restaurant->getScore(); ?></td>
                        <td><?= mb_strimwidth($restaurant->getFoodTypes(), 0, 35, "..."); ?></td>
                        <td>
                            <img 
                            src="<?= $restaurant->getRestaurantCardImageFullPath() ?>"
                            class="roundImageRestaurantCardImage"
                            alt="Restaurant Card Image"
                            >
                        </td>
                        <td>
                            <div class="d-inline-flex">
                                <button
                                onclick='window.location.href="/admin/yummy/editRestaurant?id=<?= $restaurant->getId() ?>";'
                                class="btn btn-primary"
                                >
                                <i class="fa-solid fa-file-pen"></i>
                                </button>
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
<script src="/Javascripts/ManageUsers.js"></script>
</body>
</html>



