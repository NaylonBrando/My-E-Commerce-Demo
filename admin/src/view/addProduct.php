<?php

use admin\src\controller\BrandController;
use admin\src\controller\CategoryController;

$brandController = new BrandController();
$categoryController = new CategoryController();
?>

<div class="container pt-3">
    <section class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Add Product</h3>

        </div>
        <hr>
        <div class="panel-body">
            <form action="/admin/check-add-product" method="POST" enctype="multipart/form-data" multiple="multiple">
                <label for="for-product" class="col-sm-3 control-label"><h6>Title</h6></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="title" id="for-product" maxlength="80" required
                           autofocus>
                </div>

                <label for="categoryId" class="col-sm-3 control-label"><h6>Category</h6></label>
                <div class="col-sm-3">
                    <select class="form-control" name="categoryId" id="categoryId" required>
                        <?php $categoryController->categoryComponentRowGenerator() ?>
                    </select>
                </div>

                <label for="brandId" class="col-sm-3 control-label"><h6>Brand</h6></label>
                <div class="col-sm-3">
                    <select class="form-control" name="brandId" id="brandId" required>
                        <?php $brandController->brandOptionRowGenerator() ?>
                    </select>
                </div>

                <label for="description" class="col-sm-3 control-label"><h6>Description</h6></label>
                <div class="col-sm-9">
                    <textarea name="description" style="resize: none; height:100px" class="form-control"
                              maxlength="2048"
                              required id="description"></textarea>
                </div>

                <label for="quantity" class="col-sm-3 control-label"><h6>Quantity</h6></label>
                <div class="col-sm-3">
                    <input type="text" pattern="[0-9]+" class="form-control" name="quantity" id="quantity" required>
                </div>

                <label for="stockNumber" class="col-sm-3 control-label"><h6>Stock Number</h6></label>
                <div class="col-sm-3">
                    <input type="text" pattern="[0-9]+" class="form-control" name="stockNumber" id="stockNumber"
                           required>
                </div>

                <label for="for-price" class="col-sm-3 control-label"><h6>Price</h6></label>
                <div class="col-sm-3">
                    <input type="text" pattern="([1-9][0-9]*|0)(\.[0-9]{2})?" class="form-control" name="price"
                           id="for-price" required>
                </div>

                <label for="name" class="col-sm-3 control-label"><h6>Images</h6></label> <br>
                <label for="name" class="col-sm-3 control-label">Maximum number of images is 5</label><br>
                <label for="name" class="col-sm-3 control-label">You can upload product images later</label>
                <div class="col-sm-3">
                    <input type="file" name="images[]" multiple="multiple">
                </div>

                <hr>
                <div class="col-sm-offset-3 col-sm-9 pb-3">
                    <button type="submit" class="btn btn-primary" name="add-product">Add</button>
                </div>
            </form>
        </div>
</div>