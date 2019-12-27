<?php
 error_reporting(E_ALL);
 ini_set('display_errors', 'On');
/**
 * It Changes configuration file JSON with new settings
 * It does the same process done during first configuration
 */
if(isset($_POST["db_host"])&&isset($_POST["db_usr"])&&isset($_POST["db_psw"])&&isset($_POST["db_name"])&&isset($_POST["mtype"]))
{
        $db_host=$_POST["db_host"];
        $db_usr=$_POST["db_usr"];
        $db_psw=$_POST["db_psw"];
        $db_name=$_POST["db_name"];
        $mailSend=$_POST["mtype"];
        $smtpAddress=$_POST["smtpAddress"];
        $smtpPort=$_POST["smtpPort"];
        $smtpUsr=$_POST["smtpUsr"];
        $smtpPsw=$_POST["smtpPsw"];
        $EmailSettings="";
        if($mailSend==1)
        {
            if(isset($_POST["smtpAddress"])&&isset($_POST["smtpPort"])&&isset($_POST["smtpUsr"])&&isset($_POST["smtpPsw"]))
            {
            $smtpAddress=$_POST["smtpAddress"];
            $smtpPort=$_POST["smtpPort"];
            $smtpUsr=$_POST["smtpUsr"];
            $smtpPsw=$_POST["smtpPsw"];
            $EmailSettings.='"address":"'.$smtpAddress.'",
                            "port":"'.$smtpPort.'",
                            "username":"'.$smtpUsr.'",
                            "password":"'.$smtpPsw.'",
                            "mail":0';
            }
       
        }else
        {
            $EmailSettings.='"mail":1';
        }
        $date=new DateTime();
        $edit_date= $date->format('Y/m/d H:i:s');//format data time with legit db format
        $date= $edit_date;
        $credenziales_json='{
            "database_access":
            {
            "username":"'.$db_usr.'",
            "password":"'.$db_psw.'",
            "database_name":"'.$db_name.'",
            "host":"'.$db_host.'"
            } ,
            "email":
            {
                '.$EmailSettings.'
            },
            "last_configuration": "last configuration '.$date.'",
            "isconfigurated":"ok"
            
        }';
        $file=fopen("config.json","w");
        fwrite($file,$credenziales_json);
        fclose($file);
        header("Location: /binder");
}else
{
    echo "error";
    
}

?>