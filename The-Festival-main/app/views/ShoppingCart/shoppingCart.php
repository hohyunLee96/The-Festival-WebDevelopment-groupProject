<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/22097c36aa.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://js.mollie.com/v2"></script>
    <meta name="description" content="">
    <meta name="author" content="">
    <script type="text/javascript"
            src="http://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js">
    </script>
    <script src="http://code.jquery.com/jquery-migrate-1.1.1.js"></script>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/css/Admin/AdminPanelSideBar.css">
    <!-- Boxiocns CDN Link -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<section class="h-100 h-custom" style="background-color: #d2c9ff;">
    <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-12">
                <div class="card card-registration card-registration-2" style="border-radius: 15px;">
                    <div class="card-body p-0">
                        <div class="row g-0">

                            <div class="col-lg-8">
                                <div class="p-5">
                                    <div class="d-flex justify-content-between align-items-center mb-5">
                                        <h1 class="fw-bold mb-0 text-black">Shopping Cart</h1>
                                    </div>
                                    <hr class="my-4">
                                    <?php foreach ($allItemsInShoppingCarts as $allItemsInShoppingCart) {
                                        ?>
                                        <div id="ticketContainer" class="row mb-4 d-flex justify-content-between align-items-center">
                                            <div class="col-md-2 col-lg-2 col-xl-2">
                                                    <h6 class="text-muted"><?= $allItemsInShoppingCart->getLanguage() ?></h6>
                                            </div>
                                            <div class="col-md-3 col-lg-3 col-xl-3">
                                                <h6 class="text-muted"><?= $allItemsInShoppingCart->getTicketType() ?></h6>
                                            </div>
                                            <div id="orderItemContainer<?= $allItemsInShoppingCart->getOrderItemId() ?>" class="col-md-3 col-lg-3 col-xl-3 d-flex">
                                                <button class="btn btn-decreaseQuantity px-2" onclick="updateQuantity('<?= $allItemsInShoppingCart->getOrderItemId() ?>', document.getElementById('quantityForm<?= $allItemsInShoppingCart->getOrderItemId() ?>').value - 1)">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                                <input id="quantityForm<?= $allItemsInShoppingCart->getOrderItemId() ?>" min="0" name="quantity" value="<?= $allItemsInShoppingCart->getQuantity() ?>" type="number" class="form-control form-control-sm" data-itemid="<?= $allItemsInShoppingCart->getOrderItemId() ?>"/>
                                                <button class="btn btn-increaseQuantity px-2" onclick="updateQuantity('<?= $allItemsInShoppingCart->getOrderItemId() ?>', parseInt(document.getElementById('quantityForm<?= $allItemsInShoppingCart->getOrderItemId() ?>').value) + 1)">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                            <div id="itemTotalPrice"class="col-md-3 col-lg-2 col-xl-2 offset-lg-1">
                                                <h6 class="mb-0"><?= $itemTotalPrice = $allItemsInShoppingCart->getPrice() * $allItemsInShoppingCart->getQuantity(); ?></h6>
                                            </div>
                                            <div class="col-md-1 col-lg-1 col-xl-1 text-end">
                                                <button class="text-muted" onclick="deleteItem('<?= $allItemsInShoppingCart->getOrderItemId() ?>')" style="font-size: 1.5em;">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <hr id class="my-4">

                                    <?php } ?>
                                    <?php foreach ($allRestaurantItems as $allRestaurantItem) {
                                        ?>
                                        <div id="ticketContainer" class="row mb-4 d-flex justify-content-between align-items-center">
                                            <div class="col-md-2 col-lg-2 col-xl-2">
                                                    <h6 class="text-muted"><?= $allRestaurantItem->getRestaurantName() ?></h6>
                                            </div>
                                            <div class="col-md-3 col-lg-3 col-xl-3">
                                                <h6 class="text-muted"><?= $allRestaurantItem->getRestaurantName() ?></h6>
                                            </div>
                                            <div id="orderItemContainer<?= $allRestaurantItem->getOrderItemId() ?>" class="col-md-3 col-lg-3 col-xl-3 d-flex">
                                                <button class="btn btn-decreaseQuantity px-2" onclick="updateQuantity('<?= $allRestaurantItem->getOrderItemId() ?>', document.getElementById('quantityForm<?= $allRestaurantItem->getOrderItemId() ?>').value - 1)">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                                <input id="quantityForm<?= $allRestaurantItem->getOrderItemId() ?>" min="0" name="quantity" value="<?= $allRestaurantItem->getQuantity() ?>" type="number" class="form-control form-control-sm" data-itemid="<?= $allRestaurantItem->getOrderItemId() ?>"/>
                                                <button class="btn btn-increaseQuantity px-2" onclick="updateQuantity('<?= $allRestaurantItem->getOrderItemId() ?>', parseInt(document.getElementById('quantityForm<?= $allRestaurantItem->getOrderItemId() ?>').value) + 1)">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                            <div class="col-md-3 col-lg-2 col-xl-2 offset-lg-1">
                                                <h6 class="mb-0"><?= $itemTotalPrice = $allRestaurantItem->getPrice() * $allRestaurantItem->getQuantity(); ?></h6>
                                            </div>
                                            <div class="col-md-1 col-lg-1 col-xl-1 text-end">
                                                <a href="#!" class="text-muted"><i class="fas fa-times"></i></a>
                                            </div>
                                        </div>

                                        <hr class="my-4">
                                    <?php } ?>
                                    <?php
                                    foreach ($allPerformanceItems as $allPerformanceItem) {
                                        $artistName = $allPerformanceItem->getArtistName();
                                        $halfArtistName = $artistName;
                                        $spaceIndex = strpos($artistName, ' ');
                                        if ($spaceIndex !== false) {
                                            $halfArtistName = substr($artistName, 0, $spaceIndex);
                                        }
                                        ?>
                                        <div id="ticketContainer" class="row mb-4 d-flex justify-content-between align-items-center">
                                            <div class="col-md-2 col-lg-2 col-xl-2">
                                                <h6 class="text-muted"><?= $halfArtistName ?></h6>
                                                <?php if ($spaceIndex !== false) { ?>
                                                    <h6 class="text-muted"><?= substr($artistName, $spaceIndex+1) ?></h6>
                                                <?php } ?>
                                            </div>
                                            <div class="col-md-3 col-lg-3 col-xl-3">
                                                <h6 class="text-muted"><?= $allPerformanceItem->getVenue() ?></h6>
                                            </div>
                                            <div id="orderItemContainer<?= $allPerformanceItem->getOrderItemId() ?>" class="col-md-3 col-lg-3 col-xl-3 d-flex">
                                                <button class="btn btn-decreaseQuantity px-2" onclick="updateQuantity('<?= $allPerformanceItem->getOrderItemId() ?>', document.getElementById('quantityForm<?= $allPerformanceItem->getOrderItemId() ?>').value - 1)">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                                <input id="quantityForm<?= $allPerformanceItem->getOrderItemId() ?>" min="0" name="quantity" value="<?= $allPerformanceItem->getQuantity() ?>" type="number" class="form-control form-control-sm" data-itemid="<?= $allPerformanceItem->getOrderItemId() ?>"/>
                                                <button class="btn btn-increaseQuantity px-2" onclick="updateQuantity('<?= $allPerformanceItem->getOrderItemId() ?>', parseInt(document.getElementById('quantityForm<?= $allPerformanceItem->getOrderItemId() ?>').value) + 1)">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                            <div class="col-md-3 col-lg-2 col-xl-2 offset-lg-1">
                                                <h6 class="mb-0"><?= $itemTotalPrice = $allPerformanceItem->getPrice() * $allPerformanceItem->getQuantity(); ?></h6>
                                            </div>
                                            <div class="col-md-1 col-lg-1 col-xl-1 text-end">
                                                <button class="text-muted" onclick="deleteItem('<?= $allPerformanceItem->getOrderItemId() ?>')" style="font-size: 1.5em;">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <hr class="my-4">
                                    <?php } ?>


                                    <div class="pt-5">
                                        <h6 class="mb-0"><a href="#!" class="text-body"><i
                                                        class="fas fa-long-arrow-alt-left me-2"></i>Back to shop</a>
                                        </h6>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 bg-grey">
                                <div class="p-5">
                                    <h3 class="fw-bold mb-5 mt-2 pt-1">Summary</h3>
                                    <hr class="my-4">

                                    <h5 class="text-uppercase mb-3">Give code</h5>

                                    <div class="mb-5">
                                        <div class="form-outline">
                                            <input type="text" id="form3Examplea2"
                                                   class="form-control form-control-lg"/>
                                            <label class="form-label" for="form3Examplea2">Enter your code</label>
                                        </div>
                                    </div>

                                    <hr class="my-4">

                                    <div class="d-flex justify-content-between mb-5">
                                        <h5 class="text-uppercase">Total price</h5>
                                        <h5 id="totalPrice"><?php echo $this->getTotalPrice(); ?></h5>
                                    </div>
                                    <?php if(!empty($sharingUrl)){?>
                                        <a href="<?php echo $sharingUrl; ?>" target="_blank" class="btn btn-dark btn-block btn-lg">Share</a>
                                  <?php  }?>
                                    <form method="post">
                                        <input type="hidden" name="amount" value="<?php echo $this->getTotalPrice(); ?>">
                                        <input type="hidden" name="description" value="Test">
                                        <input type="hidden" name="redirectUrl" value="http://localhost/festival/ShoppingCart/paymentRedirect">
                                        <input type="hidden" name="webhookUrl" value="https://example.com/webhook">
                                        <button id="payButton" type="submit" name="payNow" class="btn btn-dark btn-block btn-lg">Pay <?php echo $this->getTotalPrice(); ?></button>
                                    </form>
                                    <?php if (!empty($errorMessage)): ?>
                                        <div class="alert alert-danger" role="alert">
                                            <?php echo $errorMessage; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<style>
    @media (min-width: 1025px) {
        .h-custom {
            height: 100vh !important;
        }
    }

    .card-registration .select-input.form-control[readonly]:not([disabled]) {
        font-size: 1rem;
        line-height: 2.15;
        padding-left: .75em;
        padding-right: .75em;
    }

    .card-registration .select-arrow {
        top: 13px;
    }

    .bg-grey {
        background-color: #eae8e8;
    }

    @media (min-width: 992px) {
        .card-registration-2 .bg-grey {
            border-top-right-radius: 16px;
            border-bottom-right-radius: 16px;
        }
    }

    @media (max-width: 991px) {
        .card-registration-2 .bg-grey {
            border-bottom-left-radius: 16px;
            border-bottom-right-radius: 16px;
        }
    }
</style>

<script>
    function updateTotalPrice() {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'http://localhost/festival/shoppingCart/getTotalPrice');
        xhr.onload = function() {
            if (xhr.status === 200) {
                var totalPrice = xhr.responseText;
                console.log('Total price is: ' + totalPrice); // Add this line
                document.getElementById('totalPrice').innerHTML = totalPrice;
                document.getElementById('payButton').innerHTML = 'Pay ' + totalPrice;
            }
            else {
                console.log('Error fetching total price!');
            }
        };
        xhr.send();
    }

    function updateQuantity(itemId, quantity) {
        console.log(itemId,quantity);

        // Make sure the quantity is not less than zero
        if (quantity < 0) {
            quantity = 0;
        }

        // Check if the new quantity is zero
        if (quantity === 0) {
            // Send a request to delete the item from the shopping cart
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'http://localhost/festival/shoppingCart/deleteOrderItem');
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    console.log('Item deleted successfully!');
                    // Remove the item from the DOM
                    var itemContainer = document.getElementById('orderItemContainer' + itemId);
                    var ticketContainer = itemContainer.parentNode;
                    ticketContainer.removeChild(itemContainer);
                    // Check if there are any remaining items in the ticket container
                    if (ticketContainer.querySelectorAll('.row').length === 0) {
                        ticketContainer.parentNode.removeChild(ticketContainer);
                        updateTotalPrice();
                    }
                }
                else {
                    console.log('Error deleting item!');
                }
            };
            xhr.send('orderItemId=' + itemId);
        }
        else {
            // Send a request to update the quantity of the item in the shopping cart
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'http://localhost/festival/shoppingCart/updateQuantity');
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    console.log('Quantity updated successfully!');
                    // Update the quantity value in the input field
                    var quantityInput = document.getElementById('quantityForm' + itemId);
                    quantityInput.value = quantity;
                    updateTotalPrice();
                }
                else {
                    console.log('Error updating quantity!');
                }
            };
            xhr.send('orderItemId=' + itemId + '&quantity=' + quantity);
        }
    }
    function deleteItem(itemId) {
        // Send a request to delete the item from the shopping cart
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'http://localhost/festival/shoppingCart/deleteOrderItem');
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                console.log('Item deleted successfully!');
                // Remove the item from the DOM
                var itemContainer = document.getElementById('orderItemContainer' + itemId);
                var ticketContainer = itemContainer.parentNode;
                ticketContainer.removeChild(itemContainer);
                // Check if there are any remaining items in the ticket container
                if (ticketContainer.querySelectorAll('.row').length === 0) {
                    ticketContainer.parentNode.removeChild(ticketContainer);
                    updateTotalPrice();
                }
            }
            else {
                console.log('Error deleting item!');
            }
        };
        xhr.send('orderItemId=' + itemId);
    }

    function updateTotalItemPrice(itemId) {
        var quantity = document.getElementById('quantityForm' + itemId).value;
        var price = parseFloat(document.getElementById('itemPrice' + itemId).textContent);
        var totalItemPrice = quantity * price;
        document.getElementById('itemTotalPrice' + itemId).textContent = totalItemPrice.toFixed(2);
    }

    function handleClick(event) {
        event.preventDefault(); // Prevent the default link behavior.

        const closeIcon = event.target;
        const id = closeIcon.dataset.id; // Get the ID from the data-id attribute.
        const parentElement = closeIcon.parentElement; // Get the parent element.

        // Remove the item from the array.
        const index = myArray.findIndex(item => item.id === id);
        if (index !== -1) {
            myArray.splice(index, 1);
        }

        // Perform any other desired actions.
        parentElement.remove();
    }
</script>




