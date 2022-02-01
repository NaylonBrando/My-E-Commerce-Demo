<?php
if (!isset($_SESSION['admin_id'])) {
    header("location: admin-login.php");
}
include("../dbcon.php");
include('../category_dal.php');
?>

<?php
function component($id, $categoryName)
{
    $element = "
    <option selected name='category_id' value=\"$id\">$categoryName</option>
    ";
    return $element;
}

function components()
{
    $categoryDal = new CategoryDal();
    $result = $categoryDal->GetAllCategories();

    while ($row = mysqli_fetch_assoc($result)) {
        echo component($row["id"], $row["category_name"]);

    }
}

?>


<div class="container pt-3">
    <section class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Ürün Ekle</h3>

        </div>
        <hr>
        <div class="panel-body">
            <form action="product-crud-operations.php" method="post" enctype="multipart/form-data">
                <label for="for-product" class="col-sm-3 control-label">İsim</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="product_name" id="for-product" required autofocus>
                </div>

                <label for="for-category" class="col-sm-3 control-label">Kategori</label>
                <div class="col-sm-3">
                    <select class="form-control" name="category_id" required>
                        <?php components(); ?>
                    </select>
                </div>

                <label for="about" class="col-sm-3 control-label">Açıklama</label>
                <div class="col-sm-9">
                    <textarea name="product_description" class="form-control" required></textarea>
                </div>

                <label for="for-quantity" class="col-sm-3 control-label">Stok Sayısı</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control" name="product_quantity" id="for-quantity" required>
                </div>

                <label for="for-price" class="col-sm-3 control-label">Fiyat</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control" name="product_price" id="for-price" required>
                </div>

                <label for="name" class="col-sm-3 control-label">Resim</label>
                <div class="col-sm-3">
                    <input type="file" name="product_image">
                </div>

                <hr>
                <div class="col-sm-offset-3 col-sm-9 pb-3">
                    <button type="submit" class="btn btn-primary" name="add-product">Ekle</button>
                </div>
            </form>

        </div>
</div>
