<?php

use src\controller\CartController;
use src\entity\Product;

$cartController = new CartController();

function cartItemRow($productId, $productImg, $title, $price, $productQuantity, $cartQuantity): string
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

function cartItemRowGenerator($cartItems)
{
    $cartItemRows = "";
    if ($cartItems != null) {
        foreach ($cartItems as $cartItem) {
            /* @var $product Product */
            $product = $cartItem['product'];
            $cartItemRows .= cartItemRow($product->getId(), $cartItem['imagePath'], $product->getTitle(), $product->getPrice(), $product->getQuantity(), $cartItem['quantity']);
        }
        echo $cartItemRows;
    }
}

?>
<div class="container-fluid mb-50 mt-50">
    <div class="row px-5">
        <div class="col-md-7">
            <div class="shopping-cart">
                <h5>My Cart</h5>
                <hr>
                <?php
                if (isset($totalCartItems)) {
                    cartItemRowGenerator($totalCartItems);
                }
                ?>
            </div>
        </div>
        <div class="col-md-1"></div>
        <div class="col-md-4  border rounded mt-5 bg-white h-25">
            <div class="pt-4">
                <h6>PRICE DETAILS</h6>
                <hr>
                <div class="row price-details">
                    <?php
                    if ((isset($totalPrice))) {
                        echo "<h6>Total Price : $totalPrice </h6> ";
                    } else {
                        echo '<h6>Cart is Empty</h6>';
                    }
                    ?>
                </div>
            </div>

        </div>
    </div>
</div>
