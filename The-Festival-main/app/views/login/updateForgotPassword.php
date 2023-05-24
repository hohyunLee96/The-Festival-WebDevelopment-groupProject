<?php
include __DIR__ . '/../header.php';

echo "<h1>Homepage!</h1>";
?>

<h1>Articles!</h1>

<body>
<div class="container d-flex justify-content-center mt-5 pt-5">
    <div class="card mt-5" style="width:500px">
        <div class="card-header">
            <h1 class="text-center">Forgot Password</h1>
        </div>
        <div class="card-body">
            <form method="POST">
<!--                <div class="mt-4">-->
<!--                    <label for="text">Verification Code : </label>-->
<!--                    <input type="text" name="updatePasswordToken" class="form-control" placeholder="Enter Email">-->
<!--                </div>-->
                <div class="mt-4">
                    <label for="text">Password : </label>
                    <input type="text" name="updateNewPassword1" class="form-control" placeholder="Enter Email">
                </div>
                <div class="mt-4">
                    <label for="text">Confirm Password : </label>
                    <input type="text" name="updateNewPassword2" class="form-control" placeholder="Enter Email">
                </div>
                <div class="mt-4 text-end">
                    <input type="submit" name="updatePassword" class="btn btn-primary">
                    <a href="/home" class="btn btn-danger">Back</a>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>

<?php
include __DIR__ . '/../footer.php';
?>

