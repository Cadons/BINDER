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
        <title>Categories Menager</title>
        <script src="/binder/resources/js/jquery.js"></script>
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
            function Delete_Section(id)
            {
                if(confirm("Are you sure to delete this section?"))
    {
                var data=new FormData();
                data.append("action","rm");
                data.append("nameID",id);
                sendpost(data,"sectionMenager.php","Section deleted!","Something hasn't worked retry!");
                Section_List();
    }
            }
            function Section_List()
            {
            $("#account_list").empty();
            $.get("/binder/sectionMenager.php?action=get", (data)=>{
               if(data!="Nan"||data=="done")
               {
                         res=JSON.parse(data);
               
                        for(var i=0;i<res.length;i++)
                        {
                            var onclick="Delete_Section('"+res[i][1]+"')";
                            var onclickedit="Open_Update_Section_Panel('"+encodeURIComponent(res[i])+"')";
                            var out="<tr><td style='text-align:center;\
                            vertical-align: middle;'><h6>"+res[i][0]+"</h6></td><td>\
                                <button class ='btn btn-default btn-lg' style='width:100%;' onclick="+onclick+">\
                                    <i class='fas fa-trash'></i></button></button></td><td>\
                                        <button style='width:100%;' class ='btn btn-default btn-lg' data-toggle='modal' data-target='#update_account' onclick="+onclickedit+">\
                                            Edit</button></td></tr>";
                                            $("#account_list").append(out);
                        }  
                
               

               }
          
            });

            }
            var idUpdate=0;
            function Open_Update_Section_Panel(name_id)
            {
                name_id=decodeURIComponent(name_id);
                $("#account").hide();
                $("#update_account").show();
                name_id=name_id.split(',');
                idUpdate=name_id[1];
                $("#Uname").val(name_id[0]);

            }
            function AddSection()
        {
            
                $.get("/binder/sectionMenager.php?action=check&name="+$("#Nname").val(), (data)=>{
             if(data=="ok")
             {
                 var data=new FormData();
                data.append("action","add");
           
                data.append("name",$("#Nname").val());
                sendpost(data,"sectionMenager.php","Section added!","Something hasn't worked retry!");
                Section_List();
                $('#create_account').modal('toggle');
                $("#Nname").val("");
             }else
             {
                swal ( "Error!" ,  "This section has been created!\n change name",  "error" );
             }

            });
                

                
        }

function UpdateSection()
{
    if(confirm("Are you sure to edit "+$("#Uname").val()+" section?"))
    {
     
                $.get("/binder/sectionMenager.php?action=check&name="+$("#Uname").val(), (data)=>{
             if(data=="ok")
             {
                var data=new FormData();
                data.append("action","update");
                data.append("nameID",idUpdate);
                data.append("name",$("#Uname").val());
                sendpost(data,"sectionMenager.php","Section updated!","Something hasn't worked retry!");
                Section_List();
                $('#update_account').modal('toggle');
                $("#Uname").val("");
             }else
             {
                swal ( "Error!" ,  "This section has been created!\n change name",  "error" );
             }
               
       
    });
}
}

</script>
</head>
<body>
<?php 
include('resources/general_body.php');
 BodyStart();?>
<h4>Accounts Menager</h4>
<hr>
              <button class = "btn btn-default btn-lg" type="button" data-toggle="modal" data-target="#create_account" onclick="Open_New_Usr_Panel()"><img src="/binder/resources/template/icons/sectionAdd.png" width="50%"></button>            
            
            <div><br>
            
                <table class="table table-bordered" id="account_list">
                <script>
                    $().ready(function()
                    {
                        Section_List();
                    });

</script>
                </table>

   </div>

   <!--Dialogbox for creation of the accounts--> 
   <div class="modal" tabindex="-1" role="dialog" id="update_account" aria-labelledby="PublishBox" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Add Section</h5>
          </div>
          <div class="modal-body">
          <label>Insert section name</label>
                <input type="text" id="Uname" class="form-control"><br>

            </div>
              <div class="modal-footer">
              <button class="btn btn-primary" style="width: 100%;" onclick="UpdateSection()">Update Section</button>
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
          <label>Insert section name</label>
                <input type="text" id="Nname" class="form-control"><br>

            </div>
              <div class="modal-footer">
              <button class="btn btn-primary" style="width: 100%;" onclick="AddSection()">Add Section</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>      
      
     

<?php BodyEnd();?>
</body>
</html>
