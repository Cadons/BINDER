<?php



?>

<?php


?>

<html>
<head>
<title>Reset your password</title>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<link rel="stylesheet" href="style.css">
<meta name="viewport" content="width=device-width, user-scalable=no,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0"><!--Screen View -->
<link rel="shortcut icon" href="/binder/resources/favicon.ico" />
<!-- jQuery library -->
<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

<!--Icons Pack-->
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
<script src="resources/ResetPassword.js"></script>
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
<div class="loginbox">


		<img src="resources/logo.png" style="width:20%">

<h1>Reset your password</h1>

<?php
		if(!file_exists("config/config.json"))
		{
		 ?><div style="color: white;"><h5>BINDER isen't ready</h5> click configure to setup<br><a href="config/config.php" style="color: white;" >Configure Binder</a></div>
		<?php
		}?>
<div>
<form>
	<br>
    <label>Insert your Password</label>
    <input type="Password" name="psw" id="psw1" class="form-control" placeholder="Password">
<br>
<label>Confirm your Password</label>
<input type="Password" id="psw2" class="form-control" placeholder="Password">
	<br>
	<br>
<button type="button" onclick="checkPasswords()" name="sub" class="btn btn-default btn-lg"  value=1 >Reset password</button>

</form>
<a href="index.php" style="color:white"><h6>Login</h6></a>
<br>

</div>


</div>
</body>
</html>