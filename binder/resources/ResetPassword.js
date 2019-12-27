
function checkPasswords()
{
    if($("#psw1").val()==$("#psw2").val())
    {
        changePassword();
    }else
    {
        swal ( "Error" ,  "Password aren't uquals!" ,  "warning" );

    }
}

function changePassword()
{
 
    var data=new FormData();
    url=new URLSearchParams(document.location.search);
    var id=url.get("id");
    data.append("id",id);
    data.append("psw",$("#psw1").val());
    var req=$.ajax
    ({ 
        url: "ResetPassword.php",
        type: 'POST',
        dataType: 'text',  // what to expect back from the PHP script, if anything
        cache: false,
        contentType: false,
        processData: false,
        data: data
        
    });
    req.done(function (data) 

    {	
     
      if(data=="done")
      {
        swal ( "Good job!" ,  "Password has been changed",  "success" ).then(function()
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