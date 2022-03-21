<!doctype html>
<html lang="en">

<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/layout/head.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/layout/navigation-bar.php');
?>

<body>


<div class="container-fluid">
    <div class="row">
        <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/layout/sidemenu.php');
        ?>
        <main role="main" class="col-md-10 ml-sm-auto col-lg-10 pt-3 px-4">
            <?php if (isset($pageModule)) {
                require_once($pageModule);
            } ?>
        </main>
    </div>
</div>


</body>
</html>
