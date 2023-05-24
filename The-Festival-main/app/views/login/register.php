<?php
//include __DIR__ . '/../header.php';
//?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bootstrap example</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/registerStyle.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
<div class="container pt-5">
    <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-lg-12 col-xl-11">
            <div class="card text-black h-100" style="border-radius: 25px;">
                <div class="card-body p-md-5">
                    <div class="row justify-content-center">
                        <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">
                            <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4">Sign up</p>
                            <form id="registerUserForm" class="mx-1 mx-md-4" method="POST"
                                  enctype="multipart/form-data">
                                <div class="text-center">
                                    <div class="d-flex justify-content-center">
                                        <div class="position-relative">
                                            <div class="avatar-upload">
                                                <div class="avatar-edit">
                                                    <input type='file' name="createUserImage" id="imageUpload"
                                                           accept=".png, .jpg, .jpeg" onchange="previewImage(this)"/>
                                                    <label for="imageUpload"><i class="fas fa-edit"></i></label>
                                                </div>
                                                <div class="avatar-preview">
                                                    <img id="imagePreview" src="/image/<?=DEFAULT_AVATAR?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex flex-row align-items-center mb-4">
                                    <div class="form-outline flex-fill mb-0">
                                        <label class="form-label" for="form3Example4c">First Name</label>
                                        <input type="text" name="firstName" id="firstName"
                                               class="form-control"/>
                                    </div>
                                </div>
                                <div class="d-flex flex-row align-items-center mb-4">
                                    <div class="form-outline flex-fill mb-0">
                                        <label class="form-label" for="form3Example4c">Last Name</label>
                                        <input type="text" name="lastName" id="lastName"
                                               class="form-control"/>
                                    </div>
                                </div>
                                <div class="d-flex flex-row align-items-center mb-4">
                                    <div class="form-outline flex-fill mb-0">
                                        <label class="form-label" for="form3Example3c">Birth Date</label>
                                        <input type="date" name="dateOfBirth" id="dateOfBirth"
                                               class="form-control"/>
                                    </div>
                                </div>
                                <div class="d-flex flex-row align-items-center mb-4">
                                    <div class="form-outline flex-fill mb-0">
                                        <label class="form-label" for="form3Example4c">Email</label>
                                        <input type="email" name="email" id="email"
                                               class="form-control"/>
                                    </div>
                                </div>
                                <div class="d-flex flex-row align-items-center mb-4">
                                    <div class="form-outline flex-fill mb-0">
                                        <label class="form-label" for="password">Password</label>
                                        <input type="password" name="password" id="password"
                                               class="form-control"/>
                                    </div>
                                </div>
                                <div class="d-flex flex-row align-items-center mb-4">
                                    <div class="form-outline flex-fill mb-0">
                                        <label class="form-label" for="confirmPassword">Confirm
                                            Password</label>
                                        <input type="password" name="passwordConfirm" id="confirmPassword"
                                               class="form-control"/>
                                    </div>
                                </div>
                                <div class="g-recaptcha" data-sitekey="6LelT5MkAAAAAJ3QoLco_K3e7ClTd75N9A0pQu5I"></div>
                                <br/>
                                <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                                    <button type="submit" name="registerBtn" class="btn btn-primary btn-lg">
                                        Register
                                    </button>
                                </div>
                                <?php if (!empty($errorMessage)) : ?>
                                    <div class="alert alert-danger" role="alert">
                                        <?php echo $errorMessage; ?>
                                    </div>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    grecaptcha.ready(function () {
        grecaptcha.execute('6Ld_TpIkAAAAAFBlzTcxaUr4CGW_AIhB3cKYuXhX', {action: 'submit'}).then(function (token) {
            console.log(token);// Add your logic to submit to your backend server here.
        });
    });
</script>
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            let reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('imagePreview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
</body>
</html>
