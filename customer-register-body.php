<body class="text-center">
<main class="form-signin">
    <form action="#" method="post">
        <img class="mb-4" src="ecommerce-customer.png" alt="" width="72" height="57">
        <h1 class="h3 mb-3 fw-normal">Müsteri Kayıt Ekranı</h1>

        <div class="form-floating">
            <input type="email" class="form-control" id="floatingInput" name="email" placeholder="name@example.com"
                   autofocus required>
            <label for="floatingInput">Email adresi</label>
        </div>

        <div class="form-floating">
            <input type="text" class="form-control" id="namefor" name="firstname" placeholder="İsim" required>
            <label for="namefor">İsim</label>
        </div>

        <div class="form-floating">
            <input type="text" class="form-control" id="surnamefor" name="lastname" placeholder="Soyisim" required>
            <label for="surnamefor">Soyisim</label>
        </div>

        <div class="form-floating">
            <input type="password" class="form-control" id="forpassword" name="password" placeholder="Şifre">
            <label for="forpassword">Şifre</label>
        </div>

        <div class="form-floating">
            <input type="password" class="form-control" id="forcorfimpassword" name="corfimPassword"
                   placeholder="Şifre(Tekrar)">
            <label for="forcorfimpassword">Şifre Tekrar</label>
        </div>


        <button class="w-100 btn btn-lg btn-primary" type="submit" name="register">Kayıt Ol</button>
        <p class="mt-5 mb-3 text-muted">&copy; 2022-2023</p>
    </form>

    <?php
    if (isset($_POST['register'])) {
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $firstname = mysqli_real_escape_string($con, $_POST['firstname']);
        $lastname = mysqli_real_escape_string($con, $_POST['lastname']);
        $password = mysqli_real_escape_string($con, $_POST['password']);
        $confirmPassword = mysqli_real_escape_string($con, $_POST['corfimPassword']);

        //business katmanı gibi burası
        //dataaccess katmanım service olacak
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
            echo 'Sifreler eşleşmedi!';
    }
    ?>

    <div class="">
        <p><a href="customer-login.php" class="account">Giris Ekranına Dön</a></p>
    </div>
</main>
</body>