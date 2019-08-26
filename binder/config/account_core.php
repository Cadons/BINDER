<?php
    session_start();
    if(!isset($_SESSION['log']))
    {
        session_destroy();
        header("location: /binder");
    }
require_once("get_credezialies.php");
$obj=new getCredenziales();
$cred=array($obj->getHost(),$obj->getUsername(),$obj->getPassword(),$obj->getDatabase_Name());//$obj->getUsername(),$obj->getPassword(),$obj->getDatabase_Name());
//echo $cred[1];

$conn=new MySqli($cred[0],$cred[1],$cred[2],$cred[3]);

if(check_Admin_internal($conn,$_SESSION['log'])==0)
{
header("Location:/binder");
}
$report="";
if(isset($_POST["add"]))
{
    
    $usr=$_POST["usr"];
    $psw=$_POST["psw"];
$password = $psw;

$hashedPassword = crypt($password, '$2a$07$usesomesillystringforsalt$');//create hash for database first param is password the second is a string called salt. it is used to create strong hash=> +security

    $sql="INSERT INTO login (user, psw) VALUES ('$usr', '$hashedPassword')";
   
    if($conn->query($sql))
         $report.="User registration: ok\n";
    else
        $report.="User registration: failed\n";

        $privileges=$_POST["class"];
        if($privileges=="admin")
        {
            $sql="SELECT user,id FROM login WHERE user='$usr'";
            $ris=$conn->query($sql);
            if(mysqli_num_rows($ris)>0)
            {
                $id;
                while($row=$ris->fetch_assoc())
                     {
                         
                         $id=$row['id'];
                  
                     }
                     $sql="INSERT INTO admin (iduser) VALUES ($id)";
                     $conn->query($sql);
                    
            }
        }
}
else if(isset($_GET["list"]))
{
    $sql="SELECT * FROM login";
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
    $sql="SELECT * FROM login WHERE lower(user)='$usr' OR upper(user)='$usr'";
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
    $sql="SELECT * FROM login WHERE user='$usr'";
    $ris=$conn->query($sql);
    $id;
    if(mysqli_num_rows($ris)>0)//get id of user
    {
        while($row=$ris->fetch_assoc())
            $id=$row['id'];
    }
    $sql="SELECT * FROM admin WHERE iduser=$id";//check if user is admin
    $ris=$conn->query($sql);
    if(mysqli_num_rows($ris)>0)
    {
         $sql="DELETE FROM admin WHERE iduser=$id";//if it is admin delete from admin table
         $conn->query($sql);
    }
       
    $sql="DELETE FROM login WHERE user='$usr'";//remove user from login
    if($conn->query($sql))
        echo "ok";
    else
        echo "error";

}
else if(isset($_POST["update"]))
{
   
    $usr=$_POST["usr"];
    $psw=$_POST["psw"];
    $password = $psw;
    $hashedPassword = crypt($password, '$2a$07$usesomesillystringforsalt$');//create hash for database first param is password the second is a string called salt. it is used to create strong hash=> +security

    if(isset($psw))
    {
       $sql="UPDATE ".$cred[3].".login SET psw='$hashedPassword' where user='$usr'";
   
    if($conn->query($sql))
         $report.="User update: ok\n";
    else
        $report.="User update: failed\n".$sql;
 
    }
    
        $privileges=$_POST["class"];
          $sql="SELECT user,id FROM login WHERE user='$usr'";
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
                        $sql="DELETE FROM admin WHERE iduser=$id";//if it is admin delete from admin table
                        $conn->query($sql);
                     }
                     else
                      {
                        $sql="INSERT INTO admin (iduser) VALUES ($id)";
                        $conn->query($sql);
                      }
                     
        }
        echo "ok".$report;
}
else if(isset($_GET["isadmin"]))

{
    $usr=$_GET["usr"];
    $sql="SELECT user,id FROM login WHERE user='$usr'";
    $ris=$conn->query($sql);

     $id;
    if(mysqli_num_rows($ris)>0)
    {
     
        while($row=$ris->fetch_assoc())
             {
                 
                 $id=$row['id'];
          
             }
             check_Admin($conn,$id);

            }
}

//echo $report;                  
function check_Admin($conn,$id)
{
    $sql="SELECT * FROM admin WHERE iduser=$id";//check if user is admin
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
function check_Admin_internal($conn,$usr)
{
    $sql="SELECT user,id FROM login WHERE user='$usr'";
    $ris=$conn->query($sql);

     $id;
    if(mysqli_num_rows($ris)>0)
    {
     
        while($row=$ris->fetch_assoc())
        {
                    
                            $id=$row['id'];
                    $sql="SELECT * FROM admin WHERE iduser=$id";//check if user is admin
                $ris=$conn->query($sql);
                if(mysqli_num_rows($ris)>0)
                {
                    return 1;
                }
                else
                {
                    return 0;
                }
       }
    
    }
    else
    {
        return 0;
    }
}
?>