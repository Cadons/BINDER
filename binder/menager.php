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
<link rel="shortcut icon" href="/binder/resources/favicon.ico" />
<link rel="manifest" href="resources/favicon/manifest.json">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
<meta name="theme-color" content="#ffffff">
<!--Icons Pack-->
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

     <script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script>
        <script src="/binder/resources/script.js"></script>
        <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<!--Main stylesheet-->
<link rel="stylesheet" href="resources/template/body.css">
<!-- jQuery library -->


<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script> 
  
        <meta charset="UTF-8">
        <script>
      isAdminList('<?php echo $_SESSION['log']; ?>');
    </script>
</head>
<body>
<?php 
 include('resources/general_body.php');
 BodyStart();?>
   
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
                  <button type="button" class="btn btn-outline-secondary" type="submit" onclick="Search(this.id)" id="<?php if(isset($_GET['published'])) echo 'publications';else echo 'articles'; ?>">
                  <span><img src="/binder/resources/template/icons/search.png" width="50%"></span>
                  </button>
                </div>
              </div>
       
       
    <div class="listh">
    <div class="art_table">
   
</div>
     <table class="table list"  id="data_table">   
           <thead>
            <th>Title</th>
            <th>Last edit</th>
            <th>Delete</th>
        </thead>
        <tbody id="tab">
      </tbody>
      <ul class="pagination justify-content-center" >
  <li class="page-item"><a class="page-link" href="#" onclick="PreviosPage()">Previous</a></li>
  <ul class="pagination justify-content-center"  id="pageNumber"></ul>
  <li class="page-item"><a class="page-link" href="#" onclick="NextPage()">Next</a></li>
</ul>
<button class ='btn btn-primary btn-sm' onclick="DeleteSelection()" id="sel_btn_del" disabled>Delete selected <i class='fas fa-trash'></i></button>
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

</div></div>

<?php   BodyEnd();?>
</body>
</html>
