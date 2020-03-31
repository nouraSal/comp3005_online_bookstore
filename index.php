<?php
  session_start();
  $count = 0;
  // connecto database
  
  $title = "Index";
  require_once "./template/header.php";
  require_once "./functions/database_functions.php";
  include_once "admin/db/config.php";
  $db = getDbInstance();

  $conn = db_connect();
  $row = select4LatestBook($conn);

   //Get Input data from query string
   $search_string = filter_input(INPUT_GET, 'search_string');
   $filter_col = filter_input(INPUT_GET, 'filter_col');
   $order_by = filter_input(INPUT_GET, 'order_by');

  // If filter types are not selected we show latest created data first
  if (!$filter_col) {
    $filter_col = "date_added";
  }
  if (!$order_by) {
      $order_by = "Desc";
  }

  //Start building query according to input parameters.
        // If search string
        if ($search_string) 
        {
            $db->where('book_isbn', '%' . $search_string . '%', 'like');
            $db->orwhere('book_title', '%' . $search_string . '%', 'like');
            $db->orwhere('book_author', '%' . $search_string . '%', 'like');

        }

        //If order by option selected
        if ($order_by)
        {
            $db->orderBy($filter_col, $order_by);
        }

                //Get result of the query.
                $records =$db->get("books");        
        
                // get columns for order filter
                foreach ($records as $value) {
                    foreach ($value as $col_name => $col_value) {
                        $filter_options[$col_name] = $col_name;
                    }
                    //execute only once
                    break;
                }
?>

          <?php include('admin/db/flash_messages.php') ?>

            <!--    Begin filter section-->
            <div class="well text-center filter-form">
                <form class="form form-inline" action="">
                    <label for="input_search">Search</label>
                    <input type="text" class="form-control" id="input_search" name="search_string" value="<?php echo $search_string; ?>">
                    <label for ="input_order">Order By</label>
                    <select name="filter_col" class="form-control">

                        <?php
                        foreach ($filter_options as $option) {
                            ($filter_col === $option) ? $selected = "selected" : $selected = "";
                            echo ' <option value="' . $option . '" ' . $selected . '>' . $option . '</option>';
                        }
                        ?>

                    </select>

                    <select name="order_by" class="form-control" id="input_order">

                        <option value="Asc" <?php
                        if ($order_by == 'Asc') {
                            echo "selected";
                        }
                        ?> >Asc</option>
                        <option value="Desc" <?php
                        if ($order_by == 'Desc') {
                            echo "selected";
                        }
                        ?>>Desc</option>
                    </select>
                    <input type="submit" value="Search" class="btn btn-danger">

                </form>
            </div>
        <!--   Filter section end-->
     
        <?php if(isset($search_string)){?>

            <p class="lead text-center text-muted">Your Search Results</p>
          <div class="row">
            <?php foreach($records as $book) { ?>
            <div class="col-md-3">
              <a href="book.php?bookisbn=<?php echo $book['book_isbn']; ?>">
              <img class="img-responsive img-thumbnail" alt="<?=$book['book_image']?>" src="./bootstrap/img/<?php echo $book['book_image']; ?>">
              </a>
            </div>
            <?php } ?>
          </div>

            <?php }?>

      

      <!-- when search not set -->
      <?php if(!isset($search_string)){?>
      <p class="lead text-center text-muted">Latest books</p>
      <div class="row">
        <?php foreach($row as $book) { ?>
      	<div class="col-md-3">
          <a href="book.php?bookisbn=<?php echo $book['book_isbn']; ?>">
          <?php
            if($book['book_image'])
            {
                $image = $book['book_image'];
            }
            else{
                $image = "harrypotter.jpg";
            }
            ?>
           <img class="img-responsive img-thumbnail" alt="<?=$book['book_image']?>" src="./bootstrap/img/<?php echo $image; ?>">
          </a>
      	</div>
        <?php } ?>
      </div>
        <?php }
  if(isset($conn)) {mysqli_close($conn);}
  require_once "./template/footer.php";
?>