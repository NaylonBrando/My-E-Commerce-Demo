<!doctype html>
<html lang="en">

<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/src/layout/head.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/src/layout/navigation-bar.php');
?>

<body>

<!--admin homepage-->
<div class="container-fluid">
    <div class="row">
        <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/src/layout/sidemenu.php');
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
