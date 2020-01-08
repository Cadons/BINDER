/**
 * BINDER
 * CLIENT SCRIPT MENAGER
 */


//-------------------------------------------------------WARNING--------------------------------------------------------------
/** 
*IMPORTANT:
*The keywords -hastag and -e are reserved for special chars like # and &.
*It should be better use ascii code for encoding and decoding for the url, (for the get request),
*but chrome block the request when there is theese characters, error: (block:other)
*This system could be change in the future.
*/
//------------------------------------------------------------GLOBAL VARIABLES------------------------------------------------
var title_selected;
var emailUpdate;
//------------------------------------------------------------FUNCTIONS-------------------------------------------------------

function Close()
{
  $(function () {
    $('#Publish').modal('toggle');
 });
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
 //console.log(target);

  var req=$.ajax
  ({ 
 url: "/binder/binder_editor/core.php?req=search&target="+target+"&in="+tb,
 type: 'GET',    
  });
  
req.done(function (data) 

{	
data=decode(data);
  var articlesData=JSON.parse(data);
  
  $("#tab").empty();
 
  if(tb=="articles")
  { 
    SetPageNumber(false,articlesData.length);
    PrintArticleList(articlesData,articlesData.length);
  }
  else
  {
    SetPageNumber(true,articlesData.length);
    PrintArticleList(articlesData,articlesData.length,true);
  }
  
});
                        




req.fail(function (jqXHR, textStatus) {

swal ( "Error" ,  "Error during elaboration!" ,  "error" ); });
  


}

$().ready(function(){
  $("#account").hide();
  $("#update_account").hide();

  

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

 
  
    var username=$("#usr").val(),password=$("#psw").val(),email=$("#email").val();

    var conf=$("#pswconf").val();
    //console.log(username);
    //console.log(password);
  
   // alert(sel);
    if(sel==""||sel==null)
    {
      sel="writer";
    }
    if((username!=null&&password!=null&&conf!=null&&email!=null)&&(username!=""&&password!=""&&conf!=""&&email!=""))
    {
      if(password==conf)
      {
        data=new FormData();
        data.append("usr",username);
        data.append("psw",password);
        data.append("email",email);
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
        

        $(function () {
          $('#create_account').modal('toggle');
       });
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
      var out="<tr><td style='text-align:center;vertical-align: middle;'><h6>"+res[i]+"</h6></td><td><button class ='btn btn-default btn-lg' style='width:100%;' onclick="+onclick+"><i class='fas fa-trash'></i></button></button></td><td><button style='width:100%;' class ='btn btn-default btn-lg' data-toggle='modal' data-target='#update_account' onclick="+onclickedit+">Edit</button></td></tr>";
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
{  
  $("#account").hide();
$("#update_account").show();
$("#usru").empty();
$("#usru").append(usr);


$.get("/binder/config/account_core.php?get_email&usr="+usr, (data)=>{
  $("#mailu").val(data);
  setEmail();
});
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

$(function () {
  $('#update_account').modal('toggle');
});
}

function setEmail()
{
  emailUpdate= $("#mailu").val();
}
function Update_Account()
{
var go=true;
 
    sel=$("#permissionsu").val();
    var username=$("#usru").html(),password=$("#pswu").val(),email=emailUpdate;
    var conf=$("#pswconfu").val();
    //console.log(username);
    //console.log(password);
  
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
      data.append("email",email);
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

//Articles controls
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
 
  
      if(data=="OK"||data=="done")
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
function Edit(name)
{
    location.href="binder_editor/?open="+name;
}



function Delete(id)
{

  if(window.location.href.indexOf("published")<0)
  {
    $.get("/binder/binder_editor/core.php?req=getbyid&id="+id, function( data ) {

  /*

  data is response variable, and on response insert the code of the database inside the quill editor div
  */
        json=JSON.parse(data);
        data=decodeURI(json[0]);
        var ok=confirm("Are you sure to delete "+data+"?");
        if(ok)
        {
          $.get("/binder/binder_editor/core.php?req=delete&id="+id, function( data ) {
          if(data!="not_found")
          {
              
          alert("Article deleted");
          }
          else
            alert("article not found");
        /*

        data is response variable, and on response insert the code of the database inside the quill editor div
        */
        
        });
        location.reload();
        }
});
  }
  
  else
  Delete_pub(id); 
}
function Delete_pub(id)
{
  $.get("/binder/binder_editor/core.php?req=getbyid_pub&id="+id, function( data ) {
   
  /*

  data is response variable, and on response insert the code of the database inside the quill editor div
  */
        json=JSON.parse(data);
        data=decodeURI(json[0]);
        var ok=confirm("Are you sure to delete "+data+"?");
    
    if(ok)
    {
      $.get("/binder/binder_editor/core.php?req=delete_pub&id="+id, function( data ) {
      if(data!="not_found")
      {
         
      alert("Article deleted");
      }
      else
        alert("article not found");
    /*
  
    data is response variable, and on response insert the code of the database inside the quill editor div
    */
    
  });
  location.reload();
    }
  });
}
function New()
{
  var name=prompt("Insert article name");
  data=new FormData();

  if(name!=""&&name!=null)
  {
       data.append("req","create");
  data.append("title",name);
  sendpost(data,"/binder/binder_editor/core.php","Article created","Article not created");
 
  location.reload();
  }
  else
  return;
  
}
//Logout
function logout()
{
  $.get("/binder/binder_editor/core.php?req=logout");
  location.href="/binder";
}

//Display Datas functions

//Get main lists 

var SelectedArticles=[];
function Select(id)
{
  if($("#"+id).prop("checked")==true)
  {
    //add selection to the array
      SelectedArticles.push(id);
  }
  else
  {
    //Compact the array
    var index=SelectedArticles.indexOf(id);
    for(var i=index;i<SelectedArticles.length;i++)
    {
      SelectedArticles[i]=SelectedArticles[i+1];
    }
    SelectedArticles.length--;

    
    
  }
//If array is void the button will be disabled
  if(SelectedArticles.length>0)
  $("#sel_btn_del").prop( "disabled", false );
  else
  $("#sel_btn_del").prop( "disabled", true );

  //console.log(SelectedArticles.toString());
}
function DeleteSelection()
{

    for(var i=0;i<SelectedArticles.length;i++)
    {
      Delete(SelectedArticles[i]);
    }

}
function GetList(published=false)
{

 

  //setup
  var n=0;
  url=new URLSearchParams(document.location.search);
  page=url.get('page');
  if(page==""||page==null||page<1)
    page=1;

    //print Data
  if(published)
  {//Published
    
    SetPageNumber(true);//set pages bar
    
    var req=$.ajax
    ({ 
   url: "/binder/binder_editor/core.php?published&req=getlist&page="+page,
   type: 'GET',
    });
    
    req.done(function (data) 
        {	

         articlesData=JSON.parse(data);
         
        PrintArticleList(articlesData,articlesData.length,true);
         
        });
  }
  else
  {//Private Articles
    SetPageNumber();//set pages bar
    
            var req=$.ajax
            ({
          url: "/binder/binder_editor/core.php?req=getlist&page="+page,
          type: 'GET',
            });
            
            req.done(function (data) 
            {	
                  articlesData=JSON.parse(data);  
                  PrintArticleList(articlesData,articlesData.length);
                  
            
            });
      
} 


}

//Print datas and add elemento to the table
function PrintArticleList(articlesData='',n,pub=false)
{

    for(var i=0;i<n;i++)
      {
  
        if(!pub)
        {   
          var isDelivered="";
           if(articlesData[i][3]==1)
           {
              isDelivered="[published]"
           }
                  myout='<tr><td><input type="checkbox" onclick="Select('+articlesData[i][0]+')" id='+articlesData[i][0]+' class="checkbox-inline"> <a href="#" onclick="Edit('+articlesData[i][0]+')" style="color:black;">'+articlesData[i][1]+isDelivered+'</a></td><td><b>'+articlesData[i][2]+'</b></a></td><td><button class = "btn btn-default btn-lg" onclick="Delete(this.id)" id='+articlesData[i][0]+'><i class="fas fa-trash"></i></button></td></tr>';       

        }
        else
        myout='<tr><td><input type="checkbox" onclick="Select('+articlesData[i][0]+')" id='+articlesData[i][0]+' class="checkbox-inline"> <a href="#" onclick="Edit('+articlesData[i][3]+')" style="color:black;">'+articlesData[i][1]+'</a></td><td><b>'+articlesData[i][2]+'</b></a></td><td><button class = "btn btn-default btn-lg" onclick="Delete(this.id)" id='+articlesData[i][0]+'><i class="fas fa-trash"></i></button></td></tr>';       

        $("#tab").append(myout);
      }

}

//this function works on navigation button for the multiple pages. they are used when you have more then 10 articles
function SetPageNumber(pub=false,search=-1)
{  

  $("#pageNumber").empty();
 var articlesNumber;

  if(!pub)
  {
    /*
    If you are searching some data your navbar will configurate with the right number of pages
    */
    if(search==-1)
    articlesNumber=getListLength();
    else
    articlesNumber=search;

   if(articlesNumber>10&&articlesNumber<200)
   {
    $("#pageNumber").append('<li class="page-item"><a class="page-link" href="?&req=getlist&page=1">1</a></li>');
       for(var i=articlesNumber,j=2;i>0;i-=10)//10 articles for page
    {
      if(i>10)
      {
         $("#pageNumber").append('<li class="page-item"><a class="page-link" href="?req=getlist&page='+j+'">'+j+'</a></li>');
          j++;
      }
     
    }
  } 
   else
  {
   $("#pageNumber").append('<li class="page-item"><a class="page-link" href="?&req=getlist&page=1">1</a></li>');
  }
  }
  else
  {
    if(search==-1)
    articlesNumber=getListLength("published");
    else
    articlesNumber=search;
   if(articlesNumber>10)
   {
    $("#pageNumber").append('<li class="page-item"><a class="page-link" href="?published&req=getlist&page=1">1</a></li>');
       for(var i=articlesNumber,j=2;i>0;i-=10)//10 articles for page
    {
      if(i>10)
      {
         $("#pageNumber").append('<li class="page-item"><a class="page-link" href="?published&req=getlist&page='+j+'">'+j+'</a></li>');
          j++;
      }
     
    }
   }
   else
   {
    $("#pageNumber").append('<li class="page-item"><a class="page-link" href="?published&req=getlist&page=1">1</a></li>');
   }
  
  }
  
return articlesNumber; 
  

}
//return the list length
function getListLength(p='')
{
  articlesNumber=$.ajax
      ({
        url:"/binder/binder_editor/core.php?"+p+"&req=getlistnumber",
        type: "GET",
        data:"" ,
        async: false
      }
     ).responseText;
     return articlesNumber;
}

//List buttons
function PreviosPage()
{
  url=new URLSearchParams(document.location.search);
  page=url.get('page');
  
  if(page==null||page=="")
  {
    page=1; 
  }
  if(--page>=1)
  {
    
  var pubstatus="&";
  if(url.has("published"))
    pubstatus="published"+pubstatus;

  location.href="menager.php?"+pubstatus+"&req=getlist&page="+page;
  }else
  {
    page++;
  }
  
}

function NextPage()
{
  
  url=new URLSearchParams(document.location.search);
  page=url.get('page');
  if(page==null||page=="")
    {
      page=1; 
    }
   
  if(++page<=getListLength()||++page<=getListLength('published'))
  {
   var pubstatus="&";
    
    if(url.has("published"))
      pubstatus="published"+pubstatus;

    location.href="menager.php?"+pubstatus+"req=getlist&page="+page;
  }else
  {
    page--;
  }

}




//System Settings

function SMTP_settings(disabled)
{

  if(disabled==true)
  {
    
    for(var i=0;i<4;i++)
    {
      j=i+1;
      $("#smtp"+j).prop("disabled",true);
    }
  }
  else
  {
    for(var i=0;i<4;i++)
    {
      j=i+1;
      $("#smtp"+j).prop("disabled",false);
    }
  }

}
//Responsive Page


/**
 * CADONS-BINDER
 */