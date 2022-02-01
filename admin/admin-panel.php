<!--This admin panel template for admin panel components-->
<?php include('admin-auth-check.php'); ?>
<!doctype html>
<html lang="en">

<?php include('admin-panel-head.php'); ?>

<body>

<?php include('admin-panel-header.php'); ?>

<div class="container-fluid">
    <div class="row">,
        <?php include('admin-panel-sidemenu.php'); ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

            <?php include($pageName); ?>

        </main>
    </div>
</div>

<?php include('admin-panel-footer.php'); ?>

</body>
</html>