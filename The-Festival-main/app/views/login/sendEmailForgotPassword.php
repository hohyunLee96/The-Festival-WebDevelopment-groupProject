<?php
include __DIR__ . '/../header.php';
?>

<!--<body>-->
<!--<div class="container d-flex justify-content-center mt-5 pt-5">-->
<!--    <div class="card mt-5" style="width:500px">-->
<!--        <div class="card-header">-->
<!--            <h1 class="text-center">Forgot Password</h1>-->
<!--        </div>-->
<!--        <div class="card-body">-->
<!--            <form method="POST">-->
<!--                <div class="mt-4">-->
<!--                    <label for="email">Email : </label>-->
<!--                    <input type="email" name="forgotPasswordEmail" class="form-control" placeholder="Enter Email">-->
<!--                </div>-->
<!--                <div class="mt-4 text-end">-->
<!--                    <input type="submit" name="send-link" class="btn btn-primary">-->
<!--                    <a href="/home" class="btn btn-danger">Back</a>-->
<!--                </div>-->
<!--            </form>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->
<!--</body>-->
<body>
<div class="container d-flex justify-content-center mt-5 pt-5">
    <div class="card mt-5" style="max-width: 500px;">
        <div class="card-header bg-primary text-white">
            <h1 class="text-center">Forgot Password</h1>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="form-group mt-4">
                    <label for="email">Email : </label>
                    <input type="email" name="forgotPasswordEmail" class="form-control" placeholder="Enter Email">
                </div>
                <div class="form-group mt-4 d-grid gap-2">
                    <input type="submit" name="send-link" class="btn btn-primary" value="Send Link">
                    <a href="/home" class="btn btn-danger">Back</a>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.2/js/bootstrap.bundle.min.js" integrity="sha512-zK/8pSHbok4h4cLlXDE/nTBzLft6YolKUPPbRUxayznBgltnZBf1GQ+wHK/cq3X9c6L2oC+YfIJJ5x5U5oSlMw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>
</html>

<?php
include __DIR__ . '/../footer.php';
?>

