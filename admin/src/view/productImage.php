<div class="container-fluid">
    <div class="row">
        <div class="col-md-11 col-sm-6">
            <h5><?php use admin\controller\ProductImageController;

                echo 'Id: ' . $id[1] ?></h5>
            <h5><?php echo $slug ?></h5>
        </div>
        <div class="col-md-1 col-sm-6">
            <a href="/admin/product/update/<?php
            echo $id[1]
            ?>">
                <button type="button" class="btn btn-info">Product Update</button>
            </a>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-3 col-sm-6">
            <form action="/admin/check-add-product-image/<?php echo $id[1] ?>" method="POST"
                  enctype="multipart/form-data"
                  multiple="multiple">
                <div class="mb-1">
                    <input type="file" name="images[]" multiple="multiple">
                </div>
                <button class="btn btn-primary btn-sm">Add Image</button>
            </form>
        </div>
        <div class="col-md-9 col-sm-6">
            <?php
            if (isset($_SESSION['imageUploadError'])) {
                echo '<div class="alert alert-warning" role="alert">';
                foreach ($_SESSION['imageUploadError'] as $row) {
                    echo $row . '<br>';
                }
                echo '</div>';
            }
            ?>
        </div>
    </div>
    <hr>
    <div class="row">
        <?php
        $productImageController = new ProductImageController();
        $productImageController->imageCards($id[1]);
        ?>
    </div>
    <hr>
</div>


