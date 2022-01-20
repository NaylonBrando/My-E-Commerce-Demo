<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

  <title>Navbar</title>
  <style>
        .navbar-nav.navbar-center {
            position: absolute;
            left: 50%;
            transform: translatex(-50%);
        }
    </style>
</head>
<body class="mb-3">
  <header>
      <nav class="navbar navbar-expand-sm navbar-dark bg-dark">
        <div class="container">
          <a class="navbar-brand text-success" href="mainpage.php"> E-Ticaret Demo </a>
          <button
            class="navbar-toggler"
            type="button"
            data-toggle="collapse"
            data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent"
            aria-expanded="false"
            aria-label="Toggle navigation"
          >
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="nav navbar-nav navbar-center">
              <li class="nav-item">
                <a class="nav-link" href="register.php"> Üye Ol </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="login.php"> Giriş Yap </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#"> Yetkili Girişi </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#"> Sepetim (0) </a>
              </li>
            </ul>
          </div>
        </div>
      </nav>
</body>
</html>