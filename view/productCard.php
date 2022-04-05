<?php

use controller\ProductController;

?>

<div class="container mb-50 mt-50">
    <div class="row">
        <?php
        if (!isset($pageNumber)) {
            $pageNumber = 1;
        }
        $productController = new ProductController();
        $productController->productCardGenerator($pageNumber, $categoryName); ?>
    </div>
</div>
