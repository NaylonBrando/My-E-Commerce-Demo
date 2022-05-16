<?php

use admin\controller\CategoryController;

$categoryController = new CategoryController();
?>
<script>
    function SetCategoryIdToUrl($functionName) {
        let id;
        let select;
        if ($functionName === 'delete') {
            let select = document.getElementById('deleteCategory');
            let id = select.options[select.selectedIndex].value;
            document.deleteCategoryForm.action = "check-delete-category/" + id;
        } else if ($functionName === 'update') {
            let select = document.getElementById('updateCategory');
            let id = select.options[select.selectedIndex].value;
            document.updateCategoryForm.action = "category/update/" + id;
        }
    }
</script>

<div class="container mt-2 mb-5 ">
    <h3 class="text-center">Category Operations</h3>
    <div class="row">
        <div class="col-md-6">
            <h4 class="text-center">Add Category</h4>
            <hr>
            <form action="add-category" method="post">
                <div class="mb-3">
                    <label for="category-name">Category Name</label>
                    <input type="text" class="form-control" name="addCategoryName" id="category-name" maxlength="80"
                           required>
                </div>

                <div class="mb-3">
                    <label ></label>
                    <label for="parent-category-name">
                        Parent Category
                        <select name="addCategoryParentId" class="form-control">
                            <option selected value="0">None</option>
                            <?php $categoryController->categoryComponentRowGenerator() ?>
                        </select>
                    </label>
                </div>

                <div class="mb-3 mt-2">
                    <button type="submit" name="addCategory" class="btn btn-primary btn-sm">Add</button>
                    <button type="reset" class="btn btn-danger btn-sm">Cancel</button>
                </div>
            </form>
            <br>
            <div>
                <?php
                if (isset($_SESSION['category_add_error'])) {
                    echo '<div class="alert alert-warning mb-2 mt-2" role="alert">' . $_SESSION['category_add_error'] . '</div>' . '<hr>';
                }
                ?>
            </div>
            <h4 class="text-center">Delete Category</h4>
            <hr>
            <form name="deleteCategoryForm" method="POST" onsubmit="SetCategoryIdToUrl('delete')">
                <div class="mb-3">
                    <label for="deleteCategory">Select Category</label>
                    <select name="deleteCategory" id="deleteCategory" class="form-control">
                        <?php $categoryController->categoryComponentRowGenerator() ?>
                    </select>
                </div>
                <div class="mb-3 mt-2">
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">
                        Delete
                    </button>
                </div>
            </form>

            <h4 class="text-center">Update Category</h4>
            <hr>
            <form name="updateCategoryForm" method="POST" onsubmit="SetCategoryIdToUrl('update')">
                <div class="mb-3">
                    <label>Select Category</label>
                    <select name="updateCategory" id="updateCategory" class="form-control">
                        <?php $categoryController->categoryComponentRowGenerator(); ?>
                    </select>
                </div>
                <div class="mb-3 mt-2">
                    <button class="btn btn-warning" type="submit">Go to Update Page</button>
                </div>
            </form>

        </div>
        <div class="col-md-6">
            <h4 class="text-center">Category Tree</h4>
            <hr>
            <ul>
                <?php echo $categoryController->categoryTree(); ?>
            </ul>

        </div>
    </div>
</div>