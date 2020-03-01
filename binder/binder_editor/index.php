
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
               <!-- Latest compiled and minified CSS -->
        <meta name="viewport" content="width=device-width, user-scalable=no,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0"><!--Screen View -->
        <script src="https://cdn.quilljs.com/1.2.2/quill.min.js"></script>
        <link href="https://cdn.quilljs.com/1.2.2/quill.snow.css" rel="stylesheet">
        <script src="image-resize.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">     
        <!--Main stylesheet-->
        <link rel="stylesheet" href="../resources/template/body.css">
    <!-- KaTeX dependency -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/KaTeX/0.10.2/katex.min.css" integrity="sha256-uT5rNa8r/qorzlARiO7fTBE7EWQiX/umLlXsq7zyQP8=" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/KaTeX/0.10.2/katex.min.js" integrity="sha256-TxnaXkPUeemXTVhlS5tDIVg42AvnNAotNaQjjYKK9bc=" crossorigin="anonymous"></script>
    <link rel="shortcut icon" href="/binder/resources/favicon.ico" />
    <script src="/binder/resources/js/jquery.js"></script>    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mathquill/0.10.1/mathquill.min.css" integrity="sha256-Z0FmvP1JtDmwVaHpsgu75FrC/SInDnlFWL95M65PCr4=" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mathquill/0.10.1/mathquill.min.js" integrity="sha256-dxKVPdWCaZTdphHQqQEc0GSDAVZJCxshwn3ZrvHtqgo=" crossorigin="anonymous"></script>
    <script src="mathquill4quill.js"></script>


       
        <!--Icons Pack-->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

        
        <!-- Popper JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script>
        <script src="../resources/script.js"></script>
        <script src="script.js"></script>
<!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <meta charset="UTF-8">
    <script>
      isAdminList('<?php echo $_SESSION['log']; ?>');
    </script>
    <style>
      @media screen and (max-width:1124px)
    {
    .content-box
    {
        height: auto;
    }
    }
    .imgBox
    {
      width:200px;
      height:100px;
    }
      </style>
    </head>
<body>
 <?php 
 include('../resources/general_body.php');
 BodyStart();?>
     <!--Put here section's contents-->
     <button type="button" class="btn btn-outline-secondary" onclick="Save('<?php echo $_GET['open'];?>',<?php if(isset($_GET['published']))echo true; else echo false;?>)">Save</button>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#Publish">Publish...</button>
   
   
   <br>

  <div id="editor" class="editor">
    
  </div>

  <!-- Include the Quill library -->
  
  <input type="file" name="img" id="img" type="button" onchange="WriteFile()" style="display:none"  accept="image/*">
  <!-- Initialize Quill editor -->
  
  <div class="modal" tabindex="-1" role="dialog" id="Publish" aria-labelledby="PublishBox" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Publish your article</h5>
          </div>
          <div class="modal-body">
              <label>Title</label><br>
              <input type="text" id="Ptitle" class="form-control" id="title"><br>
              <label>Date</label><br>
              <input type="date" id="Pdate" class="form-control" id="date"><br>
              <label>Tag</label><br>
              <input type="text" id="Ptags" class="form-control" id="tag" placeholder="Divide tags with ;"><br>
              <label >Section</label><br>
              <select  class="form-control" id="section">
              <option selected value='null'>None</option>
           
              </select>
              <br>
              <input type='checkbox' class='form-input'  onclick='EnableImagePreview()' id="PreviewCheckBox">Image preview<br>
              <label id="PreviewLabel"></label><br>
              <button class="btn btn-secondary" onclick="uploadImage(true);" id="Preview">Select new image</button>
            </div>
              <div class="modal-footer">
              <button class="btn btn-primary" style="width: 100%;" onclick="Publish()">Publish</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <!--Images mengaer and uploader-->
    <div class="modal" tabindex="-1" role="dialog" id="ImagePanel" aria-labelledby="ImageBox" aria-hidden="true">
      <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Add new image</h5>
          </div>
          <div class="modal-body">
              
              <table class="table-responsive" id="images" style="height:500px; overflow-y:scroll">
                
              </table>
           
           
              <label id="filename"></label><br>
            <button class="btn btn-secondary" onclick="_uploadNew();">Select new image</button>
            <button class="btn btn-secondary" id="upbtn"  onclick="Upload();">Upload</button>
            <button class="btn btn-danger" id="delbtn" onclick="RemoveImages();">Delete Images</button>
             </div>
              <div class="modal-footer">
              <button class="btn btn-primary" style="width: 100%;" onclick="Add()">Add</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
<?php


    
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

  BodyEnd();?>
  
</div>
</body>
</html>