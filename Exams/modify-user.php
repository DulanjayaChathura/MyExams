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
	if (isset($_POST['save'])) {

		$user_id = $_POST['user_id'];
		$full_name = $_POST['full_name'];
		$index_num = $_POST['index_num'];
		$address = $_POST['address'];
		$nic = $_POST['nic'];
		$faculty = $_POST['faculty'];
		$batch = $_POST['batch'];
		$degreeorcourse = $_POST['degreeorcourse'];

		$req_fields = array('user_id', 'full_name', 'index_num','address', 'nic', 'faculty', 'batch', 'degreeorcourse');
		
		$errors = array_merge($errors , check_req_fields($req_fields));

		//checking max length

		$max_len_fields = array('full_name' => 300 , 'nic' => 12,'faculty' => 200, 'degreeorcourse' => 300 , 'batch' => 2 , 'index_num' => 7);
		
		$errors = array_merge($errors , check_max_len($max_len_fields));


		//checking if index number already exists
		$index_num = mysqli_real_escape_string($connection, $_POST['index_num']);

		$query = "SELECT * FROM studentdetails WHERE index_num = '{$index_num}' AND index_num != {$user_id} LIMIT 1";

		$result_set = mysqli_query($connection, $query);


		if($result_set){
			if(mysqli_num_rows($result_set) == 1){
				$errors[] = 'Index number already exists.';
			}
		}

		if (empty($errors)) {
			//no errors found.. adding new record
			$full_name = mysqli_real_escape_string($connection, $_POST['full_name']);
			$address = mysqli_real_escape_string($connection, $_POST['address']);
			$nic = mysqli_real_escape_string($connection, $_POST['nic']);
			$faculty = mysqli_real_escape_string($connection, $_POST['faculty']);
			$batch = mysqli_real_escape_string($connection, $_POST['batch']);
			$degreeorcourse = mysqli_real_escape_string($connection, $_POST['degreeorcourse']);
			// index number is already sanitized

			$query = "UPDATE studentdetails SET ";
			$query .= "full_name = '{$full_name}', ";
			$query .= "index_num = '{$index_num}', ";
			$query .= "address = '{$address}', ";
			$query .= "nic = '{$nic}', ";
			$query .= "faculty = '{$faculty}', ";
			$query .= "batch = '{$batch}', ";
			$query .= "degreeorcourse = '{$degreeorcourse}' ";
			$query .= "WHERE index_num = '{$user_id}' LIMIT 1";
			
			$result = mysqli_query($connection, $query);

			if($result){
				// query successful.. redirect users page
				header('Location: users.php?user_modified=true');

			}else{
				$errors[] = 'Failed to modify the record.';
			}
		}
	}
 ?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>View / Modify Student</title>
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
		
		<h1>View / Modify Student <span><a href="users.php">Back to student list</a></span></h1> 

		<?php 
			if (!empty($errors)) {
				display_errors($errors);	
			}
		 ?>

		<form action="modify-user.php" class = "userform" method = "post">

			<input type="hidden" name="user_id" value = "<?php echo($user_id) ?>">

			<p>
				<label for="">Full Name:</label>
				<input type="text" name="full_name" <?php echo 'value = "'.$full_name. '"' ?>>
			</p>

			<p>
				<label for="">Index NUmber:</label>
				<input type="text" name="index_num" <?php echo 'value = "'.$index_num. '"' ?>>
			</p>
			
			<p>
				<label for="">Address:</label>
				<input type="text" name="address" <?php echo 'value = "'.$address. '"' ?>>
			</p>
			
			<p>
				<label for="">Nic Number:</label>
				<input type="text" name="nic" <?php echo 'value = "'.$nic. '"' ?>>
			</p>

			<p>
				<label for="">Faculty:</label>
				<input type="text" name="faculty" <?php echo 'value = "'. $faculty . '"' ?>>
			</p>

			<p>
				<label for="">Batch:</label>
				<input type="text" name="batch" <?php echo 'value = "'.$batch . '"' ?>>
			</p>

			<p>
				<label for="">Degree/Course:</label>
				<input type="text" name="degreeorcourse" <?php echo 'value = "'.$degreeorcourse . '"' ?>>
			</p>

			<p>
				<label for="">Password:</label>
				<span>********</span> | <a href="change-password.php?user_id=<?php echo $user_id; ?>">Change Password</a>
				
			</p>
			
			<p>
				<label for="">&nbsp;</label>
				<button type="submit" name = "save">Save</button>
			</p>

		</form>
	</main>
</body>
</html>

<?php mysqli_close($connection); ?>