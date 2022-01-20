<?php include('header.php'); ?>
<?php include('productsdal.php'); ?>
<?php session_start(); ?>

<?php

function productCard($productname, $productprice, $productimg, $productid)
{

    $element = "
	<div class=\"col-md-3 pb-3\">
	<div class=\"card\">
	<img src=\"$productimg\" class=\"card-img-top\" alt=\"...\">
	<div class=\"card-body\">
	<h5 class=\"card-title\">$productname</h5>
	<h6> $productprice TL</h6>
	<a href=\"product_detail.php?product&p_id=$productid\" class=\"btn btn-primary\">İncele</a>
	</div>
	</div>
	</div>
	";

    return $element;
}

function productCards()
{

    $productsData = new ProductsDal();

    $result = $productsData->getAllProductsData();

    while ($row = mysqli_fetch_assoc($result)) {
        echo productCard($row["product_name"], $row["product_price"], $row["product_image"], $row["id"]);

    }


}

?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
            crossorigin="anonymous"></script>
    <title></title>
</head>
<body>
<div class="container-fluid">
    <div class="row text-center">
        <div class="col-md-2">
            <h1>Categories</h1>
        </div>
        <div class="col-md-10 col-12 ">
            <div class="row mt-2">
                <?php

                productCards();


                ?>
            </div>
        </div>
    </div>
</body>
</html>