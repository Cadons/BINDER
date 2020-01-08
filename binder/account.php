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
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <meta name="viewport" content="width=device-width, user-scalable=no,
initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script>
   <script src="/binder/resources/script.js"></script>
   <link rel="shortcut icon" href="/binder/resources/favicon.ico" />
   <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<!--Main stylesheet-->
<link rel="stylesheet" href="resources/template/body.css">
<!-- jQuery library -->


<!--Icons Pack-->
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">


<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script> 
<script src="/binder/output/binder.js" ></script>
   <meta charset="UTF-8">
         <script>
            isAdmin('<?php echo $_SESSION['log']; ?>');
          </script>
</head>
<body>
<?php 
include('resources/general_body.php');
 BodyStart();?>
<h4>Accounts Menager</h4>
<hr>
              <button class = "btn btn-default btn-lg" type="button" data-toggle="modal" data-target="#create_account" onclick="Open_New_Usr_Panel()"><img src="/binder/resources/template/icons/add.png" width="50%"></button> 
              <button class = "btn btn-default btn-lg" type="button" onclick="Account_List()"><img src="/binder/resources/template/icons/update.png" width="50%"></button> 
           
            
            <div><br>
            
                <table class="table table-bordered" id="account_list">
<script>
Account_List();
</script>
                </table>

   </div>

<!--Dialogbox for update of the accounts-->
      <div class="modal" tabindex="-1" role="dialog" id="update_account" aria-labelledby="PublishBox" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Update User</h5>
          </div>
          <div class="modal-body">
          <label>Username:</label><label id="usru"></label><br>
              <label>email</label><br>
                <input type="email" onclick="setEmail()" onchange="setEmail()" class="form-control" id="mailu"><br>
              <label>Insert new password</label><br>
              <input type="password" id="pswu" class="form-control"><br>
              <label>confirm new password</label><br>
              <input type="password" id="pswconfu" class="form-control"><br>
              <label>Select permission of account</label>
              <select class="form-control" id="permissionsu" onchange="permission_set()">
                  <option value="writer" id="w" >Writer</option>
                  <option value="admin" id="a">Admin</option>
              </select><br>
            </div>
              <div class="modal-footer">
              <button class="btn btn-primary" style="width: 100%;" onclick="Update_Account()">Update Account</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

   <!--Dialogbox for creation of the accounts--> 
    <div class="modal" tabindex="-1" role="dialog" id="create_account" aria-labelledby="PublishBox" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Add User</h5>
          </div>
          <div class="modal-body">
          <label>Insert username</label>
                <input type="text" id="usr" class="form-control"><br>
                <label>Insert email</label><br>
                <input type="email"  class="form-control" id="email"><br>
                <label>Insert password</label><br>
                <input type="password" id="psw" class="form-control"><br>
                <label>confirm password</label><br>
                <input type="password" id="pswconf" class="form-control"><br>
                <label>Select permission of account</label>
                <select class="form-control" id="permissions" onchange="permission_set()">
                  <option value="writer" >Writer</option>
                  <option value="admin">Admin</option>
                </select>
            </div>
              <div class="modal-footer">
              <button class="btn btn-primary" style="width: 100%;" onclick="check_Usr()">Add Account</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>      
      
     

<?php BodyEnd();?>
</body>
</html>
