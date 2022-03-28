<nav class="navbar navbar-expand-sm navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand text-success" href="/">My ECommerce Demo</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
                aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="/cart"> Cart </a>
                </li>
                <?php navbarCustomerDropDown(); ?>
            </ul>
        </div>
    </div>
</nav>

<?php

function navbarCustomerDropDown()
{

    if (isset($_SESSION['user_id'], $_SESSION['user_name'], $_SESSION['user_last_name'])) {
        $userNameAndLastName = $_SESSION['user_name'] . " " . $_SESSION['user_last_name'];
        $element = "
        <li class=\"nav-item dropdown\">
                    <a class=\"nav-link navbar-customer-name\" href=\"#\" title=\"$userNameAndLastName\" id=\"navbarDropdown\" role=\"button\" data-bs-toggle=\"dropdown\" aria-expanded=\"false\">
                        $userNameAndLastName 
                    </a>
                    <ul class=\"dropdown-menu dropdown-menu-end dropdown-menu-dark\" aria-labelledby=\"navbarDropdown\">
                        <li><a class=\"dropdown-item\" href=\"profile\">My Account</a></li>
                        <li><a class=\"dropdown-item\" href=\"logout\">Log Out</a></li>
                    </ul>
                </li>
        ";
    } else {
        $element = "
    <li class=\"nav-item\">
                    <a class=\"nav-link\" href=\"register\"> Register </a>
                </li>
                <li class=\"nav-item\">
                    <a class=\"nav-link\" href=\"login\"> Sign In </a>
                </li>
    ";
    }
    echo $element;
}

?>

