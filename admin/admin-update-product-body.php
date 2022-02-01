<?php
if (!isset($_SESSION['admin_id'])) {
    header("location: admin-login.php");
}
include("../dbcon.php");
include("../product_dal.php");
include("../category_dal.php");

?>

<?php
if (isset($_GET['productId'])) {

    $productDal = new ProductDal();
    $resultProduct = $productDal->GetProductById($_GET['productId']);
    $row = mysqli_fetch_assoc($resultProduct);
    $product_id = $row["id"];
    $product_name = $row["product_name"];
    $product_price = $row["product_price"];
    $product_image = $row["product_image"];
    $product_quantity = $row["product_quantity"];
    $product_description = $row["product_description"];


    function productAvaibleCategories(){
        global $con, $product_id;
        $productAndCategoyJoinQuery="SELECT ca.category_name, ptc.product_id FROM categories AS ca 
    INNER JOIN product_to_categories as ptc on ca.id = ptc.category_id WHERE ptc.product_id = '$product_id'";
        $joinResult= mysqli_query($con,$productAndCategoyJoinQuery);

        while ($row = mysqli_fetch_assoc($joinResult)){
            echo '<h6>'. $row['category_name'] . '</h6>';
        }
    }



    function CategoryComponent($id, $categoryName)
    {
        $element = "
    <option name='category_id' value=\"$id\">$categoryName</option>
    ";

        return $element;
    }

    function CategoryComponents()
    {
        $categoryDal = new CategoryDal();
        $result = $categoryDal->GetAllCategories();

        if ($result != null) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo CategoryComponent($row["id"], $row["category_name"]);

            }
        }

    }

}
else{
    echo "<h2>Seçili Ürün Yok </h2>";
    header("refresh:0.5;url=admin-list-products.php");
}

?>

<div class="container pt-3">
    <section class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Ürün Güncelle</h3>
            <h6>Mevcut Ürün: Stok Kodu:<?php echo $product_id ?> -- Ürün Adı: <?php echo $product_name ?></h6> <br>
            <h6>Mevcut Kategorileri:</h6>
            <?php productAvaibleCategories(); ?>
        </div>
        <hr>
        <div class="panel-body">
            <form action='product-crud-operations.php' method="post" enctype="multipart/form-data">
                <div class="col-sm-9">
                    <input value="<?php echo $product_id ?>" type="hidden" class="form-control" name="id" id="for-product" required autofocus>
                </div>
                <label for="for-product" class="col-sm-3 control-label">İsim</label>
                <div class="col-sm-9">
                    <input value="<?php echo $product_name ?>" type="text" class="form-control" name="product_name" id="for-product" required autofocus>
                </div>

<!--                Burasi product_to_category tablosuna bagli-->
                <label for="for-category" class="col-sm-3 control-label">Kategori</label>
                <div class="col-sm-3">
                    <select class="form-control" name="category_id">
                        <?php CategoryComponents(); ?>
                    </select>
                </div>

                <label for="about" class="col-sm-3 control-label">Açıklama</label>
                <div class="col-sm-9">
                    <textarea name="product_description" class="form-control" required><?php echo $product_description ?></textarea>
                </div>

                <label for="for-quantity" class="col-sm-3 control-label">Stok Sayısı</label>
                <div class="col-sm-3">
                    <input type="text" value="<?php echo $product_quantity ?>" class="form-control" name="product_quantity" id="for-quantity" required>
                </div>

                <label for="for-price" class="col-sm-3 control-label">Fiyat</label>
                <div class="col-sm-3">
                    <input type="text" value="<?php echo $product_price ?>" class="form-control" name="product_price" id="for-price" required>
                </div>

                <div class="col-sm-3">
                    <input type="hidden" value="<?php echo $product_image ?>" class="form-control" name="product_image" required>
                </div>

                <label for="name" class="col-sm-3 control-label">Resim</label>
                <div class="col-sm-3">
                    <input type="file" name="image">
                </div>

                <hr>
                <div class="col-sm-offset-3 col-sm-9 pb-3">
                    <button type="submit" class="btn btn-primary" name="update-product">Güncelle</button>
                </div>
            </form>
        </div>
</div>