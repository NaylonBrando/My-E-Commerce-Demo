<?php session_start(); ?>
<?php include('dbcon.php'); ?>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="style.css">

</head>
<body>
<div class="form-wrapper">

    <form action="#" method="post">
        <h3 style="text-align:center">Kayıt Ol</h3>

        <div class="form-item">
            <input type="email" name="email" required="required" placeholder="E Posta" autofocus required></input>
        </div>

        <div class="form-item">
            <input type="text" name="firstname" required="required" placeholder="İsim" autofocus required></input>
        </div>

        <div class="form-item">
            <input type="text" name="lastname" required="required" placeholder="Soyisim" autofocus required></input>
        </div>

        <div class="form-item">
            <input type="password" name="pass" required="required" placeholder="Şifre" required></input>
        </div>

        <div class="form-item">
            <input type="password" name="corfimPass" required="required" placeholder="Şifre Tekrar" required></input>
        </div>

        <div class="button-panel">
            <input type="submit" class="button" title="Register" name="register" value="Kayıt"></input>
        </div>
    </form>

    <?php
    if (isset($_POST['register'])) {
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $firstname = mysqli_real_escape_string($con, $_POST['firstname']);
        $lastname = mysqli_real_escape_string($con, $_POST['lastname']);
        $password = mysqli_real_escape_string($con, $_POST['pass']);
        $confirmPassword = mysqli_real_escape_string($con, $_POST['corfimPass']);

        if (strcmp($password, $confirmPassword) == 0) {

            $userCheckQuery = mysqli_query($con, "SELECT * FROM users WHERE email='$email'");
            $num_row = mysqli_num_rows($userCheckQuery);
            if ($num_row > 0) {
                echo 'Bu e posta üzerine kullanıcı zaten mevcut';

            } else {
                $hashedPassword = md5($password);
                $registerQuery = mysqli_query($con, "INSERT INTO users (
                   email, first_name, last_name, password) 
                   VALUES('$email', '$firstname','$lastname','$hashedPassword')");

                $userGetQuery = mysqli_query($con, "SELECT * FROM users WHERE email='$email'");
                $row = mysqli_fetch_array($userGetQuery);
                $_SESSION['user_id'] = $row['id'];
                header('location:mainpage.php');
            }
        } else
            echo "Passwords doesnt match!";
    }
    ?>
    <div class="">
        <p><a href="login.php" class="account">Back to Login Page</a></p>
    </div>

</div>

</body>
</html>