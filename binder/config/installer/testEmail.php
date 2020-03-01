<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require('PHPMailer.php');
require('SMTP.php');
require('Exception.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

function mailSender($sendTo,$msgMail)
{
if(mail($sendTo,"BinderTest",$msgMail))
    return 1;
    else
    return 0;
}

function SMTP_email($email,$msgMail,$_address,$_port,$_usr,$_psw){

    // validate_email è una funzione che controlla la correttezza della mail
        //invia la mail
       
        $mail = new PHPMailer();
        $mail->IsSMTP(); // Utilizzo della classe SMTP al posto del comando php mail()
        $mail->SMTPAuth = true; // Autenticazione SMTP
        $mail->Host = $_address;
        $mail->Port = $_port;
        //$mail->SMTPDebug = 2;
        $mail->SMTPSecure = "ssl";
        $mail->Username = $_usr; // Nome utente SMTP autenticato
        $mail->Password = $_psw; // Password account email con SMTP autenticato
        $mail->Priority = 1; // Highest priority - Email priority (1 = High, 3 = Normal, 5 = low)
        $mail->FromName = "BinderTest";
        $mail->From='your_gmail_id@gmail.com';
        $mail->AddAddress($email);
        $mail->IsHTML(true);
        $mail->Subject  =  "BinderTest";
        $mail->Body     =  $msgMail;
        $mail->AltBody  =  "";
        if(!$mail->Send()){
                echo "errore nell'invio della mail: ".$mail->ErrorInfo;
                return false;
        }else{
                return true;
        }
       
}

$msg="Hi this is a binder notify\n if you have received me your email serivice works!\n you can continue the configuration.";

if(isset($_POST["method"]))
{
   $mailto=$_POST["mailto"];
   $method=$_POST["method"]; 
   if($method==1)
   {
       $address=$_POST["smtpAddress"];
       $port=$_POST["smtpPort"];
      
       $usr=$_POST["smtpUsr"];
       $psw=$_POST["smtpPsw"];
       try {
          
      echo SMTP_email($mailto,$msg,$address,$port,$usr,$psw);
      
        } catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
    }
   }
   else
   {
       mailSender($mailto,$msg);
   }
}


?>