<?php

    session_start();
    if(!isset($_SESSION['log']))
    {
        session_destroy();
        header("location: /binder");
    }
    include("../isAdmin.php");
require_once("get_credezialies.php");

$obj=new getCredenziales();
$cred=array($obj->getHost(),$obj->getUsername(),$obj->getPassword(),$obj->getDatabase_Name());//$obj->getUsername(),$obj->getPassword(),$obj->getDatabase_Name());
//echo $cred[1];

$conn=new MySqli($cred[0],$cred[1],$cred[2],$cred[3]);


$report="";
if(isset($_POST["add"]))
{
    
    $usr=$_POST["usr"];
    $psw=$_POST["psw"];
    $email=$_POST["email"];
$password = $psw;

$hashedPassword = hash ("sha256",$password);//create hash for database first param is password the second is a string called salt. it is used to create strong hash=> +security

    $sql="INSERT INTO ".$cred[3].".users (username, password,email) VALUES ('$usr', '$hashedPassword','$email')";
   
    if($conn->query($sql))
         $report.="User registration: ok\n";
    else
        $report.="User registration: failed\n";

        $privileges=$_POST["class"];
        if($privileges=="admin")
        {
            $sql="SELECT username,idUser FROM ".$cred[3].".users WHERE username='$usr'";
            $ris=$conn->query($sql);
            if(mysqli_num_rows($ris)>0)
            {
                $id;
                while($row=$ris->fetch_assoc())
                     {
                         
                         $id=$row['id'];
                
                     }
                     $sql="UPDATE ".$cred[3].".users SET isAdmin=1 WHERE id=$id";
                     $conn->query($sql);
                    
            }
        }
}
else if(isset($_GET["list"]))
{

    if(check_Admin_internal($conn,$_SESSION['log'],$cred)==1)
    $sql="SELECT * FROM ".$cred[3].".users";
    else
    $sql="SELECT * FROM ".$cred[3].".users WHERE idUser=".$_SESSION['log'];
     
    $ris=$conn->query($sql);
    $data=array();
    $i=0;
    while($row=$ris->fetch_assoc())
                     {
                         
                         $data[$i]=$row['username'];
                         
                       $i++;
                     }
                     $json=json_encode($data);
                     echo $json;
}
else if(isset($_GET["check"]))
{
    $usr=$_GET['usr'];
    $sql="SELECT * FROM ".$cred[3].".users WHERE lower(username)='$usr' OR upper(username)='$usr'";
    $ris=$conn->query($sql);
    if(mysqli_num_rows($ris)>0)
    {
        echo "false";
    }
    else
    {
        echo "true";
    }
}
else if(isset($_GET["del"]))
{
    
    $usr=$_GET['usr'];
    $sql="SELECT * FROM ".$cred[3].".users WHERE username='$usr'";
    $ris=$conn->query($sql);
    $id;
    if(mysqli_num_rows($ris)>0)//get id of user
    {
        while($row=$ris->fetch_assoc())
            $id=$row['idUser'];
    }
    $sql="DELETE FROM ".$cred[3].".users WHERE idUser='$id'";//remove user from ".$cred[3].".users
    if($conn->query($sql))
    {
        if(check_Admin_internal($conn,$_SESSION['log'],$cred)==0)
        echo "logout";
        else
        echo "ok";
    }
        
    else
        echo "error";

}
else if(isset($_POST["update"]))
{
   
    $usr=$_POST["usr"];
    $psw=$_POST["psw"];
    $email=$_POST["email"];
    $password = $psw;
    $hashedPassword = hash ("sha256",$password);//create hash for database first param is password the second is a string called salt. it is used to create strong hash=> +security

    if(isset($psw))
    {
       $sql="UPDATE ".$cred[3].".".$cred[3].".users SET password='$hashedPassword' where username='$usr'";
   
    if($conn->query($sql))
         $report.="User update: ok\n";
    else
        $report.="User update: failed\n".$sql;
 
    }
    if(isset($email))
    {
       $sql="UPDATE ".$cred[3].".users SET email='$email' where username='$usr'";
   
    if($conn->query($sql))
         $report.="User update: ok\n";
    else
        $report.="User update: failed\n".$sql;
 
    }
    
        $privileges=$_POST["class"];
          $sql="SELECT username,idUser FROM ".$cred[3].".users WHERE username='$usr'";
            $ris=$conn->query($sql);
       
          
            if(mysqli_num_rows($ris)>0)
            {
                $id;
                while($row=$ris->fetch_assoc())
                     {
                         
                         $id=$row['idUser'];
                  
                     }
                     if($privileges=='writer')
                     {
                        $sql="UPDATE ".$cred[3].".users SET isAdmin=0 WHERE idUser=$id";//if it is admin delete from admin table
                        $conn->query($sql);
                     }
                     else
                      {
                        $sql="UPDATE ".$cred[3].".users SET isAdmin=1 WHERE idUser=$id";
                        $conn->query($sql);
                      }
                      echo $sql;
                     
        }
        echo "ok";
}
else if(isset($_GET["isadmin"]))

{
    $id=$_GET["usr"];
        check_Admin($conn,$id,$cred);
 
}
else if(isset($_GET["get_email"]))

{
    $usr=$_GET["usr"];
    $sql="SELECT username,email FROM ".$cred[3].".users WHERE username='$usr'";
    $ris=$conn->query($sql);

     $id;
    if(mysqli_num_rows($ris)>0)
    {
     
        $row=$ris->fetch_assoc();
            
        $email=$row['email'];
        echo $email;
             
         

    }
}
//echo $report;                  
function check_Admin($conn,$id,$cred)
{
    $sql="SELECT * FROM ".$cred[3].".users WHERE (username='$id' or idUser='$id') AND isAdmin=1";//check if user is admin

    if($ris=$conn->query($sql))
    {
           if(mysqli_num_rows($ris)>0)
    {
         echo "ok";
    }
    else
    {
        echo "error";
    } 
    }else
    {
        echo "error";
    }

}

?>