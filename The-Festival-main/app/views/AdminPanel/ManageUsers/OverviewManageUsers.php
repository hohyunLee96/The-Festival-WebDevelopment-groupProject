<section class="home-section">
    <div class="container pb-3">
        <div class="row">
            <div class="col-md-12">
                <h1>Manage Users</h1>
            </div>
        </div>
    </div>
    <div class="container pb-4">
        <div class="row align-items-center justify-content-between">
            <div class="col-md-5 mb-3 mb-md-0">
                <div class="d-flex justify-content-md-start">
                    <label for="filter-select" class="form-label me-3">Sort:</label>
                    <select class="form-select" id="filter-select" onchange="sortValueChanged(this)">
                        <option selected value="All Users">All Users</option>
                        <option value="A-z">A-z</option>
                        <option value="Z-A">Z-A</option>
                        <?php foreach (Roles::getEnumValues() as $value) : ?>
                            <option value="<?= $value ?>"><?= Roles::getLabel(new Roles($value)) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <style>
                /* Reduce the height of the select element */
                select.form-select {
                    height: 32px;
                    padding: 0.25rem 1rem;
                    font-size: 0.875rem;
                    line-height: 1.5;
                }

                /* Adjust the alignment of the select label */
                label.form-label {
                    margin-bottom: 0;
                }
            </style>
            <div class="col-md-6 col-xl-5">
                <div class="d-flex justify-content-md-end">
                    <form class="input-group">
                        <input type="search" class="form-control form-control-sm" oninput="onInputChange(this)"
                               placeholder="Search users by ID, first name, last name or email" aria-label="Search">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="d-flex flex-column flex-md-row justify-content-md-between">
                    <h4 class="pb-2">Users Overview</h4>
                    <a class="btn btn-primary" href="/admin/manageusers/registerNewUser">Register New User</a>
                </div>
                <div class="table-responsive-sm">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>User Id</th>
                            <th>Profile Pic</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Date of Birth</th>
                            <th>Registration Date</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <Style>.round-image {
                                width: 40px;
                                height: 40px;
                                border-radius: 50%;
                                object-fit: cover;
                            }
                        </Style>
                        <tbody id="tableDataDisplay">
                        <?php
                        if (!is_null($users)) {
                        foreach ($users as $user) {
                            ?>
                            <tr>
                                <td><?= $user->getId(); ?></td>
                                <td><img src="<?= "/image/" . $user->getPicture() ?>" alt="Profile Picture"
                                         class="round-image"></td>
                                <td><?= $user->getFirstName(); ?></td>
                                <td><?= $user->getLastName(); ?></td>
                                <td><?= $user->getEmail(); ?></td>
                                <td><?= Roles::getLabel($user->getRole()); ?></td>
                                <td><?= $user->getDateOfBirth()->format('d-m-Y'); ?></td>
                                <td><?= $user->getRegistrationDate()->format('d-m-Y'); ?></td>
                                <td>
                                    <div class="d-inline-flex">
                                        <form method="POST" action="/admin/manageUsers/editUser">
                                            <input type="hidden" name="hiddenUserId" id="hiddenUserId"
                                                   value="<?= $user->getId() ?>">
                                            <button class="btn btn-primary" value="" name="btnEditUser"><i
                                                        class="fa-solid fa-file-pen"></i>
                                            </button>
                                        </form>
                                        <button class="btn btn-danger ms-2"
                                                onclick="btnDeleteUserClicked(<?= $user->getId() ?>)"><i
                                                    class="fa-solid fa-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                        <?php
                        }
                        }
                        else{
                        ?>
                            <script>
                                window.onload = function () {
                                    noSearchResultFoundForSearch();
                                };
                            </script>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="/Javascripts/CustomDialogueBox.js" type="text/javascript"></script>
<script src="/Javascripts/ManageUsers.js"></script>
</body>
</html>



