<?php
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
  session_start();
  include("isAdmin.php");
  require_once(__DIR__."/config/get_credezialies.php");
  if(!isset($_SESSION['log']))
  {
      session_destroy();
      header("location: /binder");
  }
  $obj=new getCredenziales();
$cred=array($obj->getHost(),$obj->getUsername(),$obj->getPassword(),$obj->getDatabase_Name());//$obj->getUsername(),$obj->getPassword(),$obj->getDatabase_Name());
//echo $cred[1];
$conn=new MySqli($cred[0],$cred[1],$cred[2],$cred[3]);

if(check_Admin_internal($conn,$_SESSION['log'],$cred)==0)
{
header("Location:/binder");
}
else
{

if(isset($_POST["action"]))
{
    switch($_POST["action"])
    {
        case "update":
            {
                if(isset($_POST["name"])&&isset($_POST["nameID"]))
                {
                    $name=$_POST["name"];
                    $sql="UPDATE ".$cred[3].".sections SET name='$name' WHERE id=".$_POST["nameID"];
                    if($conn->query($sql))
                    {
                        echo "done";
                    }
                    else
                    {
                        echo "failed";
                    }
                    
                }
                break;
            }
        case "add":
            {
                if(isset($_POST["name"]))
                {
                    $name=$_POST["name"];
                    $sql="INSERT INTO ".$cred[3].".sections (name) VALUES('$name')";
                    if($conn->query($sql))
                    {
                        echo "done";
                    }
                    else
                    {
                        echo "failed";
                    }
                    
                }
                break; 
            }
            
            case "rm":
                {
                    if(isset($_POST["nameID"]))
                    {
                           $sql="UPDATE ".$cred[3].".publications SET idSection=null WHERE idSection=".$_POST["nameID"];
               
                        if($conn->query($sql))
                        {
                              $sql="DELETE FROM ".$cred[3].".sections WHERE id=".$_POST["nameID"];
                            if($conn->query($sql))
                            {
                            echo "done";
                            }else
                            {
                                echo "failed "; 
                            }
                        }
                        else
                        {
                            echo "failed";
                        }
                        
                    }
                    break;
                }
    }
  
}
if(isset($_GET["action"]))
{
    if($_GET["action"]=="get") 
        {
          
             
                $sql="SELECT * FROM ".$cred[3].".sections";
               $ris= $conn->query($sql);
                if(mysqli_num_rows($ris)>0)
                {
                    $data=array();
                 
                    while($row=$ris->fetch_assoc())
                        {
                            
                          
                            $data[]=[$row["name"],$row["id"]];
                            
                        }
                        echo json_encode($data);
                        
                   
                }
                else
                {
                    echo "Nan";
                }
                
            }
             
      
        if($_GET["action"]=="check") 
        {
          
             if(isset($_GET["name"]))
             {
                 $name=$_GET["name"];
                    $sql="SELECT * FROM ".$cred[3].".sections WHERE name='$name'";
               $ris= $conn->query($sql);
                if(mysqli_num_rows($ris)>0)
                {
                   echo "no";
                   
                }
                else
                {
                    echo "ok";
                }
                
             }
             
            }
             
        }
    }



  


?>