<?php  session_start();  ?>
<?php require_once('inc/connection.php'); ?>
<?php require_once('inc/functions.php'); ?>

<?php 
	
	//checking if a user is logged in 
	if (!isset($_SESSION['user_id'])) {
		header('Location: index.php');
	}
	
	$errors = array();

	$full_name = '';
	$index_num = '';
	$address = '';
	$nic = '';
	$faculty = '';
	$batch = '';
	$degreeorcourse = '';
	$password = '';

	if(isset($_GET['user_id'])){

		$user_id = mysqli_real_escape_string($connection, $_GET['user_id']);
		
		$query = "SELECT * FROM studentdetails WHERE index_num = '{$user_id}' LIMIT 1";

		$result_set = mysqli_query($connection, $query);

		if($result_set){
			if(mysqli_num_rows($result_set) == 1){
				//user found
				$result = mysqli_fetch_assoc($result_set);
				$full_name = $result['full_name'];
				$index_num = $result['index_num'];
				$address = $result['address'];
				$nic = $result['nic'];
				$faculty = $result['faculty'];
				$batch = $result['batch'];
				$degreeorcourse = $result['degreeorcourse'];
			}else{
				// user not found 
				header('Location: users.php?err=user_not_found');
			}
		}else{
			// query unsuccessful
			header('Location: users.php?err=query_failed');
		}
	}

	//checking required fields 
	if (isset($_POST['submit'])) {

		$user_id = $_POST['user_id'];
		$password = $_POST['password'];

		$req_fields = array('user_id', 'password');
		
		$errors = array_merge($errors , check_req_fields($req_fields));

		//checking max length

		$max_len_fields = array('password' => 50);
		
		$errors = array_merge($errors , check_max_len($max_len_fields));

		if (empty($errors)) {
			//no errors found.. adding new record
			$password = mysqli_real_escape_string($connection, $_POST['password']);
			$hashed_password = sha1($password);

			$query = "UPDATE studentdetails SET ";
			$query .= "password = '{$hashed_password}' ";
			$query .= "WHERE index_num = '{$user_id}' LIMIT 1";
			
			$result = mysqli_query($connection, $query);

			if($result){
				// query successful.. redirect users page
				header('Location: users.php?changed_password=true');

			}else{
				$errors[] = 'Failed toupdate the password. ';
			}
		}
	}
 ?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Change Password </title>
	<link rel="stylesheet" href="css/main.css">
</head>
<body>
	
	<header>
		<div class="appname">Student Managment System</div>
		<div class="loggedin">Welcome <?php echo $_SESSION['full_name']; ?>: <a href="logout.php">Log Out</a></div>
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

	<main>
		
		<h1>Change Password <span><a href="users.php">Back to student list</a></span></h1> 

		<?php 
			if (!empty($errors)) {
				display_errors($errors);	
			}
		 ?>

		<form action="change-password.php" class = "userform" method = "post">

			<input type="hidden" name="user_id" value = "<?php echo($user_id) ?>">

			<p>
				<label for="">Full Name:</label>
				<input type="text" name="full_name" <?php echo 'value = "'.$full_name. '"' ?> disabled>
			</p>

			<p>
				<label for="">Index NUmber:</label>
				<input type="text" name="index_num" <?php echo 'value = "'.$index_num. '"' ?> disabled>
			</p>
			
			<p>
				<label for="">Address:</label>
				<input type="text" name="address" <?php echo 'value = "'.$address. '"' ?> disabled>
			</p>
			
			<p>
				<label for="">Nic Number:</label>
				<input type="text" name="nic" <?php echo 'value = "'.$nic. '"' ?> disabled>
			</p>

			<p>
				<label for="">Faculty:</label>
				<input type="text" name="faculty" <?php echo 'value = "'. $faculty . '"' ?> disabled>
			</p>

			<p>
				<label for="">Batch:</label>
				<input type="text" name="batch" <?php echo 'value = "'.$batch . '"' ?> disabled>
			</p>

			<p>
				<label for="">Degree/Course:</label>
				<input type="text" name="degreeorcourse" <?php echo 'value = "'.$degreeorcourse. '"' ?> disabled>
			</p>

			<p>
				<label for="">Password:</label>
				<input type="password" name="password" <?php echo 'value = "'.$password . '"' ?> >
			</p>


			<p>
				<label for="">Show Password:</label>
				<input type="checkbox" name="showpassword" id="showpassword" style="width: 20px; height: 20px;">

			</p>
			
			<p>
				<label for="">&nbsp;</label>
				<button type="submit" name = "submit">Change Password</button>
			</p>

		</form>
	</main>
	
	<script src="js/jquery.js"></script>
	<script>
		$(document).ready(function () {
			$('#showpassword').click(function(){
				if($('#showpassword').is(':checked')){
					$('#password').attr('type','text');
				}else{
					$('#password').attr('type','password');
				}
			});
		});
	</script>
	

</body>
</html>

<?php mysqli_close($connection); ?>