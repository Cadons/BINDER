
/**
 * This script menage the requests of recoveryPassword.php to reset your password
 * -it send email request
 * -check if email exist
 * 
 */

function checkEmail()
{   var url="ResetPassword.php";
    var data=new FormData();
    data.append("check",1);
    data.append("email",$("#email").val());
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
     
      if(data==200)
      {
          
          SendEmail();
      }else
      {   
         swal ( "Error" ,  "The email:"+$("#email").val()+"\n isn't registred" ,  "error" );
      }
    });
    req.fail(function (jqXHR, textStatus) {

        swal ( "Error" ,  "Something hasn't worked!\n retry" ,  "error" );
     });
}

function SendEmail()
{
    var url="ResetPassword.php";
    var data=new FormData();
    data.append("send",1);
    data.append("email",$("#email").val());
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
     
      if(data==200)
      {
        swal ( "Good job!" ,  "email sended to:"+$("#email").val() ,  "success" ).then(function()
        {
            location.href="/binder/";
        });
      }else
      {
        swal ( "Error" ,  "Something hasn't worked!\n retry" ,  "error" );
      }
    });
    req.fail(function (jqXHR, textStatus) {

        swal ( "Error" ,  "Something hasn't worked!\n retry" ,  "error" );
     });
}
