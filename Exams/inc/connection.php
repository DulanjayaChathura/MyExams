<?php 

	$dbhost = 'localhost';
	$dbuser = 'root';
	$dbpass = '';
	$dbname = 'studentdb';
	$connection = mysqli_connect('localhost', 'root', '', 'studentdb');
	
	
	if (mysqli_connect_errno()){
		die('database connection failed! ' . mysqli_connect_error());
	}
 ?>