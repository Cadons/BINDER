
    <!DOCTYPE html>
    <?php
    session_start();
    if(!isset($_SESSION['log']))
    {
        session_destroy();
           header("location: /binder");
    }
    $open=$_GET['open'];
    ?>
<html>
    <head>
 
        <title>BINDER Editor</title>
        <!-- Main Quill library -->
        <meta name="viewport" content="width=device-width, user-scalable=no,
initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
        <script src="//cdn.quilljs.com/1.2.2/quill.min.js"></script>
        <link href="//cdn.quilljs.com/1.2.2/quill.snow.css" rel="stylesheet">
        <script src="image-resize.min.js"></script>
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script>
        <link href="style.css" rel="stylesheet">
        <script src="../resources/script.js"></script>
        <script src="script.js"></script>
        <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
        <meta charset="UTF-8">
    <script>
      isAdminList('<?php echo $_SESSION['log']; ?>');
    </script>
    </head>
<body>
  
<div class="editorbox">
    <nav class="navbar navbar-inverse">
                <div class="container-fluid">
                  <div class="navbar-header">
                    <a class="navbar-brand" href="/binder/menager.php">BINDER</a>
                  </div>
                  <ul class="nav navbar-nav">
                    <li><a onclick="Create()" style="cursor: pointer">New article</a></li>
                    <li><a href="/binder/menager.php?">My Articles</a></li>
                    <li><a href="/binder/menager.php?published">My Publications</a></li>
                    <li  id="account_menager"><a href="/binder/account.php">Accounts Menager</a></li>
                    <li><a href="/binder/output/">Publications</a></li>
                  </ul>
                  <ul class="nav navbar-nav navbar-right">
                   
                    <li><a onclick="logout()" style="cursor: pointer"><span class="glyphicon glyphicon-log-in"></span> Logout</a></li>
                  </ul>
                </div>
              </nav>
  <div class="toolbar">
      <div class="row">
        <div class="col-sm-6">
          <button  onclick="Back()" class = "btn btn-default btn-lg"> BACK</button>
  
        </div>  
        <div class="col-sm-6">
            <button class = "btn btn-default btn-lg" onclick="OpenSharePanel()">SHARE</button>
    
          </div>       
      </div>
      <div class="row">
        <div class="col-sm-4">
            <button class = "btn btn-default btn-lg" type="button" onclick="Save('<?php echo $_GET['open'];?>',<?php if(isset($_GET['published']))echo 'true'; else echo 'false';?>)">Save</button> 
          
          </div>
    <div class="col-sm-4"> 
             <button class = "btn btn-default btn-lg" type="button" onclick="Delete('<?php echo $_GET['open'];?>')">Delete</button> 
            

          </div>
          <div class="col-sm-4"> 
  <button class = "btn btn-default btn-lg" type="button" onclick="<?php if(isset($_GET['published'])){ ?> Openbyid( <?php echo $open;?>,true) <?php }else{ ?> Openbyid( <?php echo $open;?>)<?php } ?>">Update</button> 
            

          </div>
        </div>
          
     
 
            <label class="label label-default" id="opened">You are working on:</label>
           

   
          

   
  
  <div id="sharebox">

      <h3>PUBLISH YOUR ARTICLE</h3>
      <label>Title</label><input type="text" id="title" class="form-control" ><br>
      <label>Date</label><input type="date" id="date" class="form-control" ><br>
      <button onclick="Publish()" class="btn btn-default btn-lg">Publish</button>
      <br>      <br>

      <button class = "btn btn-default btn-lg" onclick="Close()" >Close</button>

    </div>
    <br>
  <div id="editor" class="editor">
    
  </div>
  
  <!-- Include the Quill library -->
  
  <input type="file" name="img" id="img" type="button" style="display:none"  accept="image/*">
  <!-- Initialize Quill editor -->
  <center><label>©Cadonsweb</label></center>
  

</div>
<?php

    $published=$_GET["published"];
    
    if(isset($open)&&$open!=""&&$open!=null)
      {
        if(isset($published))
        {
          ?>
          <script>
          
          Openbyid(<?php echo $open;?>,true);
           
           </script>
          <?php
        }else
          {
         ?>
         <script>
         
         Openbyid(<?php echo $open;?>);
          
          </script>
         <?php
         }
      }

  ?>
</body>
</html>