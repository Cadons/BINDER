<?php

?>
<html>
    <head>
        <title>Configure MYPRESS</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
        <meta name="viewport" content="width=device-width, user-scalable=no,
        initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        
        <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
        <script src="config.js"></script>
        <link rel="stylesheet" href="style.css">
    <head>
    <body>
        <div class="mainbox">
            <h1>Configuration of MY PRESS</h1>
            <div id="p1">
                <label>Insert an username</label><br>
                <input type="text" class="form-control" id="fuser"><br>
                <label>Insert a password</label><br>
                <input type="password" class="form-control" id="fpsw"><br>
                <div class="row">
                    <div class="col-sm-12">
                    
                <button class="btn btn-default" onclick="firstuser()" >Next</button>
            </div>
            </div>
                    </div>
              
            <div id="p2">
                
                    <label>Can you create new database?</label><br>
                    <div class="radio">
                            <label><input type="radio" name="optname" id="db_create_yes" value="yes" >Yes</label><br>
                        </div>
                    <div class="radio">
                            <label><input type="radio" name="optname" id="db_create_no" value="no" >No</label><br>
             
                        </div>
                        <div class="row">
                                <div class="col-sm-6"> 
                                     <button class="btn btn-default" onclick="back()">Back</button>
                                </div>
                                <div class="col-sm-6">
                                                                         <button class="btn btn-default" onclick="databasecfg()">Next</button>

                                     </div>
                        </div>
                      
                     
                </div>
                <div id="p3">
                     
                    <label>Insert database host adderss(void is localhost) </label><br>
                    <input type="text" class="form-control" id='dbhost'><br>
                    <label>Insert database username *</label><br>
                    <input type="text" class="form-control" id='dbusr'><br>
                    <label>Insert database password *</label><br>
                    <input type="password" class="form-control" id='dbpsw'><br>
                    <button onclick="testdb()" class="btn btn-default" style="width:50%;">Test database connection</button>
                    <div id="db_name">
                            <label>Insert database name *</label><br>
                            <input type="text" class="form-control" id='dbname'><br>
                            </div>
                            <div class="row">
                                    <div class="col-sm-6">
                                            <button class="btn btn-default" onclick="back()">Back</button>                                    </div>
                                    <div class="col-sm-6">
                                            <button class="btn btn-default" onclick="testdb();endcfg()">Next</button><br>
                                         </div>
                            </div>

                </div>
                
                <div id="p4">
                 
                    <div >
                        <table id="bill" class="table">
                            
                        </table>
                    </div>
                    <div class="row">
                            <div class="col-sm-6">
                                    <button class="btn btn-default" onclick="back()">Back</button>                                    </div>
                            <div class="col-sm-6">
                                    <button class="btn btn-default" onclick="SendtoConfig()">Set up system</button>
                                 </div>
                    </div>
                   

                 
                </div>
        </div>
    </body>
</html>