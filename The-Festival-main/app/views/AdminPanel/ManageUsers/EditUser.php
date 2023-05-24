<section class="home-section">
        <div class="container d-flex justify-content-center align-items-center pt-5">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h1 class="card-title text-center mb-4">Edit Details
                                of <?= $editingUser->getFirstName() ?></h1>
                            <div class="row mb-3">
                                <div class="col-md-4 text-center">
                                    <div class="profile-img-container mb-3">
                                        <div class="position-relative">
                                            <div class="avatar-upload">
                                                <div class="avatar-edit">
                                                    <input type='file' id="imageUpload"
                                                           accept=".png, .jpg, .jpeg"
                                                           onchange="previewImage(this)"/>
                                                    <label for="imageUpload"><i class="fas fa-edit"></i></label>
                                                </div>
                                                <div class="avatar-preview">
                                                    <img id="profilePicView"
                                                         src="<?= "/image/" . $editingUser->getPicture() ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="pt-3">
                                            <button type="button" class="btn btn-dark btn-sm"
                                                    onclick="resetProfilePicClicked('<?= DEFAULT_AVATAR ?>')">
                                                <i class="fas fa-user-secret me-2"></i> No profile picture
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="first-name" class="form-label">First Name</label>
                                                <input type="text" class="form-control" id="firstName"
                                                       value="<?= $editingUser->getFirstName() ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="last-name" class="form-label">Last Name</label>
                                                <input type="text" class="form-control" id="lastName"
                                                       value="<?= $editingUser->getLastName() ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="email" class="form-control" name="email" id="email"
                                                       value="<?= $editingUser->getEmail() ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="role" class="form-label">Role</label>
                                                <select class="form-select" id="role" name="role" required>
                                                    <?php foreach (Roles::getEnumValues() as $value) : ?>
                                                        <?php $label = Roles::getLabel(new Roles($value)); ?>
                                                        <option value="<?= $value ?>" <?php if (strcasecmp(Roles::getLabel($editingUser->getRole()), $value) === 0) {
                                                            echo 'selected';
                                                        } ?>>
                                                            <?= $label ?><?php if (strcasecmp(Roles::getLabel($editingUser->getRole()), $value) === 0) {
                                                                echo ' (Current role in system)';
                                                            } ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="dob" class="form-label">Date of Birth</label>
                                                <input type="date" class="form-control" id="dateOfBirth"
                                                       name="dateOfBirth"
                                                       value="<?= $editingUser->getDateOfBirth()->format('Y-m-d') ?>"
                                                       required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="registered-date" class="form-label">Registered Date</label>
                                                <input type="date" class="form-control" id="registeredDate"
                                                       value="<?= $editingUser->getRegistrationDate()->format('Y-m-d') ?>"
                                                       readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-8">
                                            <div class="mb-3 form-check">
                                                <input type="checkbox" class="form-check-input"
                                                       id="changePasswordCheckBox"
                                                       name="changePassword" onchange="onChangePasswordBox()">
                                                <label class="form-check-label" for="change-password">Change
                                                    Password</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="password-fields" class="d-none">
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="password" class="form-label">New Password</label>
                                                    <input type="password" class="form-control" id="password"
                                                           name="password">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="confirm-password" class="form-label">Confirm
                                                        Password</label>
                                                    <input type="password" class="form-control" id="confirmNewPassword"
                                                           name="confirmPassword">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php if (!empty($message)): ?>
                                    <div class="text-danger"><p><?= $message; ?></p></div>
                                <?php endif; ?>
                                <div class="alert alert-danger" role="alert" id="errors" hidden>
                                </div>
                                <div class="row my-3">
                                    <div class="col-md-6">
                                        <div class="d-grid gap-2">
                                            <button class="btn btn-primary btn-lg sm-mb-3" id="btnSaveChanges"
                                                    onclick="onEditUserSubmitChangesBtn(<?php echo $editingUser->getId(); ?>)"
                                                    type="button">Save
                                                Changes
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-grid gap-2">
                                            <button type="reset" class="btn btn-secondary btn-lg"
                                                    onclick="history.back()">
                                                Cancel
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
    </body>
    <script src="/Javascripts/ManageUsers.js"></script>
    </html>






