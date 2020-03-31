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

        //Get current page.
        $page = filter_input(INPUT_GET, 'page');

        //Per page limit for pagination.
        $pagelimit = 6;

        if (!$page) {
            $page = 1;
        }

        //Get DB instance. i.e instance of MYSQLiDB Library
        $db = getDbInstance();
        $select = array('genreid', 'name');

        if(isset($_POST['add_genre'])){
            $name = $_POST['name'];

            $data = array('name'=>$name);

            $chk = mysqli_query($con, "SELECT * FROM `genre` WHERE 'name' = $name");
            $chkqr = mysqli_fetch_array($chk);
            if(mysqli_num_rows($chkqr) !=0){
                $_SESSION['failure']="The Genre is already registered";
            }else{

            if($db->insert('genre',$data)){
                $_SESSION['success'] = "New genre added";
            }else{
                $_SESSION['failure'] ="Insert Error";
            }
        }
    }

        //Start building query according to input parameters.
        // If search string
        if ($search_string) 
        {
            $db->where('name', '%' . $search_string . '%', 'like');
            $db->orwhere('genreid', '%' . $search_string . '%', 'like');

        }

        //Set pagination limit
        $db->pageLimit = $pagelimit;

        //Get result of the query.
        $records =$db->arraybuilder()->paginate("genre", $page, $select);
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
                    <h2 class="page-header">All Genres</h2>
                </div>
                <div class="col-sm-3">

                    <div class="page-action-links">
                        <a class="btn btn-md btn-success" data-toggle="modal" data-target="#Genre" style="margin-right: 8px;">New Genre</td></a>
                         <!-- Add Genre Modal-->
                         <div class="modal fade" id="Genre" role="dialog">
                                <div class="modal-dialog">
                                    <form method="post" enctype="multipart/form-data">
                                    <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Register New Genre</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>
                                            <div class="modal-body">
                                        
                                                <div class="row">
                                                    <div class="col-md-11 form-group">
                                                        <label for="input">Name</label>
                                                        <input type="text" required name="name" value="<?php if (isset($name)) {
                                                                                                        echo $name;
                                                                                                    } ?>" class="form-control" placeholder="">
                                                    </div>
                                                </div>                                           

                                            </div>
                                            <div class="modal-footer">
                                                <div class="row">
                                                    <div class="col">
                                                        <button name="add_genre" class="btn btn-secondary btn-block my-2 waves-effect pull-left z-depth-0" type="submit">Add</button>
                                                    </div>
                                                    <div class="col">
                                                    <button type="button" class="btn btn-danger  waves-effect " data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                
                                </div>
                            </div>
                             <!--end of Add Genre Modal-->

                    </div>

                </div>
            </div>
                <?php include('db/flash_messages.php') ?>
            <!--    Begin filter section-->
            <div class="well text-center filter-form">
                <form class="form form-inline" action="">
                    <label for="input_search">Search</label>
                    <input type="text" class="form-control" id="input_search" name="search_string" value="<?php echo $search_string; ?>">

                    <input type="submit" value="Go" class="btn btn-primary">

                </form>
            </div>
        <!--   Filter section end-->

            <hr>


            <table class="table" style="margin-top: 20px">
              <tr>
                <th>Genre_ID</th>
                <th>Name</th>
<!--                 <th>&nbsp;</th>
 -->                <th>&nbsp;</th>
              </tr>
              <?php foreach($records as $value=>$row){ ?>
              <tr>
                <td><?php echo $row['genreid']; ?></td>
                <td><?php echo $row['name']; ?></td>
<!--                 <td><a href="genre_edit.php?genreid=<?php echo $row['genreid']; ?>">Edit</a></td>
 -->                <td><a class="btn btn-sm btn-info" href="viewbooks.php?genreid=<?php echo $row['genreid']; ?>">View</a></td>
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
                echo '<li' . $li_class . '><a href="genres.php' . $http_query . '&page=' . $i . '">' . $i . '</a></li>';
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
