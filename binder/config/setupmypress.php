<?php
$usr=$_POST["usr"];
$psw=$_POST["psw"];
$db_host=$_POST["db_host"];
$db_usr=$_POST["db_usr"];
$db_psw=$_POST["db_psw"];
$db_name=$_POST["db_name"];
$db_priv=$_POST["db_priv"];
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
    $report.="Database connection:ok\n";

 if($db_priv==1)
{
  //create database
  $sql="CREATE DATABASE $db_name CHARACTER SET utf8 COLLATE utf8_bin;";
  if($con->query($sql))
  $report.="Database creation: ok\n";
  else
  $report.="Database creation: failed\n";
}  
$sql="CREATE TABLE  $db_name.login(id int NOT NULL AUTO_INCREMENT, user varchar(20), psw TEXT, PRIMARY KEY (id))";
if($con->query($sql))
$report.="Login table creation:ok \n";
else
$report.="Login table creation: failed\n";
$sql="CREATE TABLE  $db_name.admin(id int NOT NULL AUTO_INCREMENT, iduser int, PRIMARY KEY (id))";
if($con->query($sql))
$report.="Admin table creation:ok \n";
else
$report.="Login table creation: failed\n";
//Articles
$sql="CREATE TABLE  $db_name.articles(id int NOT NULL AUTO_INCREMENT, title varchar(255), last_edit DATETIME, content TEXT, author varchar(255), PRIMARY KEY (id))";
if($con->query($sql))
$report.="Articles table creation:ok \n";
else
$report.="Articles table creation: failed\n";
//Publications
$sql="CREATE TABLE  $db_name.publications(id int NOT NULL AUTO_INCREMENT, title varchar(255), 	datepublish DATETIME, content TEXT, author varchar(255), PRIMARY KEY (id))";
if($con->query($sql))
$report.="publications table creation:ok \n";
else
$report.="publications table creation: failed\n";
//add user to login table
$password = $psw;

$psw = crypt($password, '$2a$07$usesomesillystringforsalt$');//create hash for database first param is password the second is a string called salt. it is used to create strong hash=> +security

$sql="INSERT INTO  $db_name.login (user, psw) VALUES ('$usr', '$psw')";
if($con->query($sql))
$report.="User registration: ok\n";
else
$report.="User registration: failed\n";
$sql="INSERT INTO  $db_name.admin (iduser) VALUES (1)";
if($con->query($sql))
$report.="User registration: ok\n";
else
$report.="User registration: failed\n";
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
    "last_configuration": "last configuration '.$date.'",
    "isconfigurated":"ok"
}';
$file=fopen("dbaccess.json","w");
fwrite($file,$credenziales_json);
fclose($file);
$report.="Config file: ok\n";  
}
else
{
    $report.="Database connection:failed\n";
}
echo $report;
?>