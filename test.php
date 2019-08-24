<?php
require("config/get_credezialies.php");
   $obj=new getCredenziales();
   $obj=new MySqli($obj->getHost,$obj->getUsername(),$obj->getPassword(),$obj->getDatabase_Name());
   if($obj)
   {
       echo "connected";
   }
   else
   {
       echo "error!";
   }
?>