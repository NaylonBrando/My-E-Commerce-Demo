<nav class="navbar navbar-expand-sm navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand text-success" href="mainpage.php">E-Ticaret Demo</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
                aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="#"> Sepetim (0) </a>
                </li>
                <?php navbarCustomerDropDown(); ?>
            </ul>
        </div>
    </div>
</nav>

<?php

function navbarCustomerDropDown(){

    if (isset($_SESSION['user_id'])) {
        global $con;
        $userId = $_SESSION['user_id'];
        $userData = mysqli_query($con, "SELECT first_name, last_name FROM users WHERE id=$userId");
        if (mysqli_num_rows($userData) > 0) {
            $row = mysqli_fetch_assoc($userData);

            $userNameAndLastName = $row['first_name'] . " " . $row['last_name'];

            $element = "
        <li class=\"nav-item dropdown\">
                    <a class=\"nav-link navbar-customer-name\" href=\"#\" id=\"navbarDropdown\" role=\"button\" data-bs-toggle=\"dropdown\" aria-expanded=\"false\">
                        $userNameAndLastName
                    </a>
                    <ul class=\"dropdown-menu dropdown-menu-end dropdown-menu-dark\" aria-labelledby=\"navbarDropdown\">
                        <li><a class=\"dropdown-item\" href=\"#\">Hesabım</a></li>
                        <li><a class=\"dropdown-item\" href=\"logout.php\">Çıkış Yap</a></li>
                    </ul>
                </li>
        ";
            echo $element;
        }
    }
    else{
        $element="
    <li class=\"nav-item\">
                    <a class=\"nav-link\" href=\"customer-register.php\"> Üye Ol </a>
                </li>
                <li class=\"nav-item\">
                    <a class=\"nav-link\" href=\"customer-login.php\"> Giriş Yap </a>
                </li>
    ";
        echo $element;
    }
}

?>

