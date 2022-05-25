<?php

namespace src\controller;

use Router;
use src\entity\Cart;
use src\entity\CartItem;
use src\entity\Product;
use src\entity\User;
use src\repository\CartRepository;
use src\repository\ProductRepository;

class CartController extends AbstractController
{
    public function show($pageModulePath)
    {
        $pageModule = $pageModulePath;
        $templateFilePath = str_replace('cart', 'homepageTemplate', $pageModulePath);
        $title = 'Cart';
        $totalCartItems = $this->getCartItems();
        if ($totalCartItems == null) {
            $totalPrice = null;
        } else {
            $totalPrice = $totalCartItems['totalPrice'];
            unset($totalCartItems['totalPrice']);
        }
        require_once($templateFilePath);
    }

    public function getCartItems(): array|null
    {

        $totalCartItems = [];
        $totalPrice = 0;
        $imagePath ="";
        
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
                    if ($productWithImage == null) {
                        if (count($_SESSION['cart']) == 1) {
                            $str = '<h6>Cart is Empty!</h6>';
                        }
                        unset($_SESSION['cart'][$i]);
                    } else {
                        $product = $productWithImage->getProduct();
                        $images = $productWithImage->getImages();

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

                        $totalCartItems[] = ['product' => $product, 'quantity' => $cartItem['quantity'], 'imagePath' => $imagePath];
                        $totalPrice += $cartItem['quantity'] * $product->getPrice();
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

                    $totalCartItems[] = ['product' => $product, 'quantity' => $cartItem->getQuantity(), 'imagePath' => $imagePath];

                    for ($i = 0; $i < count($cartItems); $i++) {
                        $cartItem = $cartItems[$i];
                        if ($cartItem->getProduct()->getId() == $product->getId()) {
                            $totalPrice += $cartItem->getQuantity() * $product->getPrice();
                        }
                    }
                    $cart->setGrandTotal($totalPrice);
                    $em->persist($cart);
                    $em->flush();
                }
            } else {
                $totalCartItems = null;
            }

        } else {
            $totalCartItems = null;
        }
        if ($totalCartItems != null) {
            $totalCartItems['totalPrice'] = $totalPrice;
        }
        return $totalCartItems;
    }

    public function findCartByUserId(int $id): ?Cart
    {
        $em = $this->getEntityManager();
        /* @var $cartRepository CartRepository */
        $cartRepository = $em->getRepository(Cart::class);
        return $cartRepository->findCartByUserId($id);
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
                        $product = $productController->getById($_POST['productId']);

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
                        $product = $productController->getById($_POST['productId']);

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

    public function cartSessionToCartTable()
    {
        if (isset($_SESSION['cart'])) {
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
}