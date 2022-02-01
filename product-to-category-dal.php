<?php include('dbcon.php'); ?>
<?php
$sql = "SELECT * FROM";
$con = mysqli_connect("localhost", "root", "", "ecommerce");

class ProductToCategory
{
    public function getAllProductToCategories()
    {
        global $sql, $con;

        $result = mysqli_query($con, $sql . " product_to_categories");

        if (mysqli_num_rows($result) > 0) {
            return $result;
        }
    }

    public function getProductsByCategoryId(int $id)
    {
        global $sql, $con;
        $newQuery = $sql . " product_to_categories WHERE category_id=" . $id;

        $result = mysqli_query($con, $newQuery);

        if (mysqli_num_rows($result) > 0) {
            return $result;
        }

    }

    public function DeleteByProductId(int $id)
    {
        global $con;
        $deleteQuery = "DELETE from product_to_categories where product_id=" . $id;
        $result = mysqli_query($con, $deleteQuery);
        return $result;

    }
}

?>