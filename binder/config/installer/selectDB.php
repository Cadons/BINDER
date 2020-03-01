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
        <script>
        function backdb()
            {
                $("#p2").show();
                $("#p3").hide();
            }       
 </script>
    <head>
    <body>
        <div class="mainbox">
        <img src="/binder/resources/logo.png" style="width:20%">
            <h1>Configuration of Binder</h1>

              
            <div id="p2">
            <h3>Database Confiuration</h3>
                    <label>Can you create new database?</label><br>
                    <div class="radio">
                            <label><input type="radio" name="optname" id="db_create_yes" value="yes" >Yes</label><br>
                        </div>
                    <div class="radio">
                            <label><input type="radio" name="optname" id="db_create_no" value="no" >No</label><br>
             
                        </div>
                        <div class="row">
                                <div class="col-sm-6"> 
                                     <button class="btn btn-default" onclick="history.back();">Back</button>
                                </div>
                                <div class="col-sm-6">
                                                                         <button class="btn btn-default" onclick="databasecfg()">Next</button>

                                     </div>
                                     </div>
                        </div>
                        <div id="p3">
                     <h3>Database Confiuration</h3>
                    <label>Insert database host adderss(void is localhost) </label><br>
                    <input type="text" class="form-control" id='dbhost'><br>
                    <label>Insert database username *</label><br>
                    <input type="text" class="form-control" id='dbusr'><br>
                    <label>Insert database password *</label><br>
                    <input type="password" class="form-control" id='dbpsw'><br>
                    <button onclick="testdb()" class="btn btn-default" style="width:50%;">Test database connection</button><br>
                    <div id="db_name">
                            <label>Insert database name *</label><br>
                            <input type="text" class="form-control" id='dbname'><br><br>
                            </div>
                            <div class="row" style="margin-top:3%">
                                    <div class="col-sm-6">
                                            <button class="btn btn-default" onclick="backdb()">Back</button>                                    </div>
                                    <div class="col-sm-6">
                                            <button class="btn btn-default" onclick="endDbcfg();">Next</button><br>
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