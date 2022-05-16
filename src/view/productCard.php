<?php

use src\controller\CategoryController;
use src\controller\ProductController;

$categoryController = new CategoryController();
$productController = new ProductController();

?>
<?php
function sideBarRowGenerator(int $id)
{
    $categoryController = new CategoryController();

    $html = "<nav class=\"sidebar card mt-2\">
                <div class='card-header'>
                    <h6>Categories</h6>
                </div>
                    <ul class=\"nav flex-column\">";

    $category = $categoryController->getById($id);

    $parentCategory = $categoryController->getById($category->getParentId());
    if ($parentCategory) {
        $parentCategoryName = $parentCategory->getName();
        $parentHref = str_replace(' ', '-', $parentCategoryName);
        $parentHref = strtolower($parentHref);
        $html .= "<li class=\"nav-item\">
                     <a class=\"nav-link\" href=\"/category/$parentHref\">$parentCategoryName</a>
                </li>";
    }

    $subCategories = $categoryController->getSubCategories($category->getId());

    if ($subCategories != null) {
        foreach ($subCategories as $subCategory) {
            $categoryName = $subCategory->getName();
            $href = str_replace(' ', '-', $categoryName);
            $href = strtolower($href);
            $html .= "<li class=\"nav-item\">
                     <a class=\"nav-link\" href=\"/category/$href\">$categoryName</a>
                </li>";
        }
    }
    $html .= '</ul></nav>';
    echo $html;
}

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

function paginaton($currentPageNumber, $countOfProduct, $limit): void
{
    $url = $_SERVER['REQUEST_URI'];
    if (str_contains($url, '?')) {
        if (preg_match('/\?pg=\d+/', $url)) {
            $url = preg_replace('/\?pg=\d+/', '', $url);
            $url = $url . '?pg=';
        } elseif (preg_match('/&pg=\d+/', $url)) {
            $url = preg_replace('/&pg=\d+/', '', $url);
            $url = $url . '&pg=';
        } else {
            $url = $url . '&pg=';
        }

    } else {
        $url = $url . '?pg=';
    }

    $record = 2;
    $pageCount = ceil($countOfProduct / $limit);
    $str = '<div class="justify-content-end mt-3"> <nav aria-label="Page navigation example">
                 <ul class="pagination justify-content-center">';
    if ($currentPageNumber > 1) {
        $newPage = $currentPageNumber - 1;
        $str .= '<li class="page-item"><a class="page-link" href="' . $url . $newPage . '"' . '>Geri</a></li>';
    } else {
        $str .= '<li class="page-item disabled"><a class="page-link" href="?pg=">Geri</a></li>';
    }
    for ($i = $currentPageNumber - $record; $i <= $currentPageNumber + $record; $i++) {
        if ($i == $currentPageNumber) {
            $str .= '<li class="page-item active"><a class="page-link" href="' . $url . $i . '"' . '>' . $i . '</a></li>';
        } elseif ($i > 0 && $i <= $pageCount) {
            $str .= '<li class="page-item"><a class="page-link" href="' . $url . $i . '"' . '>' . $i . '</a></li>';
        }
    }
    if ($currentPageNumber < $pageCount) {
        $newPage = $currentPageNumber + 1;
        $str .= '<li class="page-item"><a class="page-link" href="' . $url . $newPage . '"' . '>İleri</a></li>';
    } else {
        $str .= '<li class="page-item disabled"><a class="page-link" href="#">İleri</a></li>';
    }
    $str .= '</ul></nav></div>';
    echo $str;
}

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
        <div class="row">
            <div class="col-md-2">
                <?php
                if (isset($categoryParameters)) {
                    sideBarRowGenerator($categoryParameters['categoryId']);
                }
                ?>
            </div>
            <div class="col-md-10">
                <?php
                if (isset($productsWithImageDto, $paginationVariablesArray)) {
                    if (isset($avgRatingArray)) {
                        productCardGenerator($productsWithImageDto, $avgRatingArray);

                    } else {
                        productCardGenerator($productsWithImageDto);
                    }
                    paginaton($paginationVariablesArray['pageNumber'], $paginationVariablesArray['countOfProducts'], $paginationVariablesArray['limit']);
                } else {
                    echo '<div class="row text-center"><h3>No products found</h3><a href="/">Back to Home</a></div>';
                }
                ?>
            </div>
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


