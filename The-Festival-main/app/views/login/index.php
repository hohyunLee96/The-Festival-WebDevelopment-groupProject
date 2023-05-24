<!DOCTYPE html>
<html lang="en">
<?php
require_once __DIR__ . '/../header2.php';
?>

<body>

<div class="container-fluid ps-md-0">
    <div class="row g-0">
        <div class="login d-flex align-items-center justify-content-center py-5">
            <div class="container">
                <div class="row d-flex justify-content-center">
                    <div class="col-sm-12 col-md-6 col-lg-4 mx-auto">
                        <h3 class="login-heading mb-4">Welcome To Haarlem Festival</h3>
                        <!-- Sign In Form -->
                        <form class="sign-in-form" action="/login" method="POST">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="username" id="floatingInput" placeholder="Username">
                                <label for="floatingInput">Usernam</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" name="pwd" id="floatingPassword" placeholder="Password">
                                <label for="floatingPassword">Password</label>
                            </div>
                            <div class="d-grid">
                                <button class="sign-in-btn btn btn-lg btn-primary btn-login text-uppercase fw-bold mb-2" name="signInSubmit" type="submit">Sign in</button>
                            </div>
                            <div class="form-floating mb-3">
                                <label>
                                <?php
                                    // $model is the input passed by the displayView method. We check it: if it is not null, we print it here (in the label html tag).
                                    if(isset($model) && $model!=null) echo $model
                                    ?>
                                </label>
                            </div>
                        </form>
                        <form class="register-form" action="/login/register" method="POST">
                            <div class="d-grid">
                                <button class="register-btn btn btn-lg btn-primary btn-login text-uppercase fw-bold mb-2" name="registerSubmit" type="submit">Create Account</button>
                            </div>
                        </form>
                        <form class="register-form" action="/login/resetPasswordViaEmail" method="POST">
                            <div class="d-grid">
                                <button class="register-btn btn btn-lg btn-primary btn-login text-uppercase fw-bold mb-2" name="registerSubmit" type="submit">Forgot Password?</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>

</html>
