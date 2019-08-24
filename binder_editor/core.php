<?php
session_start();
if(!isset($_SESSION['log']))
{
    session_destroy();
       header("location: /binder");
       die();
}
   $logusr=$_SESSION['log'];
   $isadmin="writer";

$sql=null;
$req=null;
    if(!isset($_POST['req']))
        $req=$_GET['req'];
    else
        $req=$_POST['req'];
        

    require_once("../config/get_credezialies.php");
  
    $obj=new getCredenziales();
    $cred=array($obj->getHost(),$obj->getUsername(),$obj->getPassword(),$obj->getDatabase_Name());//$obj->getUsername(),$obj->getPassword(),$obj->getDatabase_Name());
    //echo $cred[1];
    $conn=new MySqli($cred[0],$cred[1],$cred[2],$cred[3]);
    $usr= $logusr;  
    $sql="SELECT user,id FROM login WHERE user='$usr'";
    $ris=$conn->query($sql);

     $id=null;;
    if(mysqli_num_rows($ris)>0)
    {
     
        while($row=$ris->fetch_assoc())
             {
                 
                 $id=$row['id'];
          
             }
            $isadmin=check_Admin($conn,$id);

    }


                 
function check_Admin($conn,$id)
{
    $sql="SELECT * FROM admin WHERE iduser=".$id;//check if user is admin
    $ris=$conn->query($sql);
    if(mysqli_num_rows($ris)>0)
    {
        return "admin";
        
    }
    else
    {
        return "writer";
    }
  
}

    switch($req)
    {
        case "save":
        {
           
            /*if($conn)//test db connection
            echo "connected";
            else
            echo "error";*/
    
    
            $id=$_POST['id'];
           
           
            //check existant
            $sql="SELECT * FROM articles WHERE id=".$id;
            $ris=$conn->query($sql);
           
            $content=$_POST['text'];
            
            $content=str_replace("%27","'",$content);
            //  $content=urldecode($_SERVER['REQUEST_URI']);
            $name="";
            $sql="SELECT * FROM articles WHERE id=".$id;
                    $ris=$conn->query($sql);
                    while($row=$ris->fetch_assoc())
                        {
                            $name=$row['title'];
                        }
                    
            if(mysqli_num_rows($ris)>0)
                {
                    //Update
                    $edit_date=getNowDateTime();
                    $sql="UPDATE articles SET content='$content', last_edit='$edit_date' WHERE id=".$id;
                    $conn->query($sql);
                }
                else
                {
                    //add
                   Create_new($name,$content,$conn);
                }
                echo "OK";
                break;
        }
        case "save_pub":
        {
           
            /*if($conn)//test db connection
            echo "connected";
            else
            echo "error";*/
    
    
            $id=$_POST['id'];
           
           
            //check existant
            $sql="SELECT * FROM publications WHERE id=".$id;
            $ris=$conn->query($sql);
           
            $content=$_POST['text'];
            
            
            //  $content=urldecode($_SERVER['REQUEST_URI']);
            $name="";
            $sql="SELECT * FROM publications WHERE id=".$id;
                    $ris=$conn->query($sql);
                    while($row=$ris->fetch_assoc())
                        {
                            $name=$row['title'];
                        }
                    
            if(mysqli_num_rows($ris)>0)
                {
                    //Update
                    $edit_date=getNowDateTime();
            
                    $sql="UPDATE publications SET content='$content',author='$logusr' WHERE id=".$id;
                    $conn->query($sql);
                }
                else
                {
                    //add
                   Create_new($name,$content,$conn);
                }
                echo "OK";
                break;
        }
        case "open":
        {
            $id=$_GET['id'];
            //check existant
            if($isadmin=="admin")
            $sql="SELECT * FROM articles WHERE id=".$id;
            else
            $sql="SELECT * FROM articles WHERE id=".$id." AND author='$logusr'";
            $ris=$conn->query($sql);
          
            
            if(mysqli_num_rows($ris)>0)
                {
                    //send datas
                    if($isadmin=="admin")
                    $sql="SELECT * FROM articles WHERE id=".$id;
                    else
                    $sql="SELECT * FROM articles WHERE id=".$id." AND author='$logusr'";

                    $ris=$conn->query($sql);
                    while($row=$ris->fetch_assoc())
                        {
                            echo $row['content'];
                        }
    
                }
                else
                {
                   //error not found
                   echo "not_found";
                }
             break;
        }
        case "open_pub":
        {
            $id=$_GET['id'];
            //check existant
            if($isadmin=="admin")
            $sql="SELECT * FROM publications WHERE id=".$id;
            else
            $sql="SELECT * FROM publications WHERE id=".$id." AND author='$logusr'";
            $ris=$conn->query($sql);
          
           
            if(mysqli_num_rows($ris)>0)
                {
                    //send datas
                    if($isadmin=="admin")
                     $sql="SELECT * FROM publications WHERE id=".$id;
                     else
                    $sql="SELECT * FROM publications WHERE id=".$id." AND author='$logusr'";
                    $ris=$conn->query($sql);
                    while($row=$ris->fetch_assoc())
                        {
                            echo $row['content'];
                        }
    
                }
                else
                {
                   //error not found
                   echo "not_found";
                }
             break;
        }
        case "list":
        {
            
    
            //check existant
            if($isadmin=="admin")
            $sql="SELECT * FROM articles";
            else
            $sql="SELECT * FROM articles WHERE author='$logusr'";
            $ris=$conn->query($sql);
            $data=array();
            $i=0;
            while($row=$ris->fetch_assoc())
                        {
                            
                            $data[$i]=$row['title'];
                          $i++;
                        }
                        $json=json_encode($data);
                        echo $json;
            break;
        }
        case "getbyid":
        {
            
            $id=$_GET['id'];
    
            //check existant
            if($isadmin=="admin")
            $sql="SELECT * FROM articles WHERE id=$id";
            else
            $sql="SELECT * FROM articles WHERE id=$id AND author='$logusr'";
            $ris=$conn->query($sql);
            $data=array();
            $i=0;
            while($row=$ris->fetch_assoc())
                        {
                            
                            $data[$i]=$row['title'];
                            
                          $i++;
                        }
                        $json=json_encode($data);
                        echo $json;
            break;
        }
        case "getcontbyid":
        {
            
            $id=$_GET['id'];
    
            //check existant
            if($isadmin=="admin")
            $sql="SELECT * FROM articles WHERE id=".$id;
            else
            $sql="SELECT * FROM articles WHERE id=".$id." AND author='$logusr'";

            $ris=$conn->query($sql);
            $data=array();
            $i=0;
            
            while($row=$ris->fetch_assoc())
                        {
                            
                            $data[$i]=$row['content'];
                            
                          $i++;
                        }
                        $json=json_encode($data);
                        echo $json;
            break;
        }
        case "getbyid_pub":
        {
            
            $id=$_GET['id'];
    
            //check existant
            if($isadmin=="admin")
            $sql="SELECT * FROM publications WHERE id=$id";
            else
            $sql="SELECT * FROM publications WHERE id=$id AND author='$logusr'";
            $ris=$conn->query($sql);
            $data=array();
            $i=0;
            while($row=$ris->fetch_assoc())
                        {
                            
                            $data[$i]=$row['title'];
                            
                          $i++;
                        }
                        $json=json_encode($data);
                        echo $json;
            break;
        }
        case "create":
        {
            $title=$_POST['title'];
            
            Create_new($title,"",$conn);
            echo "OK";
            break;
        }
        case "delete":
        {
            $id=$_GET['id'];

            if($isadmin=="admin")
            $sql="DELETE FROM articles WHERE id=".$id;
            else
            $sql="DELETE FROM articles WHERE id=".$id." AND author='$logusr'";

            $conn->query($sql);
            echo "OK";
            break;
        }
        case "delete_pub":
        {
            $title=$_GET['id'];
            if($isadmin=="admin")
            $sql="DELETE FROM publications WHERE id=$title";
            else
            $sql="DELETE FROM publications WHERE id=$title AND author='$logusr'";
            $conn->query($sql);
            echo "OK";
            break;
        }
        case "publish":
        {
            $name=$_POST['title'];
        
          

            $date=$_POST['date'];
            $content=$_POST['text'];
            
            $author=$_SESSION['log'];
            $sql="INSERT INTO publications (title,datepublish,content,author) VALUES ('$name','$date','$content','$author')";
            $conn->query($sql); 
            echo "OK";
            break;
        }
       case"logout": 
       {
       
        session_start();
        session_unset();
        session_destroy();
        
       break;
      
       }
       case"search": 
       {
            $target=$_POST['target'];
            $table=$_POST['in'];
            if($isadmin=="admin")
            $sql="SELECT * FROM $table WHERE lower(title) like '$target%' OR upper(title) like '$target%'";
            else
            $sql="SELECT * FROM $table WHERE lower(title) like '$target%' OR upper(title) like '$target%' AND author='$logusr'";
            $ris=$conn->query($sql);
            $data=array();
            $i=0;
            while($row=$ris->fetch_assoc())
                        {
                            if($table=="articles")
                            {
                            $data[$i][0]=$row['title'];
                            $data[$i][1]=$row['last_edit'];
                            $data[$i][2]=$row['id'];
                            }
                            else
                            {
                                $data[$i][0]=$row['title'];
                                $data[$i][1]=$row['datepublish'];
                                $data[$i][2]=$row['id'];
                            }
                          $i++;
                        }
                        $json=json_encode($data);
                        echo $json;
            break;
      
       }
       case "getlist":
       {
        if(!isset($_GET['published']))
            {
                if($isadmin=="admin")
                $sql="SELECT * FROM articles";
                else
                $sql="SELECT * FROM articles WHERE author='$logusr'";
               
                GetData($sql);
            }
            else
            {
                if($isadmin=="admin")
                $sql="SELECT * FROM publications";
                else
                $sql="SELECT * FROM publications WHERE author='$logusr'";
                GetData($sql,false); 
            }
            break;
                            
       
       
   }
   
   
   
    }
    
    function Create_new($name,$content,$conn)//Create new record void in the db
    {
        $logusr=$_SESSION["log"];
        $edit_date= getNowDateTime();
        $sql="INSERT INTO articles (title,last_edit,content,author) VALUES ('$name','$edit_date','$content','$logusr')";
        $conn->query($sql);
    }
    function getNowDateTime()
    {
        $dt = new DateTime();//get data time
        $edit_date= $dt->format('Y/m/d H:i:s');//format data time with legit db format
        return $edit_date;
    }
    
function GetData($sql,$articles=true)
{
    $logusr=$_SESSION["log"];

    $obj=new getCredenziales();
    $cred=array($obj->getHost(),$obj->getUsername(),$obj->getPassword(),$obj->getDatabase_Name());//$obj->getUsername(),$obj->getPassword(),$obj->getDatabase_Name());
    //echo $cred[1];
    $conn=new MySqli($cred[0],$cred[1],$cred[2],$cred[3]);
    $ris=$conn->query($sql);
    while($row=$ris->fetch_assoc())
                        {
                            if($articles)
                            {

                            ?>
                            
                            <tr>
                                <td><?php echo $row["title"];?></td><td><?php echo $row["last_edit"];?></td>
                                <td><button class = "btn btn-default btn-lg" id=<?php echo $row["id"]; ?> onclick="Edit(this.id)">Edit</button></td>
                                <td><button class = "btn btn-default btn-lg" onclick="Preview(this.id)"  id=<?php echo $row["id"]; ?>>Preview</button></td>
                                <td><button class = "btn btn-default btn-lg" onclick="OpenSharePanel(this.id)" id=<?php echo $row["id"]; ?>>Publish</button></td>
                                <td><button class = "btn btn-default btn-lg" onclick="Delete(this.id)" id=<?php echo $row["id"]; ?>>Delete</button></td>
                            </tr>
                            
                            <?php
                            }
                            else
                            {?>
                                <tr>
                                <td><?php echo $row["title"];?></td><td><?php echo $row["datepublish"];?></td>
                                <td><button class = "btn btn-default btn-lg" id=<?php echo $row["id"]; ?> onclick="Edit_pub(this.id)">Edit</button></td>
                                <td><button class = "btn btn-default btn-lg" onclick="Preview(this.id)"  id=<?php echo $row["id"]; ?> disabled = "disabled">Preview</button></td>
                                <td><button class = "btn btn-default btn-lg" onclick="OpenSharePanel(this.id)" id=<?php echo $row["id"]; ?> disabled = "disabled">Publish</button></td>
                                <td><button class = "btn btn-default btn-lg" onclick="Delete_pub(this.id)" id=<?php echo $row["id"]; ?>>Delete</button></td>

                            </tr> 
                           <?php }

                        }
    
}
    
    
    
    


?>