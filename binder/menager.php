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
    <title>Article Menager</title>
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
      isAdminList('<?php echo $_SESSION['log']; ?>');
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
                    <li><a href="?">My Articles</a></li>
                    <li><a href="?published">My Publications</a></li>
                    <li  id="account_menager"><a href="/binder/account.php">Accounts Menager</a></li>
                    <li><a href="/binder/output/">Publications</a></li>
                  </ul>
                  <ul class="nav navbar-nav navbar-right">
                   
                    <li><a onclick="logout()" style="cursor: pointer"><span class="glyphicon glyphicon-log-in"></span> Logout</a></li>
                  </ul>
                </div>
              </nav>
      
    
   
        <h4>
        <?php
        if(isset($_GET['published']))
        {
            echo "Pubblications Menager";
        }
        else
        {
            echo "Article Menager";
        }
        ?>    
        </h4>
        <hr>
        <div class="input-group">
                <input type="text" class="form-control" id="searchbar" placeholder="Search">
                <div class="input-group-btn">
                  <button class="btn btn-default" type="submit" onclick="Search(this.id)" id="<?php if(isset($_GET['published'])) echo 'publications';else echo 'articles'; ?>">
                    <i class="glyphicon glyphicon-search"></i>
                  </button>
                </div>
              </div>
       
       
    
    <div class="art_table">
     <table class="table list"  id="data_table">   
           <thead>
            <th>Title</th>
            <th>Last edit</th>
            <th>Edit</th>
            <th>Preview</th> 
            <th>Publish</th>
            <th>Delete</th>
        </thead>
        <tbody id="tab">
      </tbody>
<?php
if(!isset($_GET['published']))
{
    
    ?><script>GetList();</script><?php
}
else
{
    $sql="SELECT * FROM publications";
    ?><script>GetList(true);</script><?php

}


?>

</table>
</div>
<div id="sharebox">

        <h3>PUBLISH YOUR ARTICLE</h3>
        <label>Title</label><input type="text" id="title" class="form-control" ><br>
        <label>Date</label><input type="date" id="date" class="form-control" ><br>
        <button class = "btn btn-default btn-lg" onclick="Publish()" >Publish</button>
        <br>      <br>
        
        <button onclick="Close()" class="btn btn-default">Close</button>
  
      </div>
      <center><label>©Cadonsweb</label></center>
</div>
</body>
</html>
