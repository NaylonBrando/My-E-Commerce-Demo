<!DOCTYPE html>
<html lang="en">
<?php use controller\ProductController;

require_once($_SERVER['DOCUMENT_ROOT'] . '/layout/head.php'); ?>
<body>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/layout/navigation_bar.php'); ?>

<div class="container mt-50 mb-50">
    <div class="row">
        <?php
        if (isset($pageModule)) {
            require_once($pageModule);
        }
        else{
            $productController = new ProductController();
            $page = empty($_GET['pg']) ? 1 : $_GET['pg'];
            $productController->productCardGenerator($page);
            $productController->paginator($page);
        }?>
    </div>
</div>
</body>
</html>