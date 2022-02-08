<?php include('dbcon.php'); ?>
<?php
$sql = "SELECT * FROM";
$con = mysqli_connect("localhost", "root", "", "ecommerce");

class CategoryDal
{
    public function GetAllCategories()
    {
        global $sql, $con;

        $result = mysqli_query($con, $sql . " categories");

        if (mysqli_num_rows($result) > 0) {
            return $result;
        }
    }

    public function GetCategoryById(int $id)
    {
        global $sql, $con;
        $newQuery = "SELECT * FROM categories WHERE id=" . $id;

        $result = mysqli_query($con, $newQuery);

        if (mysqli_num_rows($result) > 0) {
            return $result;
        }

    }

    public function DeleteCategoryById(int $id)
    {
        global $con;
        $deleteQuery = "DELETE from categories where id=" . $id;
        $result = mysqli_query($con, $deleteQuery);
        return $result;

    }
}

?>