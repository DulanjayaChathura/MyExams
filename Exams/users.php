<?php  session_start();  ?>
<?php require_once('inc/connection.php'); ?>
<?php require_once('inc/functions.php'); ?>


<?php  
	//checking if a user is logged in 
	if (!isset($_SESSION['user_id'])) {
		header('Location: index.php');
	}

	$user_list = '';

	//getting the list of users
	$query = "SELECT * FROM studentdetails WHERE is_deleted = 0 ORDER BY full_name";

	$users = mysqli_query($connection, $query);

	verify_query($users);
	while ($user = mysqli_fetch_assoc($users)) {
	$user_list .= "<tr>";
		
		$user_list .= "<td>{$user['full_name']}</td>";
		$user_list .= "<td>{$user['index_num']}</td>";
		$user_list .= "<td>{$user['address']}</td>";
		$user_list .= "<td>{$user['nic']}</td>";
		$user_list .= "<td>{$user['faculty']}</td>";
		$user_list .= "<td>{$user['degreeorcourse']}</td>";
		$user_list .= "<td>{$user['batch']}</td>";
		$user_list .= "<td>{$user['last_login']}</td>";
		$user_list .= "<td><a href = 'modify-user.php?user_id={$user['index_num']}'>Edit</a></td>";
		$user_list .= "<td><a href = 'delete-user.php?user_id={$user['index_num']}' onclick='return confirm(\"Are you sure?\");'>Delete</a></td>";	

		$user_list .= "</tr>";
	}
	
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Students</title>
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

	<main class = "studentlist">
		
		<h1>Students <span><a href="add-user.php">+ Add New</a></span></h1>

		<table class="masterlist">
			<tr>
				<th>Full Name</th>
				<th>Index Number</th>
				<th>Address</th>
				<th>Nic Number</th>
				<th>Faculty</th>
				<th>Degree/Course</th>
				<th>Batch</th>
				<th>Last login</th>
				<th>Edit</th>
				<th>Delete</th>


			</tr>
			<?php echo $user_list; ?>


		</table>


	</main>
</body>
</html>

<?php mysqli_close($connection); ?>