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

        //Get Input data from query string
        $search_string = filter_input(INPUT_GET, 'search_string');
        $filter_col = filter_input(INPUT_GET, 'filter_col');
        $orderid = filter_input(INPUT_GET, 'orid');

        //Get current page.
        $page = filter_input(INPUT_GET, 'page');

        //Per page limit for pagination.
        $pagelimit = 6;

        if (!$page) {
            $page = 1;
        }

        //Get DB instance. i.e instance of MYSQLiDB Library
        $db = getDbInstance();
        $select = array('orderid', 'book_isbn', 'item_price', 'quantity');

        //Start building query according to input parameters.
        // If search string
        if ($search_string) 
        {
            $db->where('orderid', '%' . $search_string . '%', 'like');
            $db->orwhere('item_price', '%' . $search_string . '%', 'like');
            $db->orwhere('book_isbn', '%' . $search_string . '%', 'like');

        }


        //Set pagination limit
        $db->pageLimit = $pagelimit;

        //Get result of the query.
        $records =$db->where('orderid',$orderid)->arraybuilder()->paginate("order_items", $page, $select);
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
                    <h2 class="page-header">Order Details</h2>
                </div>
            </div>
                <?php include('db/flash_messages.php') ?>
            <!--    Begin filter section-->
            <!-- <div class="well text-center filter-form">
                <form class="form form-inline" action="">
                    <label for="input_search">Search</label>
                    <input type="text" class="form-control" id="input_search" name="search_string" value="<?php echo $search_string; ?>">
                    <input type="submit" value="Go" class="btn btn-primary">

                </form>
            </div> -->
        <!--   Filter section end-->

            <hr>


            <table class="table" style="margin-top: 20px">
              <tr>
                <th>Book ISBN</th>
                <th>Book Name</th>
                <th>Item Price($)</th>
                <th>Quantity</th>

              </tr>
              <?php foreach($records as $value=>$row){ ?>
              <tr>
                <td><?php echo $isbn=$row['book_isbn']; ?></td>
                <td><?php 
                    $joinq = mysqli_query($con,"SELECT book_title FROM books b JOIN order_items o ON b.book_isbn=o.book_isbn WHERE o.book_isbn='$isbn'");
                    while($res=mysqli_fetch_array($joinq)){
                        echo $res['book_title'];
                    }
                 ?></td>
                <td><?php echo $row['item_price']; ?></td>
                <td><?php echo $row['quantity']; ?></td>
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
            echo '<ul class="pagination pg-blue justify-content-center">';
            for ($i = 1; $i <= $total_pages; $i++) {
                ($page == $i) ? $li_class = ' class="active"' : $li_class = "";
                echo '<li' . $li_class . '><a href="orders.php' . $http_query . '&page=' . $i . '">' . $i . '</a></li>';
            }
            echo '</ul></div>';
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
