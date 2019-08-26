

function decode(x)
{
  /*
  THIS FUNCTION MUST!!!! EXECUTE INSIDE THE JQUERY READY DOCUMENT FUNCTION 
  */
  
  x=decodeURIComponent(x);
 return x;
}
function getPostList(htmlid,post_page="")
{
   
    

    $.get("/binder/output/binder.php?get=postlist",(data)=>
    { 
       
       /*
       to work with common variables we must nest requests in a once
       */ 
            var json=JSON.parse(data);
            data="";
            var length=Object.keys(json).length;
            //alert(length);
            var titlelist=new Array(length);
            for(var i=0;i<length;i++)
            {
            
                titlelist[i]=json[i];    
                    
            }

              $.get("/binder/output/binder.php?get=id",(data)=>{ 
       
        var json=JSON.parse(data);
        data="";
        var length=Object.keys(json).length;
        //alert(length);
        var idlist=new Array(length);
        
        for(var i=0;i<length;i++)
        {
            
            idlist[i]=json[i];    
                  
        }
        
        for(var i=0;i<length;i++)
        {
            
            
               var out="<a id='art"+i+"' href="+post_page+"?post_id="+idlist[i]+">"+titlelist[i]+"</a><br>";
            
            
             
            out=decode(out);
            
             $("#"+htmlid).append(out);
             
        }
           
    
      
        });
            
    });
   
}

function getPost(id,htmlid)
{
    //$()
    $.get("/binder/output/binder.php?get=post&id="+id,(data)=>{ 
       
        data=decode(data);
        $("#"+htmlid).append(data);
          
        });
}
function getPostText(id,htmlid)
{
    loadjscssfile("/binder/output/style.css", "css") //dynamically load and add this .js file

if(id==null||id<0||id=="")
{
    var url = new URL(window.location.href);
     id = url.searchParams.get("post_id");
}
    $.get("/binder/output/binder.php?get=article&id="+id,(data)=>{ 
       
        data=decode(data);
        $("#"+htmlid).append(data);
          
        });
}
function getTitle(id,htmlid)
{
    $.get("/binder/output/binder.php?get=title&id="+id,(data)=>{ 
      
        data=decode(data);
        $("#"+htmlid).append(data);
       
        });
}
function getLinkbyid(htmlid,post_page="",id)//if url location is in the same page (/?...) leave void post page
{

    $.get("/binder/output/binder.php?get=postlist",(data)=>
    { 
       
       /*
       to work with common variables we must nest requests in a once
       */ 
            var json=JSON.parse(data);
            data="";
            var length=Object.keys(json).length;
           
            var titlelist=new Array(length);
            for(var i=0;i<length;i++)
            {
            
                titlelist[i]=json[i];    
                    
            }

              $.get("/binder/output/binder.php?get=id",(data)=>{ 
       
        var json=JSON.parse(data);
        data="";
        var length=Object.keys(json).length;
        //alert(length);
        var idlist=new Array(length);
      
         var ok=false;//is boolean variable, it defines if id there is in the array
         id_pos=0;
        for(var i=0;i<length;i++)
        {
            
            idlist[i]=json[i];    
              if(idlist[i]==id)
              {
                    ok=true;
                    id_pos=i;
              }    
        }
        
        
           
           if(ok)
            {
                var out="<a id='art"+id_pos+"' href="+post_page+"?post_id="+idlist[id_pos]+">"+titlelist[id_pos]+"</a>";
          
            out=decode(out);
            
             $("#"+htmlid).append(out);
            } 
       
           
    
      
        });
            
    });
}
function getIdFromURL()
{
    /**
     * Use this function if you need to get post id
     * 
     */
    var myurl;
    var url = new URL(window.location.href);//get my url

    myurl = url.searchParams.get("post_id");//search get parameter in the url string
    return myurl;
   
}
var last_post_ids;//Array
/*
*To use this array you must before call getLast_N_id it will create and load with your last posts id.
then if you what print data use the other functions inside the code
*/
function getLast_N_id(n)
{
    var last_post;
    $.get("/binder/output/binder.php?get=postlist",(data)=>
    { 
       
       /*
       to work with common variables we must nest requests in a once
       */ 
            var json=JSON.parse(data);
            data="";
            var length=Object.keys(json).length;
            
            var titlelist=new Array(length);
            for(var i=0;i<length;i++)
            {
            
                titlelist[i]=json[i];    
                    
            }

              $.get("/binder/output/binder.php?get=id",(data)=>{ 
       
        var json=JSON.parse(data);
        data="";
        var length=Object.keys(json).length;
        
        var idlist=new Array(length);
                
        for(var i=0;i<length;i++)
        {
            
            idlist[i]=json[i];    
              
        }
        
        localStorage.setItem("lastn",idlist);
           
          
       
           
    
      
        });
            
    });
    last_post=new Array(n);
    last_post=localStorage.getItem("lastn").split();
    last_post_ids=new Array(n);
    for(var i=0, j=0;j<n;i++)
    {
        if(i%2==0)
        {
            if(last_post[0][i]!=null||last_post[0][i]!=""||last_post[0][i]!="undefined")
            {
                last_post_ids[j]=last_post[0][i];
            j++;
            
            }
         else
         {
             break;
         }
        }

    }
    localStorage.clear();//clear local storage used to save data from get function

}

//Add quill data

function loadjscssfile(filename, filetype){
    if (filetype=="js"){ //if filename is a external JavaScript file
        var fileref=document.createElement('script')
        fileref.setAttribute("type","text/javascript")
        fileref.setAttribute("src", filename)
    }
    else if (filetype=="css"){ //if filename is an external CSS file
        var fileref=document.createElement("link")
        fileref.setAttribute("rel", "stylesheet")
        fileref.setAttribute("type", "text/css")
        fileref.setAttribute("href", filename)
    }
    if (typeof fileref!="undefined")
        document.getElementsByTagName("head")[0].appendChild(fileref)
}
 
