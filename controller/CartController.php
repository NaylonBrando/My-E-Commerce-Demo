<?php

namespace controller;

use Router;
use src\entity\Cart;
use src\entity\CartItem;
use src\entity\Product;
use src\entity\User;
use src\repository\CartRepository;
use src\repository\ProductRepository;

class CartController extends AbstractController
{

    private float $totalPrice = 0;

    public function getTotalPrice(): float
    {
        return $this->totalPrice;
    }

    public function show($pageModulePath)
    {
        $pageModule = $pageModulePath;
        $templateFilePath = str_replace('cart', 'homepageTemplate', $pageModulePath);
        $title = 'Cart';
        require_once($templateFilePath);
    }

    public function findCartByUserId(int $userId): ?Cart
    {
        $em = $this->getEntityManager();
        /* @var $cartRepository CartRepository */
        $cartRepository = $em->getRepository(Cart::class);
        return $cartRepository->findCartByUserId($userId);
    }

    public function add()
    {
        $em = $this->getEntityManager();
        $url = Router::parseReferer();

        if (!isset($_SESSION['user_id'])) {
            if (isset($_SESSION['cart'])) {
                $match = false;
                for ($i = 0; $i < count($_SESSION['cart']); $i++) {
                    $cartItem = $_SESSION['cart'][$i];
                    if ($cartItem['productId'] == $_POST['productId']) {
                        echo "<script>alert('Product is already added in the cart..!')</script>";
                        echo "<script>window.location = '$url'</script>";
                        $match = true;
                        break;
                    }
                }
                if (!$match) {
                    $product = $em->find(Product::class, $_POST['productId']);
                    if ($product) {
                        $count = count($_SESSION['cart']);
                        $_SESSION['cart'][$count] = ['productId' => $product->getId(), 'quantity' => 1];
                        echo "<script>alert('Product is added in the cart..!')</script>";

                    }
                }
            } else {
                $_SESSION['cart'] = [];
                $product = $em->find(Product::class, $_POST['productId']);
                if ($product) {
                    $_SESSION['cart'][0] = ['productId' => $product->getId(), 'quantity' => 1];
                }

            }
        } else {
            /* @var $cartRepository CartRepository */
            $cartRepository = $em->getRepository(Cart::class);
            $cart = $cartRepository->findCartByUserId($_SESSION['user_id']);
            if (!isset($cart)) {
                $user = $em->getRepository(User::class)->find($_SESSION['user_id']);
                $cart = new Cart();
                $cart->setUser($user);

                $product = $em->getRepository(Product::class)->find($_POST['productId']);
                $cartItem = new CartItem();
                $cartItem->setProduct($product);
                $cartItem->setQuantity(1);
                $cartItem->setCart($cart);

                $em->persist($cart);
                $em->flush();

                echo "<script>alert('Product added in the cart')</script>";

            } else {
                $cartItem = $cartRepository->findCartItemByCartIdandProductId($cart->getId(), $_POST['productId']);
                if (!isset($cartItem)) {
                    $product = $em->getRepository(Product::class)->find($_POST['productId']);
                    $cartItem = new CartItem();
                    $cartItem->setProduct($product);
                    $cartItem->setQuantity(1);
                    $cartItem->setCart($cart);

                    $em->persist($cartItem);
                    $em->flush();
                    echo "<script>alert('Product added in the cart')</script>";
                } else {
                    echo "<script>alert('Product is already added in the cart..!')</script>";
                }
            }
        }
        echo "<script>window.location = '$url'</script>";
    }

    public function delete()
    {
        if (isset($_POST['action']) && $_POST['action'] == 'delete') {
            if (isset($_SESSION['user_id'])) {
                $em = $this->getEntityManager();
                /* @var $cartRepository CartRepository */
                $cartRepository = $em->getRepository(Cart::class);

                $cart = $cartRepository->findCartByUserId($_SESSION['user_id']);
                $cartItems = $cart->getCartItem()->getValues();

                foreach ($cartItems as $cartItem) {
                    if ($cartItem->getProduct()->getId() == $_POST['productId']) {
                        $em->remove($cartItem);
                        $em->flush();
                        break;
                    }
                }

            } else {
                foreach ($_SESSION['cart'] as $key => $cartItem) {
                    if ($cartItem['productId'] == $_POST['productId']) {
                        unset($_SESSION['cart'][$key]);
                        break;
                    }
                }
            }
        }
        header('Location:/cart');
    }

    public function update()
    {

        if (isset($_POST['action']) && $_POST['action'] == 'update') {
            if (isset($_SESSION['user_id'])) {
                $em = $this->getEntityManager();
                /* @var $cartRepository CartRepository */
                $cartRepository = $em->getRepository(Cart::class);

                $cart = $cartRepository->findCartByUserId($_SESSION['user_id']);
                $cartItems = $cart->getCartItem()->getValues();

                foreach ($cartItems as $cartItem) {
                    if ($cartItem->getProduct()->getId() == $_POST['productId']) {
                        $productController = new ProductController();
                        $product = $productController->getProductById($_POST['productId']);

                        if ($_POST['quantity'] > $product->getQuantity()) {
                            $quantity = $_POST['quantity'];
                            $maxQuantity = $product->getQuantity();
                            echo "<script>alert('You cannot buy $quantity of this product, you can only buy $maxQuantity at most.')</script>";
                            echo "<script>window.location = '/cart'</script>";
                        } else {
                            $cartItem->setQuantity($_POST['quantity']);
                            $em->persist($cartItem);
                            $em->flush();
                            header('Location:/cart');
                        }
                        break;
                    }
                }
            } else {
                foreach ($_SESSION['cart'] as $key => $cartItem) {
                    if ($cartItem['productId'] == $_POST['productId']) {
                        $productController = new ProductController();
                        $product = $productController->getProductById($_POST['productId']);

                        if ($_POST['quantity'] > $product->getQuantity()) {
                            $quantity = $_POST['quantity'];
                            $maxQuantity = $product->getQuantity();
                            echo "<script>alert('You cannot buy $quantity of this product, you can only buy $maxQuantity at most.')</script>";
                            echo "<script>window.location = '/cart'</script>";
                        } else {
                            $_SESSION['cart'][$key]['quantity'] = $_POST['quantity'];
                            header('Location:/cart');
                        }
                        break;
                    }
                }

            }
        }
        header('Location:/cart');
    }

    public function cartSessionToCart()
    {
        if (isset($_SESSION['cart'])) {
            //cart session ile cart ı karsilastir, eger cart sessionda var ve cartda yoksa cart sessiondan carta ekle
            $em = $this->getEntityManager();
            /* @var $cartRepository CartRepository */
            $cartRepository = $em->getRepository(Cart::class);

            $cart = $cartRepository->findCartByUserId($_SESSION['user_id']);
            $cartItems = $cart->getCartItem()->getValues();

            foreach ($_SESSION['cart'] as $cartItem) {
                $flag = false;
                foreach ($cartItems as $cartItem2) {
                    if ($cartItem['productId'] == $cartItem2->getProduct()->getId()) {
                        $flag = true;
                        break;
                    }
                }
                if (!$flag) {
                    $product = $em->getRepository(Product::class)->find($cartItem['productId']);
                    $cartItem3 = new CartItem();
                    $cartItem3->setProduct($product);
                    $cartItem3->setQuantity($cartItem['quantity']);
                    $cartItem3->setCart($cart);

                    $em->persist($cartItem3);
                    $em->flush();
                }
            }
            unset($_SESSION['cart']);
        }
    }

    public function cartItemRowGenerator()
    {

        $str = '';
        $em = $this->getEntityManager();
        /** @var ProductRepository $productRepository */
        $productRepository = $em->getRepository(Product::class);
        $productImageController = new ProductImageController();

        if (!isset($_SESSION['user_id']) && isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {

            for ($i = 0; $i < count($_SESSION['cart']) + 1; $i++) {
                $match = false;
                if (isset($_SESSION['cart'][$i])) {
                    $cartItem = $_SESSION['cart'][$i];
                    $productWithImage = $productRepository->findProductById($cartItem['productId']);
<<<<<<< 47ca190ee13d92e6dd4a742ff4c0eabfaa4d1d4f
<<<<<<< 47ca190ee13d92e6dd4a742ff4c0eabfaa4d1d4f
                    if($productWithImage ==null){
                        if(count($_SESSION['cart']) == 1){
=======
                    if ($productWithImage == null) {
                        if (count($_SESSION['cart']) == 1) {
>>>>>>> Code clean up
                            $str = "<h6>Cart is Empty!</h6>";
=======
                    if ($productWithImage == null) {
                        if (count($_SESSION['cart']) == 1) {
                            $str = '<h6>Cart is Empty!</h6>';
>>>>>>> Refactor ve clean up code
                        }
                        unset($_SESSION['cart'][$i]);
                    } else {
                        $product = $productWithImage->getProduct();
                        $images = $productWithImage->getImages();
                        $totalPrice = $cartItem['quantity'] * $product->getPrice();
                        $this->setTotal($totalPrice);

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

                        $str .= self::cartItemRow($product->getId(), $imagePath, $product->getTitle(),
                            $product->getPrice(), $product->getQuantity(), $cartItem['quantity']);
                    }
                }
            }

        } elseif (isset($_SESSION['user_id'])) {
            /* @var $cartRepository CartRepository */
            $cartRepository = $em->getRepository(Cart::class);
            $cart = $cartRepository->findCartByUserId($_SESSION['user_id']);

            /** @var CartItem[] $cartItems */
            $cartItems = $cart->getCartItem()->getValues();
            if ($cartItems) {
                foreach ($cartItems as $cartItem) {
                    $match = false;

                    $product = $cartItem->getProduct();
                    $images = $productImageController->findImagesByProductId($product->getId());

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

                    $str .= self::cartItemRow($product->getId(), $imagePath, $product->getTitle(),
                        $product->getPrice(), $product->getQuantity(), $cartItem->getQuantity());

                    for ($i = 0; $i < count($cartItems); $i++) {
                        $cartItem = $cartItems[$i];
                        if ($cartItem->getProduct()->getId() == $product->getId()) {
                            $totalPrice = $cartItem->getQuantity() * $product->getPrice();
                        }
                    }
                    $this->setTotal($totalPrice);
                    $cart->setGrandTotal($totalPrice);
                    $em->persist($cart);
                    $em->flush();
                }
            } else {
                $str = '<h6>Cart is Empty!</h6>';
            }


        } else {
            $str = '<h6>Cart is Empty!</h6>';
        }

        echo $str;
    }

    private function setTotal(float $total): void
    {
        $this->totalPrice = $total + $this->totalPrice;
    }

    public function cartItemRow($productId, $productImg, $title, $price, $productQuantity, $cartQuantity): string
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
                                            <input type=\"hidden\" name=\"productId\" value=\"$productId\">
                                            <input type=\"hidden\" name=\"action\" value=\"delete\"/>
                                            <button type=\"submit\" class=\"btn btn-danger\">Remove Item</button>
                                        </form>
                                        </div>
                                        <div class=\"col-md-3 py-5\">
                                             <form action=\"/check-change-quantity-from-cart\" method=\"post\">
                                             <input type=\"hidden\" name=\"productId\" value=\"$productId\">
                                             <input type=\"hidden\" name=\"action\" value=\"update\">
                                             <input type=\"number\" value=\"$cartQuantity\" pattern=\"[1-9]+\" min=\"1\" max=\"$productQuantity\" name=\"quantity\" class=\"form-control\" onChange=\"this.form.submit()\">
                                            </form>
                                        </div>
                                    </div>
                                </div>
                ";
    }


}