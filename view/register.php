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

    <link href="../css/signin.css" rel="stylesheet">
</head>
<body class="text-center">
<main class="form-signin">
    <form action="check-register" method="post">
        <img class="mb-4" src="../image/ecommerce-customer.png" alt="" width="72" height="57">
        <h1 class="h3 mb-3 fw-normal">Create Account</h1>

        <div class="form-floating">
            <input type="email" class="form-control" id="floatingInput" name="email" placeholder="name@example.com"
                   autofocus required>
            <label for="floatingInput">Email adress</label>
        </div>

        <div class="form-floating">
            <input type="text" class="form-control" id="namefor" name="firstName" placeholder="İsim" required>
            <label for="namefor">Name</label>
        </div>

        <div class="form-floating">
            <input type="text" class="form-control" id="surnamefor" name="lastName" placeholder="Soyisim" required>
            <label for="surnamefor">Surname</label>
        </div>

        <div class="form-floating">
            <input type="password" class="form-control" id="forpassword" name="password" placeholder="Şifre">
            <label for="forpassword">Password</label>
        </div>

        <div class="form-floating">
            <input type="password" class="form-control" id="forcorfimpassword" name="$confirmPassword"
                   placeholder="Şifre(Tekrar)">
            <label for="forcorfimpassword">Re-enter password</label>
        </div>


        <button class="w-100 btn btn-lg btn-primary" type="submit" name="register">Register</button>
        <?php
        if (isset($_SESSION['registererror'])) {
            echo '<div class="alert alert-warning mt-2" role="alert">' . $_SESSION['registererror'] . '</div>';
        }
        ?>
        <p class="mt-5 mb-3 text-muted">&copy; 2022-2023</p>
    </form>

    <div class="">
        <p><a href="login" class="account">Back to Login Page</a>
        <p><a href="/" class="account">Back to Main Page</a></p>

    </div>
</main>
</body>
</html>
