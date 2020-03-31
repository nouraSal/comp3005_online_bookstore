<?php
	session_start();
	if(!isset($_POST['submit'])){
		echo "Something wrong! Check again!";
		exit;
	}
	require_once "db/config.php";
	

	$name = trim($_POST['name']);
	$pass = trim($_POST['pass']);

	if($name == "" || $pass == ""){
		echo "Name or Pass is empty!";
		exit;
	}

	$name = mysqli_real_escape_string($con, $name);
	$pass = mysqli_real_escape_string($con, $pass);
	$pass = sha1($pass);

	// get from db
	$query = mysqli_query($con, "SELECT * from `admin` WHERE `name`='$name' AND `pass`='$pass'");
	if(mysqli_num_rows($query) == 0){
		echo "Name or pass is wrong. Check again!";
		exit;
	}else{
		$_SESSION['admin'] = true;
		header("Location: all_books.php");
	}
		
?>