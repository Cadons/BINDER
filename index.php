<!doctype html>


<html>
<head>
<title>LOGIN</title>
<meta name="viewport" content="width=device-width, user-scalable=no,
initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<link rel="stylesheet" href="style.css">
<!-- jQuery library -->
<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

</head>
<body>
<?php
// retrive data from login table inside database


session_start();
if(isset($_SESSION['log']))
{
	   header("location: menager.php");
	   die();
}

	if(isset($_POST["sub"]))
	{
		require("Connect_db.php");
		require_once("config/get_credezialies.php");
		$obj=new getCredenziales();
		$cred=array($obj->getHost(),$obj->getUsername(),$obj->getPassword(),$obj->getDatabase_Name());//$obj->getUsername(),$obj->getPassword(),$obj->getDatabase_Name());
		//echo $cred[1];
		
		$db=new DatabaseMenager\Connect_db($cred[1],$cred[2],$cred[3]);
		$userPassword = $_POST["psw"]; 
		$hashedUserPassword = crypt($userPassword, '$2a$07$usesomesillystringforsalt$');//creata hash for login
			if($db->Check_Admin_Credezialies($_POST["usr"],$hashedUserPassword))
			{
				$_SESSION['log']=$_POST["usr"];
				?><script>alert("Login ok");</script>
	<?php
				header("location: /binder/menager.php");
			}
			else
			{
		?><script>
		 swal ( "Login failed" ,  "Password Wrong",  "error" );

		</script>
	<?php
	   session_destroy();	
			}

	}


?>
<center >
	<div class="loginbox">	<?php
		if(!file_exists("config/dbaccess.json"))
		{
		 ?><div style="color: white;"><h5>BINDER isen't ready</h5> click configure to setup<br><a href="config/config.php" style="color: white;" >Configure Binder</a></div>
		<?php
		}?>
		<img src="resources/logo.png" style="width:20%">
<h1>Login to BINDER</h1>
<div style="width:50%; ">
<form action="?" method="POST">
	<input type="text" name="usr" class="form-control" placeholder="Username">
	<br>
	<br>
<input type="Password" name="psw" class="form-control" placeholder="Password">
	<br>
	<br>
<button type="submit" name="sub" class="btn btn-default btn-lg" style="width:100%;" >Login</button>

</form>
<br>

</div>

</center>
</div>
</body>
</html>
