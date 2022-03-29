<?php

namespace controller;

use src\dto\ProductWithImageDto;
use src\entity\Product;
use src\entity\ProductImage;
use src\repository\ProductRepository;

class ProductController extends AbstractController
{


    public function productCardGenerator($pageNumber)
    {
        $pageNumber = intval($pageNumber);
        $em = $this->getEntityManager();
        /** @var Product[] $products */
        $products = $em->getRepository(Product::class)->findAll();
        /** @var ProductRepository $productRepository */
        $productRepository = $em->getRepository(Product::class);
        /** @var ProductWithImageDto[] $productResult */
        $productResult = $productRepository->findProductsWithPaginator($pageNumber, 6);
        $str = "";

        foreach ($productResult as $row) {
            $match = false;

            $product = $row->getProduct();
            $images = $row->getImages();
            $imagePath ="";

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
            $str .= self::productCard($product->getId(), $product->getTitle(), $product->getPrice(), $imagePath, $product->getSlug());
        }
        echo $str;

    }

    public function productCard($id, $title, $price, $img, $slug): string
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
                        <h6 class=\"font-weight-semibold mb-2\"> <a href=\"$slug\" class=\"text-default mb-2\" title=\"$title\" data-abc=\"true\">$title</a> </h6>
                    </div>
                    <h3 class=\"mb-0 font-weight-semibold\">$$price</h3>
                     <input type=\"hidden\" name=\"productId\" value=\"$id\">
                    <button type=\"submit\" name=\"addProductToCart\" class=\"btn bg-cart mt-3\"><i class=\"fa fa-cart-plus mr-2\"></i> Add to cart</button>
                   
                </div>
            </div>
        </form>
        </div>
        ";
    }

    public function show($pageModulePath, $parameters)
    {
        $em = $this->getEntityManager();
        /** @var Product $product */
        $product = $em->getRepository(Product::class)->findOneBy(array('slug' => $parameters[1]));

        if ($product) {
            $title = $product->getTitle();
            $pageModule = $pageModulePath;
            $templateFilePath = str_replace('product', 'homepage', $pageModulePath);
        } else {
            $templateFilePath = str_replace('product', '404', $pageModulePath);
        }
        require_once($templateFilePath);
    }

    public function imageSliderListItemGenerator($productId)
    {
        $active = true;
        $em = $this->getEntityManager();
        /** @var ProductImage[] $productImages */
        $productImages = $em->getRepository(ProductImage::class)->findBy(array('productId' => $productId));
        $str = "";
        if ($productImages) {
            foreach ($productImages as $productImage) {
                $str .= self::imageSliderListItem('../upload/' . $productImage->getPath(), $active);
                $active = false;
            }
        } else {
            $str .= self::imageSliderListItem('../image/' . 'productImageComingSoon.jpg', $active);
        }
        echo $str;
    }

    public function imageSliderListItem($imagePath, $active): string
    {
        if ($active) {
            return "<div class=\"carousel-item active\"><img src=\"$imagePath\" class=\"d-block w-100\" alt=\"...\" style=\"height: 500px\"></div>";
        } else {
            return "<div class=\"carousel-item\"><img src=\"$imagePath\" class=\"d-block w-100\" alt=\"...\" style=\"height: 500px\"></div>";
        }

    }

    public function paginator($page)
    {
        $limit = 6;
        $record = 2;
        $em = $this->getEntityManager();
        $productCount = $em->getRepository(Product::class)->count(array());
        $pageCount = ceil($productCount / $limit);
        $str = '<div class="row justify-content-end mt-3"> <nav aria-label="Page navigation example">
                 <ul class="pagination justify-content-center">';
        if ($page > 1) {
            $newPage = $page - 1;
            $str .= '<li class="page-item"><a class="page-link" href="/?pg=' . $newPage . '"' . '>Geri</a></li>';
        } else {
            $str .= '<li class="page-item disabled"><a class="page-link" href="/?pg=">Geri</a></li>';
        }
        for ($i = $page - $record; $i <= $page + $record; $i++) {
            if ($i == $page) {
                $str .= '<li class="page-item active"><a class="page-link" href="/?pg=' . $i . '"' . '>' . $i . '</a></li>';
            } else {
                if ($i > 0 and $i <= $pageCount) {
                    $str .= '<li class="page-item"><a class="page-link" href="/?pg=' . $i . '"' . '>' . $i . '</a></li>';
                }
            }
        }
        if ($page < $pageCount) {
            $newPage = $page + 1;
            $str .= '<li class="page-item"><a class="page-link" href="/?pg=' . $newPage . '"' . '>İleri</a></li>';
        } else {
            $str .= '<li class="page-item disabled"><a class="page-link" href="#">İleri</a></li>';
        }
        $str .= '</ul></nav></div>';
        echo $str;
    }


}