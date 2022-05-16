<?php

use src\controller\ProductController;

$productController = new ProductController();

function productCardGenerator(array $productResult, $avgRateArray = null): void
{
    $str = "<div class=\"row justify-content-center\">";
    foreach ($productResult as $row) {
        $match = false;

        $product = $row->getProduct();
        $images = $row->getImages();
        $imagePath = '';

        if ($images != null) {
            foreach ($images as $image) {
                if ($image->getIsThumbnail()) {
                    $imagePath = '../upload/' . $image->getPath();
                    $match = true;
                    break;
                }
            }
            if (!$match) {
                $imagePath = '../upload/' . $images[0]->getPath();
            }
        } else {
            $imagePath = '../image/productImageComingSoon.jpg';
        }
        $match = false;
        if ($avgRateArray != null) {
            foreach ($avgRateArray as $avgRate) {
                if ($avgRate['productId'] == $product->getId()) {
                    //remove the decimal part
                    $str .= productCard($product->getId(), $product->getTitle(), $product->getPrice(), $imagePath, $product->getSlug(), round($avgRate['avgRate'],2), $avgRate['rateCount']);
                    $match = true;
                }
            }
        }
        if (!$match) {
            $str .= productCard($product->getId(), $product->getTitle(), $product->getPrice(), $imagePath, $product->getSlug(), 0, 0);
        }
    }
    $str .= '</div>';
    echo $str;
}

function productCard($id, $title, $price, $img, $slug, $score = null, $totalReviews = null): string
{
    return "
        <div class=\"col-md-3 col-xs-3 mt-2\">
        <form action=\"/check-add-product-to-cart\" method=\"POST\">
            <div class=\"card\">
                <div class=\"card-body\">
                    <div class=\"card-img-actions\"> <img src=\"$img\" class=\"card-img img-fluid\" width=\"96\" height=\"350\" title=\"$slug\"> </div>
                </div>
                <div class=\"card-body bg-light text-center\">
                    <div class=\"mb-2\">
                        <h6 class=\"font-weight-semibold mb-2\"> <a href=\"/product/$slug\" class=\"text-default mb-2\" title=\"$title\" data-abc=\"true\">$title</a> </h6>
                    </div>
                    <h3 class=\"mb-0 font-weight-semibold\">$$price</h3>
                    <div>                
                           <i class=\"fa fa-star star\"></i>
                           <i>$score</i>
                    </div>
                    <div class=\"text-muted mb-3\">$totalReviews reviews</div>
                     <input type=\"hidden\" name=\"productId\" value=\"$id\">
                    <button type=\"submit\" name=\"addProductToCart\" class=\"btn bg-cart mt-3\" value=\"fromProductCard\"><i class=\"fa fa-cart-plus mr-2\"></i> Add to cart</button>
                </div>
            </div>
        </form>
        </div>
        ";
}

?>

<div class="mt-50">
    <!-- Carousel -->
    <div id="carouselHero" class="container carousel slide" data-bs-ride="carousel">

        <!-- Indicators/dots -->
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselHero" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#carouselHero" data-bs-slide-to="1"></button>
        </div>

        <!-- The slideshow/carousel -->
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="../../image/slide1.jpg" class="d-block w-100">
            </div>
            <div class="carousel-item">
                <img src="../../image/slide3.jpg" class="d-block w-100">
            </div>
        </div>

        <!-- Left and right controls/icons -->
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselHero" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselHero" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>
    <div class="container mb-50 mt-50">
        <div class="row">
            <h5>New Arrivals</h5>
            <?php
            if (isset($lastAddedProducts)) {
                productCardGenerator($lastAddedProducts);
            }
            ?>
        </div>
    </div>
