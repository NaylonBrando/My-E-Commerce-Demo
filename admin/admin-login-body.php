<body class="text-center">
<main class="form-signin">
    <form action="#" method="post">
        <img class="mb-4" src="ecommerce-admin.png" alt="" width="72" height="57">
        <h1 class="h3 mb-3 fw-normal">Admin Giris</h1>

        <div class="form-floating">
            <input type="email" class="form-control" id="floatingInput" name="email" placeholder="isim@örnek.com"
                   autofocus required>
            <label for="floatingInput">Email adresi</label>
        </div>
        <div class="form-floating">
            <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Şifre">
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
        $query = mysqli_query($con, "SELECT * FROM admins WHERE  password='$hashedPass' and email='$email'");
        $row = mysqli_fetch_array($query);
        $num_row = mysqli_num_rows($query);

        if ($num_row > 0) {
            session_start();
            $_SESSION['admin_id'] = $row['id'];
            header('location:admin-list-products.php');

        } else {
            echo 'Eposta veya şifre yanlış.';
        }
    }
    ?>
</main>
</body>