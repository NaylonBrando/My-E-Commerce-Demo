<?php

namespace controller;

use src\dto\ProductWithImageDto;
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
        }
        else{
            $pageModule = $pageModulePath;
            $templateFilePath = str_replace('cart', 'homepage', $pageModulePath);
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
                header("Location:/");
            } else {
                echo "<script>alert('Product is already added in the cart..!')</script>";
                echo "<script>window.location = 'index.php'</script>";
            }

        }

    }

    public function delete($productId)
    {
        $em = $this->getEntityManager();
        $cartRepository = $em->getRepository(Cart::class);
        $cart = $cartRepository->findOneBy(['productId' => $productId, 'userId' => $_SESSION['user_id']]);
        if ($cart != null) {
            $em->remove($cart);
            $em->flush();
            header("Location:/cart");
        } else {
            require_once($_SERVER['DOCUMENT_ROOT'] . '/view/404.php');
        }
    }

    public function cartItemRowGenerator()
    {

        $str = "";
        $match = false;
        $em = $this->getEntityManager();

        /** @var ProductRepository $productRepository */
        $productRepository = $em->getRepository(Product::class);
        /** @var ProductWithImageDto[] $productWithImages */
        $productWithImages = $productRepository->findProductsByCartUserId($_SESSION['user_id']);

        if ($productWithImages) {
            foreach ($productWithImages as $row) {
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
                $str .= self::cartItemRow($product->getId(), $imagePath, $product->getTitle(), $product->getPrice());
            }
        } else {
            $str = "<h6>Cart is Empty!</h6>";
        }

        echo $str;
    }

    public function cartItemRow($productId, $productImg, $productTitle, $productPrice): string
    {
        return "
               <div class=\"border rounded\">
                                    <div class=\"row bg-white\">
                                        <div class=\"col-md-3 pl-0\">
                                            <img src=$productImg alt=\"Image1\" class=\"img-fluid cartImg\">
                                        </div>
                                        <div class=\"col-md-6\">
                                            <h5 class=\"pt-2\">$productTitle</h5>
                                            <h5 class=\"pt-2\">$$productPrice</h5>
                                            <a class=\"btn btn-danger mx-2\"  href=\"/check-delete-product-from-cart/$productId\">Remove</a>
                                        </div>
                                        <div class=\"col-md-3 py-5\">
                                            <div>
                                                <button type=\"button\" class=\"btn bg-light border rounded-circle\"><i class=\"fas fa-minus\"></i></button>
                                                <input type=\"text\" value=\"1\" class=\"form-control w-25 d-inline\">
                                                <button type=\"button\" class=\"btn bg-light border rounded-circle\"><i class=\"fas fa-plus\"></i></button>
                                            </div>
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