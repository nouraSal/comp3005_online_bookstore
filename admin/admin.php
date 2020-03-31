<?php
	$title = "Administration section";
	?>

	<?php require_once "headers/page_head.php";
	require_once "../template/header.php";
	?>

<body class="grey lighten-3">
	  <!--Main layout-->
<main class="pt-5 mx-lg-3">
    <div class="container-fluid mt-5">
		<form class="form-horizontal" method="post" action="admin_verify.php">
		<div class="card-heading h4">ADMIN Sign in</div>
			<div class="form-group">
				<label for="name" class="control-label col-md-4">Username</label>
				<div class="col-md-4">
					<input type="text" name="name" class="form-control">
				</div>
			</div>
			<div class="form-group">
				<label for="pass" class="control-label col-md-4">Password</label>
				<div class="col-md-4">
					<input type="password" name="pass" class="form-control">
				</div>
			</div>
			<input type="submit" name="submit" class="btn btn-primary">
		</form>
	</div>
</main>



  