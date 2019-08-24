
function SetUpQuill()
{
    var toolbarOptions = [
        ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
        ['blockquote', 'code-block'],
      
        [{ 'header': 1 }, { 'header': 2 }],               // custom button values
        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
        [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
        [{ 'direction': 'rtl' }],                         // text direction
        [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
        [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
        [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
        [{ 'font': [] }],
        [{ 'align': [] }],
        ['image','video'],
        [{ 'link': function(value) {
            if (value) {
              var href = prompt('Enter the URL');
              this.quill.format('link', href);//imposta url
            } else {
              this.quill.format('link', false);
            }
          }
        }],	
      ];
      
      
          var quill = new Quill('#editor', {
            theme: 'snow',
            modules:
            {
             
              toolbar: toolbarOptions,
              imageResize: {
               
      
               },
            }
          });
          quill.getModule("toolbar").addHandler("image", ()=>{uploadImage();});
      
} 
function uploadImage()
{
  var file=document.getElementById("img");
    file.click();
    $("#img").change(function(e){
      isuploaded=false;
      Upload();
      
     });
     

  
  
}

//On page start
$().ready(function ()
    {
        

        SetUpQuill();
    });
var isuploaded=false;




function Upload()
{
  var file=document.getElementById("img");
  data=new FormData();
  data.append("img",file.files[0]);
  var req=$.ajax
		({ 
		url: 'img_upload.php',
		type: 'POST',
		 dataType: 'text',  // what to expect back from the PHP script, if anything
        cache: false,
        contentType: false,
        processData: false,
		data: data
		
    })
    ;
			
	
	req.done(function (data) { console.log('Done'); 
			 
    swal ( "Good job!" ,  "la tua immagine è stata caricata" ,  "success" ).then(

  );
  if(!isuploaded)
  {
    $( "div.ql-editor" ).append("<img src='/binder/img/"+file.files[0].name+"' alt="+file.files[0].name+" >");
    isuploaded=true;
  }

	
	});
	req.fail(function (jqXHR, textStatus) { swal ( "Ops" ,  "la tua immagine non è stata caricata" ,  "error" )}); 

}
function Save(id,published=false)
{
   var content=$( "div.ql-editor" ).html();//get html code inside the quill editor div
    data=new FormData();
if(!published)
{
 data.append("req",'save');
}
else
{
  data.append("req",'save_pub');
}

 
  data.append("id",id);
  data.append("text",content);
  sendpost(data,"/binder/binder_editor/core.php","Saved","Not Saved");
}
function Openbyid(id, published=false)
{
  
        Open(id,published);
    
  }
    
  
   

function Back()
{
    location.href="/binder/menager.php";
}
function Open(id,pub=false)
{
    
 
  $( "div.ql-editor" ).empty(); 
  //console.log(title);
  if(!pub)
  {
      $.get("/binder/binder_editor/core.php?req=open&id="+id, function( data ) {
    if(data!="not_found")
    {

    
   
  
    //console.error(data);
     // $("#opened").html(title);
      
     
     $( "div.ql-editor" ).html( data ); 
    }
      
    else
      alert("article not found");
  /*

  data is response variable, and on response insert the code of the database inside the quill editor div
  
  */
});

  }
  else
  {
    $.get("/binder/binder_editor/core.php?req=open_pub&id="+id, function( data ) {
      if(data!="not_found")
      {
  
      
     

      //console.error(data);
     //   $("#opened").html(title);
        
       
       $( "div.ql-editor" ).html( data ); 
      }
        
      else
        alert("article not found");
    /*
  
    data is response variable, and on response insert the code of the database inside the quill editor div
    
    */
  });
  }

}

function Delete(id)
{
  
  var ok=confirm("Are you sure to delete?");
  if(ok)
  {
    $.get("/binder/binder_editor/core.php?req=delete&id="+id, function( data ) {
    if(data!="not_found")
    {
      swal ( "Good job!" ,  "Article deleted",  "success" ).then(()=>{ location.href="../menager.php";});
      
   
    }
    else
    swal ( "Ops!" ,  "Article not found",  "error" );
  /*

  data is response variable, and on response insert the code of the database inside the quill editor div
  */
  
});
  }
  
}

function Publish()
{

  var ok=confirm("Are you sure to publish this article?");
  if(ok)
  {    
      var title=$("#title").val();
     
      //alert(title);
      var date=$("#date").val();
    
      var content=$( "div.ql-editor" ).html();//get html code inside the quill editor div
     
         
            data=new FormData();
            
           title= title.replace("'","%27");
            data.append("req",'publish');
            data.append("title",title);
            data.append("date",date);
            data.append("text",content);
            sendpost(data,"/binder/binder_editor/core.php","Published","Not Published");
            Close();
  }

  
}





