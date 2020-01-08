<?php
$usr=$_POST["usr"];
$psw=$_POST["psw"];
$db_host=$_POST["db_host"];
$db_usr=$_POST["db_usr"];
$db_psw=$_POST["db_psw"];
$db_name=$_POST["db_name"];
$db_priv=$_POST["db_priv"];
$mailSend=$_POST["mailSend"];
$EmailSettings="";
if($mailSend==1)
{
    $smtpAddress=$_POST["smtpAddress"];
    $smtpPort=$_POST["smtpPort"];
    $smtpUsr=$_POST["smtpUsr"];
    $smtpPsw=$_POST["smtpPsw"];
    $EmailSettings.='"address":"'.$smtpAddress.'",
                     "port":"'.$smtpPort.'",
                     "username":"'.$smtpUsr.'",
                     "password":"'.$smtpPsw.'",
                     "mail":0';
}else
{
    $EmailSettings.='"mail":1';
}



$email=$_POST["email"];
$defaultname="binder";
$con=null;
if($db_priv==1)
{ 
$con=new MySqli($db_host,$db_usr,$db_psw);
  $db_name=$defaultname; 
 
}
else
{   //connect to exist database
    $con=new MySqli($db_host,$db_usr,$db_psw,$db_name);
}
//create table {login}{articles}{publictions}
/*
CREATE TABLE Persons (
    PersonID int NOT NULL AUTO_INCREMENT,
    LastName varchar(255),
    FirstName varchar(255),
    Address varchar(255),
    City varchar(255) 
);
*/

//Login
$report="";
if($con)
{
    $report.="Database connection:ok<br>";

 if($db_priv==1)
{
  //create database
  $sql="CREATE DATABASE $db_name CHARACTER SET utf8 COLLATE utf8_bin;";
  if($con->query($sql))
  $report.="Database creation: ok<br>";
  else
  $report.="Database creation: failed<br>";
}  
$sql="CREATE TABLE  $db_name.login(id int NOT NULL AUTO_INCREMENT, user varchar(20), psw TEXT, admin BOOLEAN DEFAULT 0, email varchar(255) COLLATE utf8_bin DEFAULT NULL,RecoveryCode varchar(255) COLLATE utf8_bin DEFAULT NULL, PRIMARY KEY (id, user,email))";
if($con->query($sql))
$report.="Login table creation:ok <br>";
else
$report.="Login table creation: failed<br>";

//Articles
$sql="CREATE TABLE  $db_name.articles (id int(11) NOT NULL AUTO_INCREMENT,title varchar(255) COLLATE utf8_bin DEFAULT NULL,last_edit datetime DEFAULT NULL,content text COLLATE utf8_bin, author varchar(255) COLLATE utf8_bin DEFAULT NULL,PRIMARY KEY (id))";
if($con->query($sql))
$report.="Articles table creation:ok <br>";
else
$report.="Articles table creation: failed<br>";
//Publications
$sql="CREATE TABLE $db_name.publications (id int(11) NOT NULL AUTO_INCREMENT,title varchar(255) COLLATE utf8_bin DEFAULT NULL,datepublish datetime DEFAULT NULL,content int(11) NOT NULL DEFAULT '0' COMMENT 'contiene l''indice dell''articolo per il testo',author varchar(255) COLLATE utf8_bin DEFAULT NULL,Preview varchar(255) COLLATE utf8_bin DEFAULT NULL,'idSection' varchar(255) COLLATE utf8_bin DEFAULT NULL,PRIMARY KEY (id,content)) ";
if($con->query($sql))
$report.="publications table creation:ok <br>";
else
$report.="publications table creation: failed<br>";
$sql="CREATE TABLE $db_name.sections ('id' int(11) NOT NULL AUTO_INCREMENT,'name' varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',PRIMARY KEY ('id','name')) ";
if($con->query($sql))
$report.="Section table creation:ok <br>";
else
$report.="Section table creation: failed<br>";
//TagReference
$sql="CREATE TABLE $db_name.TagReference (ID int(11) NOT NULL AUTO_INCREMENT,TagName varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',IDPublication int(11) NOT NULL DEFAULT '0',PRIMARY KEY (ID,TagName,IDPublication)) ";
if($con->query($sql))
$report.="TagReference table creation:ok <br>";
else
$report.="TagReference table creation: failed<br>";
//Images
$sql="CREATE TABLE $db_name.images (Id int(11) NOT NULL AUTO_INCREMENT,name varchar(255) COLLATE utf8_bin DEFAULT NULL,PRIMARY KEY (Id)) ";
if($con->query($sql))
$report.="images table creation:ok <br>";
else
$report.="images table creation: failed<br>";


//add user to login table
$psw=hash ("sha256",$psw);
$sql="INSERT INTO  $db_name.login (user, psw,admin,email) VALUES ('$usr', '$psw',1,'$email')";
if($con->query($sql))
$report.="User registration: ok<br>";
else
$report.="User registration: failed<br>";
//create json file for database credenziales
$date=new DateTime();
$edit_date= $date->format('Y/m/d H:i:s');//format data time with legit db format
$date= $edit_date;
$credenziales_json='{
    "database_access":
    {
    "username":"'.$db_usr.'",
    "password":"'.$db_psw.'",
    "database_name":"'.$db_name.'",
    "host":"'.$db_host.'"
    } ,
    "email":
    {
        '.$EmailSettings.'
    },
    "last_configuration": "last configuration '.$date.'",
    "isconfigurated":"ok"
    
}';
$file=fopen("config.json","w");
fwrite($file,$credenziales_json);
fclose($file);
$report.="Config file: ok<br>";  
}
else
{
    $report.="Database connection:failed<br>";
}
echo $report;
?>