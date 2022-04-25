<?php

use controller\ProductController;

?>

<div class="container mb-50 mt-50">
    <div class="row">
        <div class="col-md-12">
            <h5><?php if (isset($categoryName)) {
                    echo $categoryName;
                } ?></h5>
            <div class="row">
                <?php
                if (isset($categoryName)) {
                    $parseUrl = Router::parse_url();
                    echo "<div class=\"dropdown text-end\">
                    <button type=\"button\" class=\"btn btn-primary dropdown-toggle\" data-bs-toggle=\"dropdown\">
                        Filter
                    </button>
                    <div class=\"dropdown-menu\">
                        <a class=\"dropdown-item\" href=\"$parseUrl?rate=desc\">Link 1</a>
                        <a class=\"dropdown-item\" href=\"$parseUrl?rate=asc\">Link 2</a>
                    </div>
                </div>";
                }
                ?>
            </div>
        </div>
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


<script>
    let dropDownItem = document.querySelectorAll('.dropdown-item')
    dropDownItem.forEach(item => {
        item.addEventListener('click', function () {
            this.closest('.dropdown').children[0].innerText = this.innerText
        })
    })
</script>

