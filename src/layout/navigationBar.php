<nav class="navbar navbar-expand-sm navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand text-success" href="/">My ECommerce Demo</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
                aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <ul class="navbar-nav">
                <li class="nav-item dropdown has-megamenu">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                        Categories </a>
                    <div class="dropdown-menu megamenu" role="menu">
                        <div class="row g-3">
                            <?php

                            use src\controller\NavigationBarController;

                            $navigationBarController = new NavigationBarController();
                            $navigationBarController->selectCategoryRowGenerator();

                            ?>
                        </div><!-- end col-3 -->
                    </div><!-- end row -->
                </li>
        </div>

        <ul class="nav navbar-nav ms-auto mb-2 mb-lg-0">
            <li class="nav-item">
                <form name="searchProduct" id="searchProduct" method="post" onsubmit="productCardSearchProduct()">
                    <label for="search">
                        <input type="text" class="form-control" name="search" id="search" placeholder="Search">
                    </label>
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit" name="submit" id="submit">
                        Search
                    </button>
                </form>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/cart"> Cart </a>
            </li>
            <?php navbarCustomerDropDown(); ?>
        </ul>
    </div>
</nav>
<script>
    function productCardSearchProduct() {
        let searchValue = document.getElementById("search").value;
        if (searchValue === '' && searchValue.length === 0) {
            document.searchProduct.action = "/";
        } else {
            document.searchProduct.action = "/search/" + searchValue;
        }
    }
</script>


<?php
function navbarCustomerDropDown()
{

    if (isset($_SESSION['user_id'], $_SESSION['user_name'], $_SESSION['user_last_name'])) {
        $userNameAndLastName = $_SESSION['user_name'] . ' ' . $_SESSION['user_last_name'];
        $element = "
        <li class=\"nav-item dropdown\">
                    <a class=\"nav-link navbar-customer-name\" href=\"#\" title=\"$userNameAndLastName\" id=\"navbarDropdown\" role=\"button\" data-bs-toggle=\"dropdown\" aria-expanded=\"false\">
                        $userNameAndLastName 
                    </a>
                    <ul class=\"dropdown-menu dropdown-menu-end dropdown-menu-dark\" aria-labelledby=\"navbarDropdown\">
                        <li><a class=\"dropdown-item\" href=\"/profile\">My Account</a></li>
                        <li><a class=\"dropdown-item\" href=\"/logout\">Log Out</a></li>
                    </ul>
                </li>
        ";
    } else {
        $element = "
    <li class=\"nav-item\">
                    <a class=\"nav-link\" href=\"/register\"> Register </a>
                </li>
                <li class=\"nav-item\">
                    <a class=\"nav-link\" href=\"/login\"> Sign In </a>
                </li>
    ";
    }
    echo $element;
}

?>

