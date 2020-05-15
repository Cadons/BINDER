<?php
//
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
    $sql="SELECT idUser,isAdmin FROM users WHERE idUser=".$id."AND isAdmin=1";//check if user is admin
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
            $sql="SELECT * FROM articles WHERE idArticle=".$id;
            $ris=$conn->query($sql);
           
            $content=$_POST['text'];
            $content=str_replace("'","%27",$content);
          //  echo $content;
            //  $content=urldecode($_SERVER['REQUEST_URI']);
            $name="";
            $sql="SELECT * FROM articles WHERE idArticle=".$id;
                    $ris=$conn->query($sql);
                    while($row=$ris->fetch_assoc())
                        {
                            $name=$row['title'];
                        }
                    
            if(mysqli_num_rows($ris)>0)
                {
                    //Update
                    $edit_date=getNowDateTime();
                    $sql="UPDATE articles SET content='$content', lastEdit='$edit_date' WHERE idArticle=".$id;
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
        
            $sql="SELECT * FROM articles WHERE idArticle=".$id." AND author=$logusr";
            $ris=$conn->query($sql);
          
            
            if(mysqli_num_rows($ris)>0)
                {
                    //send datas
               
                   
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
            
    
     
            $sql="SELECT * FROM articles WHERE author=$logusr";
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
    
           
            $sql="SELECT * FROM articles WHERE idArticle=$id AND author=$logusr";
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
    
      
            $sql="SELECT * FROM articles WHERE idArticle=".$id." AND author=$logusr";

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
    
      
            $sql="SELECT * FROM publications WHERE idPublication=$id";
     
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

          
            $sql="DELETE FROM articles WHERE idArticle=".$id;
            if($conn->query($sql))
            echo "ok";
            else
            echo "error";
            break;
        }
        case "delete_pub":
        {
            $title=$_GET['id'];
     
            $sql="DELETE FROM publications WHERE idPublication=$title";
            
            if($conn->query($sql))
           echo "ok";
           else
           echo $conn->error;
            //remove Reference of tags
           /* $sql="DELETE FROM TagReference WHERE IDPublication=$title";
            $conn->query($sql);
            echo "OK";*/
            break;
        }
        case "publish":
        {
            $name=$_POST['title'];
            $date=$_POST['date'];
            $preview=$_POST['preview']; 
            $section=$_POST['section'];
            $idcontent=$_POST['article_id'];
            $tag=$_POST['tags'];        //This string must be divided in an array using ,
            $tagArray=array();
            $tagArray=explode(',',$tag);//explode return an array with tags 
            $date.=" ".date('H:i');
            $author=$_SESSION['log'];
            
            //check if there is an other article
try{
    $sql="SELECT idPublication,content FROM publications WHERE content=$idcontent";
    $ris=$conn->query($sql);
    if(mysqli_num_rows($ris)>0)
    {
        //Update Datas
        $id=$ris->fetch_assoc();
        $id=$id["idPublication"];
           $sql="UPDATE publications SET title='$name', date='$date', Preview=$preview,idSection=$section WHERE idPublication=$id"; 
           
           
        $conn->query($sql);


        $sql="DELETE from publications_has_tag where publications_content=".$idcontent;
        $ris=$conn->query($sql); 
        foreach($tagArray as $e)
        {


        
              
          
         $sql="SELECT name FROM tag where name='$e'";
         $ris=$conn->query($sql);
         if(mysqli_num_rows($ris)==0)
         {
               if($e!="")
         {
                $sql="INSERT INTO tag (Name) VALUES ('$e')";
                    $conn->query($sql);  
         }   
         }
  
         //** */
         //add new tag or edit
       
         $sql="SELECT * from tag where Name='$e' ";
         $ris=$conn->query($sql);  
    
         $row=$ris->fetch_assoc();
         $sql="INSERT INTO publications_has_tag (publications_idpublication,publications_content,Tag_idTag)  VALUES ($id,$idcontent,".$row['idTag'].")";
         $conn->query($sql); 
       
   
       
    
        }
        
    }else{

            /*Do publication process */
        
                $sql="INSERT INTO publications (date,content,Preview,idSection,title) VALUES ('$date',$idcontent,$preview,$section,'$name')";
      
            $conn->query($sql); 
            foreach($tagArray as $e)
            {
            
              

        
                $sql="SELECT name FROM tag where name='$e'";
                $ris=$conn->query($sql);
                if(mysqli_num_rows($ris)==0)
                {
                      if($e!="")
                {
                       $sql="INSERT INTO tag (Name) VALUES ('$e')";
                           $conn->query($sql);  
                }   
                }
         
                //** */
                //add new tag or edit
              
                $sql="SELECT * from tag where Name='$e' ";
                $ris=$conn->query($sql);  
           
                $row=$ris->fetch_assoc();
                $sql="INSERT INTO publications_has_tag (publications_idpublication,publications_content,Tag_idTag)  VALUES ($id,$idcontent,".$row['idTag'].")";
                $conn->query($sql); 
              
          
              
       
                 
        
            }
            
           
                   
            
    }
    
       echo "OK"; 
}catch (Excption $e)
{
    echo "Error"; 
}
            
     
            break;
        }
       case"logout": 
       {
        $log=fopen("../log/access_log.txt","a");
        fwrite($log,$_SESSION['user']." has disconnected at ".date("Y-m-d h:i:s a")."\n");
        fclose($log);
        
        session_unset();
        session_destroy();
        exit();
       break;
      
       }
       case "getImages"://get names of images
       {
           $sql="SELECT * FROM images";
           $ris=$conn->query($sql);
           $photos_names=array();
           if(mysqli_num_rows($ris)>0)
           {
               $i=0;
               while($row=$ris->fetch_assoc())
           {
                $photos_names[$i]=array();
                $photos_names[$i][0]=$row["idimage"];
                $photos_names[$i][1]=$row['name'];
                $i++;
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
            $sql="SELECT * FROM $table WHERE name like '%$target%'";
            else
            $sql="SELECT * FROM $table WHERE name like '$target%' AND author='$logusr'";
    
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
                                /*ini_set('display_errors', 1);
                                ini_set('display_startup_errors', 1);
                                error_reporting(E_ALL);*/

                                $_sql="SELECT * FROM tag WHERE Name like '%$target%'";//check if there are articles with tags similar $target
                                $ris=$conn->query($_sql);
                               if(mysqli_num_rows($ris)>0)//if there are print else check title 
                                {
                                    //Create a string with conditions of sql query
                                    $condition="";
                                    $tagConditionid="Tag_idTag in(";
                                    while($row=$ris->fetch_assoc())
                                    {
                                        $tagList.=$row["idTag"].",";
                                    }
                                    $tagList.="0";
                                    $tagConditionid.=$tagList.")";
                                    $_sql="SELECT * FROM publications_has_tag WHERE ".$tagConditionid;//check if there are articles with tags similar $target
                                   // echo $_sql;
                                    if($ris=$conn->query($_sql))
                                    {
                                              while($row=$ris->fetch_assoc())
                                    {
                                        
                                        $condition.=" idpublication=".$row['publications_idpublication']." OR";//add condition element and value of publication's id 

                                    }
                                     $_sql="SELECT * FROM publications WHERE ".$condition."  (title like '%".$target."%')";//Find Datas with id of tags and target title

                                    GetData($_sql,false);//execute and print result 
                                    }
                             
                                 }else
                                 {
                                      GetData($sql,false);
                                 }
                               //echo $conn->error;
                            } 
                            
                            
            break;
      
       }
       case "getlist":
      
         
        if(!isset($_GET['published']))
            {
              
                $sql="SELECT * FROM articles WHERE author=$logusr";
               
                GetData($sql);
            }
            else
            {
               
                $sql="SELECT
                publications.idPublication,
                publications.date,
                publications.content,
                publications.preview,
                publications.title,
                articles.author,
                articles.idArticle
              FROM
                publications
                  INNER JOIN articles ON (publications.content = articles.idArticle)
              WHERE
                author =$logusr";
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
                         $sql="SELECT
                         publications.idPublication,
                         publications.date,
                         publications.content,
                         publications.preview,
                         publications.title,
                         articles.author,
                         articles.idArticle
                       FROM
                         publications
                           INNER JOIN articles ON (publications.content = articles.idArticle)
                       WHERE
                         author =$logusr";
                         GetData($sql,false,true); 
                     }                 
                    break;
       
   
   
   
   
    }
    
    function Create_new($name,$content,$conn)//Create new record void in the db
    {
        $logusr=$_SESSION["log"];
        $edit_date= getNowDateTime();
        $sql="INSERT INTO articles (name,lastEdit,content,author) VALUES ('$name','$edit_date','$content','$logusr')";
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
                if(mysqli_num_rows($ris)>0)
             {   while($row=$ris->fetch_assoc())
                                    {

                                        if($cont<=(int)$page_number&&$cont>=(int)$page_number-10)
                                        {
                                                //add datas inside datas array
                                        if($articles)
                                        {

                                            //if articles has been published, its name will be signed to informed user to this
                                            //if isDelivered is 1 the article has been delivered else it is a proof 
                                            $_sql="SELECT * FROM publications WHERE content=".$row['idArticle'];
                                            $_ris=$conn->query($_sql);
                                            $isDelivered=0;
                                            if(mysqli_num_rows($_ris)>0)
                                            {
                                                $isDelivered=1;
                                            }
                                            
                                            $datas[$i][0]=$row['idArticle'];
                                            $datas[$i][1]=$row["name"];
                                            $datas[$i][2]=$row["lastEdit"];
                                            $datas[$i][3]=$isDelivered;
                                        }
                                        else
                                        {
                                            
                                            $datas[$i][0]=$row['idPublication'];
                                            $datas[$i][1]=$row["title"];
                                            $datas[$i][2]=$row["date"];
                                            $datas[$i][3]=$row["content"];
                                        
                                        }
                                        $i++;
                                        }
                                
                                        $cont++;
                                    }
                //JSON encoding
            $json=json_encode($datas);
            echo $json;}
    }
    
}
    
    
    
    


?>