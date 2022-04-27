<?php

use controller\ProductController;

?>

<div class="container mb-50 mt-50">
    <div class="row">
        <div class="col-md-12">
            <form class="form-horizontal" id='sorting-options-form' action='' method='GET'>
            <div class="row">
                <div class="col-6 col-md-auto"></div>
                <div class="col-6 col-md">
                    <h5><?php if (isset($categoryParameters['categoryName'])) {
                            echo $categoryParameters['categoryName'];
                        } ?></h5>
                </div>
                <div class="col-6 col-md-auto">
                    <label for="sorting">
                        <select class="form-control" name="sorting" id="sorting" data-selector="sorting-option">
                            <option value="">Sort</option>
                            <option value="priceASC"> Lowest Price First</option>
                            <option value="priceDESC">Highest Price Fist</option>
                            <option value="rateDESC">Highest Rate First</option>
                        </select>
                    </label>
                </div>
            </div>
            </form>
        </div>
        <div class="col-md-12">
            <?php
            $productController = new ProductController();
            if (!isset($pageNumber)) {
                $pageNumber = 1;
            }
            if (isset($categoryParameters)) {
                $productController->productCardGeneratorWithCategory($categoryParameters['categoryName'], $categoryParameters['pg'], $categoryParameters['rate'], $categoryParameters['price']);
            } elseif (isset($searchTermParameters)) {
                $productController->productCardGeneratorWithSearchTerm($searchTermParameters['searchTerm'], $searchTermParameters['pg'], $searchTermParameters['rate'], $searchTermParameters['price']);
            }
            ?>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {
        $('#sorting').on('change', function () {
            
            if ($(this).val() === 'priceASC') {
                $(this).attr('name', 'price');
            }
            if ($(this).val() === 'priceDESC') {
                $(this).attr('name', 'price');
            }
            if ($(this).val() === 'rateDESC') {
                $(this).attr('name', 'rate');
            }
            $('#sorting-options-form').trigger('submit');
        });
    });
</script>

