<?php include('header.php'); ?>
<?php include('productsdal.php'); ?>


<?php
function productDetails(int $id)
{

    $productsData = new ProductsDal();
    $result = $productsData->getProductById($id);
    $row = mysqli_fetch_assoc($result);
    $product_id = $row["id"];
    $product_name = $row["product_name"];
    $product_price = $row["product_price"];
    $product_image = $row["product_image"];

//    $element = "
//	<div class=\"col-md-3 pb-3\">
//	<div class=\"card\">
//	<img src=\"$product_image\" class=\"card-img-top\" alt=\"...\">
//	<div class=\"card-body\">
//	<h5 class=\"card-title\">$product_name</h5>
//	<p class=\"card-text\">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
//	<h6> $product_price TL</h6>
//	</div>
//	</div>
//	</div>
//	";
//    echo $element;

    $element ="
<div class=\"card\">
        <div class=\"card-body\">
            <h3 class=\"card-title\">$product_name</h3>
            <div class=\"row\">
                <div class=\"col-lg-12 col-md-12 col-sm-12\">
                    <div class=\"white-box text-center\"><img src=\"$product_image\" class=\"img-responsive\"></div>
                </div>
                <div class=\"col-lg-7 col-md-7 col-sm-6\">
                    <h4 class=\"box-title mt-5\">Product description</h4>
                    <p>Lorem Ipsum available,but the ma</p>
                    <h2 class=\"mt-5\">
                        $product_price<small class=\"text-success\">(36%off)</small>
                    </h2>
                    <button class=\"btn btn-primary btn-rounded\">Sepete Ekle</button>
                    <h3 class=\"box-title mt-5\">Key Highlights</h3>
                    <ul class=\"list-unstyled\">
                        <li><i class=\"fa fa-check text-success\"></i>Sturdy structure</li>
                        <li><i class=\"fa fa-check text-success\"></i>Designed to foster easy portability</li>
                        <li><i class=\"fa fa-check text-success\"></i>Perfect furniture to flaunt your wonderful collectibles</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
";
    echo $element;
}




?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>Document</title>

    <style>
        body{
            background-color: #edf1f5;
        }
        .card {
            margin-bottom: 30px;
        }
        .card {
            position: relative;
            display: flex;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-color: #fff;
            background-clip: border-box;
            border: 0 solid transparent;
            border-radius: 0;
        }
        .card .card-subtitle {
            font-weight: 300;
            margin-bottom: 10px;
            color: #8898aa;
        }
        .table-product.table-striped tbody tr:nth-of-type(odd) {
            background-color: #f3f8fa!important
        }
        .table-product td{
            border-top: 0px solid #dee2e6 !important;
            color: #728299!important;
        }

    </style>
</head>
<body>
<div class="container">

    <div class="col-md-10 col-12 ">
        <div class="row mt-2">
            <?php

            if (isset($_GET['p_id'])){

                $product_id=$_GET['p_id'];
                productDetails($product_id);


            }
            else{
                echo "<h1 style='text-align: center' class='py-5'> Böyle bir ürün yok </h1>";
            }


            ?>
        </div>
    </div>
</div>



</body>
</html>

