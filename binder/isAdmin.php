<?php
function check_Admin_internal($conn,$usr,$cred)
{
    $sql="SELECT user,id FROM ".$cred[3].".login WHERE user='$usr' AND admin=1";
    $ris=$conn->query($sql);

     $id;
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