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
            
            <div id="p4">
                <h3>Email Confiuration</h3>
                <label>Select emails sending method</label><br>
                    <div class="radio">
                            <label><input type="radio" name="optname" id="smtp" value="1" >SMTP</label><br>
                        </div>
                    <div class="radio">
                            <label><input type="radio" name="optname" id="mailphp" value="2" >via Php(mail())</label><br>
             
                        </div>
                        <div class="row " style="margin-top:3%">
                                <div class="col-sm-6"> 
                                     <button class="btn btn-default" onclick="history.back();">Back</button>
                                </div>
                                <div class="col-sm-6">
                                     <button class="btn btn-default" onclick="Emailcfg()">Next</button>
                                     </div>
                        </div>
 
                 </div>
            <div id="p5">
            <script>
            function backmail()
            {
                $("#p4").show();
                $("#p5").hide();
            }       
 </script>
                        
                        <div id="mail_smtp">
                            <label>Insert SMTP address *</label><br>
                            <input type="text" class="form-control" id='smtpAddress'><br>
                            <label>Insert SMTP port *</label><br>
                            <input type="number" class="form-control" id='smtpPort'><br>
                          
                            <label>Insert SMTP username *</label><br>
                            <input type="text" class="form-control" id='smtp_usr'><br>
                            <label>Insert SMTP password *</label><br>
                            <input type="password" class="form-control" id='smtp_psw'><br>
                            <hr>
                            <h4>Test Service</h4>
                            <label>Insert Email destination</label><br>
                            <input type="email" class="form-control" id='smtp_test'><br>
                            <button onclick="TestEmail()" class="btn btn-default" style="width:50%;">Test</button>
                        </div>
                            
                            <div id="mail_php">
                                        <h4>Test Service</h4>
                                        <label>Insert Email destination</label><br>
                                        <input type="email" class="form-control" id='mail_test'><br>
                                        <button onclick="TestEmail()" class="btn btn-default" style="width:50%;">Test</button>
                                    </div>
                                    <div class="row" style="margin-top:3%">
                                        <div class="col-sm-6"> 
                                            <button class="btn btn-default" onclick="backmail()">Back</button>
                                        </div>
                                        <div class="col-sm-6">
                                            <button class="btn btn-default" onclick="EndConfiguration()">Next</button>

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