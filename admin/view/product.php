<?php

use admin\controller\ProductController;

$productController = new ProductController();
?>

<div class="pt-3">
    <h2>Product</h2>
    <a class="btn btn-primary" href="product/add" role="button">Add Product</a>
    <div class="table-responsive mt-2">
        <table class="table table-striped table-sm text-center">
            <thead class="thead-light">
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Stock Number</th>
                <th scope="col">Status</th>
                <th scope="col">Title</th>
                <th scope="col">Created At</th>
                <th scope="col">Category</th>
                <th scope="col">Brand</th>
                <th scope="col">Quantity</th>
                <th scope="col">Price</th>
                <th scope="col">(Update)<br>(Disable/Enable)<br>(See Images)<br>(Delete)</th>
            </tr>
            </thead>
            <tbody>
            <?php $productController->productTableRowGenerator() ?>
            </tbody>
        </table>
    </div>
</div>