<div class="container-fluid mt-50">
    <!-- Carousel -->
    <div id="carouselHero" class="carousel slide" data-bs-ride="carousel">

        <!-- Indicators/dots -->
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselHero" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#carouselHero" data-bs-slide-to="1"></button>
        </div>

        <!-- The slideshow/carousel -->
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="../image/slide1.jpg" class="d-block w-100">
            </div>
            <div class="carousel-item">
                <img src="../image/slide3.jpg" class="d-block w-100">
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

            use controller\ProductController;

            $productController = new ProductController();
            $productController->getLastAddedProductCardGeneratorWithLimit(4);
            ?>
        </div>
    </div>
