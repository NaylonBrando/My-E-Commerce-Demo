<?php

use admin\controller\UserController;

$userController = new UserController();

?>
<script>
    function searchWithTermRouter() {
        let searchValue = document.getElementById("search").value;
        document.searchUser.action = "/admin/user/search/" + searchValue;
    }
</script>

<div class="pt-3">
    <h2>User</h2>
    <div class="row">
        <div class="col-md-7">
            <a class="btn btn btn-success btn-sm" href="/admin/product" role="button">All Users</a>
        </div>
        <div class="col-md-5">
            <form name="searchUser" class="form-inline justify-content-end" method="post"
                  onsubmit="searchWithTermRouter()">
                <div class="form-group mx-sm-3 mb-2">
                    <label for="search">
                        <input type="text" class="form-control" id="search" name="search" placeholder="Search" required>
                    </label>
                </div>
                <button type="submit" class="btn btn-primary mb-2">Search</button>
            </form>
        </div>
    </div>
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
            <?php
            if (isset($searchTermParameters)) {
                $userController->userTableRowGeneratorWithSearchTerm($searchTermParameters['searchTerm'], $searchTermParameters['pg']);
            } else {
                if (isset($parameters['pg'])) {
                    $userController->userTableRowGenerator($parameters['pg']);
                } else {
                    $userController->userTableRowGenerator(1);
                }
            } ?>
            </tbody>
        </table>
    </div>
</div>
