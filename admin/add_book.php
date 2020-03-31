<!DOCTYPE html>
<html lang="en">



<body class="grey lighten-3">

  <!--Main Navigation-->
  <header>

    <?php include_once "headers/sidebar.php";?>
    <title>Add New Book || ADMIN</title> 

  </header>
  <!--Main Navigation-->

  <!--Main layout-->
  <main class="pt-5 mx-lg-3">
    <div class="container-fluid mt-2">

      <!-- MAIN PAGE DETAILS-->
      <?php
        @session_start();
        include_once "db/config.php";
        $db = getDbInstance();

        if(isset($_POST['add'])){
            $isbn = trim($_POST['isbn']);
            $isbn = mysqli_real_escape_string($con, $isbn);
            
            $title = trim($_POST['title']);
            $title = mysqli_real_escape_string($con, $title);
    
            $author = trim($_POST['author']);
            $author = mysqli_real_escape_string($con, $author);
            
            $descr = trim($_POST['descr']);
            $descr = mysqli_real_escape_string($con, $descr);
            
            $price = floatval(trim($_POST['price']));
            $price = mysqli_real_escape_string($con, $price);

            $genre = $_POST['genre'];            
            $publisherid = $_POST['publisher'];
    
            // add image
            if(isset($_FILES['image']) && $_FILES['image']['name'] != ""){
                $image = $_FILES['image']['name'];

                $target_dir = "/img/";
                $target_file = $target_dir . basename($_FILES['image']['name']);
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                /* $directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']);
                $uploadDirectory = $_SERVER['DOCUMENT_ROOT'] . $directory_self . "bootstrap/img/";
                $uploadDirectory .= $image; */
                //move_uploaded_file($_FILES['image']['tmp_name'], $uploadDirectory);
                if (!move_uploaded_file($_FILES['image']['name'], $target_dir)){
                    $_SESSION['failure'] = "File Upload Error";
                }
            }

    
            $bkid=mysqli_query($con,"SELECT * FROM books WHERE book_isbn=$isbn");
            if(!empty($bkid)){
                $_SESSION['failure']="Book ID ".$isbn." already assigned";
            }
    
            $query = "INSERT INTO books VALUES ('" . $isbn . "', '" . $title . "', '" . $author . "', '" . $image . "', '" . $descr . "', '" . $price . "', '" . $publisherid . "','" . $genre . "',CURRENT_TIMESTAMP)";
            $result = mysqli_query($con, $query);
            if(!$result){
                $_SESSION['failure'] = "Can't add new data " . mysqli_error($con);
                exit;
            } else {
                header("Location: all_books.php");
                $_SESSION['success']="New Book added to catalogue";
            }
        }

        

       ?>

       <!--REGISTRATION FORM-->

       <div class="card">

<h5 class="card-header light-color gray-text text-center py-4">
    <strong>New Book Registration</strong>
</h5>

<div class="card-body px-lg-5 pt-0">

<div class="row mt-1">
    <div class="col">
        <?php include('db/flash_messages.php'); ?>
    </div>
</div>

    <form class="md-form" method="post" enctype="multipart/form-data" style="color: #757575;">
    <div class="row">
        <div class="col-md-6 form-group">
              <label for="input">ISBN</label>
               <input type="text" required name="isbn"  value="<?php if (isset($isbn)) {
                                                            echo $isbn;
                                                        } ?>" maxlength="20" class="form-control" placeholder="">
        </div>

        <div class="col-md-6 form-group">
            <label for="input">Book Title</label>
            <input type="text" required name="title" value="<?php if (isset($title)) {
                                                            echo $title;
                                                        } ?>" maxlength="50" class="form-control" placeholder="">
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 form-group">
            <label for="input">Author</label>
            <input type="text" required name="author" value="<?php if (isset($author)) {
                                                            echo $author;
                                                        } ?>"  maxlength="50" class="form-control" placeholder="">
        </div>

        <div class="col-md-3 form-group">
            <label for="input">Price</label>
            <input type="text" required name="price" value="<?php if (isset($price)) {
                                                            echo $price;
                                                        } ?>" maxlength="10" class="form-control" placeholder="">
        </div>
        <div class="col-md-3 form-group">
                <select class='custom-select' name="genre" required=''>
                           <option value="" selected disabled="true">Select Genre</option>
                           <?php
                           $run_querry = mysqli_query($con, "SELECT * FROM `genre` ORDER BY `name` ASC");
                           while ($row_querry = mysqli_fetch_array($run_querry)) :
                               $Name = $row_querry['name'];
                               ?>
                   <option  value="<?php echo $row_querry['genreid']; ?>"><?php echo $Name; ?> </option>
                   <?php endwhile; ?>
               </select>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 form-group">
            <label for="input">Description</label>
            <textarea class="form-control md-textarea" required name="descr" value="<?php if (isset($descr)) {
                                                            echo $Description;
                                                        } ?>" placeholder=""></textarea>
        </div>

    </div>
        <div class="row">
            <div class="col-md-6">
                <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" required name="image" aria-describedby="inputGroupFileAddon01">
                                <label class="custom-file-label">Upload Image</label>
                            </div>
                </div>
            </div>
            <div class="col-md-6 form-group">
                <select class='custom-select' name="publisher" required=''>
                           <option value="" selected disabled="true">Select Publisher</option>
                           <?php
                           $run_querry = mysqli_query($con, "SELECT * FROM `publisher` ORDER BY `publisher_name` aSC");
                           while ($row_querry = mysqli_fetch_array($run_querry)) :
                               $Name = $row_querry['publisher_name'];
                               ?>
                   <option  value="<?php echo $row_querry['publisherid']; ?>"><?php echo $Name; ?> </option>
                   <?php endwhile; ?>
                       </select>
            </div>
        </div>

    <div class="row">
        <div class="col-md-6 form-group">
            <button class="btn btn-outline-primary  btn-block my-4 waves-effect z-depth-0" name="add" type="submit">Add Now</button>
        </div>
        <div class="col-md-6 form-group">
            <input type="reset" value="Reset" class="btn btn-outline-default  btn-block my-4 waves-effect z-depth-0">
        </div>
    </div>

    </form>

</div>
</div>

       <!--END OF REGISTRATION FORM-->



</div>
<!--Main container end-->




   

    </div>
  </main>
  <!--Main layout-->

  <!--Footer-->
  <?php //include_once "../headers/footer.php";?>
  <!--/.Footer-->

 
</body>

<?php include_once 'includes/scripts.php'; ?>

</html>
