function Edit(name)
{
    location.href="binder_editor/?open="+name;
}
function Edit_pub(name)
{
    location.href="binder_editor/?published&open="+name;
}
var title_selected;



function Delete(id)
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

function Publish()
{

  var ok=confirm("Are you sure to publish this article?");
  if(ok)
  {    
      var title=$("#title").val();
      var date=$("#date").val();
     
      var content;
     

      $.get("/binder/binder_editor/core.php?req=getcontbyid&id="+title_selected, function( data ) {
        if(data!="not_found")
        {
            var json=JSON.parse(data);
            content=json[0];
            title= title.replace("'","%27");
            data=new FormData();
            data.append("req",'publish');
            data.append("title",title);
            data.append("date",date);
            data.append("text",content);
            sendpost(data,"/binder/binder_editor/core.php","Published","Not Published");
          // alert(content);
        }
        else
          alert("article not found");  
          data.append("text",content);
    //  console.log(content);

  /*

  data is response variable, and on response insert the code of the database inside the quill editor div
  */
  
});
  }

  Close();
}



function Preview(id)
{
    $.get("/binder/binder_editor/core.php?req=getcontbyid&id="+id, function( data ) {
        if(data!="not_found")
        {
            var json=JSON.parse(data);
            content=json[0];
          
       
            var myWindow = window.open("", "Preview window"+id);
            myWindow.document.clear();
            myWindow.document.write(content);

          // alert(content);
        }
        else
          alert("article not found"); });
}

function logout()
{
  $.get("/binder/binder_editor/core.php?req=logout");
  location.href="/binder";
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
function GetList(publish=false)
{
  if(publish)
  {
    $.get("/binder/binder_editor/core.php?published&req=getlist", function( data ) {
         
          data=decode(data);
          $("#tab").append(data);
        
        });
  }
  else
  {
    $.get("/binder/binder_editor/core.php?req=getlist", function( data ) {
      
          
      data=decode(data);
      $("#tab").append(data);
    
         
    
    });
  }
  
}