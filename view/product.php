<?php

use controller\ProductController;
use controller\ReviewController;
use src\entity\Product;

/** @var Product $product */

$productController = new ProductController();

$reviewController = new ReviewController();
?>

<div class="container mt-50 mb-50">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <?php $productController->imageSliderListItemGenerator($product->getId()) ?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls"
                                data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls"
                                data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-md-6">
            <form action="/check-add-product-to-cart" method="POST">
                <input type="hidden" name="productId" value="<?php echo $product->getId(); ?>">
                <div class="card px-2 py-2">
                    <div class="about"><h4><?php echo $product->getTitle() ?> </h4>
                        <h4><?php echo '$' . $product->getPrice() ?></h4>
                    </div>
                    <div class="buttons">
                        <button class="btn btn-outline-warning btn-long cart" type="submit" name="addProductToCart"
                                value="fromProductPage">Add to Cart
                        </button>
                    </div>
                    <hr>
                    <div class="product-description">
                        <div class="d-flex flex-row align-items-center"><i class="fa fa-calendar-check-o"></i> <span
                                    class="ml-1">Delivery from Turkey, 3-5 days</span></div>
                        <div class="mt-2"><span class="font-weight-bold">Description</span>
                            <p><?php echo $product->getDescription() ?></p>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row mb-4 mt-4">
        <div class="col-md-12">
            <div class="card px-2 py-2">
                <div class="row">
                    <div class="col-md-10">
                        <h6>Reviews</h6>
                        <!--                        <div class="d-flex flex-row">-->
                        <!--                            <div class="stars"> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> </div> <span class="ml-1 font-weight-bold">4.6</span>-->
                        <!--                        </div>-->
                    </div>
                    <div class="col-md-2">
                        <!-- Button to Open the Modal -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#exampleModal">
                            Add Review
                        </button>
                    </div>
                </div>
                <hr>
                <div class="comment-section">
                    <?php $reviewController->reviewRowGenerator($product->getId()) ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/view/productReviewModal.php'); ?>

