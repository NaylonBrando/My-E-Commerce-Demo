<?php

use src\controller\UserController;

$userController = new UserController();
$user = $userController->getById($_SESSION['user_id']);
?>

<!doctype html>
<html lang="en">
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/src/layout/profileHead.php'); ?>

<body>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/src/layout/navigationBar.php'); ?>

<div class="container-xl px-4 mt-4">
    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/src/layout/profileNavbar.php'); ?>
    <div class="row">
        <?php if (isset($pageModule)) {
            require_once($pageModule);
        } ?>
    </div>
</div>
</body>
</html>
