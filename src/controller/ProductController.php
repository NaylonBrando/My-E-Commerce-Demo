<?php

namespace src\controller;

use src\dto\ProductWithImageDto;
use src\entity\Product;
use src\entity\ProductImage;
use src\repository\ProductRepository;

class ProductController extends AbstractController
{
    public function showProductCardPageWithCategoryFilter($pageModulePath, array $parameters)
    {
        $pageModule = $pageModulePath;
        $reviewController = new ReviewController();
        /** @var ProductRepository $productRepository */
        $productRepository = $this->entityManager->getRepository(Product::class);

        if (isset($parameters['categoryName'])) {
            $parameters['categoryName'] = str_replace('-', ' ', $parameters['categoryName']);
            $parameters['categoryName'] = ucfirst($parameters['categoryName']);
        }
        $categoryController = new CategoryController();
        $category = $categoryController->getByName($parameters['categoryName']);

        if ($category == null) {
            $templateFilePath = str_replace('productCard', '404', $pageModulePath);
            $title = '404 - Page not found';
        } else {
            $templateFilePath = str_replace('productCard', 'homepageTemplate', $pageModulePath);
            $title = $category->getName();
            $parameters['categoryId'] = $category->getId();
            $categoryParameters = $this->extractedParameters($parameters);

            $subCategories = $categoryController->getSubCategories($category->getId());
            if ($subCategories != null) {
                $categoryNameArray[] = $category->getName();
                foreach ($subCategories as $subCategory) {
                    $categoryNameArray[] = $subCategory->getName();
                }
                $productsWithImageDto = $this->getByCategoryName($categoryNameArray, $categoryParameters['pg'], $categoryParameters['rate'], $categoryParameters['price']);
                $countOfProducts = $productRepository->countProductsByCategoryName($categoryNameArray, true);

            } else {
                $productsWithImageDto = $this->getByCategoryName($categoryParameters['categoryName'], $categoryParameters['pg'], $categoryParameters['rate'], $categoryParameters['price']);
                $countOfProducts = $productRepository->countProductsByCategoryName($categoryParameters['categoryName'], true);
            }

            $paginationVariablesArray = ['pageNumber' => $categoryParameters['pg'], 'countOfProducts' => $countOfProducts, 'limit' => 8];

            foreach ($productsWithImageDto as $productWithImageDto) {
                $productAvgRate = $reviewController->getAvgReviewRateByProductId($productWithImageDto->getProduct()->getId());
                if ($productAvgRate != null) {
                    $avgRatingArray[] = ['productId' => $productAvgRate];
                }
            }
        }
        require_once($templateFilePath);
    }

    /**
     * @param array $parameters
     * @return array
     */
    public function extractedParameters(array $parameters): array
    {
        if (isset($parameters['pg'])) {
            (int)$parameters['pg'] == 0 ? 1 : $pageNumber = (int)$parameters['pg'];
        } else {
            $parameters['pg'] = 1;
        }

        if (isset($parameters['rate'])) {
            $parameters['rate'] = 'desc';
        } else {
            $parameters['rate'] = null;
        }

        if (isset($parameters['price'])) {
            if ($parameters['price'] == 'priceASC') {
                $parameters['price'] = 'asc';
            } elseif ($parameters['price'] == 'priceDESC') {
                $parameters['price'] = 'desc';
            } else {
                $parameters['price'] = 'asc';
            }
        } else {
            $parameters['price'] = null;
        }
        return $parameters;

    }

    /**
     * @param $categoryName
     * @param int $pageNumber
     * @param string|null $rate
     * @param string|null $price
     * @return ProductWithImageDto[]|null
     */
    public function getByCategoryName($categoryName, int $pageNumber, string $rate = null, string $price = null): array|null
    {
        $em = $this->getEntityManager();

        /** @var ProductRepository $productRepository */
        $productRepository = $em->getRepository(Product::class);

        $productsWithImageDto = $productRepository->findProductsByCategoryName($categoryName, $pageNumber, 8, $rate, $price);

        if ($productsWithImageDto != null) {
            return $productsWithImageDto;

        } else {
            return null;
        }

    }

    public function showProductCardPageWithSearchTerm($pageModulePath, array $parameters)
    {
        $pageModule = $pageModulePath;
        $productRepository = $this->entityManager->getRepository(Product::class);

        if (isset($parameters['searchTerm'])) {
            $parameters['searchTerm'] = str_replace('%20', ' ', $parameters['searchTerm']);
        }
        $searchTermParameters = $this->extractedParameters($parameters);

        $templateFilePath = str_replace('productCard', 'homepageTemplate', $pageModulePath);
        $title = 'Product';

        $productsWithImageDto = $this->getBySearchTerm($searchTermParameters['searchTerm'], $searchTermParameters['pg'], $searchTermParameters['rate'], $searchTermParameters['price']);
        $countOfProducts = $productRepository->countProductsBySearchTerm($parameters['searchTerm'], true);
        if($productsWithImageDto != null){
            $paginationVariablesArray = ['pageNumber' => $searchTermParameters['pg'], 'countOfProducts' => $countOfProducts, 'limit' => 8];
        }
        require_once($templateFilePath);
    }

    public function getBySearchTerm($searchTerm, $pageNumber, $rate = null, $price = null): array|null
    {
        $em = $this->getEntityManager();

        /** @var ProductRepository $productRepository */
        $productRepository = $em->getRepository(Product::class);

        $productsWithImageDto = $productRepository->findProductsBySearchTerm($pageNumber, 8, $searchTerm, $rate, $price);

        if ($productsWithImageDto) {
            return $productsWithImageDto;
        } else {
            return null;
        }

    }

    /**
     * @param $limit
     * @return Product[]|null
     */
    public function getLastAddedProductCardGeneratorWithLimit($limit): array|null
    {
        $em = $this->getEntityManager();
        /** @var ProductRepository $productRepository */
        $productRepository = $em->getRepository(Product::class);
        $productResult = $productRepository->getLastProductsByLimit($limit);
        if (count($productResult) > 0) {
            return $productResult;
        } else {
            return null;
        }

    }

    public function getById($id): Product|null
    {
        $em = $this->getEntityManager();
        return $em->find(Product::class, $id);
    }

    public function showProductPage($pageModulePath, $parameters)
    {
        $em = $this->getEntityManager();
        $reviewController = new ReviewController();
        $product = $em->getRepository(Product::class)->findOneBy(['slug' => $parameters[1], 'isActive' => 1]);
        $reviewsWithUserDto = $reviewController->getByProductId($product->getId());

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
        $productImages = $em->getRepository(ProductImage::class)->findBy(['productId' => $productId]);
        $str = '';
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