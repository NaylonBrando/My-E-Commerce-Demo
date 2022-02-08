<?php
$page = empty($_GET['pg']) ? 1 : $_GET['pg'];

$limit = 4;
$startLimit = ($page * $limit) - $limit;

$pdDal = new ProductDal();
$totalRecord = $pdDal->countProducts();
$pageNumber = ceil($totalRecord / $limit);

$catId = 0;
if(isset($_GET['catId'])){ $catId=$_GET['catId']; }
$totalRecordWithCategoryFilter =0;
$pageNumberWithCategoryFilter =0;

function productCards()
{
    if (!isset($_GET['catId'])) {
        global $startLimit, $limit;
        $productsData = new ProductDal();

        $result = $productsData->getAllProducts($startLimit, $limit);

        while ($row = mysqli_fetch_assoc($result)) {
            echo productCard($row["product_name"], $row["product_price"], $row["product_image"], $row["id"]);
        }
    } else {
        categoryFilter($_GET['catId']);
    }

}

function productCard($productname, $productprice, $productimg, $productid)
{

    $element = "
	<div class=\"col-md-3 pb-3\">
	<div class=\"card\">
	<img src=\"$productimg\" class=\"card-img-top product_card_image\" alt=\"...\">
	<div class=\"card-body\">
	<h5 class=\"card-title\" title='$productname'>$productname</h5>
	<h6> $productprice TL</h6>
	<a href=\"product_detail.php?product&p_id=$productid\" class=\"btn btn-primary\">İncele</a>
	</div>
	</div>
	</div>
	";

    return $element;
}

function categoryFilter($id)
{
    global $con, $startLimit, $limit, $totalRecordWithCategoryFilter, $pageNumberWithCategoryFilter;
    $productsData = new ProductDal();

    $resultProduct = $productsData->getByMultipleCategoryId(getCategoryIdsByParentId($id), $startLimit, $limit);

    if ($resultProduct != null) {
        $totalRecordWithCategoryFilter=$productsData->countProductWithCategoryFilter(getCategoryIdsByParentId($id));
        $pageNumberWithCategoryFilter=ceil($totalRecordWithCategoryFilter / $limit);
        while ($row = mysqli_fetch_assoc($resultProduct)) {
            echo productCard($row["product_name"], $row["product_price"], $row["product_image"], $row["id"]);
        }
    }
}

$dizi = array();
function getCategoryIdsByParentId($parentId)
{
    global $dizi;
    $disaridan = $parentId;
    //Secili kategori ve varsa onun alt dallarındaki kategorileri çektikten sonra sıfırlama
    $dizi = array();
    return getCategoryIdsByParentIdRecursive($parentId, $disaridan);

}

function getCategoryIdsByParentIdRecursive($parentId, $disaridan)
{

    global $con, $dizi;
    array_push($dizi, $parentId);

    $result = mysqli_query($con, "SELECT * FROM categories WHERE parent_id = '$parentId'");

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            getCategoryIdsByParentIdRecursive($row['id'], $disaridan);
        }

    }
    if ($parentId == $disaridan) {
        return $dizi;
    }
}

?>


<div class="container-fluid">
    <div class="row text-center">
        <div class="col-md-3 col-sm-12 mt-2 text-right">
            <?php include('categories_side_menu.php'); ?>
        </div>
        <div class="col-md-9 col-sm-12 ">
            <div class="row mt-2">
                <?php productCards(); ?>
            </div>
            <div class="row justify-content-end">
                <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-end">
                        <?php
                        //Pagination with cateogires
                        if(isset($_GET['catId'])){

                            if ($page > 1) {
                                $newPage = $page - 1;
                                echo '<li class="page-item"><a class="page-link" href="mainpage.php?catId=' .$catId .'&pg='.$newPage . '"'.'>Geri</a></li>';
                            } else {
                                echo '<li class="page-item disabled"><a class="page-link" href="#">Geri</a></li>';
                            }
                            $record = 2;
                            for ($i = $page - $record; $i <= $page + $record; $i++) {
                                if ($i == $page) {
                                    echo '<li class="page-item active"><a class="page-link" href="mainpage.php?catId=' .$catId .'&pg=' . $i . '"' . '>' . $i . '</a></li>';
                                } else {
                                    if ($i > 0 and $i <= $pageNumberWithCategoryFilter) {
                                        echo '<li class="page-item"><a class="page-link" href="mainpage.php?catId=' .$catId .'&pg=' . $i . '"' . '>' . $i . '</a></li>';
                                    }
                                }
                            }

                            if ($page < $pageNumberWithCategoryFilter) {
                                $newPage = $page + 1;
                                echo '<li class="page-item"><a class="page-link" href="mainpage.php?catId=' .$catId .'&pg='.$newPage . '"' . '>İleri</a></li>';
                            } else {
                                echo '<li class="page-item disabled"><a class="page-link" href="#">İleri</a></li>';
                            }

                        }
                        //Pagination without cateogires
                        else{
                            if ($page > 1) {
                                $newPage = $page - 1;
                                echo '<li class="page-item"><a class="page-link" href="mainpage.php?pg=' . $newPage . '"' . '>Geri</a></li>';
                            } else {
                                echo '<li class="page-item disabled"><a class="page-link" href="#">Geri</a></li>';
                            }
                            $record = 2;
                            for ($i = $page - $record; $i <= $page + $record; $i++) {
                                if ($i == $page) {
                                    echo '<li class="page-item active"><a class="page-link" href="mainpage.php?pg=' . $i . '"' . '>' . $i . '</a></li>';
                                } else {
                                    if ($i > 0 and $i <= $pageNumber) {
                                        echo '<li class="page-item"><a class="page-link" href="mainpage.php?pg=' . $i . '"' . '>' . $i . '</a></li>';
                                    }
                                }
                            }

                            if ($page < $pageNumber) {
                                $newPage = $page + 1;
                                echo '<li class="page-item"><a class="page-link" href="mainpage.php?pg=' . $newPage . '"' . '>İleri</a></li>';
                            } else {
                                echo '<li class="page-item disabled"><a class="page-link" href="#">İleri</a></li>';
                            }
                        }
                        ?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>