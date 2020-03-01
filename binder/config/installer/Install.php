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

            <div id="p6">
                 
                 <div >
                     <table id="bill" class="table">
                         
                     </table>
                 </div>
                 <div class="row">
                         <div class="col-sm-6">
                                 <button class="btn btn-default" onclick="history.back();">Back</button>                                    </div>
                         <div class="col-sm-6">
                                 <button class="btn btn-default" onclick="SendtoConfig()">Set up system</button>
                              </div>
                 </div>
                 <div class="row">
                         <div class="col-sm-12" id="output">
                                 
                              </div>
                 </div>
                

              
             </div>
            </div>
    </body>
</html><?php
}
else
{
    header("Location:../../");
}