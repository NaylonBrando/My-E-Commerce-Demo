<?php

use admin\controller\BrandController;
use admin\controller\CategoryController;
use src\entity\Product;
use src\entity\ProductToCategory;

$brandController = new BrandController();
$categoryController = new CategoryController();
?>

<div class="container pt-3">
    <section class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Update Product</h3>
            <a href="/admin/product/image/<?php
            /** @var Product $product */
            echo $product->getId()
            ?>">
                <button type="button" class="btn btn-info">Images</button>
            </a>
            <hr>
            <h5><?php /** @var Product $product */
                echo 'Id: ' . $product->getId() ?></h5>
            <h5><?php /** @var Product $product */
                echo 'Slug: ' . $product->getSlug() ?></h5>
        </div>
        <hr>
        <div class="panel-body">
            <form action='/admin/check-update-product' method="POST" enctype="multipart/form-data" multiple="multiple">
                <div>
                    <input value="<?php
                    /** @var Product $product */
                    echo $product->getId()
                    ?>" type="hidden" class="form-control" name="productId">
                </div>
                <label for="for-product" class="col-sm-3 control-label font-weight-bold"><h6>Title</h6></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="title" id="for-product" maxlength="80" required
                           autofocus
                           value="<?php /** @var Product $product */
                           echo $product->getTitle() ?>">
                </div>

                <label for="for-category" class="col-sm-3 control-label"><h6>Category</h6></label>
                <div class="col-sm-3">
                    <select class="form-control" name="categoryId" required>
                        <?php /** @var ProductToCategory $product_to_category */
                        $categoryController->categoryComponentRowGenerator($product_to_category->getCategoryId()) ?>
                    </select>
                </div>

                <label for="for-category" class="col-sm-3 control-label"><h6>Brand</h6></label>
                <div class="col-sm-3">
                    <select class="form-control" name="brandId" id="brandId" required>
                        <?php /** @var Product $product */
                        $brandController->brandOptionRowGenerator($product->getBrandId()) ?>
                    </select>
                </div>

                <label for="about" class="col-sm-3 control-label"><h6>Description</h6></label>
                <div class="col-sm-9">
                    <textarea name="description" id="description" style="resize: none; height:100px"
                              class="form-control" maxlength="2048"
                              required><?php /** @var Product $product */
                        echo $product->getDescription() ?></textarea>
                </div>

                <label for="for-quantity" class="col-sm-3 control-label"><h6>Quantity</h6></label>
                <div class="col-sm-3">
                    <input type="number" class="form-control" name="quantity" id="quantity" required
                           value="<?php /** @var Product $product */
                           echo $product->getQuantity() ?>">
                </div>

                <label for="for-quantity" class="col-sm-3 control-label"><h6>Stock Number</h6></label>
                <div class="col-sm-3">
                    <input type="number" class="form-control" name="stockNumber" id="stockNumber" required
                           value="<?php /** @var Product $product */
                           echo $product->getStockNumber() ?>">
                </div>

                <label for="for-price" class="col-sm-3 control-label"><h6>Price</h6></label>
                <div class="col-sm-3">
                    <input type="text" pattern="([1-9][0-9]*|0)(\.[0-9]{2})?" class="form-control" name="price" id="price" required
                           value="<?php /** @var Product $product */
                           echo $product->getPrice() ?>">
                </div>

                <hr>
                <div class="col-sm-offset-3 col-sm-9 pb-3">
                    <button type="submit" class="btn btn-primary" name="submitUpdateProduct">Update</button>
                </div>
            </form>
        </div>
</div>