<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register</title>


    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <link href="/src/css/signin.css" rel="stylesheet">
</head>
<body class="text-center">
<main class="form-signin">
    <form action="check-login" method="post">
        <img class="mb-4" src="../../image/ecommerce-customer.png" alt="" width="64" height="64">
        <h1 class="h3 mb-3 fw-normal">Sign In</h1>

        <div class="form-floating">
            <input type="email" class="form-control" id="floatingInput" name="email" placeholder="name@example.com"
                   autofocus required>
            <label for="floatingInput">Email adress</label>
        </div>
        <div class="form-floating">
            <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Password"
                   required>
            <label for="floatingPassword">Password</label>
        </div>

        <button class="w-100 btn btn-lg btn-primary" type="submit" name="login">Log In</button>

        <p class="mt-5 mb-3 text-muted">&copy; 2022-2023</p>

        <?php
        if (isset($_SESSION['login_error'])) {
            echo '<div class="alert alert-warning mt-2" role="alert">' . $_SESSION['login_error'] . '</div>';
        }
        ?>
        <div>
            <p><a href="register" class="account">Go to Create Account Page</a></p>
            <p><a href="/" class="account">Back to Main Page</a></p>

        </div>
    </form>
</main>
</body>