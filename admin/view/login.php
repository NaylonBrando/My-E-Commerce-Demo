<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">


    <link href="../../signin.css" rel="stylesheet">
</head>
<body class="text-center">
<main class="form-signin">
    <form action="check-login" method="post">
        <img class="mb-4" src="../../image/ecommerce-admin.png" alt="" width="72" height="57">
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

        <button class="w-100 btn btn-lg btn-primary" type="submit" name="login">Log In</button>
        <p class="mt-5 mb-3 text-muted">&copy; 2022-2023</p>

        <?php
        if (isset($_SESSION['admin_login_error'])) {
            echo '<div class="alert alert-warning mt-2" role="alert">' . $_SESSION['admin_login_error'] . '</div>';
        }
        ?>

    </form>
</main>
</body>
</html>