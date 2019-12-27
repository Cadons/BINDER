<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require('PHPMailer.php');
require('SMTP.php');
require('Exception.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

function mailSender($sendTo,$msgMail,$subject)
{
if(mail($sendTo,$subject,$msgMail))
    return 1;
    else
    return 0;
}

function SMTP_email($email,$msgMail,$_address,$_port,$_usr,$_psw,$subject){

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
        $mail->FromName = "Binder";
        $mail->From='your_gmail_id@gmail.com';
        $mail->AddAddress($email);
        $mail->IsHTML(true);
        $mail->Subject  =  $subject;
        $mail->Body     =  $msgMail;
        $mail->AltBody  =  "";
        if(!$mail->Send()){
                echo "errore nell'invio della mail: ".$mail->ErrorInfo;
                return false;
        }else{
                return true;
        }
       
}



?>
