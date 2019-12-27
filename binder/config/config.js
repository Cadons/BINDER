var first_username,first_user_password,db_host,db_usr,db_psw,db_name,can_create_db,email,SMTP;
var step_number=1;

$().ready(function()
{   
    $("#p1").show();
    $("#p2").hide();
    $("#p3").hide();
    $("#p4").hide();
    $("#p5").hide();
    $("#p6").hide();
    $("#db_name").hide();
    $("#mail_smtp").hide();
    $("#mail_php").hide();
}

);
function testdb()
{
    if($("#dbhost").val()=="")
    {
        db_host="localhost";
    }
    else
    {
         db_host=$("#dbhost").val();
    }
   
    db_psw=$("#dbpsw").val();
    db_usr=$("#dbusr").val();

    data=new FormData();
    data.append("req","testdb");
    data.append("db_host",db_host);
    data.append("db_usr",db_usr);
    data.append("db_psw",db_psw);

    var req=$.ajax
    ({ 
   url: 'testdb.php',
   type: 'POST',
   dataType: 'text',  // what to expect back from the PHP script, if anything
   cache: false,
   contentType: false,
   processData: false,
   data: data
        
    });
    
req.done(function (data) 

{	
    if(data=="ok")
    alert("Connection work!");
 else
 alert("Connection not work!\n1)Check credenziales\n 2)Check database server is online\n 3)Check database server allow remote or localhost connection\n");

});
req.fail(function (jqXHR, textStatus) {
    alert("Connection not work!\n1)Check credenziales\n 2)Check database server is online\n 3)Check database server allow remote or localhost connection\n");
 }); 
}
function back()
{
    var id="p"+step_number;
    $("#"+id).hide();
    step_number--;

     id="p"+step_number;
    $("#"+id).show();
   
}
function Forward()
{
    var id="p"+step_number;
    $("#"+id).hide();
    step_number++;
     id="p"+step_number;
    $("#"+id).show();
}
function firstuser()
{
    if($("#fuser").val()!=""&&$("#fpsw").val()!="")
    {
    first_username=$("#fuser").val();
    first_user_password=$("#fpsw").val();
    email=$("#fmail").val();
    Forward(); 
    }
    else
    {
        alert("Compile all fields!");
    }

}
function databasecfg()
{
   var sel=$( "input:checked" ).val();
   if(sel!=null&&sel!="")
   {
      if(sel=="no")
   {
    can_create_db=0;
    Forward();
    $("#db_name").show();
   }
   else
   {
    can_create_db=1;
    $("#db_name").hide();
       Forward();
   }  
   }
   else
   {
       alert("Select an option please!");
   }
  
}
function Emailcfg()
{
   var sel=$( "input:checked" ).val();
   if(sel!=null&&sel!="")
   {
      if(sel=="1")
   {
    SMTP=1;
    Forward();
    $("#mail_smtp").show();
    $("#mail_php").hide();
   }
   else
   {
    SMTP=2;
    $("#mail_smtp").hide();
    $("#mail_php").show();
       Forward();
   }  
   }
   else
   {
       alert("Select an option please!");
   }
  
}

function endDbcfg()
{

    if($("#dbpsw").val()!=""&&$("#dbusr").val()!=null )
    {
        if(can_create_db==0&&$("#dbname").val()=="")
        {
             alert("Compile * fields!");
        }
        else
        {
            if($("#dbhost").val()=="")
        {
            db_host="localhost";
        }
        else
        {
             db_host=$("#dbhost").val();
        }
       
        db_psw=$("#dbpsw").val();
        db_usr=$("#dbusr").val();
        if(can_create_db==0)
        {
            db_name=$("#dbname").val();
       
        }
        else
        {
            db_name="binder";
          
        }
        Forward();
      }
    }else
    {
        alert("Compile * fields!");
    }
   
}
//SSL Credentiles
var SMTP_address,SMTP_port,SMTP_usr,SMTP_psw,mailto;
function SetupEmail()
{
    if(SMTP==1)//SMTP method
    {
        SMTP_address=$("#smtpAddress").val();
        SMTP_port=$("#smtpPort").val();
        mailto=$("#smtp_test").val();
        SMTP_usr=$("#smtp_usr").val();
        SMTP_psw=$("#smtp_psw").val();
    }
 else
 {
         mailto=$("#mail_test").val();
 }
    
   
}
function TestEmail()
{
    SetupEmail();
    data=new FormData();
    if(SMTP==1)
    {
            data.append("mailto",mailto);
            data.append("smtpAddress",SMTP_address);
            data.append("smtpPort",SMTP_port);
            data.append("smtpUsr",SMTP_usr);
            data.append("smtpPsw",SMTP_psw);
            data.append("method",SMTP); 
            //console.log("dati caricati");
    }else
    {
        data.append("mailto",mailto);
        data.append("method",SMTP); 
    }

    var req=$.ajax
    ({ 
        url: "testEmail.php",
        type: 'POST',
        dataType: 'text',  // what to expect back from the PHP script, if anything
        cache: false,
        contentType: false,
        processData: false,
        data: data
        
    });
    req.done(function(data)
    {
            if(data==1)
            {
                alert("it works!");
            }else
            {
                alert("it doesn't work!\n check credenzialies");
            }
    });
}
function EndConfiguration()
{
 
   SetupEmail();
    getBill();
    Forward();
}
function getBill()
{
    mailconfiguration="";
    if(SMTP==1)
    {
        mailconfiguration="<tr><td>Mail Sended by</td><td>SMTP</td></tr><tr><td>SMTP Server</td><td>"+SMTP_address+"</td></tr><tr><td>SMTP Port</td><td>"+SMTP_port+"</td></tr><tr><td>SMTP Username</td><td>"+SMTP_usr+"</td></tr><tr><td>SMTP Password</td><td>"+SMTP_psw+"</td></tr>";
    }
    else
    {
        mailconfiguration="<tr><td>Mail Sended by</td><td>Php mail()</td></tr>";

    }
    $("#bill").empty(); 
    var output="<tr><td>Username</td><td>"+first_username+"</td></tr><tr><td>Password</td><td>"+first_user_password+"</td></tr><td>Database host</td><td>"+db_host+"</td><tr><td>Database Username</td><td>"+db_usr+"</td><tr><td>Database Password</td><td>"+db_psw+"</td></tr>"+mailconfiguration;
   if(can_create_db==0)
   output+="<tr><td>Database Name</td><td>"+db_name+"</td></tr>";
    $("#bill").append(output);
    //console.log(output);
}
function SendtoConfig()
{
    data=new FormData();
    data.append("usr",first_username);
    data.append("psw",first_user_password);
    data.append("email",email);
    data.append("db_host",db_host);
    data.append("db_usr",db_usr);
    data.append("db_psw",db_psw);
    data.append("db_name",db_name);
    data.append("db_priv",can_create_db);
    data.append("mailSend",SMTP);
    if(SMTP==1)
    {
          data.append("smtpAddress",SMTP_address);
          data.append("smtpPort",SMTP_port);
          data.append("smtpUsr",SMTP_usr);
          data.append("smtpPsw",SMTP_psw);
         
    }
  
    var req=$.ajax
    ({ 
   url: 'setupmypress.php',
   type: 'POST',
   dataType: 'text',  // what to expect back from the PHP script, if anything
   cache: false,
   contentType: false,
   processData: false,
   data: data
        
    });
    
req.done(function (data) 

{	
    $("#output").append("BINDER has bin configurated:<br>"+data+"<br><a href='/binder/'><button class='btn btn-default'>Login</button></a>");
    
 
    
});
                          




req.fail(function (jqXHR, textStatus) {
    alert("BINDER hasn't bin configurated! \ncheck data and retry");
 }); 

   /* $.get("setupmypress.php&usr="++"&psw="+first_user_password+"&db_host="+db_host+"&db_usr="+db_usr+"&db_psw="+db_psw,(data)=>
    {
        if(data=="ok")
        {
            alert("configuration completed!");
            location.href="../";
        }else
        {
            alert("Error retry");
        }

    });*/
}