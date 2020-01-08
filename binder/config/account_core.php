<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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

if(check_Admin_internal($conn,$_SESSION['log'],$cred)==0)
{
header("Location:/binder");
}
$report="";
if(isset($_POST["add"]))
{
    
    $usr=$_POST["usr"];
    $psw=$_POST["psw"];
    $email=$_POST["email"];
$password = $psw;

$hashedPassword = hash ("sha256",$password);//create hash for database first param is password the second is a string called salt. it is used to create strong hash=> +security

    $sql="INSERT INTO ".$cred[3].".login (user, psw,email) VALUES ('$usr', '$hashedPassword','$email')";
   
    if($conn->query($sql))
         $report.="User registration: ok\n";
    else
        $report.="User registration: failed\n";

        $privileges=$_POST["class"];
        if($privileges=="admin")
        {
            $sql="SELECT user,id FROM ".$cred[3].".login WHERE user='$usr'";
            $ris=$conn->query($sql);
            if(mysqli_num_rows($ris)>0)
            {
                $id;
                while($row=$ris->fetch_assoc())
                     {
                         
                         $id=$row['id'];
                  
                     }
                     $sql="UPDATE ".$cred[3].".login SET admin=1 WHERE id=$id";
                     $conn->query($sql);
                    
            }
        }
}
else if(isset($_GET["list"]))
{
    $sql="SELECT * FROM ".$cred[3].".login";
    $ris=$conn->query($sql);
    $data=array();
    $i=0;
    while($row=$ris->fetch_assoc())
                     {
                         
                         $data[$i]=$row['user'];
                       $i++;
                     }
                     $json=json_encode($data);
                     echo $json;
}
else if(isset($_GET["check"]))
{
    $usr=$_GET['usr'];
    $sql="SELECT * FROM ".$cred[3].".login WHERE lower(user)='$usr' OR upper(user)='$usr'";
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
    $sql="SELECT * FROM ".$cred[3].".login WHERE user='$usr'";
    $ris=$conn->query($sql);
    $id;
    if(mysqli_num_rows($ris)>0)//get id of user
    {
        while($row=$ris->fetch_assoc())
            $id=$row['id'];
    }
    $sql="SELECT * FROM ".$cred[3].".login WHERE id=$id";//check if user is admin
    $ris=$conn->query($sql);
    if(mysqli_num_rows($ris)>0)
    {
         $sql="UPDATE ".$cred[3].".login SET admin=1 WHERE id=$id";//if it is admin delete from admin table
         $conn->query($sql);
    }
       
    $sql="DELETE FROM ".$cred[3].".login WHERE user='$usr'";//remove user from ".$cred[3].".login
    if($conn->query($sql))
        echo "ok";
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
       $sql="UPDATE ".$cred[3].".".$cred[3].".login SET psw='$hashedPassword' where user='$usr'";
   
    if($conn->query($sql))
         $report.="User update: ok\n";
    else
        $report.="User update: failed\n".$sql;
 
    }
    if(isset($email))
    {
       $sql="UPDATE ".$cred[3].".login SET email='$email' where user='$usr'";
   
    if($conn->query($sql))
         $report.="User update: ok\n";
    else
        $report.="User update: failed\n".$sql;
 
    }
    
        $privileges=$_POST["class"];
          $sql="SELECT user,id FROM ".$cred[3].".login WHERE user='$usr'";
            $ris=$conn->query($sql);
       
          
            if(mysqli_num_rows($ris)>0)
            {
                $id;
                while($row=$ris->fetch_assoc())
                     {
                         
                         $id=$row['id'];
                  
                     }
                     if($privileges=='writer')
                     {
                        $sql="UPDATE ".$cred[3].".login SET admin=0 WHERE id=$id";//if it is admin delete from admin table
                        $conn->query($sql);
                     }
                     else
                      {
                        $sql="UPDATE ".$cred[3].".login SET admin=1 WHERE id=$id";
                        $conn->query($sql);
                      }
                     
        }
        echo "ok".$report;
}
else if(isset($_GET["isadmin"]))

{
    $usr=$_GET["usr"];
    $sql="SELECT user,id FROM ".$cred[3].".login WHERE user='$usr'";
    $ris=$conn->query($sql);

     $id;
    if(mysqli_num_rows($ris)>0)
    {
     
        while($row=$ris->fetch_assoc())
             {
                 
                 $id=$row['id'];
          
             }
             check_Admin($conn,$id,$cred);

            }
}
else if(isset($_GET["get_email"]))

{
    $usr=$_GET["usr"];
    $sql="SELECT user,email FROM ".$cred[3].".login WHERE user='$usr'";
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
    $sql="SELECT * FROM ".$cred[3].".login WHERE id=$id AND admin=1";//check if user is admin
    $ris=$conn->query($sql);
    if(mysqli_num_rows($ris)>0)
    {
         echo "ok";
    }
    else
    {
        echo "error";
    }
}

?>