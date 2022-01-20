<?php include('dbcon.php'); ?>
<?php
$sql = "SELECT * FROM products";
$con = mysqli_connect("localhost", "root", "", "ecommerce");

class ProductsDal
{
    public function getAllProductsData()
    {
        global $sql, $con;

        $result = mysqli_query($con, $sql);

        if (mysqli_num_rows($result) > 0) {
            return $result;
        }
    }

    public function getProductById(int $id){
        global $sql, $con;
        $newQuery = $sql . " WHERE id=". $id;

        $result = mysqli_query($con, $newQuery);

        if (mysqli_num_rows($result) > 0) {
            return $result;
        }

    }
}

?>