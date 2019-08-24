<html>
<head>
        <meta name="viewport" content="width=device-width, user-scalable=no,
        initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
    <title>
    <?php
    require_once("../config/get_credezialies.php");
  $obj=new getCredenziales();
  $cred=array($obj->getHost(),$obj->getUsername(),$obj->getPassword(),$obj->getDatabase_Name());//$obj->getUsername(),$obj->getPassword(),$obj->getDatabase_Name());
  $conn=new MySqli($cred[0],$cred[1],$cred[2],$cred[3]);
      
      
     if(!isset($_GET["post_id"]))
     {
         echo "BINDER";
     }
     else
     {
       $id=$_GET["post_id"];
        $sql="SELECT * FROM publications WHERE id=$id";
        
      
        $ris=$conn->query($sql);
        while($row=$ris->fetch_assoc())
                            {
                            echo( urldecode( $row["title"]));
                            }

     }
    ?>

    
    </title>
 
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="style.css">
<script src="../resources/script.js"></script>
<script src="binder.js"></script>
<meta charset="UTF-8">
</head>
<body>
<div class="mainbox">
    
<nav class="navbar navbar-inverse">
    <div class="container-fluid">
      <div class="navbar-header">
        <a class="navbar-brand" href="?">BINDER</a>
      </div>
      <ul class="nav navbar-nav">
       
        <li><a href="?">Publications</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
       
        <li><a href="/binder/" style="cursor: pointer"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
      </ul>
      <form class="navbar-form navbar-left" action="?">
            <div class="input-group">
              <input type="text" class="form-control" name="article" placeholder="Search">
              <div class="input-group-btn">
                <button class="btn btn-default" type="submit" >
                  <i class="glyphicon glyphicon-search"></i>
                </button>
              </div>
            </div>
          </form>
    </div>
  </nav>
  <div id="post">


    <?php
 

    if(!isset($_GET["post_id"]))
    {

  
    ?>

        <h1>My post</h1>
        <hr>
        <table class="table" id="list">
    <script>
    getPostList("list");
    </script>
    </table>

    <?php
      }
      else
      {
          $id=$_GET["post_id"];
    ?>
    <script>
    getPost(<?php echo $id; ?>,"post");
    </script>
    <?php
      }
      
    ?>
    </div>
</div>
</body>
</html>