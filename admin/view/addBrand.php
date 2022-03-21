<div class="container pt-3">
    <section class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Add Brand</h3>
        </div>
        <hr>
        <div class="panel-body">
            <form action="/admin/check-add-brand" method="post">
                <label for="for-product" class="col-sm-3 control-label">Brand Name</label>
                <div class="row">
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="brandName" id="for-product" maxlength="80"
                               required
                               autofocus autocomplete="off">
                    </div>
                    <hr>
                    <div class="col-3 col-sm-3">
                        <button type="submit" class="btn btn-primary" name="addBrand">Add</button>
                    </div>
                    <div class="col-9">
                        <?php
                        if (isset($_SESSION['brand_error'])) {
                            echo '<div class="alert alert-warning mt-2" role="alert">' . $_SESSION['brand_error'] . '</div>';
                        }
                        ?>
                    </div>
                </div>
            </form>
        </div>
</div>
