<?php
require('imageupload.php');
$img=$_FILES['img'];
$upload=new UploadImage();
echo $upload->Upload('img',"../img/");

?>