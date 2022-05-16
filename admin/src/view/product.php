<?php

use admin\controller\ProductController;
use src\dto\ProductDetailDto;

$productController = new ProductController();

/**
 * @param ProductDetailDto[]
 */
function productTableRowGenerator(array $productsWithDetail)
{
    $str = '';
    /** @var ProductDetailDto $row */
    foreach ($productsWithDetail as $row) {
        $str .= productTableRow(
            $row->getProduct()->getId(), $row->getProduct()->getStockNumber(), $row->getProduct()->getIsActive(),
            $row->getProduct()->getTitle(), $row->getProduct()->getCreatedAt()->format('d/m/Y H:i:s'),
            $row->getCategoryName(), $row->getBrandName(),
            $row->getProduct()->getQuantity(), $row->getProduct()->getPrice(), $row->getProduct()->getSlug());
    }
    echo $str;
}

function productTableRow($id, $stockNumber, $isActive, $title, $createdAt,
                         $category, $brand, $quantity, $price, $slug): string
{
    $status = '';
    if ($isActive == 1) {
        $isActive = 'Activated';
        $element = "
        <tr>
            <td class=\"miniCol\">$id</td>
            <td class=\"miniCol\">$stockNumber</td>
            <td class=\"miniCol\">$isActive</td>
            <td class=\"lgTitleCol\"><a href=\"/product/$slug\"><p class=\"title\" title=\"$title\">$title</p></a></td>
            <td class=\"miniCol\">$createdAt</td>
            <td class=\"miniCol\">$category</td>
            <td class=\"miniCol\">$brand</td>
            <td class=\"miniCol\">$quantity</td>
            <td class=\"miniCol\">$price</td>
            <td class=\"buttonsCol\"><a class=\"btn btn-warning btn-sm\" href=\"/admin/check-set-deactivate-product/$id\" role=\"button\">Set Deactive</a>
            <a class=\"btn btn-info btn-sm\" href=\"product/update/$id\" role=\"button\">Update</a>
            <a class=\"btn btn-danger btn-sm\" href=\"/admin/check-delete-product/$id\" role=\"button\" onclick=\"return confirm('Are you sure? for delete $title');\">Delete</a></td>
        </tr>
        ";
    } elseif ($isActive == 0) {
        $isActive = 'Deactivated';
        $element = "
        <tr>
            <td class=\"miniCol\">$id</td>
            <td class=\"miniCol\">$stockNumber</td>
            <td class=\"miniCol\">$isActive</td>
            <td class=\"lgTitleCol\"><p class=\"title\">$title</p></td>
            <td class=\"miniCol\">$createdAt</td>
            <td class=\"miniCol\">$category</td>
            <td class=\"miniCol\">$brand</td>
            <td class=\"miniCol\">$quantity</td>
            <td class=\"miniCol\">$price</td>
            <td class=\"buttonsCol\"><a class=\"btn btn-success btn-sm\" href=\"/admin/check-set-active-product/$id\" role=\"button\">Set Active</a>
            <a class=\"btn btn-info btn-sm\" href=\"product/image/$id\" role=\"button\">Update</a>
            <a class=\"btn btn-danger btn-sm\" href=\"/admin/check-delete-product/$id\" role=\"button\" onclick=\"return confirm('Are you sure?');\">Delete</a></td>
        </tr>
        ";
    }

    return $element;
}

function pagination($currentPageNumber, $countOfProduct, $limit): void
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
    $str = '<div class="mt-3"> <nav aria-label="Page navigation example">
                 <ul class="pagination justify-content-end">';
    if ($currentPageNumber > 1) {
        $newPage = $currentPageNumber - 1;
        $str .= '<li class="page-item"><a class="page-link" href="' . $url . $newPage . '"' . '>Geri</a></li>';
    } else {
        $str .= '<li class="page-item disabled"><a class="page-link" href="?pg=">Geri</a></li>';
    }
    for ($i = $currentPageNumber - $record; $i <= $currentPageNumber + $record; $i++) {
        if ($i == $currentPageNumber) {
            $str .= '<li class="page-item active"><a class="page-link" href="' . $url . $i . '"' . '>' . $i . '</a></li>';
        } else {
            if ($i > 0 and $i <= $pageCount) {
                $str .= '<li class="page-item"><a class="page-link" href="' . $url . $i . '"' . '>' . $i . '</a></li>';
            }
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

<script>
    function searchWithTermRouter() {
        let searchValue = document.getElementById("search").value;
        document.searchProduct.action = "/admin/product/search/" + searchValue;
    }
</script>

<div class="pt-3">
    <h2>Product</h2>
    <div class="row">
        <div class="col-md-7">
            <a class="btn btn-primary" href="/admin/product/add" role="button">Add Product</a>
        </div>
        <div class="col-md-5">
            <form name="searchProduct" class="form-inline justify-content-end" method="post"
                  onsubmit="searchWithTermRouter()">
                <div class="form-group mx-sm-3 mb-2">
                    <label for="search">
                        <input type="text" class="form-control" id="search" name="search" placeholder="Search" required>
                    </label>
                </div>
                <button type="submit" class="btn btn-primary mb-2">Search</button>
            </form>
        </div>
    </div>
    <a class="btn btn btn-success btn-sm" href="/admin/product" role="button">All Products</a>
    <div class="table-responsive mt-2">
        <table class="table table-striped table-sm text-center">
            <thead class="thead-light">
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Stock Number</th>
                <th scope="col">Status</th>
                <th scope="col">Title</th>
                <th scope="col">Created At</th>
                <th scope="col">Category</th>
                <th scope="col">Brand</th>
                <th scope="col">Quantity</th>
                <th scope="col">Price</th>
                <th scope="col">Action</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if (isset($productsWithDetail, $parameters, $totalProducts)) {
                productTableRowGenerator($productsWithDetail);
            } else {
                echo '<tr><td colspan="10">No Product</td></tr>';
            } ?>
            </tbody>
        </table>
        <?php
        if (isset($productsWithDetail, $parameters['pg'], $totalProducts, $limit)) {
            pagination($parameters['pg'], $totalProducts, $limit);
        }
        ?>
    </div>
</div>