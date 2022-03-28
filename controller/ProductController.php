<?php

namespace controller;

use src\entity\Product;
use src\entity\ProductImage;

class ProductController extends AbstractController
{


    public function productCardGenerator()
    {

        $em = $this->getEntityManager();
        /** @var Product[] $products */
        $products = $em->getRepository(Product::class)->findAll();
        $str = "";
        foreach ($products as $product) {
            /** @var ProductImage[] $productImages */
            $productImages = $em->getRepository(ProductImage::class)->findBy(array('productId' => $product->getId()));
            if ($productImages) {
                $productImagePath = '/upload/' . $productImages[0]->getPath();
                foreach ($productImages as $image) {
                    if ($image->getIsThumbnail()) {
                        $productImagePath = '/upload/' . $image->getPath();

                    }
                }
                $str .= self::productCard($product->getId(), $product->getTitle(), $product->getPrice(), $productImagePath, $product->getSlug());
            } else {
                $str .= self::productCard($product->getId(), $product->getTitle(), $product->getPrice(), 'image/productImageComingSoon.jpg', $product->getSlug());
            }
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


}