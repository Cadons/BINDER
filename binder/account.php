<!DOCTYPE html>
    <?php
    session_start();
    if(!isset($_SESSION['log']))
    {
        session_destroy();
        header("location: /binder");
    }
 
    ?>
    
<html>
<head>
        <title>Accounts Menager</title>
     
        <meta name="viewport" content="width=device-width, user-scalable=no,
        initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script>
        <link href="style.css" rel="stylesheet">
        <script src="script.js"></script>
        <script src="/binder/resources/script.js"></script>
              <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">

          <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

          <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
        <meta charset="UTF-8">  
         <script>
            isAdmin('<?php echo $_SESSION['log']; ?>');
          </script>
</head>
<body>

<div class="articlelist">
        <nav class="navbar navbar-inverse">
                <div class="container-fluid">
                  <div class="navbar-header">
                    <a class="navbar-brand" href="/binder/menager.php">BINDER</a>
                  </div>
                  <ul class="nav navbar-nav">
                    <li><a onclick="New()" style="cursor: pointer">New article</a></li>
                    <li><a href="/binder/menager.php?">My Articles</a></li>
                    <li><a href="/binder/menager.php?published">My Publications</a></li>
                    <li id="account_menager"><a href="/binder/account.php">Accounts Menager</a></li>
                    <li><a href="/binder/output/">Publications</a></li>
                  </ul>
                  <ul class="nav navbar-nav navbar-right">
                   
                    <li><a onclick="logout()" style="cursor: pointer"><span class="glyphicon glyphicon-log-in"></span> Logout</a></li>
                  </ul>
                </div>
              </nav>
              <button class = "btn btn-default btn-lg" type="button" onclick="Open_New_Usr_Panel()">  <span class="glyphicon glyphicon-plus"></span></button> 
              <button class = "btn btn-default btn-lg" type="button" onclick="Account_List()"><span class="glyphicon glyphicon-repeat"></span></button> 
            <div class="create_account" id="account">
                <label>Insert username</label>
                <input type="text" id="usr" class="form-control"><br>
                <label>Insert password</label><br>
                <input type="password" id="psw" class="form-control"><br>
                <label>confirm password</label><br>
                <input type="password" id="pswconf" class="form-control"><br>
                <label>Select permission of account</label>
                <select class="form-control" id="permissions" onchange="permission_set()">
                  <option value="writer" >Writer</option>
                  <option value="admin">Admin</option>
                </select><br>
         
            <button class = "btn btn-default btn-lg" style="  width:100%;" type="button" onclick="check_Usr()">Add new Account</button> <br>
            <button class = "btn btn-default btn-lg" style="  width:100%;" type="button" onclick="Close_New_Usr_Panel()">Close</button> 
            </div>
            <div class="create_account" id="update_account">
              <label>Username:</label><label id="usru"></label><br>
              <label>Insert new password</label><br>
              <input type="password" id="pswu" class="form-control"><br>
              <label>confirm new password</label><br>
              <input type="password" id="pswconfu" class="form-control"><br>
              <label>Select permission of account</label>
              <select class="form-control" id="permissionsu" onchange="permission_set()">
                  <option value="writer" id="w" >Writer</option>
                  <option value="admin" id="a">Admin</option>
              </select><br>
              
            
              
              
         
          <button class = "btn btn-default btn-lg" style="  width:100%;" type="button" onclick="Update_Account()">Update Account</button> <br>
          <button class = "btn btn-default btn-lg" style="  width:100%;" type="button" onclick="Close_Update_Usr_Panel()">Close</button> 
          </div>
            <div><br>
                <table class="table table-bordered" id="account_list">

                </table>
            </div>
      
      <center><label>©Cadonsweb</label></center>
</div>
</body>
</html>
