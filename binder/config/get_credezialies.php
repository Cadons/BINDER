<?php

   /* echo "Credeziales:<br>";
   
  for($i=0;$i<sizeof($data["database_access"]);$i++)
    echo $ref[$i]." : ".$data["database_access"][$ref[$i]]."<br>";

  $cred=new getCredenziales();
   echo ($cred->getHost());  */


   //require("../config/get_credezialies.php");
      //$obj=new getCredenziales();
     // echo ($obj->y());// $obj=new MySqli($obj->getHost(),$obj->getUsername(),$obj->getPassword(),$obj->getDatabase_Name());
     

  class getCredenziales
   {
        private $json=array();
         
       public function __construct ()
        {
            
            $this->json=$this->getJSON();
            //echo "data:".$this->json;
        }
        public function y()
        {
            return $this->json["database_access"]["host"];
        }
    public function getUsername()
        {
           $get=array();
           $get=$this->json;
           return $get["database_access"]['username'];
        }
        public function getPassword()
        {
            $get=array();
            $get= $this->json;
            
            return $get["database_access"]['password'];
        }

        public function getDatabase_Name()
        {
            $get=array();
            $get= $this->json;
            return $get["database_access"]['database_name'];
        }
        public function getHost()
        {
            $get=array();
            $get= $this->json;
            return $get["database_access"]['host'];
        }
        public function getJSON()
        {
            $json=file_get_contents(__DIR__."/config.json");
            //    var_dump($json);
               $data = json_decode($json, true);
               //var_dump($data); // print array
            //  $result=array("username","password","database_name","host");
                    return $data;            
        }
//email credentialies
   
        public function getSMTPaddress()
        {
            $get=array();
            $get= $this->json;
            return $get["email"]['address'];
        }
        public function getSMTPport()
        {
            $get=array();
            $get= $this->json;
            return $get["email"]['port'];
        }
        public function getSMTPusername()
        {
            $get=array();
            $get= $this->json;
            return $get["email"]['username'];
        }
        public function getSMTPpassword()
        {
            $get=array();
            $get= $this->json;
            return $get["email"]['password'];
        }
        public function getEmailType()
        {
            $get=array();
            $get= $this->json;
            return $get["email"]['mail'];
        }  
         //get last edit
         public function getLastEdit()
         {
             $get=array();
             $get= $this->json;
             return $get['last_configuration'];
         }  
   }


?>