<?php
if (!isset($_SESSION['admin_id'])) {
    header("location: admin-login.php");
}
include("../dbcon.php");
include('../product_dal.php');

?>

<?php
function component($productname, $productprice, $productimage, $id, $productDescription, $productQuantity)
{
    $element = "
        <tr>
            <td>$id</td>
            <td><img class='' src=\"../$productimage\"></td>
            <td>$productname</td>
            <td>$productDescription</td>
            <td>$productQuantity</td>
            <td>$productprice TL</td>
            <td>    <a class=\"btn btn-primary\" href=\"admin-update-product.php?productId=$id\" role=\"button\">Güncelle</a></td>
            <td>    
    <form action='product-crud-operations.php' method=\"POST\">
        <button type=\"submit\" name=\"delete_product\" value=\"$id\" class=\"btn btn-danger\">Sil</button>
    </form>
            </td>
        </tr>
";
    return $element;
}

function components()
{

    $productsDal = new ProductDal();
    $result = $productsDal->getAllProducts();
    if ($result == "" || $result == null) {
        echo "<h3>Listelenecek Bir Ürün Yok!</h3>";
    } else {
        while ($row = mysqli_fetch_assoc($result)) {
            echo component($row["product_name"], $row["product_price"], $row["product_image"], $row["id"], $row["product_description"], $row["product_quantity"]);

        }
    }
}

if (isset($_POST["delete_product"])) {
    $productDal = new ProductDal();
    $result = $productDal->deleteProductById($_POST["delete_product"]);

}


?>
<div class="pt-3">
    <h2>Ürünler</h2>
    <a class="btn btn-primary" href="admin-add-product.php" role="button">Ürün Ekle</a>
    <div class="table-responsive">
        <table class="table table-striped table-sm">
            <thead>
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Ürün Resmi</th>
                <th scope="col">Ürün İsmi</th>
                <th scope="col">Açıklama</th>
                <th scope="col">Stok Sayısı</th>
                <th scope="col">Ürün Fiyatı</th>
            </tr>
            </thead>
            <tbody>
            <?php components(); ?>
            </tbody>
        </table>
    </div>
</div>