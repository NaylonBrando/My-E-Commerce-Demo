<?php

use admin\controller\CategoryController;
use src\entity\Category;

$categoryController = new CategoryController();
?>

<div class="container pt-3">
    <section class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Update Category</h3>
            <h6 class="">Current Category Name: <?php
                /** @var Category $category */
                echo $category->getName() ?>
            </h6>
        </div>
        <hr>
        <div class="panel-body">
            <form action='/admin/check-update-category' method="post">
                <div>
                    <input value="<?php
                    /** @var Category $category */
                    echo $category->getId()
                    ?>" type="hidden" class="form-control" name="categoryId">
                </div>
                <label for="brandName" class="control-label">Category Name</label>
                <div class="row">
                    <div class="col-sm-9" id="brandName">
                        <input value="<?php
                        /** @var Category $category */
                        echo $category->getName()
                        ?>" type="text" class="form-control" name="categoryName"
                               id="categoryName" maxlength="80" required autofocus>
                    </div>
                    <div class="col-3 col-sm-3">
                        <button type="submit" class="btn btn-primary" name="/brand/update">Update</button>
                    </div>
                </div>
            </form>
        </div>
</div>

