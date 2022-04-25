<?php

use admin\controller\UserController;

?>

<div class="pt-3">
    <h2>User</h2>
    <div class="table-responsive mt-2">
        <table class="table table-striped table-sm text-center">
            <thead class="thead-light">
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Name</th>
                <th scope="col">Surname</th>
                <th scope="col">Email</th>
                <th scope="col">Created At</th>
                <th scope="col">Status</th>
                <th scope="col">Action</th>
            </tr>
            </thead>
            <tbody>
            <?php $userController = new UserController();
            $userController->userRowGenerator();
            ?>
            </tbody>
        </table>
    </div>
</div>
