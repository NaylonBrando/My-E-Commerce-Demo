<?php

use src\entity\User;

?>

<div class="container pt-3">
    <section class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Update User</h3>
        </div>
        <hr>
        <div class="panel-body">
            <form action='/admin/check-update-user' method="POST">
                <div>
                    <input value="<?php
                    /** @var User $user */
                    echo $user->getId()
                    ?>" type="hidden" class="form-control" name="userId">
                </div>
                <div class="col-md-9">
                    <?php
                    if (isset($_SESSION['user_update_error'])) {
                        echo '<div class="alert alert-warning mt-2" role="alert">' . $_SESSION['user_update_error'] . '</div>';
                    }
                    ?>
                </div>
                <label for="firstName" class="col-sm-3 control-label font-weight-bold"><h6>First Name</h6></label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="firstName" id="firstName" maxlength="80" required
                           autofocus
                           value="<?php echo $user->getFirstName() ?>">
                </div>
                <label for="lastName" class="col-sm-3 control-label font-weight-bold"><h6>Last Name</h6></label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="lastName" id="lastName" maxlength="80" required
                           value="<?php echo $user->getLastName() ?>">
                </div>
                <label for="email" class="col-sm-3 control-label font-weight-bold"><h6>Email</h6></label>
                <div class="col-sm-4">
                    <input type="email" class="form-control" name="email" id="email" maxlength="80" required
                           value="<?php echo $user->getEmail() ?>">
                </div>
                <hr>
                <div class="col-sm-offset-3 col-sm-9 pb-3">
                    <button type="submit" class="btn btn-primary" name="submitUpdateProduct">Update</button>
                </div>
            </form>
        </div>
</div>