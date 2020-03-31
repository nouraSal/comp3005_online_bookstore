<?php
@session_start();
require_once "db/config.php";
$db = getDbInstance();

//If User has already logged in, redirect to dashboard page.
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === TRUE) {
    header('Location:dashboard.php');
}

//If user has previously selected "remember me option", his credentials are stored in cookies.
if(isset($_COOKIE['email']) && isset($_COOKIE['pass']))
{
	//Get user credentials from cookies.
	$email = filter_var($_COOKIE['email']);
	$pass = filter_var($_COOKIE['pass']);
	$db->where ("email", $email);
	$db->where ("pass", $pass);
    $row = $db->get('admin');

    if ($db->count >= 1) 
    {    	
        $_SESSION['user_logged_in'] = TRUE;
        $_SESSION['Email']=$row[0]['email'];       
        
	}
    else //Email Or pass might be changed. Unset cookie
    {
    unset($_COOKIE['email']);
    unset($_COOKIE['pass']);
    setcookie('email', null, -1, '/');
    setcookie('pass', null, -1, '/');
    header('Location:login.php');
    exit;
    }
}



include_once 'headers/page_head.php';
?>
<div id="page-" class="col-md-4 col-md-offset-4">
	<form class="form loginform" method="POST" action="authenticate.php">
		<div class="login-card card card-default">
			<div class="card-heading h3 text-center">ADMIN Sign in</div>
			<div class="card-body">
				<div class="form-group">
					<label class="control-label">Email</label>
					<input type="text" name="email" class="form-control" required="required">
				</div>
				<div class="form-group">
					<label class="control-label">Password</label>
					<input type="pass" name="pass" class="form-control" required="required">
				</div>
				<div class="checkbox">
					<label>
						<input name="remember" type="checkbox" value="1">Remember Me
					</label>
				</div>
				<?php
				if(isset($_SESSION['login_failure'])){ ?>
				<div class="alert alert-danger alert-dismissable fade in">
					<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					<?php echo $_SESSION['login_failure']; unset($_SESSION['login_failure']);?>
				</div>
				<?php } ?>
				<button type="submit" name="login" class="btn btn-success loginField" >Login</button>
			</div>
		</div>
	</form>
</div>

