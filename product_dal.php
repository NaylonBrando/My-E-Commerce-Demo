<?php
include_once('dbcon.php');
$sql = "SELECT * FROM";
$con = mysqli_connect("localhost", "root", "", "ecommerce");

class ProductDal
{
    public function GetAllProducts()
    {
        global $sql, $con;

        $result = mysqli_query($con, $sql . " products");

        if (mysqli_num_rows($result) > 0) {
            return $result;
        }
    }

    public function GetProductById($id)
    {
        global $sql, $con;
        $newQuery = $sql . " products WHERE id=" . $id;

        $result = mysqli_query($con, $newQuery);

        if (mysqli_num_rows($result) > 0) {
            return $result;
        }

    }

    public function DeleteProductById(int $id)
    {
        global $sql, $con;
        $findQuery = $sql . " products WHERE id=" . $id;
        $result = mysqli_query($con, $findQuery);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $productImage = $row["product_image"];
            $deleteQuery = "DELETE from products where id=" . $id;
            if (mysqli_query($con, $deleteQuery)) {
//                Dosya crud islemi icin bir helper kurulmalı oradan buraya sınıf nesnesiyle kullanılmalı
                if ($productImage != "" || $productImage != null) {
                    $imageDirectory = "." . $productImage;
                    if(unlink($imageDirectory))
                        return true;

                }
                else
                    return true;

            }
        }
    }
}

?>