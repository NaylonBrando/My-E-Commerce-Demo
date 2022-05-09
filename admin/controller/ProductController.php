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

        if (isset($parameters['pg'])) {
            (int)$parameters['pg'] == 0 ? $pageNumber = 1 : $pageNumber = (int)$parameters['pg'];
        } else {
            $parameters['pg'] = 1;
        }

        $pageModule = $pageModulePath;
        $templateFilePath = str_replace('product', 'adminPanelTemplate', $pageModulePath);
        $title = 'Product';
        require_once($templateFilePath);

    }

    public function showProductSearch(string $pageModulePath, $parameters)
    {
        if (isset($parameters['searchTerm'])) {
            $parameters['searchTerm'] = str_replace('%20', ' ', $parameters['searchTerm']);
        }
        if (isset($parameters['pg'])) {
            (int)$parameters['pg'] == 0 ? $pageNumber = 1 : $pageNumber = (int)$parameters['pg'];
        } else {
            $parameters['pg'] = 1;
        }
        $searchTermParameters = $parameters;

        $pageModule = $pageModulePath;
        $templateFilePath = str_replace('product', 'adminPanelTemplate', $pageModulePath);
        $title = 'Product';
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
        $product_to_category = $em->getRepository(ProductToCategory::class)->findOneBy(['productId' => $id[1]]);

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

    public function productTableRowGenerator($pageNumber)
    {
        $em = $this->getEntityManager();
        /* @var ProductRepository $productRepository */
        $productRepository = $em->getRepository(Product::class);
        $products = $productRepository->findProductsWithDetails($pageNumber, 8);
        $countOfProducts = $productRepository->countProducts();

        if (count($products) > 0) {
            $this->echoProductsExtracted($products);
            $this->paginator($pageNumber, $countOfProducts, 8);
        } else {
            echo '<h1>No products found</h1>';
        }

    }

    /**
     * @param array $products
     * @return void
     */
    public function echoProductsExtracted(array $products): void
    {
        $str = '';
        /** @var ProductDetailDto $row */
        foreach ($products as $row) {
            $str .= self::productTableRow(
                $row->getProduct()->getId(), $row->getProduct()->getStockNumber(), $row->getProduct()->getIsActive(),
                $row->getProduct()->getTitle(), $row->getProduct()->getCreatedAt()->format('d/m/Y H:i:s'),
                $row->getCategoryName(), $row->getBrandName(),
                $row->getProduct()->getQuantity(), $row->getProduct()->getPrice(), $row->getProduct()->getSlug());
        }
        echo $str;
    }

    public function productTableRow($id, $stockNumber, $isActive, $title, $createdAt,
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

    public function paginator($currentPageNumber, $countOfProduct, $limit): void
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

    public function productTableRowGeneratorWithSearchTerm($searchTerm, $pageNumber = 1)
    {
        $em = $this->getEntityManager();

        /** @var ProductRepository $productRepository */
        $productRepository = $em->getRepository(Product::class);
        $products = $productRepository->findProductsWithDetailsBySearchTerm($searchTerm, $pageNumber, 8);
        $countOfProducts = $productRepository->countProductsBySearchTerm($searchTerm);


        if (count($products) > 0) {
            $this->echoProductsExtracted($products);
            $this->paginator($pageNumber, $countOfProducts, 8);
        } else {
            echo '<h1>No products found</h1>';
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