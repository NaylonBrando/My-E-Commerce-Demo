<?php session_start(); ?>
<?php include('dbcon.php'); ?>

<html>
<head>
	<link rel="stylesheet" type="text/css" href="style.css">

</head>
<body>
	<div class="form-wrapper">

		<form action="#" method="post">
			<h3 style="text-align:center">Login to Account</h3>

			<div class="form-item">
				<input type="email" name="email" required="required" placeholder="Username" autofocus required></input>
			</div>

			<div class="form-item">
				<input type="password" name="pass" required="required" placeholder="Password" required></input>
			</div>

			<div class="button-panel">
				<input type="submit" class="button" title="Log In" name="login" value="Login"></input>
			</div>
		</form>
		<?php
		if (isset($_POST['login']))
		{
			$username = mysqli_real_escape_string($con, $_POST['email']);
			$password = mysqli_real_escape_string($con, $_POST['pass']);
			$hashedPass = md5($password);
			$query 		= mysqli_query($con, "SELECT * FROM users WHERE  password='$hashedPass' and email='$username'");
			$row		= mysqli_fetch_array($query);
			$num_row 	= mysqli_num_rows($query);
			
			if ($num_row > 0) 
			{			
				$_SESSION['user_id']=$row['id'];
				header('location:mainpage.php');

			}
			else
			{
				echo 'Invalid Username and Password Combination';
			}
		}
		?>
		<div class="">
			<p><a href="register.php" class="account">Create New Account Here</a></p>
		</div>

	</div>

</body>
</html>