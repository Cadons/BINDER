<?php
require_once("get_data.php");

$req=$_GET["get"];
$id="";
if(isset($_GET["id"]))
$id=$_GET["id"];

$binder=new get_data();
switch ($req) {
    case 'title':
        echo $binder->GetPostTitle($id);
        break;
        case 'id':
        echo $binder->GetIdList();
        break;
    case 'post':
        echo $binder->GetMyPost($id);
        break;
    case 'article':
        echo $binder->GetMyPost($id);
        break;
        case 'postlist':
        $output=$binder->GetTitleList();
        echo ($output);
        break;
    default:
       echo "none";
        die();
        break;
}
die();
?>