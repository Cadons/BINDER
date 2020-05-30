<?php
//
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);
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
    $sql="SELECT username,id FROM user WHERE user='$usr'";
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
    $sql="SELECT idUser,isAdmin FROM user WHERE idUser=".$id."AND isAdmin=1";//check if user is admin
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
            $sql="SELECT * FROM article WHERE idArticle=".$id;
            $ris=$conn->query($sql);
           
            $content=$_POST['text'];
            $content=str_replace("'","%27",$content);
          //  echo $content;
            //  $content=urldecode($_SERVER['REQUEST_URI']);
            $name="";
            $sql="SELECT * FROM article WHERE idArticle=".$id;
                    $ris=$conn->query($sql);
                    while($row=$ris->fetch_assoc())
                        {
                            $name=$row['title'];
                        }
                    
            if(mysqli_num_rows($ris)>0)
                {
                    //Update
                    $edit_date=getNowDateTime();
                    $sql="UPDATE article SET content='$content', lastEdit='$edit_date' WHERE idArticle=".$id;
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
        
            $sql="SELECT * FROM article WHERE idArticle=".$id." AND author=$logusr";
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
            
    
     
            $sql="SELECT * FROM article WHERE author=$logusr";
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
    
           
            $sql="SELECT name FROM article WHERE idArticle=$id AND author=$logusr";
            $ris=$conn->query($sql);
            $data=array();
            $i=0;
            while($row=$ris->fetch_assoc())
                        {
                            
                            $data[$i]=$row['name'];
                            
                          $i++;
                        }
                        $json=json_encode($data);
                        echo $json;
            break;
        }
        case "getcontbyid":
        {
            
            $id=$_GET['id'];
    
      
            $sql="SELECT content FROM article WHERE idArticle=".$id." AND author=$logusr";

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
    
      
            $sql="SELECT title FROM publication WHERE idPublication=$id";
     
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

          
            $sql="DELETE FROM article WHERE idArticle=".$id;
            if($conn->query($sql))
            echo "ok";
            else
            echo "error";
            break;
        }
        case "delete_pub":
        {
            $id=$_GET['id'];
     
            $sql="DELETE FROM publication WHERE idPublication=$id";
       
            if($conn->query($sql))
           echo "ok";
           else
           echo $conn->error;
   
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
    $sql="SELECT idPublication,content FROM publication WHERE content=$idcontent ";
    $ris=$conn->query($sql);
    if(mysqli_num_rows($ris)>0)
    {
        //Update Datas
        $id=$ris->fetch_assoc();
        $id=$id["idPublication"];
           $sql="UPDATE publication SET title='$name', date='$date', preview=$preview,idSection=$section WHERE idPublication=$id"; 
           
           
        $conn->query($sql);


        $sql="DELETE from tag_has_publication where publication_idPublication=".$id;
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
    
         while($row=$ris->fetch_assoc())
         {
         $sql="INSERT INTO tag_has_publication (publication_idpublication,Tag_idTag)  VALUES ($id,".$row['idTag'].")";
         $conn->query($sql); 
       
         }
       
    
        }
        
    }else{

            /*Do publication process */
        if($preview!=NULL)
        {
            $sql="INSERT INTO publication (date,content,preview,idSection,title) VALUES ('$date',$idcontent,$preview,$section,'$name')";

        }
     else
     {
        $sql="INSERT INTO publication (date,content,idSection,title) VALUES ('$date',$idcontent,$section,'$name')";

     }
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
              
                $sql="SELECT idTag from tag where Name='$e' ";
                $ris=$conn->query($sql);  
           
                while($row=$ris->fetch_assoc())
                {
                        $sql="INSERT INTO tag_has_publication (publication_idPublication,Tag_idTag)  VALUES ((SELECT idPublication FROM publication WHERE content=$idcontent),".$row['idTag'].")";

                $conn->query($sql); 
                }
            
            
          
              
       
                 
        
            }  
            
           
                   
            
    }
    
       echo "OK";
       echo $conn->error; 
}catch (Excption $e)
{
    echo "Error"; 
}
            
     
            break;
        }
       case"logout": 
       {
        $log=fopen("../log/access_log.txt","a");
       	function getUserIpAddr(){
					if(!empty($_SERVER['HTTP_CLIENT_IP'])){
						//ip from share internet
						$ip = $_SERVER['HTTP_CLIENT_IP'];
					}elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
						//ip pass from proxy
						$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
					}else{
						$ip = $_SERVER['REMOTE_ADDR'];
					}
					return $ip;
				}
				fwrite($log,$_SESSION['user']." IP ADDRESS:[".getUserIpAddr()."] has disconnected at ".date("Y-m-d h:i:s a")."\n");
        fclose($log);
        
        session_unset();
        session_destroy();
        exit();
       break;
      
       }
       case "getImages"://get names of images
       {
           $sql="SELECT * from image";
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
                $sql="DELETE from image WHERE name='$img'";
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

        
        
           
    
      /**
       * This snippet needs to retrive datas during research phase.
       * it searchs datas based on title if it has to find article
       * and it searchs datas based on tag and title if it has to find publication 
       * 
       */
                            if($table=="article")
                            { $sql="SELECT * FROM $table WHERE name like '$target%' AND author='$logusr'";
                                GetData($sql);//print with the standard function
                            }
                            else
                            {
                                /*ini_set('display_errors', 1);
                                ini_set('display_startup_errors', 1);
                                error_reporting(E_ALL);*/

                                $sql="SELECT publication.idPublication, publication.title, publication.date, article.idArticle FROM publication INNER JOIN article on publication.content=article.idarticle WHERE (title like'%$target%' OR idPublication IN (SELECT publication_idPublication FROM tag_has_publication INNER JOIN tag ON tag.idTag=tag_has_publication.tag_idTag WHERE tag.Name Like '%$target%')) AND article.author='$logusr'";
                               //check if there are article with tags similar $target

                                    GetData($sql,false);//execute and print result 
                                    
                            
                               //echo $conn->error;
                            } 
                            
                            
            break;
      
       }
       case "getlist":
      
         
        if(!isset($_GET['published']))
            {
              
                $sql="SELECT * FROM article WHERE author=$logusr";
               
                GetData($sql);
            }
            else
            {
               
                $sql="SELECT
                publication.idPublication,
                publication.date,
                publication.content,
                publication.preview,
                publication.title,
                article.author,
                article.idArticle
              FROM
                publication
                  INNER JOIN article ON (publication.content = article.idArticle)
              WHERE
                author =$logusr";
                GetData($sql,false); 
             
            }
            break;
      
     case "getlistnumber":
       
             
                 if(!isset($_GET['published']))
                     {
                         if($isadmin=="admin")
                         $sql="SELECT count(*) as NumberOfarticle FROM article";
                         else
                         $sql="SELECT count(*) as NumberOfarticle FROM article WHERE author='$logusr'";
                        
                       
                     }
                     else
                     {
                         if($isadmin=="admin")
                         $sql="SELECT count(*) as NumberOfarticle FROM publication";
                         else
                         $sql="SELECT
                      count(*) as NumberOfarticle
                       FROM
                         publication
                           INNER JOIN article ON (publication.content = article.idArticle)
                       WHERE
                         author =$logusr";
                         
                     }   
                     $ris=$conn->query($sql);
                     if(mysqli_num_rows($ris)>0)
                     {   while($row=$ris->fetch_assoc()){
                         echo $row["NumberOfarticle"];
                     } 
                    }           
                    break;
       
   
   
   
   
    }
    
    function Create_new($name,$content,$conn)//Create new record void in the db
    {
        $logusr=$_SESSION["log"];
        $edit_date= getNowDateTime();
        $sql="INSERT INTO article (name,lastEdit,content,author) VALUES ('$name','$edit_date','$content','$logusr')";
        $conn->query($sql);
    }
    function getNowDateTime()
    {
        $dt = new DateTime();//get data time
        $edit_date= $dt->format('Y/m/d H:i:s');//format data time with legit db format
        return $edit_date;
    }
    
function GetData($sql="",$article=true)
{
    if($sql!="")
    {
                    $logusr=$_SESSION["log"];

                $obj=new getCredenziales();
                $cred=array($obj->getHost(),$obj->getUsername(),$obj->getPassword(),$obj->getDatabase_Name());//$obj->getUsername(),$obj->getPassword(),$obj->getDatabase_Name());
                //echo $cred[1];
                $conn=new MySqli($cred[0],$cred[1],$cred[2],$cred[3]);
                $ris=$conn->query($sql);
             

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
                                        if($article)
                                        {

                                            //if article has been published, its name will be signed to informed user to this
                                            //if isDelivered is 1 the article has been delivered else it is a proof 
                                            $_sql="SELECT * FROM publication WHERE content=".$row['idArticle'];
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
                                            $datas[$i][3]=$row["idArticle"];
                                     
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