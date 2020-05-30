<!doctype html>


<html>
<head>
<title>LOGIN</title>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<link rel="stylesheet" href="style.css">
<meta name="viewport" content="width=device-width, user-scalable=no,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0"><!--Screen View -->
<link rel="shortcut icon" href="/binder/resources/favicon.ico" />
<!-- jQuery library -->
<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script>
<script src="/binder/resources/js/jquery.js"></script>
<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

<!--Icons Pack-->
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
<style>
	.form-control{
  margin: auto;
  width: 50%;
}
.btn-default{
  margin: auto;
  width: 50%;
}

	</style>
</head>
<body class="bodyLogin">
<?php
// retrive data from login table inside database
require("Connect_db.php");
require_once("config/get_credezialies.php");

session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if(isset($_SESSION['log']))
{
	   header("location: menager.php");
	   die();
}

	if(isset($_POST["sub"]))
	{
		
		
		$obj=new getCredenziales();
		$cred=array($obj->getHost(),$obj->getUsername(),$obj->getPassword(),$obj->getDatabase_Name());//$obj->getUsername(),$obj->getPassword(),$obj->getDatabase_Name());
		//echo $cred[1];
		
		$db=new DatabaseMenager\Connect_db($cred[1],$cred[2],$cred[3]);
	
		$userPassword = $_POST["psw"]; 
		
		$hashedUserPassword =hash ("sha256",$userPassword);//create hash for login


			if($db->Check_Credezialies($_POST["usr"],$hashedUserPassword))
			{
				$_SESSION['log']=$db->getUserID($_POST["usr"]);
				$_SESSION['user']=$_POST["usr"];
				

				$log=fopen('log/access_log.txt','a');
				function getUserIpAddr(){
					if(!empty($_SERVER['HTTP_CLIENT_IP'])){
						//ip from share internet
						$ip = $_SERVER['HTTP_CLIENT_IP'];
					}elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
						//ip pass from proxy
						$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
					}else{
						$ip = $_SERVER['REMOTE_ADDR'];
					}
					return $ip;
				}
				fwrite($log,$_SESSION['user']." IP ADDRESS:[".getUserIpAddr()."] has logged at ".date("Y-m-d h:i:s a")."\n");
				fclose($log);
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
<div class="loginbox">

		<?php
		if(!file_exists("config/config.json"))
		{
		 ?><div style="color: white;"><h5>BINDER isen't ready</h5> click configure to setup<br><a href="config/config.php" style="color: white;" >Configure Binder</a></div>
		<?php
		}?>
		<img src="resources/logo.png" style="width:20%">
<h1>Login to BINDER</h1>
<div>
<form action="?" method="POST">
	<input type="text" name="usr" class="form-control" placeholder="Username">
	<br>
	<br>
<input type="Password" name="psw" class="form-control" placeholder="Password">
	<br>
	<br>
<button type="submit" name="sub" class="btn btn-default btn-lg"  value=1 >Login</button>

</form>
<a href="ForgottenPassword.php" style="color:white"><h6>I have forgetten my Password</h6></a>
<br>

</div>


</div>

</body>
</html>
