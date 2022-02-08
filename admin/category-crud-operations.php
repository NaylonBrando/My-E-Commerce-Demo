<?php
include('admin-auth-check.php');
include("../dbcon.php");
$con = mysqli_connect("localhost", "root", "", "ecommerce");

?>
<?php

if (isset($_POST['add-category'])) {
    $categoryName = mysqli_real_escape_string($con, $_POST['category_name']);
    $parentId = mysqli_real_escape_string($con, $_POST['parent_id']);

    $categoryCheckQuery = mysqli_query($con, "SELECT * FROM categories WHERE category_name='$categoryName'");
    $num_row = mysqli_num_rows($categoryCheckQuery);
    if ($num_row > 0) {

        mysqli_close($con);
        echo "Böyle bir kategori zaten var!";
        header("refresh:1;url=admin-list-categories.php");

    } else {
        $categoryAddQuery = mysqli_query($con, "INSERT INTO categories (
                   category_name, parent_id) 
                   VALUES('$categoryName', '$parentId')");

        if ($categoryAddQuery) {
            mysqli_close($con);
            echo "Kategori Başarılıyla Eklendi";
            header("refresh:1;url=admin-list-categories.php");

        } else {
            mysqli_close($con);
            echo "Kategori Eklenemedi!";
            header("refresh:1;url=admin-list-categories.php");

        }

    }
}


if (isset($_POST['delete-category'])) {
    $result = deleteCategory($_POST['delete-category-id']);
    if ($result)
        echo "Kategori Silindi";
    header("refresh:1;url=admin-list-categories.php");

}

if (isset($_POST['update-category'])) {
   global $con;
    $id = mysqli_real_escape_string($con,$_POST['id']);
    $categoryName = mysqli_real_escape_string($con, $_POST['category_name']);
    $parentId = mysqli_real_escape_string($con, $_POST['parent_id']);

    $updateQuery= "UPDATE categories SET category_name='$categoryName', parent_id='$parentId' WHERE id=$id";

    $result = mysqli_query($con,$updateQuery);

    if($result){
        mysqli_close($con);
        echo "Kategori Güncellendi";
        header("refresh:1;url=admin-list-categories.php");
    }
    else{
        mysqli_close($con);
        echo "Kategori Güncellenemedi!!";
        header("refresh:1;url=admin-list-categories.php");
    }



}



function deleteCategory($id)
{

    global $con;
    $deleteQuery = mysqli_query($con, "DELETE FROM categories WHERE id = '$id'");

    if ($deleteQuery) {

        $result = mysqli_query($con, "SELECT * FROM categories WHERE parent_id = '$id'");

        while ($row = mysqli_fetch_assoc($result)) {

            deleteCategory($row['id']);

        }

    }
    return true;
}

?>
