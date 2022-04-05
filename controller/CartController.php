<?php

namespace controller;

use src\entity\Cart;
use src\entity\Product;
use src\repository\ProductRepository;

class CartController extends AbstractController
{

    private float $total = 0;
    private int $quantity = 0;

    public function getTotal(): float
    {
        return $this->total;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function show($pageModulePath)
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location:/login");
        } else {
            $pageModule = $pageModulePath;
            $templateFilePath = str_replace('cart', 'homepageTemplate', $pageModulePath);
            $title = "Cart";
            require_once($templateFilePath);
        }
    }

    public function add()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location:/login");
        } else {

            $em = $this->getEntityManager();
            $cartRepository = $em->getRepository(Cart::class);
            $cart = $cartRepository->findOneBy(['productId' => $_POST['productId'], 'userId' => $_SESSION['user_id']]);
            if ($cart == null) {
                $cart = new Cart();
                $cart->setProductId($_POST['productId']);
                $cart->setUserId($_SESSION['user_id']);
                $cart->setQuantity(1);
                $em->persist($cart);
                $em->flush();

                $productController = new ProductController();
                $product = $productController->getProductById($_POST['productId']);
                $slug = $product->getSlug();

                if (strcmp($_POST['addProductToCart'], 'fromProductCard') == 0) {
                    echo "<script>alert('Product added your cart!')</script>";
                    echo "<script>window.location = '/'</script>";
                } elseif (strcmp($_POST['addProductToCart'], 'fromProductPage') == 0) {
                    echo "<script>alert('Product added your cart!')</script>";
                    echo "<script>window.location = '/product/$slug'</script>";
                }
            } else {
                echo "<script>alert('Product is already added in the cart..!')</script>";
                echo "<script>window.location = '/cart'</script>";
            }

        }

    }

    public function delete()
    {
        if (isset($_POST['action']) && $_POST['action'] == "delete") {
            $em = $this->getEntityManager();
            $cartItem = $em->find(Cart::class, $_POST['cartId']);
            if ($cartItem != null) {
                $em->remove($cartItem);
                $em->flush();
                header("Location:/cart");
            } else {
                require_once($_SERVER['DOCUMENT_ROOT'] . '/view/404.php');
            }
        }
    }

    public function update()
    {
        if (isset($_POST['action']) && $_POST['action'] == "update") {
            $em = $this->getEntityManager();
            $cart = $em->find(Cart::class, $_POST['cartId']);
            if ($cart != null) {
                $productController = new ProductController();
                $product = $productController->getProductById($cart->getProductId());

                if ($_POST['quantity'] > $product->getQuantity()) {
                    $quantity = $_POST['quantity'];
                    $maxQuantity = $product->getQuantity();
                    echo "<script>alert('You cannot buy $quantity of this product, you can only buy $maxQuantity at most.')</script>";
                    echo "<script>window.location = '/cart'</script>";
                } else {
                    $cart->setQuantity($_POST['quantity']);
                    $em->persist($cart);
                    $em->flush();
                    header("Location:/cart");
                }
            } else {
                require_once($_SERVER['DOCUMENT_ROOT'] . '/view/404.php');
            }
        }
    }

    public function cartItemRowGenerator()
    {

        $str = "";
        $em = $this->getEntityManager();

        /** @var ProductRepository $productRepository */
        $productRepository = $em->getRepository(Product::class);
        $productWithImages = $productRepository->findProductsByCartUserId($_SESSION['user_id']);

        if ($productWithImages) {
            foreach ($productWithImages as $row) {
                $match = false;

                $product = $row->getProduct();
                $images = $row->getImages();

                $cartRepository = $em->getRepository(Cart::class);
                /** @var Cart $cart */
                $cart = $cartRepository->findOneBy(array('productId' => $product->getId(), 'userId' => $_SESSION['user_id']));
                $this->total += $product->getPrice() * $cart->getQuantity();
                $this->quantity += $cart->getQuantity();

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
                $str .= self::cartItemRow($product->getId(), $imagePath, $product->getTitle(), $product->getPrice(), $product->getQuantity(), $cart->getId(), $cart->getQuantity());
            }
        } else {
            $str = "<h6>Cart is Empty!</h6>";
        }

        echo $str;
    }

    public function cartItemRow($productId, $productImg, $title, $price, $productQuantity, $cartId, $cartQuantity): string
    {
        return "
               <div class=\"border rounded\">
                                    <div class=\"row bg-white\">
                                        <div class=\"col-md-3 pl-0\">
                                            <img src=$productImg alt=\"Image1\" class=\"img-fluid cartImg\">
                                        </div>
                                        <div class=\"col-md-6\">
                                        <h5 class=\"pt-2\">$title</h5>
                                        <h5 class=\"pt-2\">$$price</h5>
                                        <form action=\"/check-delete-product-from-cart\" method=\"post\">
                                            <input type=\"hidden\" name=\"cartId\" value=\"$cartId\">
                                            <input type=\"hidden\" name=\"action\" value=\"delete\"/>
                                            <button type=\"submit\" class=\"btn btn-danger\">Remove Item</button>
                                        </form>
                                        </div>
                                        <div class=\"col-md-3 py-5\">
                                             <form action=\"/check-change-quantity-from-cart\" method=\"post\">
                                             <input type=\"hidden\" name=\"cartId\" value=\"$cartId\">
                                             <input type=\"hidden\" name=\"action\" value=\"update\">
                                             <input type=\"number\" value=\"$cartQuantity\" pattern=\"[1-9]+\" min=\"1\" max=\"$productQuantity\" name=\"quantity\" class=\"form-control\" onChange=\"this.form.submit()\">
                                            </form>
                                        </div>
                                    </div>
                                </div>
                ";
    }

    private function setTotal(float $total): void
    {
        $this->total = $total + $this->total;
    }


}