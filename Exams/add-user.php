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

	//checking required fields 
	if (isset($_POST['submit'])) {

		$full_name = $_POST['full_name'];
		$index_num = $_POST['index_num'];
		$address = $_POST['address'];
		$nic =$_POST['nic'];
		$faculty =$_POST['faculty'];
		$batch =$_POST['batch'];
		$degreeorcourse =$_POST['degreeorcourse'];
		$password =$_POST['password'];

		$req_fields = array('full_name', 'index_num','address', 'nic', 'faculty', 'batch', 'degreeorcourse', 'password');
		
		$errors = array_merge($errors , check_req_fields($req_fields));

		//checking max length

		$max_len_fields = array('full_name' => 300 , 'nic' => 12,'faculty' => 200, 'password' => 50 , 'degreeorcourse' => 300 , 'batch' => 2 , 'index_num' => 7);
		
		$errors = array_merge($errors , check_max_len($max_len_fields));

		

		//checking if index number already exists
		$index_num = mysqli_real_escape_string($connection, $_POST['index_num']);

		$query = "SELECT * FROM studentdetails WHERE index_num = '{$index_num}' LIMIT 1";

		$result_set = mysqli_query($connection, $query);

		if($result_set){
			if(mysqli_num_rows($result_set) == 1){
				$errors[] = 'Indedx number already exists.';
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
			$password = mysqli_real_escape_string($connection, $_POST['password']);
			// index number is already sanitized
			$hashed_password = sha1($password);

			$query = "INSERT INTO studentdetails ( ";
			$query .= "full_name, address, nic, is_deleted,  faculty, degreeorcourse, batch ,  password, index_num"; 
			$query .= ") VALUES (";
			$query .= " '{$full_name}', '{$address}', '{$nic}', 0,  '{$faculty}', '{$degreeorcourse}', '{$batch}',  '{$hashed_password}', '{$index_num}'";
			$query .= ")";

			echo "this is finished";

			$result = mysqli_query($connection, $query);

			verify_query($result);

			echo "<br>success";

			if($result){
				// query successful.. redirect users page
				header('Location: users.php?student_added=true');

			}else{
				$errors[] = 'Failed to add the new record.';
			}

		}


	}

	


 ?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Add New Student</title>
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
		
		<h1>Add New Student <span><a href="users.php">Back to student list</a></span></h1> 

		<?php 

			if (!empty($errors)) {
				display_errors($errors);
				
			}

		 ?>

		<form action="add-user.php" class = "userform" method = "post">

			<p>
				<label for="">Full Name:</label>
				<input type="text" name="full_name" <?php echo 'value = "'.$full_name . '"' ?>>
			</p>

			<p>
				<label for="">Index Number:</label>
				<input type="text" name="index_num" <?php echo 'value = "'. $index_num . '"' ?>>
			</p>

			<p>
				<label for="">Nic Number:</label>
				<input type="text" name="nic" <?php echo 'value = "'. $nic . '"' ?>>
			</p>

			<p>
				<label for="">Address:</label>
				<!-- <textarea rows="8" cols="82.1" name="address" <?php echo 'value = "'.$address . '"' ?>> </textarea> -->
				<input type="text" name="address" id="" <?php echo 'value = "'.$address . '"' ?>>
			</p>

			<p>
				<label for="">Faculty:</label>
				<input type="text" name="faculty" <?php echo 'value = "'.$faculty . '"' ?> >
			</p>
			<p>
				<label for="">Degree/Course:</label>
				<input type="text" name="degreeorcourse" <?php echo 'value = "'.$degreeorcourse . '"' ?> >
			</p>
			
			<p>
				<label for="">Batch:</label>
				<input type="text" name="batch" <?php echo 'value = "'.$batch . '"' ?>>
			</p>

			<p>
				<label for="">New Password:</label>
				<input type="password" name="password" >
			</p>

			<p>
				<label for="">&nbsp;</label>
				<button type="submit" name = "submit">Save</button>
			</p>


		</form>


	</main>
</body>
</html>

<?php mysqli_close($connection); ?>