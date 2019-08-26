/*
IMPORTANT:
The keywords -hastag and -e are reserved for special chars like # and &.
It should be better use ascii code for encoding and decoding for the url, (for the get request),
but chrome block the request when there is theese characters, error: (block:other)
This system could be change in the future.


function Special_Characters_encodeURL(content)
{
  for(var i=0;i<content.length;i++)
    {
content=content.replace("#","-hastag");
content=content.replace("'","-ap");
content=content.replace("&","-e");
content=content.replace("&amp","-e");

    }
return content;
}
function Special_Characters_dencodeURL(content)
{
  for(var i=0;i<content.length;i++)
    {
  content=content.replace("-ap ","'");
  content=content.replace("-hastag","#"	);
  content=content.replace("-e","&");
  content=content.replace("-e","&amp");
    }
    return content;
}*/
function OpenSharePanel(id)
{
    title_selected=id;
    $("#sharebox").show();
}
function Close()
{
    $("#sharebox").hide();
}
function decode(x)
{
  /*
  THIS FUNCTION MUST!!!! EXECUTE INSIDE THE JQUERY READY DOCUMENT FUNCTION 
  */
  
  x=decodeURIComponent(x);
 return x;
}
function Search(tb)
{
 var target=$("#searchbar").val();
 target=target.replace("'","%27");
 
 data=new FormData();
 data.append("req", "search");
 data.append("target", target);
 data.append("in", tb);
  var req=$.ajax
  ({ 
 url: "/binder/binder_editor/core.php",
 type: 'POST',
 dataType: 'text',  // what to expect back from the PHP script, if anything
 cache: false,
 contentType: false,
 processData: false,
 data: data
      
  });
  
req.done(function (data) 

{	
data=decode(data);
  var json=JSON.parse(data);
  
  $("#data_table").empty();
  if(tb=="articles")
  {
    for(var i=0;i<json.length;i++)
  { var output='<tr><td>'+json[i][0]+'</td><td>'+json[i][1]+'</td><td><button class = "btn btn-default btn-lg" id='+json[i][2]+' onclick="Edit(this.id)">Edit</button></td><td><button class = "btn btn-default btn-lg" onclick="Preview(this.id)"  id='+json[i][2]+'>Preview</button></td><td><button class = "btn btn-default btn-lg" onclick="OpenSharePanel(this.id)" id='+json[i][2]+'>Publish</button></td><td><button class = "btn btn-default btn-lg" onclick="Delete(this.id)" id='+json[i][0]+'>Delete</button></td></tr>';
    $("#data_table").append(output);
  }
  }
  else
  {
    for(var i=0;i<json.length;i++)
    { var output=' <tr><td>'+json[i][0]+'</td><td>'+json[i][1]+'</td><td><button class = "btn btn-default btn-lg" id='+json[i][2]+' onclick="Edit(this.id)">Edit</button></td><td><button class = "btn btn-default btn-lg" onclick="Preview(this.id)"  id='+json[i][2]+' disabled>Preview</button></td><td><button class = "btn btn-default btn-lg" onclick="OpenSharePanel(this.id)" id='+json[i][2]+' disabled>Publish</button></td><td><button class = "btn btn-default btn-lg" onclick="Delete(this.id)" id='+json[i][0]+'>Delete</button></td></tr>';

    $("#data_table").append(output);
    }
  }
  
});
                        




req.fail(function (jqXHR, textStatus) {

swal ( "Error" ,  "Error during elaboration!" ,  "error" ); });
  


}

$().ready(function(){
  $("#account").hide();
  $("#update_account").hide();
  Account_List();

});

//ACCOUNT MENAGMENT
function Open_New_Usr_Panel()
{
  $("#account").show();
  $("#update_account").hide();
}
function Close_New_Usr_Panel()
{
  $("#account").hide();
  
}
function check_Usr()
{

  var username=$("#usr").val();
  $.get("/binder/config/account_core.php?check&usr="+username, (data)=>{
    if(data=="false")
    {
      swal ( "Error!" ,  "Username not aviable",  "error" );
     
    }
    else
    {
      Create_Account();
    }

  });

}
var sel="writer";
function permission_set()
{
  sel=$("#permissions").val();
 // alert(sel);
}

function Create_Account()
{

 
  
    var username=$("#usr").val(),password=$("#psw").val();
    var conf=$("#pswconf").val();
    console.log(username);
    console.log(password);
  
   // alert(sel);
    if(sel==""||sel==null)
    {
      sel="writer";
    }
    if((username!=null&&password!=null&&conf!=null)&&(username!=""&&password!=""&&conf!=""))
    {
      if(password==conf)
      {
        data=new FormData();
        data.append("usr",username);
        data.append("psw",password);
        data.append("class",sel);
        data.append("add",'1');
        var req=$.ajax
        ({ 
       url: 'config/account_core.php',
       type: 'POST',
       dataType: 'text',  // what to expect back from the PHP script, if anything
       cache: false,
       contentType: false,
       processData: false,
       data: data
            
        });
        
    req.done(function (data) 
    
    {	
        
        
        swal ( "Good Job!" ,  "Account has bin added",  "success" );
        $('.create_account').find('input:text').val('');//clear the form data
        $('.create_account').find('input:password').val('');//clear the form data
        

        Close_New_Usr_Panel();
      Account_List();
        
    });
                              
    
    
    
    
    req.fail(function (jqXHR, textStatus) {
      swal ( "Error!" ,  "ERROR:Account has bin added",  "error" );
     }); 
      }
      else
      {
        swal ( "Error!" ,  "Passwords aren't equals!",  "error" );
      }
    }
    else
    swal ( "Error!" ,  "Compile all fields",  "error" );
  
  
}
function Account_List()
{
  $("#account_list").empty();
  $.get("/binder/config/account_core.php?list", (data)=>{
    res=JSON.parse(data);

    for(var i=0;i<res.length;i++)
    {
      var onclick="Delete_Account('"+res[i]+"')";
      var onclickedit="Open_Update_Usr_Panel('"+res[i]+"')";
      var out="<tr><td style='text-align:center;><label '>"+res[i]+"</label></td><td><button class ='btn btn-default btn-lg' style='width:100%;' onclick="+onclick+">Delete</button></td><td><button style='width:100%;' class ='btn btn-default btn-lg' onclick="+onclickedit+">Edit</button></td></tr>";
      $("#account_list").append(out);
    }

  });

}
function Delete_Account(usr)
{
  var confirm_del=confirm("Are you sure to delete "+usr+" account?");
  if(confirm_del)
  {
    $.get("config/account_core.php?del&usr="+usr, (data)=>{
          if(data=='ok')
          {
            swal ( "Good Job!" ,  "Account has bin removed",  "success" );
            Account_List();
          }
          else
          {
            swal ( "Error!" ,  "ERROR:Account hasn't bin removed",  "error" );
          }
        });
  }
  
}
function Open_Update_Usr_Panel(usr)
{  $("#account").hide();
$("#update_account").show();
$("#usru").empty();
$("#usru").append(usr);
$.get("/binder/config/account_core.php?isadmin&usr="+usr, (data)=>{
  if(data=='ok')
  {
 
   $("#permissionsu").val("admin");
  }
  else
  {
    $("#permissionsu").val("writer");
  }
});

}
function Close_Update_Usr_Panel()
{
$("#update_account").hide();
}
function Update_Account()
{
var go=true;
 
    sel=$("#permissionsu").val();
    var username=$("#usru").html(),password=$("#pswu").val();
    var conf=$("#pswconfu").val();
    console.log(username);
    console.log(password);
  
   // alert(sel);
    if(sel==""||sel==null)
    {
      sel="writer";
    }
  

      if(password!=""&&password!=null)
      {
        if(password==conf)
       go=true;
       else
       {
          swal ( "Error!" ,  "Passwords aren't equals!",  "error" );
        go =false;
       }
      }
   
    if(go)
    {
    data=new FormData();
      data.append("usr",username);
      if(password!=""&&password!=null)
      {
        data.append("psw",password);
      }
      
      data.append("class",sel);
      //alert(sel);
      data.append("update",'1');
      var req=$.ajax
      ({ 
     url: '/binder/config/account_core.php',
     type: 'POST',
     dataType: 'text',  // what to expect back from the PHP script, if anything
     cache: false,
     contentType: false,
     processData: false,
     data: data
          
      });
      
  req.done(function (data) 
  
  {	
      
      
      swal ( "Good Job!" ,  "Account has bin updated",  "success" );
      $('.create_account').find('input:text').val('');//clear the form data
      $('.create_account').find('input:password').val('');//clear the form data
      

      Close_Update_Usr_Panel();
    Account_List();
      
  });
                            
  
  
  
  
  req.fail(function (jqXHR, textStatus) {
    swal ( "Error!" ,  "ERROR:Account has bin updated",  "error" );
   }); 
    }
      
  
  
}

function isAdmin(usr)
{


  $.get("/binder/config/account_core.php?isadmin&usr="+usr, (data)=>{
    if(data!='ok')
    {
      location.href="/binder/";
     
    }
    
  });
}
function isAdminList(usr)
{


  $.get("/binder/config/account_core.php?isadmin&usr="+usr, (data)=>{
    if(data!='ok')
    {
      
      $("#account_menager").hide();
    }
    
  });
}
function sendpost(data,url,successtxt,error)
{
  var req=$.ajax
    ({ 
   url: url,
   type: 'POST',
   dataType: 'text',  // what to expect back from the PHP script, if anything
   cache: false,
   contentType: false,
   processData: false,
   data: data
        
    });
    
req.done(function (data) 

{	
 
  
      if(data=="OK")
  {
  swal ( "Good job!" ,  successtxt ,  "success" );
  }else
  {
    swal ( "Error" ,  error ,  "error" );
  }
  

    
});
                          




req.fail(function (jqXHR, textStatus) {

  swal ( "Error" ,  error ,  "error" ); });
}