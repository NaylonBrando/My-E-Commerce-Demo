<?php include('dbcon.php'); ?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Müsteri Giris Ekranı</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.1/examples/sign-in/">


    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <link href="signin.css" rel="stylesheet">

</head>
<body class="text-center">

<main class="form-signin">
    <form action="#" method="post">
        <img class="mb-4" src="ecommerce-customer.png" alt="" width="72" height="57">
        <h1 class="h3 mb-3 fw-normal">Müsteri Girisi</h1>

        <div class="form-floating">
            <input type="email" class="form-control" id="floatingInput" name="email" placeholder="name@example.com"
                   autofocus required>
            <label for="floatingInput">Email adresi</label>
        </div>
        <div class="form-floating">
            <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Password">
            <label for="floatingPassword">Şifre</label>
        </div>

        <button class="w-100 btn btn-lg btn-primary" type="submit" name="login">Giriş Yap</button>
        <p class="mt-5 mb-3 text-muted">&copy; 2022-2023</p>
    </form>

    <?php
    if (isset($_POST['login'])) {
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $password = mysqli_real_escape_string($con, $_POST['password']);
        $hashedPass = md5($password);
        $query = mysqli_query($con, "SELECT * FROM users WHERE  password='$hashedPass' and email='$email'");
        $row = mysqli_fetch_array($query);
        $num_row = mysqli_num_rows($query);

        if ($num_row > 0) {
            session_start();
            $_SESSION['user_id'] = $row['id'];
            header('location:mainpage.php');

        } else {
            echo 'Invalid Username and Password Combination';
        }
    }
    ?>
</main>


</body>
</html>
