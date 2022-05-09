<?php

use admin\controller\ProductController;

$productController = new ProductController();
?>

<script>
    function searchWithTermRouter() {
        let searchValue = document.getElementById("search").value;
        document.searchProduct.action = "/admin/product/search/" + searchValue;
    }
</script>

<div class="pt-3">
    <h2>Product</h2>
    <div class="row">
        <div class="col-md-7">
            <a class="btn btn-primary" href="/admin/product/add" role="button">Add Product</a>
        </div>
        <div class="col-md-5">
            <form name="searchProduct" class="form-inline justify-content-end" method="post"
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
    <a class="btn btn btn-success btn-sm" href="/admin/product" role="button">All Products</a>
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
                <th scope="col">Action</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if (isset($searchTermParameters)) {
                $productController->productTableRowGeneratorWithSearchTerm($searchTermParameters['searchTerm'], $searchTermParameters['pg']);
            } else {
                if (isset($parameters['pg'])) {
                    $productController->productTableRowGenerator($parameters['pg']);
                } else {
                    $productController->productTableRowGenerator(1);
                }
            } ?>
            </tbody>
        </table>
    </div>
</div>