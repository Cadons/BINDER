//On page start
var previewEnable=false;
var PreviewImageName;
var preview=false;//if you want add an image to article it will be set false, if you want add preview of article it will be set true  
var isuploaded=false;
var imageId;
$().ready(function ()
    {
        

        SetUpQuill();
        $("#delbtn").prop('disabled', true);
        $("#upbtn").prop('disabled', true);
        $("#Preview").prop('disabled', true);
        Section_List();
        getPublicationInfo();
       
    });
//Setup quill editor
function SetUpQuill()
{
  //setup toolbar
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
    [{ 'link': function(value) {
        if (value) {
          var href = prompt('Enter the URL');
          this.quill.format('link', href);//imposta url
        } else {
          this.quill.format('link', false);
        }
      }
    }],
    ['image','video', "formula"]
  ];

  //setup quill options
  const quillOptions = {
    modules: {
      formula: true,
      toolbar: toolbarOptions,
      imageResize: {
               
      
               }

    },
    placeholder:
      "Write here your text",
    theme: "snow"
  };

  const options = {};
  const enableMathQuillFormulaAuthoring = window.mathquill4quill();//setup quillmath for maths formulas
  const quill = new Quill("#editor", quillOptions);//Create binder editor
  enableMathQuillFormulaAuthoring(quill, options);//enabel quillmath
  quill.getModule("toolbar").addHandler("image", ()=>{uploadImage();});
  
      
} 
function WriteFile()
{
  var file=document.getElementById("img");
  $("#filename").empty();
  $("#filename").append(file.files[0].name);  
    isuploaded=false;
    $("#upbtn").prop('disabled', false);
}
function uploadImage(_preview=false)
{
  if(_preview==true)
  {
    preview=true;
  }
  else
  {
    preview=false;
  }

  get_Images_List();   //Load uploaded images list
  $("#ImagePanel").modal();//enable bootstrap modal dialog 

  
}

//Upload new image
function _uploadNew()
{ 

  var file=document.getElementById("img");
  file.click();
 
 
}
var Images=[];//This array contains names of selected checkbox
function LoadSelectedImages(name)
{
  var founded=false;
 Images.forEach(element=>{
    if(element==name)
    {
      
        Images.splice(Images.indexOf(element),1);
        founded=true;
        //console.log(Images.length);
    }
 });

  if(founded==false)
     Images.push(name);
  if(Images.length>0)
  {
    $("#delbtn").prop('disabled', false);
  }
  else
  {
    $("#delbtn").prop('disabled', true);
  }
 ////console.log(Images);
}
function get_Images_List()
{
  var req=$.ajax
  ({ 
  url: 'core.php?req=getImages',
  type: 'GET',
   dataType: 'text',  // what to expect back from the PHP script, if anything
      cache: false,
      contentType: false,
      processData: false,
  
  })
  ;
    

    req.done(function (data) 
    {
      $("#images").empty();
      if(data!="Nan")
      {
          var json=JSON.parse(data);
          var n=0;
          $("#images").append("<tr>");
      json.forEach(element => {
        if(n<5)
        {
          n++;
          imgId=element[0];
          $("#images").append("<td><img src='/binder/img/"+element[1]+"' class='imgBox' ><br><input type='checkbox' class='form-input' style='margin-left:50%' onclick='LoadSelectedImages(this.id)' id="+element[1]+" value="+imgId+"></td>");//Output html

        }
        else
        {
          n=0;
          $("#images").append("</tr>");
          $("#images").append("<tr>");
        }
       
      });

      }
    

    });
req.fail(function (jqXHR, textStatus) { });
}
function RemoveImages()
{
  if(Images.length>0)//check if Images array is void, if it isn't delete files
  {
      var elements="";
      Images.forEach(element=>{elements+=element+",";}); //compose array string
      var req=$.ajax//request
      ({ 
      url: 'core.php?req=cancImages&images='+elements,
      type: 'GET',
      dataType: 'text',  // what to expect back from the PHP script, if anything
          cache: false,
          contentType: false,
          processData: false,
      
      })
      ;
    

    req.done(function (data) 
    {
      $("#images").empty();
        swal ( "Good job!" ,  "Your images has been deleted" ,  "success" ).then(function(){
          get_Images_List(); //update list
          Images=[];//remove all items from array
        });


    });
req.fail(function (jqXHR, textStatus) { });

  }
 
}

function Upload()
{
  var file=document.getElementById("img");
  data=new FormData();
  data.append("img",file.files[0]);
  if(file.files[0]!=null)//it checks if there is a file to upload
  {
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
			
	
	req.done(function (data) { //console.log('Done'); 
			 
    swal ( "Good job!" ,  "la tua immagine è stata caricata" ,  "success" ).then(function()
    {
      $("#filename").empty();
        $("#filename").empty();
      document.getElementById("img").files[0]=null;
    } 
  );
  if(!isuploaded)
  {
    if(preview==false)
  {
    $( "div.ql-editor" ).append("<img src='/binder/img/"+data+"' alt="+data+" >");
    isuploaded=true;
    get_Images_List();   
    $("#ImagePanel").modal('hide');
    $("#upbtn").prop('disabled', true);
  }
  else{
    
    if(data!=null)
    {
      PreviewImageName=data;
    }
   $("#PreviewLabel").empty();
    $("#PreviewLabel").append("Preview Image: "+PreviewImageName);
    $("#ImagePanel").modal("hide");
  }
  }

	
	});
	req.fail(function (jqXHR, textStatus) { swal ( "Ops" ,  "la tua immagine non è stata caricata" ,  "error" )}); 

  }
  
}


function Add()
{
  if(preview==false)
  {
        Images.forEach(element=>
      {
        $( "div.ql-editor" ).append("<img src='/binder/img/"+element+"' alt="+element+" >");
      });
      Images=[];
      $("#ImagePanel").modal("hide");

  }
  else
  {
    var file=document.getElementById("img");
    if(file.files[0]!=null)
    {
      PreviewImageName=file.files[0];
    }
    else
    {
      if(Images.length>0)
      {
        PreviewImageName=Images[0];
      }
    }
   $("#PreviewLabel").empty(); 
   $("#PreviewLabel").append("Preview Image: "+PreviewImageName);
   imageId=document.getElementById(Images[0]).value;
   console.log(imageId);
    $("#ImagePanel").modal("hide");  
  }
////console.log(PreviewImageName);

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

content= content.replace("'","%27");

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
  ////console.log(title);
  if(!pub)
  {
      $.get("/binder/binder_editor/core.php?req=open&id="+id, function( data ) {
    if(data!="not_found")
    {

    
   
  
    //console.error(data);
     // $("#opened").html(title);
      
     data=decodeURI(data);
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

function Section_List()
            {
            $("#account_list").empty();
            $.get("/binder/sectionMenager.php?action=get", (data)=>{
                res=JSON.parse(data);

                for(var i=0;i<res.length;i++)
                {
             
                var out="<option value="+res[i][1]+">"+res[i][0]+"</option>";
                $("#section").append(out);
                }

            });

            }
function Publish()
{

  var ok=confirm("Are you sure to publish this article?");
  if(ok)
  {    

      var title=$("#Ptitle").val();
     
      //alert(title);
      var date=$("#Pdate").val();
    
      var tags=$("#Ptags").val();
      var cat=$("#section").val();
      url=new URLSearchParams(document.location.search);
      page=url.get('open');
      var content=page;//get html code inside the quill editor div
     if(date!=null&&title!=null&&content!=null&&date!=""&&title!=""&&content!=""&&cat!="null"&&cat!=null&&cat!="")
     {
      data=new FormData();
            
      title= title.replace("'","%27");
      content=content.replace("'","%27");
      data.append("tags",tags);
      data.append("req",'publish');
      data.append("title",title);
      if(previewEnable)
      data.append("preview",imageId);
      else
      data.append("preview","1");
      data.append("date",date);
      data.append("article_id",content);
      data.append("section",cat);
 

      sendpost(data,"/binder/binder_editor/core.php","Published","Not Published");
      Close();
     }else
     {
      swal ( "Complete all fields" ,  "you must complete all fields" ,  "error" );
     }
         
            
  }

  
}
function getPublicationInfo()
{
  url=new URLSearchParams(document.location.search);
  var id=url.get('open');
  $.get("/binder/binder_editor/PublicationsInfo.php?req=title&id="+id, function( data ) {
    if(data!="not_found")
    {
     $( "#Ptitle" ).val(data); 
    }
    });

  $.get("/binder/binder_editor/PublicationsInfo.php?req=date&id="+id, function( data ) {
      if(data!="not_found")
      {
        data = data.split(' ')[0];
       $( "#Pdate" ).val(data); 
      }
      });
      $.get("/binder/binder_editor/PublicationsInfo.php?req=section&id="+id, function( data ) {
        if(data!="not_found")
        {
          data = data.split(' ')[0];
       try {
          $( "#section").find("option[value="+data+"]").attr('selected', 'selected');
            $("#section").val(data);
       } catch (error) {
        $( "#section option:first").val(); 
      
       }
         
         
        }
        });
      $.get("/binder/binder_editor/PublicationsInfo.php?req=tags&id="+id, function( data ) {
        if(data!="not_found")
        {
          if(data!=""&&data!=null)
          {
            var out="";
            var json=JSON.parse(data);
            json.forEach(element=>{
              out+=element+",";
            });

            $( "#Ptags" ).val(out); 
          }

        }
        });
        $.get("/binder/binder_editor/PublicationsInfo.php?req=preview&id="+id, function( data ) {
          data=JSON.parse(data);
          if(data!="not_found")
          {

              if(data!=""&&data!=null)
              {
                imageId=data[0];
                PreviewImageName=data[1];
                
               $("#PreviewLabel").empty(); $("#PreviewLabel").append("Preview Image: "+PreviewImageName);
                $("#PreviewCheckBox").prop("checked",true);
                EnableImagePreview();
              }
  
          }
          });
        
}

function EnableImagePreview()
{
  if(previewEnable==false)
  {
     $("#Preview").prop('disabled', false);
     previewEnable=true;
     preview=true;
  }
  else
  {
    $("#Preview").prop('disabled', true);
    previewEnable=false;
    preview=false;
  }
  
}



