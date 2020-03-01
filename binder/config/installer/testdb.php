<?php

 if($_POST["req"]=="testdb")
   {
    $db_host=$_POST["db_host"];
    $db_usr=$_POST["db_usr"];
    $db_psw=$_POST["db_psw"];
    $connection=mysqli_connect($db_host,$db_usr,$db_psw);
    if (!$connection) {
        
      echo "error";
    } 
    else
    echo "ok";
    
   
   }

?>