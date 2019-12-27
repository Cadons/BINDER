<!DOCTYPE html>
    <?php
    session_start();
    if(!isset($_SESSION['log']))
    {
        session_destroy();
        header("location: /binder");
    }
 
    ?>
    
<html>
<head>
        <title>Accounts Menager</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <meta name="viewport" content="width=device-width, user-scalable=no,
initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script>
   <script src="/binder/resources/script.js"></script>
   <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<!--Main stylesheet-->
<link rel="stylesheet" href="resources/template/body.css">
<!-- jQuery library -->

<link rel="shortcut icon" href="/binder/resources/favicon.ico" />
<!--Icons Pack-->
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">


<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script> 
<script src="/binder/output/binder.js" ></script>
   <meta charset="UTF-8">
         <script>
            isAdmin('<?php echo $_SESSION['log']; ?>');
          </script>
</head>
<body>
<?php 
include('resources/general_body.php');
require_once("config/get_credezialies.php");
$obj=new getCredenziales();
$credDB=array($obj->getHost(),$obj->getUsername(),$obj->getPassword(),$obj->getDatabase_Name());
try
{
   $credMail=array($obj->getSMTPaddress(),$obj->getSMTPport(),$obj->getSMTPusername(),$obj->getSMTPpassword(),$obj->getEmailType()); 
}
catch(Exception $e)
{

}

$mail="";
$smtp="";
$ledit=$obj->getLastEdit();
$statusSMTPform="";
if($credMail[4]==1)
 {
     $mail="checked";
     $statusSMTPform="disabled";
 }
 else
 {
     $smtp="checked";
 }

 BodyStart();?>

<div>
<h4>Binder Settings</h4>
<hr>
    <table class="table list" >
    <form method="POST" action="config/updateSettings.php">
    <tr><th colspan=2>Database settings</th></tr>
    <tr><td>Host</td><td><input type="text" class="form-control" name="db_host" value="<?php echo $credDB[0];?>" required></td></tr>
    <tr><td>Username</td><td><input type="text" class="form-control" name="db_usr" value="<?php echo $credDB[1];?>" required></td></tr>
    <tr><td>Password</td><td><input type="text" class="form-control" name="db_psw" value="<?php echo $credDB[2];?>" required></td></tr>
    <tr><td>Database name</td><td><input type="text" class="form-control" name="db_name" value="<?php echo $credDB[3];?>" readonly required></td></tr>
    <!-------------------------------------------->
    <tr><th colspan=2>Email settings</th></tr>
    <tr><td>Sending Method</td><td><input type="radio" name="mtype" value="1" onclick="SMTP_settings(false)" <?php echo $smtp;?> required>SMTP <input  type="radio"  onclick="SMTP_settings(true)" name="mtype" value="2" <?php echo $mail;?> required>mail()</td></tr>
    <tr><th colspan=2>SMTP settings</th></tr>
    <tr><td>Host</td><td><input type="text" class="form-control" name="smtpAddress" id="smtp1" value="<?php echo $credMail[0];?>" <?php echo $statusSMTPform;?> required></td></tr>
    <tr><td>Username</td><td><input type="text" class="form-control" name="smtpUsr" id="smtp2" value="<?php echo $credMail[2];?>" <?php echo $statusSMTPform;?> required></td></tr>
    <tr><td>Password</td><td><input type="text" class="form-control" name="smtpPsw" id="smtp3" value="<?php echo $credMail[3];?>" <?php echo $statusSMTPform;?> required></td></tr>
    <tr><td>Port</td><td><input type="number" class="form-control" name="smtpPort" id="smtp4" value="<?php echo $credMail[1];?>" <?php echo $statusSMTPform;?> required></td></tr>
    <tr><td colspan=2><h5><?php echo $ledit;?></h5></td></tr>
    <tr><td colspan=2><button type="submit" style="width:100%" class="btn btn-secondary">Update Settings</button></td></tr>
    <form>
    </table>
    <br>
    <h6>Binder Version:1.0</h6>
</div>



<?php BodyEnd();?>
</body>
</html>
