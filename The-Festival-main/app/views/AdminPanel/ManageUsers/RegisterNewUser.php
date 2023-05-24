
<div class="container pt-5">
    <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-lg-12 col-xl-11">
            <div class="card text-black h-100" style="border-radius: 25px;">
                <div class="card-body p-md-5">
                    <div class="row justify-content-center">
                        <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">
                            <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4">Register New User</p>
                            <form id="registerUserForm" class="mx-1 mx-md-4" method="POST"
                                  enctype="multipart/form-data">
                                <div class="text-center">
                                    <div class="d-flex justify-content-center">
                                        <div class="position-relative">
                                            <div class="avatar-upload">
                                                <div class="avatar-edit">
                                                    <input type='file' id="imageUpload" name="profilePicUpload"
                                                           accept=".png, .jpg, .jpeg"
                                                           onchange="previewImage(this)"/>
                                                    <label for="imageUpload"><i class="fas fa-edit"></i></label>
                                                </div>
                                                <div class="avatar-preview">
                                                    <img id="profilePicView" src="/image/<?=DEFAULT_AVATAR?>">
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
                                        <label class="form-label" for="email">Email</label>
                                        <input type="email" name="email" id="email"
                                               class="form-control"/>
                                    </div>
                                </div>
                                <div class="d-flex flex-row align-items-center mb-4">
                                    <div class="form-outline flex-fill mb-0">
                                        <label class="form-label" for="role">Select a role for the new user</label>
                                        <select class="form-select" id="filter-select-role" name="role">
                                            <?php foreach (Roles::getEnumValues() as $value) : ?>
                                                <option value="<?= $value ?>"><?= Roles::getLabel(new Roles($value)) ?></option>
                                            <?php endforeach; ?>
                                        </select>
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
                                <?php if (!empty($message)): ?>
                                    <div class="alert alert-danger" role="alert"><p><?= $message; ?></p></div>
                                <?php endif; ?>
                                <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                                    <button type="submit" name="btnRegister" class="btn btn-primary btn-lg">
                                        Register
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            let reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('profilePicView').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
</body>
</html>
