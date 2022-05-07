<?php

use controller\CartController;

$cartController = new CartController()
?>
<div class="container-fluid">
    <div class="row px-5">
        <div class="col-md-7">
            <div class="shopping-cart">
                <h5>My Cart</h5>
                <hr>
                <?php
                $cartController->cartItemRowGenerator();
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
                    $total = $cartController->getTotalPrice();
                    if ($total > 0) {
                        echo "<h6>Total Price : $$total </h6> ";
                    } else {
                        echo "<h6>Cart is Empty</h6>";
                    }
                    ?>
                </div>
            </div>

        </div>
    </div>
</div>
