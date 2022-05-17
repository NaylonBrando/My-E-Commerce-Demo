<?php

use admin\src\controller\UserController;
use src\entity\User;

$userController = new UserController();

function userRow($id, $firstName, $lastName, $email, $createdAt, $status): string
{
    if ($status) {
        $status = "<a href='/admin/check-change-user-status/$id' class='btn btn-success btn-sm'>Activated</a>";
    } else {
        $status = "<a href='/admin/check-change-user-status/$id' class='btn btn-warning btn-sm'>Passived</a>";
    }
    return "<tr>
                        <td>$id</td>
                        <td>$firstName</td>
                        <td>$lastName</td>
                        <td>$email</td>
                        <td>$createdAt</td>
                        <td>$status</td>
                        <td>
                       <a href='/admin/user/update/$id' class='btn btn-info btn-sm'>Update</a>
                        <a href='/admin/check-delete-user/$id' class='btn btn-danger btn-sm' onclick=\"return confirm('Are you sure?');\">Delete</a></td>
                    </tr>";
}

/**
 * @param User[]
 * @return void
 */
function userRowGenerator(array $users)
{
    $str = '';
    /** @var User $user */
    foreach ($users as $user) {
        $str .= userRow($user->getId(), $user->getFirstName(), $user->getLastName(), $user->getEmail(),
            $user->getCreatedAt()->format('d/m/Y H:i:s'), $user->getIsActive());
    }
    echo $str;
}

function pagination($currentPageNumber, $totalUser, $limit): void
{
    $url = $_SERVER['REQUEST_URI'];
    if (str_contains($url, '?')) {
        if (preg_match('/\?pg=\d+/', $url)) {
            $url = preg_replace('/\?pg=\d+/', '', $url);
            $url = $url . '?pg=';
        } elseif (preg_match('/&pg=\d+/', $url)) {
            $url = preg_replace('/&pg=\d+/', '', $url);
            $url = $url . '&pg=';
        } else {
            $url = $url . '&pg=';
        }

    } else {
        $url = $url . '?pg=';
    }

    $record = 2;
    $pageCount = ceil($totalUser / $limit);
    $str = '<div class="mt-3"> <nav aria-label="Page navigation example">
                 <ul class="pagination justify-content-end">';
    if ($currentPageNumber > 1) {
        $newPage = $currentPageNumber - 1;
        $str .= '<li class="page-item"><a class="page-link" href="' . $url . $newPage . '"' . '>Geri</a></li>';
    } else {
        $str .= '<li class="page-item disabled"><a class="page-link" href="?pg=">Geri</a></li>';
    }
    for ($i = $currentPageNumber - $record; $i <= $currentPageNumber + $record; $i++) {
        if ($i == $currentPageNumber) {
            $str .= '<li class="page-item active"><a class="page-link" href="' . $url . $i . '"' . '>' . $i . '</a></li>';
        } elseif ($i > 0 && $i <= $pageCount) {
            $str .= '<li class="page-item"><a class="page-link" href="' . $url . $i . '"' . '>' . $i . '</a></li>';
        }
    }
    if ($currentPageNumber < $pageCount) {
        $newPage = $currentPageNumber + 1;
        $str .= '<li class="page-item"><a class="page-link" href="' . $url . $newPage . '"' . '>İleri</a></li>';
    } else {
        $str .= '<li class="page-item disabled"><a class="page-link" href="#">İleri</a></li>';
    }
    $str .= '</ul></nav></div>';
    echo $str;
}

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
            <a class="btn btn btn-success btn-sm" href="/admin/user" role="button">All Users</a>
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
            if (isset($users, $parameters, $totalUser)) {
                userRowGenerator($users);

            } else {
                echo "<tr><td colspan='7'>No Users Found</td></tr>";
            } ?>
            </tbody>
        </table>

        <?php
        if (isset($users, $parameters['pg'], $totalUser, $limit)) {
            pagination($parameters['pg'], $totalUser, $limit);
        } ?>
    </div>
</div>
