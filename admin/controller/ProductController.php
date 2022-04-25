<?php

namespace admin\controller;

use SlugGenerator;
use src\dto\ProductDetailDto;
use src\entity\Product;
use src\entity\ProductToCategory;
use src\repository\ProductRepository;


class ProductController extends AdminAbstractController
{


    public function show(string $pageModulePath)
    {

        $pageModule = $pageModulePath;
        $templateFilePath = str_replace('product', 'adminPanelTemplate', $pageModulePath);
        $title = "Product";
        require_once($templateFilePath);

    }

    public function showProductSearch(string $pageModulePath, $parameters)
    {
        $function = 'showProductSearch';
        $searchTerm = $parameters[1];
        $pageModule = $pageModulePath;
        $templateFilePath = str_replace('product', 'adminPanelTemplate', $pageModulePath);
        $title = "Product";
        require_once($templateFilePath);
    }

    public function showAdd(string $pageModulePath)
    {

        $pageModule = $pageModulePath;
        $templateFilePath = str_replace('addProduct', 'adminPanelTemplate', $pageModulePath);
        $title = "Add Product";
        require_once($templateFilePath);

    }

    public function showUpdate(string $pageModulePath, $id)
    {
        $em = $this->getEntityManager();
        $product = $em->find(Product::class, $id[1]);
        $product_to_category = $em->getRepository(ProductToCategory::class)->findOneBy(array('productId' => $id[1]));

        if ($product) {
            $title = "Update Product Slug: " . $product->getSlug();
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
        if ($_POST['submitUpdateProduct'] == "") {
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
            $page404 = "../admin/view/404.php";
            require_once($page404);
        }


    }

    public function getAll(): ?array
    {
        $em = $this->getEntityManager();
        /** @var Product[] $result */
        $result = $em->getRepository(Product::class)->findAll();
        if ($result) {
            return $result;
        }
        return null;
    }

    public function productTableRowGenerator($searchTerm = null)
    {
        $em = $this->getEntityManager();
        /* @var ProductRepository $productRepository */
        $productRepository = $em->getRepository(Product::class);
        if($searchTerm != null) {
            $products = $productRepository->findProductsWithDetailsBySearchTerm($searchTerm);
        }
        else{
            $products = $this->getAllWithDetails();
        }

        if (!$products) {
            echo "<h3>No products to list !</h3>";
        } else {
            $str = "";
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
    }

    public function getAllWithDetails(): array
    {
        $em = $this->getEntityManager();
        /** @var ProductRepository $er */
        $er = $em->getRepository(Product::class);
        return $er->findAllProductsWithDetails();

    }

    public function productTableRow($id, $stockNumber, $isActive, $title, $createdAt,
                                    $category, $brand, $quantity, $price, $slug): string
    {
        $status = '';

        if ($isActive == 1) {
            $isActive = "Activated";
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
            $isActive = "Deactivated";
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

    public function setIsActiveTrue($id)
    {

        $em = $this->getEntityManager();
        $product = $em->find(Product::class, $id);

        if ($product) {
            $product->setIsActive(true);
            $em->persist($product);
            $em->flush();
        }
        header("Location:/admin/product");
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
        header("Location:/admin/product");
    }

}