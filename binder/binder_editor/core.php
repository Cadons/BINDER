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
    $sql="SELECT id,admin FROM login WHERE id=".$id."AND admin=1";//check if user is admin
    $ris=$conn->query($sql);
    if(mysqli_num_rows($ris)>0||$ris!=null)
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
            $content=str_replace("'","%27",$content);
          //  echo $content;
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
            //remove Reference of tags
            $sql="DELETE FROM TagReference WHERE IDPublication=$title";
            $conn->query($sql);
            echo "OK";
            break;
        }
        case "publish":
        {
            $name=$_POST['title'];
            $date=$_POST['date'];
            $preview=$_POST['preview'];
            $idcontent=$_POST['article_id'];
            $tag=$_POST['tags'];        //This string must be divided in an array using ,
            $tagArray=array();
            $tagArray=explode(',',$tag);//explode return an array with tags 
            $date.=" ".date('H:i');
            $author=$_SESSION['log'];
            
            //check if there is an other article

            $sql="SELECT id,content FROM publications WHERE content=$idcontent";
            $ris=$conn->query($sql);
            if(mysqli_num_rows($ris)>0)
            {
                //Update Datas
                $id=$ris->fetch_assoc();
                $id=$id["id"];
                $sql="UPDATE publications SET title='$name', datepublish='$date', Preview='$preview' WHERE id=$id";
                $conn->query($sql);

                $sql="SELECT * FROM TagReference WHERE IDPublication=$id";
                $ris=$conn->query($sql);
                    /*
                           *Search and Add new tag, if they are inside array and db do nothing else add or if inside array there is no tag and it is in the table remove it from db
                           */
                if(mysqli_num_rows($ris)>0)
                {
                    $db_tags=array();
                    $binaryList=array();
                    //Load db_tags array
                       while($row=$ris->fetch_assoc())
                        {
                            $db_tags[]=$row["TagName"];
                        }
                        
                    //Check if user tags and db tags coincide
                            $founded=false;
                             
                    for($i=0;$i<count($db_tags);$i++)
                    {   
                      
                  
                            for($j=0;$j<count($tagArray);$j++)
                            {
                                    if($tagArray[$j]==$db_tags[$i])
                                    {
                                        $founded=true;
                                    }
                            }
                            if($founded==true)
                            $binaryList[$i]=1;
                            else
                            $binaryList[$i]=0;
                            
                            
                            $founded=false;
                    }

                   $n=0;//counter
                    foreach($binaryList as $tag)
                    {
                        if($tag==0)
                        {
                            $sql="DELETE FROM TagReference WHERE TagName='$db_tags[$n]' AND IDPublication=$id";
                            $conn->query($sql); 
                        }
                        $n++;
                    }

                    //If tags aren't inside db insert them
                    foreach($tagArray as $e)
                       {
                       
                           
                                  $sql="SELECT * FROM TagReference WHERE TagName='$e' AND IDPublication=$id";
                                  $ris=$conn->query($sql);

                                  if(mysqli_num_rows($ris)==0)
                                  {

                                          $sql="INSERT INTO Tag (Name) VALUES ('$e')";
                                          $conn->query($sql);  
                                          $sql="INSERT INTO TagReference (TagName, IDPublication) VALUES ('$e',$id)";
                                          $conn->query($sql); 
                                  }
                   
                       }
                
                
                
                }
                else
                {
  
                       foreach($tagArray as $e)
                       {
                       
                           

                         
                                $sql="INSERT INTO Tag (Name) VALUES ('$e')";
                                $conn->query($sql);  
                                $sql="INSERT INTO TagReference (TagName, IDPublication) VALUES ('$e',$id)";
                                $conn->query($sql); 
                            
                   
                       }
                }
                     
                   echo "OK";
                    
            }
            else
            {
                    /*Do publication process */
                    $sql="INSERT INTO publications (title,datepublish,content,author,Preview) VALUES ('$name','$date','$idcontent','$author','$preview')";
                    $conn->query($sql); 
                    //get publications id
                    $sql="SELECT * FROM publications WHERE title='$name' AND author='$author'";
                    $ris=$conn->query($sql);
                
                if(mysqli_num_rows($ris)>0)
                    {
                        $row=$ris->fetch_assoc();
                        $id=$row["id"];
                        
                        /*Do tag loading*/
                    foreach($tagArray as $e)
                        {
                        $sql="INSERT INTO Tag (Name) VALUES ('$e')";
                        $conn->query($sql);  
                        $sql="INSERT INTO TagReference (TagName, IDPublication) VALUES ('$e',$id)";
                        $conn->query($sql); 
                        }
                    echo "OK";
                    } 
                    else
                    {
                        echo "ERROR";
                    }
            }
            
            
     
            break;
        }
       case"logout": 
       {
       
        
        session_unset();
        session_destroy();
        exit();
       break;
      
       }
       case "getImages"://get names of images
       {
           $sql="SELECT name FROM images";
           $ris=$conn->query($sql);
           $photos_names=array();
           if(mysqli_num_rows($ris)>0)
           {
               while($row=$ris->fetch_assoc())
           {
                $photos_names[]=$row['name'];
           }
           $json=json_encode($photos_names);
           echo $json;
           }else
           {
               echo "Nan";//if there isn't images
           }
           
        break;
       }
       case "cancImages"://remove selected images
        {

        if(isset($_GET["images"]))
        {
           $images=array();
           $images=explode(",",$_GET["images"]);
            print_r($images);
            foreach($images as $img)
            {
                //delete image from database
                $sql="DELETE FROM images WHERE name='$img'";
                $conn->query($sql);
               //delete file from hhd
                unlink("../img/".$img);//get file from img folder. it is located in the previus folder of core.php's directory
            }
            echo "done";
        }
        echo "Nan";
            
         break;
        }
       case"search": 
       {
            $target=$_GET['target'];
            $table=$_GET['in'];

        
            if($isadmin=="admin")
            $sql="SELECT * FROM $table WHERE title like '%$target%'";
            else
            $sql="SELECT * FROM $table WHERE title like '$target%' AND author='$logusr'";
    
      /**
       * This snippet needs to retrive datas during research phase.
       * it searchs datas based on title if it has to find articles
       * and it searchs datas based on tag and title if it has to find publications 
       * 
       */
                            if($table=="articles")
                            {
                                GetData($sql);//print with the standard function
                            }
                            else
                            {

                                $_sql="SELECT * FROM TagReference WHERE TagName like '%$target%'";//check if there are articles with tags similar $target
                                $ris=$conn->query($_sql);
                               if(mysqli_num_rows($ris)>0)//if there are print else check title 
                                {
                                    //Create a string with conditions of sql query
                                    $condition="";
                                    while($row=$ris->fetch_assoc())
                                    {
                                        $condition.=" id=".$row['IDPublication']." OR";//add condition element and value of publication's id 
                                    }
                                    
                                     $_sql="SELECT * FROM publications WHERE ".$condition."  title like '%".$target."%'";//Find Datas with id of tags and target title

                                    GetData($_sql,false);//execute and print result
                                 }else
                                 {
                                      GetData($sql,false);
                                 }
                               
                            } 
                            
                            
            break;
      
       }
       case "getlist":
      
         
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
      
     case "getlistnumber":
       
                  
                 if(!isset($_GET['published']))
                     {
                         if($isadmin=="admin")
                         $sql="SELECT * FROM articles";
                         else
                         $sql="SELECT * FROM articles WHERE author='$logusr'";
                        
                         GetData($sql,true,true);
                     }
                     else
                     {
                         if($isadmin=="admin")
                         $sql="SELECT * FROM publications";
                         else
                         $sql="SELECT * FROM publications WHERE author='$logusr'";
                         GetData($sql,false,true); 
                     }                 
                    break;
       
   
   
   
   
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
    
function GetData($sql="",$articles=true,$number=false)
{
    if($sql!="")
    {
                    $logusr=$_SESSION["log"];

                $obj=new getCredenziales();
                $cred=array($obj->getHost(),$obj->getUsername(),$obj->getPassword(),$obj->getDatabase_Name());//$obj->getUsername(),$obj->getPassword(),$obj->getDatabase_Name());
                //echo $cred[1];
                $conn=new MySqli($cred[0],$cred[1],$cred[2],$cred[3]);
                $ris=$conn->query($sql);
                if($number)
                {
                    echo mysqli_num_rows($ris);
                    exit();
                }

                $datas=array();
                $i=0;
                if(isset($_GET["page"]))
                $page_number=$_GET["page"];
                else
                $page_number=1;

                
                $page_number=$page_number.'0';
                
                $cont=0;
                while($row=$ris->fetch_assoc())
                                    {

                                        if($cont<=(int)$page_number&&$cont>=(int)$page_number-10)
                                        {
                                                //add datas inside datas array
                                        if($articles)
                                        {

                                            //if articles has been published, its name will be signed to informed user to this
                                            //if isDelivered is 1 the article has been delivered else it is a proof 
                                            $_sql="SELECT * FROM publications WHERE content=".$row['id'];
                                            $_ris=$conn->query($_sql);
                                            $isDelivered=0;
                                            if(mysqli_num_rows($_ris)>0)
                                            {
                                                $isDelivered=1;
                                            }
                                            
                                            $datas[$i][0]=$row['id'];
                                            $datas[$i][1]=$row["title"];
                                            $datas[$i][2]=$row["last_edit"];
                                            $datas[$i][3]=$isDelivered;
                                        }
                                        else
                                        {
                                            
                                            $datas[$i][0]=$row['id'];
                                            $datas[$i][1]=$row["title"];
                                            $datas[$i][2]=$row["datepublish"];
                                            $datas[$i][3]=$row["content"];
                                        
                                        }
                                        $i++;
                                        }
                                
                                        $cont++;
                                    }
                //JSON encoding
            $json=json_encode($datas);
            echo $json;
    }
    
}
    
    
    
    


?>