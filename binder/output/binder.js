
//Client functions
function getPostLength()
{
    
    var result = null;
    var scriptUrl = "/binder/output/binder.php?get=postlist";
    $.ajax({
       url: scriptUrl,
       type: 'get',
       dataType: 'html',
       async: false,
       success: function(data) {
        var json=JSON.parse(data);
        var length=Object.keys(json).length;
           result = length;
       } 
    });
    return result;
   
    
 
          
        
        
}

function getPostList(htmlid,post_page)
{
   post_page="";
    
   var titlelist;
   post_page="";
       $.ajax({
           url:"/binder/output/binder.php?get=postlist",
           type: 'get',//type of request
           dataType: 'html',//datatype of response
           async: false,//asyncronus function, false otherwise the following will be execute without waiting the end of the request.
           success: function(data) {
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
         $.ajax({
            url:"/binder/output/binder.php?get=id",
            type: 'get',//type of request
            dataType: 'html',//datatype of response
            async: false,//asyncronus function, false otherwise the following will be execute without waiting the end of the request.
            success: function(data) {
        
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
                
         
               
                   
            }
        });
           }
       });
       
       

   
}

function getPostListJSON()
{
/**
 * This function return array composed by:
 * enum index
 * output[n][0]=title
 * output[n][0]=id
 */
   post_page="";
    
   var output;
   post_page="";
       $.ajax({
           url:"/binder/output/binder.php?get=postlist",
           type: 'get',//type of request
           dataType: 'html',//datatype of response
           async: false,//asyncronus function, false otherwise the following will be execute without waiting the end of the request.
           success: function(data) {
                /*
          to work with common variables we must nest requests in a once
          */ 
         var json=JSON.parse(data);
       
         data="";
         var length=Object.keys(json).length;
        
         output=new Array(length);
         for(var i=0;i<length;i++)
         {
         
            output[i]=json[i];    
                 
         }
      
         $.ajax({
            url:"/binder/output/binder.php?get=id",
            type: 'get',//type of request
            dataType: 'html',//datatype of response
            async: false,//asyncronus function, false otherwise the following will be execute without waiting the end of the request.
            success: function(data) {
 
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
                 var tmp=output[i];
                output[i]=new Array(2); 
                output[i][0]=tmp;
                output[i][1]=idlist[i];
                

                  
             }
                
         
               
                   
            }
        });
           }
       });
      
    
       return output;
       

   
}
function getPostText(id)
{
    loadjscssfile("/binder/output/style.css", "css"); //dynamically load and add this .js file
    loadjscssfile("https://cdnjs.cloudflare.com/ajax/libs/KaTeX/0.10.2/katex.min.css", "css");
    loadjscssfile("https://cdnjs.cloudflare.com/ajax/libs/KaTeX/0.10.2/katex.min.js", "js");
var post="";
    
    if(id==null||id<0||id=="")
    {
        var url = new URL(window.location.href);
        id = url.searchParams.get("post_id");
    }
    $.ajax({
        url:"/binder/output/binder.php?get=article&id="+id,
        type: 'get',//type of request
        dataType: 'html',//datatype of response
        async: false,//asyncronus function, false otherwise the following will be execute without waiting the end of the request.
        success: function(data) {
                
        data=decode(data);
        post=data;

            
        }
        });
      
    return post;
}
function getTitle(id)
{
var title="";
    $.ajax({
        url:"/binder/output/binder.php?get=title&id="+id,
        type: 'get',//type of request
        dataType: 'html',//datatype of response
        async: false,//asyncronus function, false otherwise the following will be execute without waiting the end of the request.
        success: function(data) {
                
        data=decode(data);
        title=data;
    
            
        }
        });

   return title;
         
}

function getLinkbyid(htmlid,post_page,id)
{
    //if url location is in the same page (/?...) leave void post page
    var titlelist;
post_page="";
    $.ajax({
        url:"/binder/output/binder.php?get=postlist",
        type: 'get',//type of request
        dataType: 'html',//datatype of response
        async: false,//asyncronus function, false otherwise the following will be execute without waiting the end of the request.
        success: function(data) {
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
      $.ajax({
        url:"/binder/output/binder.php?get=id",
        type: 'get',//type of request
        dataType: 'html',//datatype of response
        async: false,//asyncronus function, false otherwise the following will be execute without waiting the end of the request.
        success: function(data) {
    
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
           
               
        }
    });
        }

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

function getLast_N_id(n)
{
    var last_post;
    var result = null;
     var scriptUrl = "/binder/output/binder.php?get=id";
     /**
      * The difference than $.get() and $.ajax() is : $.get doesn't return the return value of the callback function. 
      * It returns the XHR object; and $.ajax() can work with external variables and datas.
      * If you need to return data from request use $.ajax() otherwise if you need to print data inside the page you can use $.ajax() or also $.get() methods
      *  $.get() can't modifys external values, you can only read thouse, if you need to edit external data i adwaise to use localStorage to save datas. 
      */
     $.ajax({
        url: scriptUrl,//url
        type: 'get',//type of request
        dataType: 'html',//datatype of response
        async: false,//asyncronus function, false otherwise the following will be execute without waiting the end of the request.
        success: function(data) {//this will be execute on request success [200 code]
            //get ids and loads those insede the array idlist
            var json=JSON.parse(data);
            data="";
            var length=Object.keys(json).length;
            
            var idlist=new Array(length);
                    
            for(var i=0;i<length;i++)
            {
                
                idlist[i]=json[i];    
                  
            }
         
            var last_post=idlist;//last_post is an array loaded with lastn data that is a list.
    //load array with id of articles 

/**
 * I have deleted the parity control of chars because it works only with numbers less than 10.
 * I remamber that the request, in the get method, returns and saves an array with the articles' ids.
 * so the following code is more simple.
 * This problem has caused slower loading of data inside the page and posts with id greater than or equal of 10 wasen't read
 */
    last_post_ids=new Array(n);
    for(var i=0, j=0;j<n;i++)
    {
    
            if(last_post[i]!=null||last_post[i]!=""||last_post[i]!="undefined")//cheak if datas are valid
            {
                last_post_ids[j]=last_post[i];//add id to the array
            j++;
            
            }
         else
         {
             break;
         }
        

    }
   
            result = last_post_ids;
        } 
     });
     return result;
    
}
function Search(target)
{
    var datas;
    $.ajax({
        url: "/binder/output/binder.php?get=search&target="+target,//url
        type: 'get',//type of request
        dataType: 'html',//datatype of response
        async: false,//asyncronus function, false otherwise the following will be execute without waiting the end of the request.
        success: function(data) 
        {
            /**
             * data return all datas of record
             */
            json=JSON.parse(data);
           datas=json;
           /**
            * Index of objects    
            * n=it is the index of item inside json result  
            *     [n][0]["id"];
                  [n][1]["title"];
                  [n][2]["datepublish"];
                  [n][3]["author"];
                  [n][4]["Preview"];
                  [n][5]["content"]; 

            */
        }

});
return datas;
}
//------------------------------------------------------------------------------------------------------------------------
//Internal functions
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
function getDataFromURL() {
    /**
     * Use this function if you need to get post id
     *
     */
    var myurl;
    var url = new URL(window.location.href); //get my url
    myurl = url.searchParams.get("post_id"); //search get parameter in the url string
    return myurl;
}
function decode(x)
{
  /*
  THIS FUNCTION MUST!!!! EXECUTE INSIDE THE JQUERY READY DOCUMENT FUNCTION 
  */
  
  x=decodeURIComponent(x);
 return x;
}
