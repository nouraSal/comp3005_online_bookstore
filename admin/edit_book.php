<!DOCTYPE html>
<html lang="en">



<body class="grey lighten-3">

  <!--Main Navigation-->
  <header>

    <!--NAVBAR-->
    <?php //include_once "headers/mainav.php";?>
    <!-- END NAVBAR-->


    <!-- Sidebar -->
    <?php include_once "headers/sidebar.php";?>
    <!-- Sidebar -->

  </header>
  <!--Main Navigation-->

  <!--Main layout-->
  <main class="pt-5 mx-lg-3">
    <div class="container-fluid mt-5">

      <!-- MAIN PAGE DETAILS-->
      <?php
        //session_start();
        include_once "db/config.php";
        $db = getDbInstance();

        $book_isbn = filter_input(INPUT_GET, 'bookisbn');
    
        // get book data
        $query = "SELECT * FROM books WHERE book_isbn = '$book_isbn'";
        $result = mysqli_query($con, $query);
        if(!$result){
            echo "Can't retrieve data " . mysqli_error($conn);
            exit;
        }
        $row = mysqli_fetch_assoc($result);

        //update database
        if(isset($_POST['save_change'])){
            $data = array('book_isbn'=>$_POST['isbn'],'book_title'=>$_POST['title'],'book_author'=>$_POST['author'],'book_image'=>$_POST['image'],'book_descr'=>$_POST['descr'],'book_price'=>$_POST['price'],'publisherid'=>$_POST['publisher']);
            $updateq = $db->update('books',$data);
            if (!$updateq) {
                echo json_encode($db->getLastError());
                return;
            }
        }



        ?>

        <!--Main container start-->
        <div id="page-wrapper">
            <div class="row">

                <div class="col-sm-9">
                    <h2 class="page-header">Edit Book</h2>
                </div>
                <div class="col-sm-3">
                    <div class="page-action-links text-right">
                        <a href="add_book.php">
                            <button class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Add new </button>
                        </a>
                    </div>
                </div>
            </div>
                <?php include('db/flash_messages.php') ?>


            <hr>


            <form method="post" action="edit_book.php" enctype="multipart/form-data">
		<table class="table">
			<tr>
				<th>ISBN</th>
				<td><input type="text" name="isbn" value="<?php echo $row['book_isbn'];?>" readOnly="true"></td>
			</tr>
			<tr>
				<th>Title</th>
				<td><input type="text" name="title" value="<?php echo $row['book_title'];?>" required></td>
			</tr>
			<tr>
				<th>Author</th>
				<td><input type="text" name="author" value="<?php echo $row['book_author'];?>" required></td>
			</tr>
			<tr>
				<th>Image</th>
                <td><input type="file" required name="image" aria-describedby="inputGroupFileAddon01"></td></tr>
			<tr>
				<th>Description</th>
				<td><textarea name="descr" cols="40" rows="5"><?php echo $row['book_descr'];?></textarea>
			</tr>
			<tr>
				<th>Price</th>
				<td><input type="text" name="price" value="<?php echo $row['book_price'];?>" required></td>
			</tr>
			<tr>
				<th>Publisher</th>
				<td><input type="text" name="publisher" value="<?php echo $row['publisherid']; ?>" required></td>
			</tr>
		</table>
		<input type="submit" name="save_change" value="Change" class="btn btn-sm btn-primary">
		<input type="reset" value="cancel" class="btn btn-sm btn-default">
	</form>
	<br/>
	<a href="all_books.php" class="btn btn-success">Confirm</a>



</div>
<!--Main container end-->




   

    </div>
  </main>
  <!--Main layout-->

  <!--Footer-->
  <?php include_once "headers/footer.php";?>
  <!--/.Footer-->


</body>

</html>
