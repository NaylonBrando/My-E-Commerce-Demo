<?php

namespace admin\controller;

use admin\helper\SlugGenerator;
use src\dto\ProductDetailDto;
use src\entity\Product;
use src\entity\ProductToCategory;
use src\repository\ProductRepository;

class ProductController extends AdminAbstractController
{
    public function show(string $pageModulePath, $parameters)
    {
        $pageModule = $pageModulePath;
        $templateFilePath = str_replace('product', 'adminPanelTemplate', $pageModulePath);
        $title = 'Product';

        if (isset($parameters['pg'])) {
            (int)$parameters['pg'] == 0 ? $pageNumber = 1 : $pageNumber = (int)$parameters['pg'];
        } else {
            $parameters['pg'] = 1;
        }
        $limit = 10;
        $productsWithDetail = $this->getProductsByLimit($parameters['pg'], $limit);
        $em = $this->getEntityManager();
        /** @var ProductRepository $productRepository */
        $productRepository = $em->getRepository(Product::class);
        $totalProducts = $productRepository->countProducts();

        require_once($templateFilePath);

    }

    /**
     * @param $pageNumber
     * @param $limit
     * @return Product[]|null
     */
    public function getProductsByLimit($pageNumber, $limit): ?array
    {
        $em = $this->getEntityManager();

        /* @var ProductRepository $productRepository */
        $productRepository = $em->getRepository(Product::class);

        $products = $productRepository->findProductsWithDetails($pageNumber, $limit);

        if ($products) {
            return $products;
        } else {
            return null;
        }

    }

    public function showProductSearch(string $pageModulePath, $parameters)
    {
        $pageModule = $pageModulePath;
        $templateFilePath = str_replace('product', 'adminPanelTemplate', $pageModulePath);
        $title = 'Product';

        if (isset($parameters['searchTerm'])) {
            $parameters['searchTerm'] = str_replace('%20', ' ', $parameters['searchTerm']);
        }
        if (isset($parameters['pg'])) {
            (int)$parameters['pg'] == 0 ? $pageNumber = 1 : $pageNumber = (int)$parameters['pg'];
        } else {
            $parameters['pg'] = 1;
        }
        $searchTermParameters = $parameters;

        $limit = 10;

        $em = $this->getEntityManager();
        /** @var ProductRepository $productRepository */
        $productRepository = $em->getRepository(Product::class);
        $productsWithDetail = $this->getBySearchTermAndLimit($parameters['searchTerm'], $parameters['pg'], $limit);
        $totalProducts = $productRepository->countProductsBySearchTerm($parameters['searchTerm']);

        require_once($templateFilePath);
    }

    public function showAdd(string $pageModulePath)
    {

        $pageModule = $pageModulePath;
        $templateFilePath = str_replace('addProduct', 'adminPanelTemplate', $pageModulePath);
        $title = 'Add Product';
        require_once($templateFilePath);

    }

    public function showUpdate(string $pageModulePath, $id)
    {
        $em = $this->getEntityManager();
        $product = $em->find(Product::class, $id[1]);
        $productToCategory = $em->getRepository(ProductToCategory::class)->findOneBy(['productId' => $id[1]]);
        $categoryId = $productToCategory->getCategoryId();

        if ($product) {
            $title = 'Update Product Slug: ' . $product->getSlug();
            $pageModule = $pageModulePath;
            $templateFilePath = str_replace('updateProduct', 'adminPanelTemplate', $pageModulePath);
        } else {
            $templateFilePath = str_replace('updateProduct', '404', $pageModulePath);
        }
        require_once($templateFilePath);
    }

    public function add()
    {

        $em = $this->getEntityManager();

        $product = new Product();
        $product->setTitle($_POST['title']);
        $product->setSlug(SlugGenerator::slugify($_POST['title']));
        $product->setPrice($_POST['price']);
        $product->setDescription($_POST['description']);
        $product->setQuantity($_POST['quantity']);
        $product->setBrandId($_POST['brandId']);
        $product->setStockNumber($_POST['stockNumber']);

        $em->persist($product);
        $em->flush();

        if ($product->getId()) {
            $productToCategoryController = new ProductToCategoryController();
            if ($productToCategoryController->add($product->getId(), $_POST['categoryId'])) {
                $productImageController = new ProductImageController();
                $productImageController->add($product->getId());
            }
        }
    }

    public function update()
    {
        if ($_POST['submitUpdateProduct'] == '') {
            $em = $this->getEntityManager();
            $product = $em->find(Product::class, $_POST['productId']);
            $product->setTitle($_POST['title']);
            $product->setSlug(SlugGenerator::slugify($_POST['title']));
            $product->setPrice($_POST['price']);
            $product->setDescription($_POST['description']);
            $product->setQuantity($_POST['quantity']);
            $product->setBrandId($_POST['brandId']);
            $product->setStockNumber($_POST['stockNumber']);

            $productToCategoryController = new ProductToCategoryController();
            if ($productToCategoryController->updateProductCategory($product->getId(), $_POST['categoryId'])) {
                $em = $this->getEntityManager();
                $em->persist($product);
                $em->flush();
                header('location: /admin/product/update/' . $_POST['productId']);
            }

        }

    }

    public function delete($id)
    {
        $em = $this->getEntityManager();
        $product = $em->find(Product::class, $id);
        if ($product) {
            $productToCategoryController = new ProductToCategoryController();
            if ($productToCategoryController->delete($id)) {
                $productImageController = new ProductImageController();
                $productImageController->deleteAll($id);
                $reviewController = new ReviewController();
                $reviewController->deleteReviewsByProductId($id);
                $em->remove($product);
                $em->flush();
                header('location: /admin/product');
            }
        } else {
            $page404 = '../admin/view/404.php';
            require_once($page404);
        }

    }

    /**
     * @param $searchTerm
     * @param $pageNumber
     * @param $limit
     * @return ProductDetailDto|null
     */
    public function getBySearchTermAndLimit($searchTerm, $pageNumber, $limit): ?array
    {
        $em = $this->getEntityManager();

        /** @var ProductRepository $productRepository */
        $productRepository = $em->getRepository(Product::class);
        $products = $productRepository->findProductsWithDetailsBySearchTerm($searchTerm, $pageNumber, $limit);

        if ($products) {
            return $products;
        } else {
            return null;
        }

    }
    
    public function setIsActiveTrue($id)
    {

        $em = $this->getEntityManager();
        $product = $em->find(Product::class, $id);

        if ($product) {
            $product->setIsActive(true);
            $em->persist($product);
            $em->flush();
        }
        header('Location:/admin/product');
    }

    public function setIsActiveFalse($id)
    {

        $em = $this->getEntityManager();
        $product = $em->find(Product::class, $id);

        if ($product) {
            $product->setIsActive(false);
            $em->persist($product);
            $em->flush();
        }
        header('Location:/admin/product');
    }
}