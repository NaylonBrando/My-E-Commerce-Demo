<?php

use src\controller\UserController;

$userController = new UserController();
$user = $userController->getById($_SESSION['user_id']);
?>

<div class="col-xl-8">
    <!-- Account details card-->
    <div class="card mb-4">
        <div class="card-header">Account Details</div>
        <div class="card-body">
            <form action="/check-update-profile" method="post">
                <!-- Form Row-->
                <div class="row gx-3 mb-3">
                    <!-- Form Group (first name)-->
                    <input type="hidden" class="form-control" name="userId"
                           value="<?php echo $user->getId() ?>">
                    <div class="col-md-6">
                        <label class="small mb-1" for="firstName">First Name</label>
                        <input class="form-control" id="firstName" type="text" name="firstName" required
                               value="<?php echo $user->getFirstName() ?>">
                    </div>
                    <!-- Form Group (last name)-->
                    <div class="col-md-6">
                        <label class="small mb-1" for="lastName">Last Name</label>
                        <input class="form-control" id="lastName" name="lastName" type="text" required
                               value="<?php echo $user->getLastName() ?>">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="small mb-1" for="email">Email adress</label>
                    <input class="form-control" id="email" type="email" name="email" required
                           value="<?php echo $user->getEmail() ?>">
                </div>
                <div class="row gx-3 mb-3">
                    <div class="col-md-6">
                        <label class="small mb-1" for="password">Password (To save changes enter your
                            password)</label>
                        <input class="form-control" id="password" type="password" required
                               name="password" placeholder="Enter your password ">
                    </div>
                </div>
                <!-- Save changes button-->
                <button class="btn btn-primary" type="submit">Save changes</button>
            </form>
            <?php
            if (isset($_SESSION['user_update_profile_error'])) {
                echo '<div class="alert alert-warning mt-2" role="alert">' . $_SESSION['user_update_profile_error'] . '</div>';
            }
            ?>
        </div>
    </div>
</div>
<div class="col-xl-4">
    <div class="card mb-4">
        <div class="card-header">Change Password</div>
        <div class="card-body">
            <form action="/check-update-password" method="post">
                <input type="hidden" class="form-control" name="userId" value="<?php echo $user->getId() ?>">
                <!-- Form Group (current password)-->
                <div class="mb-3">
                    <label class="small mb-1" for="currentPassword">Current Password</label>
                    <input class="form-control" id="currentPassword" type="password" name="currentPassword"
                           placeholder="Enter current password">
                </div>
                <!-- Form Group (new password)-->
                <div class="mb-3">
                    <label class="small mb-1" for="newPassword">New Password</label>
                    <input class="form-control" id="newPassword" type="password" name="newPassword"
                           placeholder="Enter new password">
                </div>
                <!-- Form Group (confirm password)-->
                <div class="mb-3">
                    <label class="small mb-1" for="confirmNewPassword">Confirm Password</label>
                    <input class="form-control" id="confirmNewPassword" type="password"
                           name="confirmNewPassword"
                           placeholder="Confirm new password">
                </div>
                <button class="btn btn-primary" type="submit">Save</button>
            </form>
            <?php
            if (isset($_SESSION['user_update_password_error'])) {
                echo '<div class="alert alert-warning mt-2" role="alert">' . $_SESSION['user_update_password_error'] . '</div>';
            }
            ?>
        </div>
    </div>
</div>

