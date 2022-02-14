<?php
function productDetails(int $id)
{

    $productsData = new ProductDal();
    $result = $productsData->getProductById($id);
    if ($result != null) {
        $row = mysqli_fetch_assoc($result);
        $product_id = $row["id"];
        $product_name = $row["product_name"];
        $product_price = $row["product_price"];
        $product_image = $row["product_image"];
        $product_description = $row["product_description"];

        $element = "
<div class=\"card\">
        <div class=\"card-body\">
            <h3 class=\"card-title\">$product_name</h3>
            <div class=\"row\">
                <div class=\"col-lg-12 col-md-12 col-sm-12\">
                    <div class=\"white-box text-center\"><img src=\"$product_image\" class=\"img-responsive\"></div>
                </div>
                <div class=\"col-lg-7 col-md-7 col-sm-6\">
                    <h4 class=\"box-title mt-5\">Ürün Açıklaması</h4>
                    <p>$product_description</p>
                    <h2 class=\"mt-5\">
                        $product_price TL <small class=\"text-success\">(36%indirim)</small>
                    </h2>
                    <button class=\"btn btn-primary btn-rounded\">Sepete Ekle</button>
                    
                </div>
            </div>
        </div>
    </div>
";
        echo $element;
    }
    else{
        header('location:mainpage.php');
    }
}

?>

<div class="container">
    <div class="col-md-12 col-12 ">
        <div class="row mt-2">
            <?php

            if (isset($_GET['p_id'])) {

                $product_id = $_GET['p_id'];
                productDetails($product_id);


            } else {
                echo "<h1 style='text-align: center' class='py-5'> Böyle bir ürün yok </h1>";
            }

            ?>
        </div>
    </div>
</div>