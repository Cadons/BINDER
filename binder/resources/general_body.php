<?php
 /*error_reporting(E_ALL);
 ini_set('display_errors', 'On');*/
//check path and get right directory
if($_SERVER['PHP_SELF']=="/binder/binder_editor/index.php")
{
require_once("../../binder/config/get_credezialies.php");
}else
{
  require_once("../binder/config/get_credezialies.php");
}


function BodyStart()
{
   ?>   <nav class="menu-bar">
    <div>
     
            <nav class="navbar navbar-light light-blue ">

                    <a href="/binder/menager.php"><h1>BINDER</h1></a>
                  
                    <!-- Collapse button -->
                    <button id="hamburger" class="navbar-toggler toggler-example" type="button" data-toggle="collapse" data-target="#Menu"
                      aria-controls="Menu" aria-expanded="false"  style="border-color:white" aria-label="Toggle navigation"><span class="dark-blue-text" ><i
                          class="fas fa-bars fa-1x" style="color:white;"></i></span></button>
                  
            
                  
                  </nav>
        </div>
                <!-- Collapsible content -->
                <div class="collapse navbar-collapse" id="Menu">
                  
                        <!-- Links -->
                        <ul class="navbar-nav mr-auto ">
                              <li>
                                      <a href="/binder/menager.php"><p class="label-menu">Your Articles</p></a>
                                      </li>
                                      <li>
                                      <a href="/binder/menager.php?published" ><p class="label-menu">Your Pubblications</p></a>
                                      </li>
                                      <li>
                                      <a href="#" onclick="New()"><p class="label-menu">Create New</p></a>
                                      </li>
                                        <?php
                                        error_reporting(E_ALL);
                                        ini_set('display_errors', 'On');
                                    
                         
                                       
                                        function check_Admin_internal()
                                        { 
                                          $obj=new getCredenziales();
                                          $cred=array($obj->getHost(),$obj->getUsername(),$obj->getPassword(),$obj->getDatabase_Name());//$obj->getUsername(),$obj->getPassword(),$obj->getDatabase_Name());
                                          $conn=new MySqli($cred[0],$cred[1],$cred[2],$cred[3]);
                                            $sql="SELECT user,id FROM ".$cred[3].".login WHERE user='".$_SESSION['log']."' AND admin=1";
                                            $ris=$conn->query($sql);
                                            if(mysqli_num_rows($ris)>0)
                                            {
                                             
                                                return 1;
                                            
                                            }
                                            else
                                            {
                                                return 0;
                                            }
                                        }
                                        if(check_Admin_internal()==1)
                                        {?>
                                        <li>
                                    
                                          <a href="/binder/account.php"><p class="label-menu">Accounts Menager</p></a>
                                        </li>
                                        <li>
                                    
                                    <a href="/binder/Settings.php"><p class="label-menu">Settings</p></a>
                                  </li>
                                        <?php }
                                       
                                      ?>
                                      <li>
                                      <a href="#" onclick="logout()"><p class="label-menu">Logout <i class="fas fa-sign-out-alt"></i> </p></a>
                                      </li>
                        </ul>
                        <!-- Links -->
                    
                      </div>
                      <!-- Collapsible content -->
      
     
    
    

</nav>  
<nav class="menu-bar left-bar" id="tool-bar">
    
  <div style="width: 100%;">
      <ul class="menu-list">
          <li>
         <a href="/binder/menager.php"><p class="label-menu">Your Articles</p></a>
         </li>
         <li>
         <a href="/binder/menager.php?published" ><p class="label-menu">Your Pubblications</p></a>
         </li>
         <li>
         <a href="#" onclick="New()"><p class="label-menu">Create New</p></a>
         </li>
         <?php
                              
                                 
                                        if(check_Admin_internal()==1)
                                        {?>
                                        <li>
                                    
                                          <a href="/binder/account.php"><p class="label-menu">Accounts Menager</p></a>
                                        </li>
                                        <li>
                                    
                                    <a href="/binder/Settings.php"><p class="label-menu">Settings</p></a>
                                  </li>
                                        <?php }
                                       
                                      ?>
         <li>
         <a href="#" onclick="logout()"><p class="label-menu">Logout <i class="fas fa-sign-out-alt"></i> </p></a>
         </li>
      </ul>
     
  </div>

</nav>
<div class="content-box">

    <?php
}
function BodyEnd()
{
  
    echo '</div>';
}
?>