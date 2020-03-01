<?php
if(!file_exists("../config.json"))
{
?>
<html>
    <head>
        <title>Configure Binder</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
        <meta name="viewport" content="width=device-width, user-scalable=no,
        initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
        <!-- jQuery library -->
        <script src="/binder/resources/js/jquery.js"></script>        
        <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
        <script src="config.js"></script>
        <link rel="stylesheet" href="style.css">
    <head>
    <body>
        <div class="mainbox">
            
            <img src="/binder/resources/logo.png" style="width:20%">
            <h1>Configuration of Binder</h1>
            <div id="p1">
            <h3>Add an account</h3>
                <label>Insert an username</label><br>
                <input type="text" class="form-control" id="fuser"><br>
                <label>Insert email</label><br>
                <input type="email" class="form-control" id="fmail"><br>
                <label>Insert a password</label><br>
                <input type="password" class="form-control" id="fpsw"><br>
                <div class="row">
                    <div class="col-sm-13">
                    
                <button class="btn btn-default" onclick="firstuser()" >Next</button>
            </div>
            </div>
    </body>
</html><?php
}
else
{
    header("Location:../../");
}