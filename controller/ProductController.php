<?php

namespace controller;

use src\entity\Category;
use src\entity\Product;
use src\entity\ProductImage;
use src\repository\ProductRepository;

class ProductController extends AbstractController
{

    public function showProductCardPageWithCategoryFilter($pageModulePath, array $parameters)
    {
        $pageModule = $pageModulePath;
        
        if (isset($parameters['categoryName'])) {
            $parameters['categoryName'] = str_replace('-', ' ', $parameters['categoryName']);
        }
        
        if (isset($parameters['pg'])) {
            (int)$parameters['pg'] == 0 ? $pageNumber = 1 : $pageNumber = (int)$parameters['pg'];
        } else {
            $parameters['pg'] = 1;
        }

        if (isset($parameters['rate'])) {
            if ($parameters['rate'] == 'asc' || $parameters['rate'] == 'desc') {
                $rate = $parameters['rate'];
            } else {
                $rate = 'asc';
            }
        } else {
            $parameters['rate'] = null;
        }

        if (isset($parameters['price'])) {
            if ($parameters['price'] == 'asc' || $parameters['price'] == 'desc') {
                $price = $parameters['price'];
            } else {
                $price = 'asc';
            }
        }
        else {
            $parameters['price'] = null;
        }

        $categoryParameters = $parameters;

        $categoryController = new CategoryController();
        $categoryEntity = $categoryController->getCategoryByName($parameters['categoryName']);

        if ($categoryEntity == null) {
            $templateFilePath = str_replace('productCard', '404', $pageModulePath);
            $title = "404 - Page not found";
        } else {
            $templateFilePath = str_replace('productCard', 'homepageTemplate', $pageModulePath);
            $title = $categoryEntity->getName();
        }
        require_once($templateFilePath);
    }

    public function showProductCardPageWithSearchTerm($pageModulePath, array $parameters)
    {
        $pageModule = $pageModulePath;
        
        if (isset($parameters['searchTerm'])) {
            $parameters['searchTerm'] = str_replace('%20', ' ', $parameters['searchTerm']);
        }

        if (isset($parameters['pg'])) {
            (int)$parameters['pg'] == 0 ? $pageNumber = 1 : $pageNumber = (int)$parameters['pg'];
        } else {
            $parameters['pg'] = 1;
        }

        if (isset($parameters['rate'])) {
            if ($parameters['rate'] == 'asc' || $parameters['rate'] == 'desc') {
                $rate = $parameters['rate'];
            } else {
                $rate = 'asc';
            }
        } else {
            $parameters['rate'] = null;
        }

        if (isset($parameters['price'])) {
            if ($parameters['price'] == 'asc' || $parameters['price'] == 'desc') {
                $price = $parameters['price'];
            } else {
                $price = 'asc';
            }
        }
        else {
            $parameters['price'] = null;
        }

        $searchTermParameters = $parameters;
        

        $templateFilePath = str_replace('productCard', 'homepageTemplate', $pageModulePath);
        $title = 'Product';

        require_once($templateFilePath);
    }

    public function getProductById($id): Product|null
    {
        $em = $this->getEntityManager();
        return $em->find(Product::class, $id);
    }

    public function getLastAddedProductCardGeneratorWithLimit($limit)
    {
        $em = $this->getEntityManager();
        /** @var ProductRepository $productRepository */
        $productRepository = $em->getRepository(Product::class);
        $productResult = $productRepository->getLastProductsByLimit($limit);
        if (count($productResult) > 0) {
            $this->extracted($productResult);
        }

    }

    /**
     * @param array $productResult
     * @return void
     */
    public function extracted(array $productResult): void
    {
        $str = "<div class=\"row justify-content-center\">";
        foreach ($productResult as $row) {
            $match = false;

            $product = $row->getProduct();
            $images = $row->getImages();
            $imagePath = "";

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
        $str .= "</div>";
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
                        <h6 class=\"font-weight-semibold mb-2\"> <a href=\"/product/$slug\" class=\"text-default mb-2\" title=\"$title\" data-abc=\"true\">$title</a> </h6>
                    </div>
                    <h3 class=\"mb-0 font-weight-semibold\">$$price</h3>
                     <input type=\"hidden\" name=\"productId\" value=\"$id\">
                    <button type=\"submit\" name=\"addProductToCart\" class=\"btn bg-cart mt-3\" value=\"fromProductCard\"><i class=\"fa fa-cart-plus mr-2\"></i> Add to cart</button>
                </div>
            </div>
        </form>
        </div>
        ";
    }

    public function productCardGeneratorWithSearchTerm($searchTerm, $pageNumber, $rate = null, $price = null)
    {
        $em = $this->getEntityManager();

        /** @var ProductRepository $productRepository */
        $productRepository = $em->getRepository(Product::class);

        $countOfProducts = $productRepository->countProductsBySearchTerm($searchTerm);
        $productResult = $productRepository->findProductsBySearchTerm($pageNumber, 4, $searchTerm, $rate, $price);

        if (count($productResult) > 0) {
            $this->extracted($productResult);
            $this->paginator($pageNumber, $countOfProducts);
        } else {
            echo '<div class="row text-center">';
            echo '<h3>No products found</h3>';
            echo '<a href="/">Back to Home</a>';
            echo '</div>';
        }

    }

    public function paginator($page, $countOfProduct): void
    {
        $limit = 4;
        $record = 2;
        $pageCount = ceil($countOfProduct / $limit);
        $str = '<div class="justify-content-end mt-3"> <nav aria-label="Page navigation example">
                 <ul class="pagination justify-content-center">';
        if ($page > 1) {
            $newPage = $page - 1;
            $str .= '<li class="page-item"><a class="page-link" href="?pg=' . $newPage . '"' . '>Geri</a></li>';
        } else {
            $str .= '<li class="page-item disabled"><a class="page-link" href="?pg=">Geri</a></li>';
        }
        for ($i = $page - $record; $i <= $page + $record; $i++) {
            if ($i == $page) {
                $str .= '<li class="page-item active"><a class="page-link" href="?pg=' . $i . '"' . '>' . $i . '</a></li>';
            } else {
                if ($i > 0 and $i <= $pageCount) {
                    $str .= '<li class="page-item"><a class="page-link" href="?pg=' . $i . '"' . '>' . $i . '</a></li>';
                }
            }
        }
        if ($page < $pageCount) {
            $newPage = $page + 1;
            $str .= '<li class="page-item"><a class="page-link" href="?pg=' . $newPage . '"' . '>İleri</a></li>';
        } else {
            $str .= '<li class="page-item disabled"><a class="page-link" href="#">İleri</a></li>';
        }
        $str .= '</ul></nav></div>';
        echo $str;
    }

    public function productCardGeneratorWithCategory($categoryName, $pageNumber, $rate = null, $price = null)
    {
        $em = $this->getEntityManager();

        /** @var ProductRepository $productRepository */
        $productRepository = $em->getRepository(Product::class);

        $categoryController = new CategoryController();
        $categoryEntity = $categoryController->getCategoryByName($categoryName);
        /** @var Category[] $categoryArray */
        $categoryArray = $categoryController->getSubCategories($categoryEntity->getId());

        if ($categoryArray) {
            $categoryArray[] = $categoryEntity;
            $categoryNameArray = [];
            foreach ($categoryArray as $category) {
                $categoryNameArray[] = $category->getName();
            }

            /** @var ProductRepository $productRepository */
            $productRepository = $em->getRepository(Product::class);
            $countOfProducts = $productRepository->countProductsByCategoryName($categoryNameArray);

            $productResult = $productRepository->findProductsByCategoryName($pageNumber, 4, $categoryNameArray, $rate, $price);
        } else {
            $countOfProducts = $productRepository->countProductsByCategoryName($categoryName);
            $productResult = $productRepository->findProductsByCategoryName($pageNumber, 4, $categoryName, $rate, $price);
        }

        if (count($productResult) > 0) {
            $this->extracted($productResult);
            $this->paginator($pageNumber, $countOfProducts);
        } else {
            echo '<div class="row text-center">';
            echo '<h3>No products found</h3>';
            echo '<a href="/">Back to Home</a>';
            echo '</div>';
        }

    }

    public function showProductPage($pageModulePath, $parameters)
    {
        $em = $this->getEntityManager();
        /** @var Product $product */
        $product = $em->getRepository(Product::class)->findOneBy(array('slug' => $parameters[1], 'isActive' => 1));

        if ($product) {
            $title = $product->getTitle();
            $pageModule = $pageModulePath;
            $templateFilePath = str_replace('product', 'homepageTemplate', $pageModulePath);
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
        } else $str .= self::imageSliderListItem('../image/' . 'productImageComingSoon.jpg', true);
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