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


//Login
$report="";
if($con)
{
    $report.="Database connection:ok<br>";

 if($db_priv==1)
{
  //create database
  $sqlDB="CREATE DATABASE $db_name CHARACTER SET utf8 COLLATE utf8_bin;";
  
} 
else
{
    $sqlDB="";
}
$sql=$sqlDB."USE ".$db_name.";";
$configFile=fopen("binder.sql","r");
$sql.=fread($configFile,filesize("binder.sql"));
fclose($configFile);
$pepper=bin2hex(random_bytes(5));
$psw=$psw.$pepper;
$psw=hash ("sha256",$psw); 
$sqlusr="INSERT INTO $db_name.user (username, password,isAdmin,email) VALUES ('$usr', '$psw',1,'$email')";
$querys=explode(";",$sql); 
mysqli_select_db($con, $db_name);

foreach($querys as $q)
{

    $con->query($q);
}
$con->close();
$con=new MySqli($db_host,$db_usr,$db_psw,$db_name);//new connection, if you use the old con, it jumped the insert into query

$con->query($sqlusr);



//Generate pepper code


    
 
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
        "isconfigurated":"ok",
        "pepper":"'.$pepper.'"
    }';
    $file=fopen("config.json","w");
    fwrite($file,$credenziales_json);
    fclose($file);
    $report.="Config file: ok<br>";  

    echo mysqli_error($con);
    

}



?>