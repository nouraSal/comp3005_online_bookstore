<?php
@session_start();
include_once "db/config.php";
$db = getDbInstance();

$qr=mysqli_query($con, "SELECT total_cost,counts,MONTHNAME(date_delivered) AS month FROM supplies");
$chart_data="";
while($row=mysqli_fetch_array($qr)){
    $chart_data .="{expense:".$row['total_cost'].", sales:".$row['counts']."},";
}

$chart_data = substr($chart_data,0,-1);







?>

<html>
<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">

</head>
<body>
    <br> <br>

    <!--MAIN CONTAINER START-->
    <div class="container" style="width:900px;">
        <h2 align="center">IT Bookstore Annual Report</h2>
        <h3 align="center">Sales Vs Expenditure</h3>
       <br><br>
        <div id="chart"></div>


    </div>
    <!--MAIN CONTAINER STOP-->

    

<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.3.0/raphael.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>

</body>

</html>

<script>
Morris.Bar(
    {
        element:"chart",
        data:[<?=$chart_data;?>],
        xkeys:['month'],
        ykeys:['expense','sales'],
        labels:['expense','sales'],
        hideHover:'auto',

    }
);


</script>