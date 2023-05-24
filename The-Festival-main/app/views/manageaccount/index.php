<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/../css/manageAccountStyle.css" rel="stylesheet" type="text/css">
    <title>Manage account</title>

</head>
<body>
<main>
<main>

<div class="container d-flex justify-content-center align-items-center pt-5 mb-4">
<div class="row justify-content-center">
    <div class="col-lg-8 col-md-10">
        <div class="card shadow-sm">
            <div class="card-body">
                <h1 class="card-title text-center mb-4"><?= $currentUser->getFirstName() . ' ' . $currentUser->getLastName() ?></h1><span id="userId" style="display:none"><?php echo $currentUser->getId();?></span><br>
                <form method="POST" enctype="multipart/form-data" id="updateForm" action="/manageaccount/updateAccountData">
                    <div class="row mb-3 justify-content-center">
                            <div class="profile-img-container mb-3 text-center" >
                              <img src="<?= $currentUser->getPicture() ?>"alt="Profile Picture" class="img-fluid">
                              <div class="mb-3">
                              <br><br>
                              <label>Change your picture:</label>
                                <input type="file" name="file" id="file"><br><br>
                                </div>
                        </div>
                        <div class="col-md-8 ">
                         <div class="row mb-3 ">
                            <div class="col-md-6">
                                <div class="mb-3">
                                        <label for="first-name" class="form-label">First Name</label>
                                        <input type="text" id="firstname" class="form-control" name="firstName" value="<?= $currentUser->getFirstName() ?>" required>
                                    </div>
                                 </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="last-name" class="form-label">Last Name</label>
                                        <input type="text" class="form-control" name="lastName" value="<?= $currentUser->getLastName() ?>" required>
                                    </div>
                                </div>
                            </div>


                           <div class="row mb-3" id="userRole">
                           <div class="col-md-6">
                             <div class="mb-3">
                                    <select name="userRole" >
                                    <option value="none" selected disabled hidden><?= Roles::getLabel($currentUser->getRole()) ?></option>
                                    <option value="Customer">Customer</option>
                                    <option value="Administrator">Administrator</option>
                                    <option value="Employee">Employee</option>
                                  </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" name="email" id="email" value="<?= $currentUser->getEmail() ?>" required>
                                    </div>
                                </div>
                            </div>
                            <p id="emailValidation"></p>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="dob" class="form-label">Date of Birth</label>
                                        <input type="date" name="dateOfBirth" class="form-control" value="<?= $currentUser->getDateOfBirth()->format('Y-m-d') ?>"required>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">

                            <div class="col-md-6">
                                    <div class="mb-3 form-check">
                                        <input type="checkbox" class="form-check-input" id="changePasswordCheckBox"
                                               name="changePassword">
                                        <label class="form-check-label" for="change-password">Change
                                            Password</label>
                                    </div>
                                </div>

                                <div id="password-fields" class="row" style="display:none"> 
                             
                            <div class="col-md-6">

                               <div class="mb-3">
                                    <label for="password" class="form-label">New password</label>
                                    <input type="password" name="newPassword" id="newPassword" class="form-control" value="">
                                </div>
                                </div>
                                <div class="col-md-6">
                                <div class="mb-3">
                                <label for="password" class="form-label">Confirm password</label>
                                <input type="password" name="confirmPassword" id="confirmPassword" class="form-control" value="">    
                            </div>
                            </div>
                            </div>
                            <p id="validationMessage"></p>
                            </div>
                            <div class="row my-3">
                                <div class="col-md-6">
                                    <div class="d-grid gap-2">
                                        <button type="submit" id="submit" name="updateProfile" class="btn btn-primary btn-lg">Save Changes</button>
                                    </div>
                                </div>
                            <div class="col-md-6">
                                    <div class="d-grid gap-2">
                                        <button type="reset" class="btn btn-secondary btn-lg" onclick="history.back()">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>

<script src="/Javascripts/ManageAccount.js"></script>

</main>
</body>
</html>



