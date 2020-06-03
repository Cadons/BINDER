<?php

require_once("config/get_credezialies.php");
include("config/SendEmail.php");
$obj=new getCredenziales();
$cred=array($obj->getHost(),$obj->getUsername(),$obj->getPassword(),$obj->getDatabase_Name());//$obj->getUsername(),$obj->getPassword(),$obj->getDatabase_Name());
//echo $cred[1];
$conn=new MySqli($cred[0],$cred[1],$cred[2],$cred[3]);
$urlCode=bin2hex(random_bytes(24));

$emailSettings=array($obj->getSMTPaddress(),$obj->getSMTPport(),$obj->getSMTPusername(),$obj->getSMTPpassword(),$obj->getEmailType());
/**
 * Send email using credentialies gave from admin during the configuration
 */
if(isset($_POST["email"])&&isset($_POST["check"]))
        { 
            if(checkEmail($_POST["email"],$conn,$cred[3])==200)
            {
                echo "200";
            }
            else
            {
                echo "404";
            }
        }
      
        if(isset($_POST["email"])&&isset($_POST["send"]))
        { 
            if(checkEmail($_POST["email"],$conn,$cred[3])==200)
            {
                echo SendEmail($conn,$emailSettings,$urlCode,$cred[3]);
            }

        }
        if(isset($_POST["id"])&&isset($_POST["psw"]))
        { 
            ChangePassword($conn,$_POST["id"],$_POST["psw"],$cred[3]);
        }
      
 function checkEmail($_email,$conn,$dbName)
 {
    $_sql="SELECT email FROM ".$dbName.".user where email='$_email'";
    
    $_ris=$conn->query($_sql);
    if(mysqli_num_rows($_ris)>0)
    {
        return 200;//founded
    }
    else
    {
        return 404;//email doesn't exist
    }
 }

function SendEmail($conn,$emailSettings,$urlCode,$dbName)
{
    $url=$_SERVER['HTTP_HOST']."/binder/UpdatePassword.php?id=".$urlCode;//compose url to recover data
    $my_msg="Hi, to reset password click the following link:<br><a href='".$url."'>Reset Password</a>";
  if(isset($_POST["email"]))
        { 
             $user_email=$_POST["email"];
            // echo $user_email."<br>".print_r($emailSettings);
            if($emailSettings[4]=="0")
            {
            
                $sql="UPDATE $dbName.user SET recovery='$urlCode' WHERE email='$user_email'";
            
                $conn->query($sql);
                $v=SMTP_email($user_email,$my_msg,$emailSettings[0],$emailSettings[1],$emailSettings[2],$emailSettings[3],"Reset Password");
                
               
             
            }
            else
            {
                $v=mailSender($user_email,$my_msg,"Reset Passowrd");
            }  
                
            if($v==1)
            {
                return 200;//sended
            }
            else
            {
                return 500;//not sended
            }
        }
}
function ChangePassword($conn,$code,$password,$dbName)
{
 
    $obj=new getCredenziales();

    $password=$password.$obj->getPepper();
    $hashedPassword = hash ("sha256",$password);//create hash for database first param is password the second is a string called salt. it is used to create strong hash=> +security

    $sql="UPDATE ".$dbName.".user SET password='$hashedPassword', recovery=null where recovery='$code'";
    if($conn->query($sql))
    {
        echo "done";
    }
    else
    {
        echo "error";
    }

}

?>