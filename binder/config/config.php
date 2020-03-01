<?php
if(!file_exists("config.json"))
{

    header("Location:installer/createUser.php");
}
else
{
    header("Location:../");
}