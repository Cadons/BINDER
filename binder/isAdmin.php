<?php
function check_Admin_internal($conn,$id,$cred)
{
    $sql="SELECT idUser FROM ".$cred[3].".users WHERE idUser=$id AND isAdmin=1";
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
?>