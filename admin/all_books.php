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
        @session_start();
        include_once "db/config.php";
        $db = getDbInstance();

        //Get Input data from query string
        $search_string = filter_input(INPUT_GET, 'search_string');
        $filter_col = filter_input(INPUT_GET, 'filter_col');
        $order_by = filter_input(INPUT_GET, 'order_by');

        //Get current page.
        $page = filter_input(INPUT_GET, 'page');

        //Per page limit for pagination.
        $pagelimit = 6;

        if (!$page) {
            $page = 1;
        }

        // If filter types are not selected we show latest created data first
        if (!$filter_col) {
            $filter_col = "date_added";
        }
        if (!$order_by) {
            $order_by = "Desc";
        }

        //Get DB instance. i.e instance of MYSQLiDB Library
        $db = getDbInstance();
        $select = array('book_isbn', 'book_title', 'book_author','book_image', 'publisherid', 'book_descr', 'book_price', 'date_Added');

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

        //Set pagination limit
        $db->pageLimit = $pagelimit;

        //Get result of the query.
        $records =$db->arraybuilder()->paginate("books", $page, $select);
        $total_pages = $db->totalPages;


        // get columns for order filter
        foreach ($records as $value) {
            foreach ($value as $col_name => $col_value) {
                $filter_options[$col_name] = $col_name;
            }
            //execute only once
            break;
        }
       /*  include_once 'includes/header.php';  */
        ?>

        <!--Main container start-->
        <div id="page-wrapper">
            <div class="row">

                <div class="col-sm-9">
                    <h2 class="page-header">All books</h2>
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
                    <input type="submit" value="Go" class="btn btn-primary">

                </form>
            </div>
        <!--   Filter section end-->

            <hr>


            <table class="table" style="margin-top: 20px">
              <tr>
                <th>ISBN</th>
                <th>Title</th>
                <th>Author</th>
                <th>Image</th>
                <th>Description</th>
                <th>Price</th>
                <th>Publisher</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
              </tr>
              <?php foreach($records as $value=>$row){ ?>
              <tr>
                <td><?php echo $row['book_isbn']; ?></td>
                <td><?php echo $row['book_title']; ?></td>
                <td><?php echo $row['book_author']; ?></td>
                <td><?php echo $row['book_image']; ?></td>
                <td><?php echo $row['book_descr']; ?></td>
                <td><?php echo $row['book_price']; ?></td>
                <td><?php $pid = $row['publisherid'];
                $jqr=mysqli_query($con,"SELECT * FROM publisher p JOIN books b ON b.publisherid=p.publisherid WHERE b.publisherid=$pid");
                $row=mysqli_fetch_array($jqr);
                echo $row['publisher_name'];
                
                 ?></td>
                <td><a class="btn btn-sm btn-secondary" href="edit_book.php?bookisbn=<?php echo $row['book_isbn']; ?>">Edit</a></td>
                <td><a class="btn btn-sm btn-danger" href="admin_delete.php?bookisbn=<?php echo $row['book_isbn']; ?>">Delete</a></td>
              </tr>
              <?php } ?>
          </table>


        
        <!--    Pagination links-->
            <div class="text-center">

                <?php
                if (!empty($_GET)) {
                    //we must unset $_GET[page] if previously built by http_build_query function
                    unset($_GET['page']);
                    //to keep the query sting parameters intact while navigating to next/prev page,
                    $http_query = "?" . http_build_query($_GET);
                } else {
                    $http_query = "?";
                }
                //Show pagination links
                if ($total_pages > 1) {
                    echo '<nav aria-label="Page navigation">';
                    echo '<ul class="pagination pg-blue justify-content-center">';

                    for ($i = 1; $i <= $total_pages; $i++) {
                        ($page == $i) ? $li_class = ' class="active"' : $li_class = "";
                        echo '<li' . $li_class . '><a href="all_books.php' . $http_query . '&page=' . $i . '">' . $i . '</a></li>';
                    }
                    echo '</ul> </nav>
                    </div>';
                }
                ?>
            </div>

    <!--    Pagination links end-->

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
