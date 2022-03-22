<?php

use src\entity\Brand;

$brandController = new BrandController();
?>

<div class="container pt-3">
    <section class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Update Brand</h3>
            <h6 class="">Current Brand Name: <?php
                /** @var Brand $brand */
                echo $brand->getName() ?>
            </h6>
        </div>
        <hr>
        <div class="panel-body">
            <form action='/admin/check-update-brand' method="post">
                <div>
                    <input value="<?php
                    /** @var Brand $brand */
                    echo $brand->getId()
                    ?>" type="hidden" class="form-control" name="brandId">
                </div>
                <label for="brandName" class="control-label">Brand Name</label>
                <div class="row">
                    <div class="col-md-9 col-sm-9" id="brandName">
                        <input value="<?php
                        /** @var Brand $brand */
                        echo $brand->getName()
                        ?>" type="text" class="form-control" name="brandName"
                               id="categoryName" maxlength="80" required autofocus>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <button type="submit" class="btn btn-primary" name="brand/update">Update</button>
                    </div>
                </div>
                <div class="col-md-9">
                    <?php
                    if (isset($_SESSION['brand_update_error'])) {
                        echo '<div class="alert alert-warning mt-2" role="alert">' . $_SESSION['brand_update_error'] . '</div>';
                    }
                    ?>
            </form>
        </div>
</div>
