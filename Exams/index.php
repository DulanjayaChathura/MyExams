<?php  session_start();  ?>
<?php require_once('inc/connection.php'); ?>
<?php require_once('inc/functions.php'); ?>

<?php  

	if (isset($_POST['submit'])){

		$errors = array();

		if (!isset($_POST['index_num']) || strlen(trim($_POST['index_num']))<1) {
			$errors[]= 'Username is missing / Invalid';
		}

		if (!isset($_POST['password']) || strlen(trim($_POST['password']))<1) {
			$errors[]= 'Password is missing / Invalid';
		}

		if (empty($errors)) {

			$index_num = mysqli_real_escape_string($connection, $_POST['index_num']);
			$password = mysqli_real_escape_string($connection, $_POST['password']);
			$hashed_password = sha1($password);

			$query = "SELECT * FROM studentdetails WHERE index_num = '{$index_num}' AND password = '{$hashed_password}' LIMIT 1";

			$result_set = mysqli_query($connection, $query);

			verify_query($result_set);
				//query succesful
			if (mysqli_num_rows($result_set) == 1 ) {
						//valid user found
				$user = mysqli_fetch_assoc($result_set);
				$_SESSION['user_id'] = $user['index_num'];
				$_SESSION['full_name'] = $user['full_name'];

				//updating last login
				$query = "UPDATE studentdetails SET last_login = NOW() ";
				$query .= "WHERE index_num = '{$_SESSION['user_id']}' LIMIT 1";

				$result_set = mysqli_query($connection, $query);

				verify_query($result_set);


				header('Location: users.php');
					
			}else{
				$errors[] = 'Invalid Username / Password';
			}

		}

	}

?>




<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Log In - User Managment System</title>
	<link rel="stylesheet" href="css/main.css">
</head>
<body>

	<header>
		<div class="appname">Student Managment System</div>
		<div class="loggedin">Welcome:</div>
	</header>

	<div class="header clearfix">
			<div class="header-image clearfix">
				<img src="img/University_of_Moratuwa_logo.png">
			</div><!-- header-image  -->
			<div class="logo">
				<h1>university of moratuwa</h1>
				<p>Exam Division</p>
			</div><!-- logo -->
	</div><!-- header -->

	<div class="login">
		
		<form action="index.php" method = 'post'>
			
			<fieldset>
				<legend> <h1>Log In</h1></legend>

				<?php  

					if (isset($errors) && !empty($errors) ) {
						echo "<p class='error'>Invalid Username / password</p>";
					}

				?>		
				<?php 

					if (isset($_GET['logout'])) {
						echo "<p class='info'>You have succesfully logged out from system </p>";
					}



				 ?>

				<p>
					<label for="">Username:</label>
					<input type="text" name="index_num" id="" placeholder = 'Index Number'>
				</p>

				<p>
					<label for="">Password:</label>
					<input type="password" name="password" placeholder = 'Password' id="">

				</p>

				<p>
					<button type="submit" name = 'submit'>Log In</button>

				</p>

			</fieldset>

		</form>



	</div> <!-- .login -->

</body>
</html>
<?php mysqli_close($connection); ?>