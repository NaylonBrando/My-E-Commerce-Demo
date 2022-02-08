<?php
include_once('dbcon.php');
$sql = "SELECT * FROM";
$con = mysqli_connect("localhost", "root", "", "ecommerce");

class ProductDal
{
    public function getAllProducts($startLimit=NULL, $limit=NULL)
    {
        global $sql, $con;

        if($startLimit==null && $limit==null ){
            $result = mysqli_query($con, $sql . " products ORDER BY İD ASC");
            if (mysqli_num_rows($result) > 0) {
                return $result;
            }
        }
        else{
            $result = mysqli_query($con, $sql . " products ORDER BY product_name ASC LIMIT $startLimit,$limit");
            if (mysqli_num_rows($result) > 0) {
                return $result;
            }
        }

    }

    public function countProducts()
    {
        global $sql, $con;

        $result = mysqli_query($con, "SELECT COUNT(*) FROM products");
        $result= mysqli_fetch_row($result);
        return $result[0];

    }

    public  function  countProductWithCategoryFilter(array $array){
        global $con;
        $idString = "";
        $arrayLength = count($array);

        for ($x = 0; $x < $arrayLength; $x++) {
            $idString .= $array[$x];

            if ($x != $arrayLength - 1) {
                $idString .= ",";
            }
        }
        $idString = "(" . $idString . ")";

        $newQuery = "SELECT COUNT(*)
        FROM products as p INNER JOIN product_to_categories as ptc 
        on p.id = ptc.product_id WHERE ptc.category_id IN $idString";

        $result = mysqli_query($con, $newQuery);

        if (mysqli_num_rows($result) > 0) {
            $result= mysqli_fetch_row($result);
            return $result[0];
        }
    }

    public function getProductById($id)
    {
        global $sql, $con;
        $newQuery = $sql . " products WHERE id=" . $id;

        $result = mysqli_query($con, $newQuery);

        if (mysqli_num_rows($result) > 0) {
            return $result;
        }

    }


    public function getByCategoryId($id)
    {

        global $con;
        $newQuery = "SELECT p.id, p.product_name, p.product_image, p.product_price
        FROM products as p INNER JOIN product_to_categories as ptc 
        on p.id = ptc.product_id WHERE ptc.category_id=$id";

        $result = mysqli_query($con, $newQuery);

        if (mysqli_num_rows($result) > 0) {
            return $result;
        }

    }

    public function getByMultipleCategoryId(array $array, $startLimit = 0, $limit = 4)
    {
        global $con;
        $idString = "";
        $arrayLength = count($array);

        for ($x = 0; $x < $arrayLength; $x++) {
            $idString .= $array[$x];

            if ($x != $arrayLength - 1) {
                $idString .= ",";
            }
        }
        $idString = "(" . $idString . ")";

        $newQuery = "SELECT p.id, p.product_name, p.product_image, p.product_price
        FROM products as p INNER JOIN product_to_categories as ptc 
        on p.id = ptc.product_id WHERE ptc.category_id IN $idString ORDER BY p.product_name ASC LIMIT $startLimit,$limit";

        $result = mysqli_query($con, $newQuery);

        if (mysqli_num_rows($result) > 0) {
            return $result;
        }

    }

    public function deleteProductById(int $id)
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
                    if (unlink($imageDirectory))
                        return true;

                } else
                    return true;

            }
        }
    }
}

?>