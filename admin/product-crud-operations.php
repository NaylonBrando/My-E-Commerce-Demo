<?php
include('admin-auth-check.php');
include("../dbcon.php");
include('../product_dal.php');
include('../category_dal.php');
include('../product-to-category-dal.php');

?>

<?php

if (isset($_POST['add-product'])) {
    $productName = mysqli_real_escape_string($con, $_POST['product_name']);
    //category_id, products_to_category için
    $categoryId = mysqli_real_escape_string($con, $_POST['category_id']);
    $description = mysqli_real_escape_string($con, $_POST['product_description']);
    $quantity = mysqli_real_escape_string($con, $_POST['product_quantity']);
    $price = mysqli_real_escape_string($con, $_POST['product_price']);

    define('MB', 1048576);


    //business katmanı gibi burası
    //dataaccess katmanım service olacak
    $productCheckQuery = mysqli_query($con, "SELECT * FROM products WHERE product_name='$productName'");
    $num_row = mysqli_num_rows($productCheckQuery);
    if ($num_row > 0) {

        mysqli_close($con);
        echo "<h2> Böyle bir ürün zaten var! <h2>";
        header("refresh:0.5;url=admin-add-product.php");

    } else {

        $fileExtension = pathinfo($_FILES["product_image"]["name"], PATHINFO_EXTENSION);
        $allowTypes = array('jpg', 'png', 'jpeg');


        if ($_FILES['product_image']['size'] < 5 * MB && in_array($fileExtension, $allowTypes)) {
            $targetDir = "../upload/";
            $fileName = md5(time() . rand()) . "." . $fileExtension;
            $targetFilePath = $targetDir . $fileName;
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

            //Resim ekeleme basariliysa
            if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $targetFilePath)) {

                $newTargetPath = substr($targetFilePath, 1);
                $updateQueryResult = mysqli_query($con, "INSERT INTO products (
                   product_name, product_description, product_quantity, product_price, product_image) 
                   VALUES('$productName','$description','$quantity','$price','$newTargetPath')");

                if ($updateQueryResult) {

                    $productId = mysqli_insert_id($con);
                    $p_to_c_add_query = mysqli_query($con, "INSERT INTO product_to_categories (
                   product_id, category_id) 
                   VALUES('$productId','$categoryId')");
                    mysqli_close($con);
                    echo "<h2>Ürün resmi ile eklendi!</h2>";
                    header('refresh:0.5;url=admin-update-product.php?productId=' . $productId);


                }

            } else {

                mysqli_close($con);
                echo "<h2>Üzgünüm, resim yüklenirken bir hata oluştu! </h2>";
                header("refresh:1;url=admin-add-product.php");

            }
        } elseif ($_FILES['product_image']['size'] == 0) {
            $updateQueryResult = mysqli_query($con, "INSERT INTO products (
                   product_name, product_description, product_quantity, product_price) 
                   VALUES('$productName','$description','$quantity','$price')");

            if ($updateQueryResult) {
                $productId = mysqli_insert_id($con);
                $p_to_c_add_query = mysqli_query($con, "INSERT INTO product_to_categories (
                   product_id, category_id) 
                   VALUES('$productId','$categoryId')");
                mysqli_close($con);
                echo "<h2>Ürün resimsiz bir şekilde eklendi!</h2>";
                header('refresh:0.5;url=admin-update-product.php?productId=' . $productId);

            }
        } else {

            mysqli_close($con);
            echo "<h2>Sadece JPG, JPEG, PNG formatında olmalı ve boyutu 5mb'den düsük olmalıdır. <h2>";
            header("refresh:0.5;url=admin-add-product.php");

        }

    }
}


if (isset($_POST['update-product'])) {

    $productId = mysqli_real_escape_string($con, $_POST['id']);
    $productName = mysqli_real_escape_string($con, $_POST['product_name']);
    $categoryId = mysqli_real_escape_string($con, $_POST['category_id']);
    $description = mysqli_real_escape_string($con, $_POST['product_description']);
    $quantity = mysqli_real_escape_string($con, $_POST['product_quantity']);
    $price = mysqli_real_escape_string($con, $_POST['product_price']);
    $oldImage = mysqli_real_escape_string($con, $_POST['product_image']);

    define('MB', 1048576);

    $targetDir = "../upload/";
    $fileExtension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
    $fileName = md5(time() . rand()) . "." . $fileExtension;

    $targetFilePath = $targetDir . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
    $allowTypes = array('jpg', 'png', 'jpeg');


    //Buraya Resim için if else. Resim koyulacaksa eski resim silinsin, yeni resim eklensin. yeni resim basariliysa
    //sql güncelleme sorgusu eklensin

    if ($_FILES['image']['size'] == 0) {
        $updateQuery = "UPDATE products SET product_name='$productName', product_description='$description',
            product_quantity='$quantity', product_price='$price' WHERE id=$productId";
        $updateQueryResult = mysqli_query($con, $updateQuery);
        if ($updateQueryResult) {

            $updateProductCategory = "UPDATE product_to_categories SET category_id='$categoryId' WHERE product_id=$productId";
            mysqli_query($con, $updateProductCategory);

            mysqli_close($con);
            echo 'Ürün Güncellendi';
            header("refresh:1;url=admin-update-product.php?productId=$productId");
        }
    } elseif ($_FILES['image']['size'] > 5 * MB || !in_array($fileType, $allowTypes)) {
        echo 'Resim boyutu 5 megabyteden fazla olamaz veya türü jpg, png, jpeg değil.';
        header("refresh:1;url=admin-update-product.php?productId=$productId");
    } else {
        $newTargetPath = substr($targetFilePath, 1);

        $oldImageDirectory = "." . $oldImage;

        //Daha önce ürünün resmi yoksa
        if ($oldImageDirectory == "." || ($oldImageDirectory != "." && unlink($oldImageDirectory))) {
            move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath);
            $newTargetPath = substr($targetFilePath, 1);
            $updateQuery = "UPDATE products SET product_name='$productName', product_description='$description',
            product_quantity='$quantity', product_price='$price', product_image='$newTargetPath' WHERE id=$productId";
            $updateQueryResult = mysqli_query($con, $updateQuery);
            if ($updateQueryResult) {

                echo 'Ürün Güncellendi';
                header("refresh:1;url=admin-update-product.php?productId=$productId");
            }
        }
    }
}


if (isset($_POST["delete_product"])) {
    $productDal = new ProductDal();
    $resultDeleteProduct = $productDal->deleteProductById($_POST["delete_product"]);
    if ($resultDeleteProduct) {
        $productToCategoryDal = new ProductToCategory();
        $productToCategoryDal->DeleteByProductId($_POST["delete_product"]);
        if ($productToCategoryDal)
            echo "<h2> Ürün Silindi! <h2>";
        header("refresh:0.5;url=admin-list-products.php");

    }
}
?>

