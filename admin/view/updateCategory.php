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

                <div class="row">
                    <div class="col-sm-5">
                        <label for="parentId" class="control-label">Parent Category</label>
                        <select name="parentId" class="form-control">
                            <option selected value="0">None</option>
                            <?php $categoryController->categoryComponentRowGeneratorForUpdate($category->getId(), $category->getParentId()) ?>
                        </select>
                    </div>
                    <div class="col-sm-5">
                        <label for="categoryName" class="control-label">Category Name</label>
                        <input value="<?php
                        /** @var Category $category */
                        echo $category->getName()
                        ?>" type="text" class="form-control" name="categoryName"
                               id="categoryName" maxlength="80" required autofocus>
                    </div>
                    <div class="col-sm-2">
                        <br>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </form>
        </div>
</div>

