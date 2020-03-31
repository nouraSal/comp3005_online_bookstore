<?php
@session_start();
	$book_isbn = $_GET['bookisbn'];

	include_once "db/config.php";
        $db = getDbInstance();

	$query = "DELETE FROM books WHERE book_isbn = '$book_isbn'";
	$result = mysqli_query($con, $query);
	if(!$result){
		echo "delete data unsuccessfully " . mysqli_error($conn);
		exit;
	}
	header("Location: all_books.php");
	$_SESSION['success'] ="Data deleted";
?>