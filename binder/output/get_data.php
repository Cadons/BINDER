<?php

class get_data
{
    public static function GetTitleList()
    {
        require_once("../config/get_credezialies.php");
    
        $obj=new getCredenziales();
        $cred=array($obj->getHost(),$obj->getUsername(),$obj->getPassword(),$obj->getDatabase_Name());//$obj->getUsername(),$obj->getPassword(),$obj->getDatabase_Name());
        //echo $cred[1];
        $conn=new MySqli($cred[0],$cred[1],$cred[2],$cred[3]);
        $sql="SELECT * FROM publication";
    $ris=$conn->query($sql);
    $output=array();
    $i=0;
    if(mysqli_num_rows($ris)>0)
    {
    while($row=$ris->fetch_assoc())
                        {
                           
                          
                        $output[$i]=$row["title"];         
                               
                         $i++;   
                       
                        }
                        $output=json_encode ($output);
                        echo $output;
                    }
    }
    public static function GetIdList()
    {
        require_once("../config/get_credezialies.php");
    
        $obj=new getCredenziales();
        $cred=array($obj->getHost(),$obj->getUsername(),$obj->getPassword(),$obj->getDatabase_Name());//$obj->getUsername(),$obj->getPassword(),$obj->getDatabase_Name());
        //echo $cred[1];
        $conn=new MySqli($cred[0],$cred[1],$cred[2],$cred[3]);
        $sql="SELECT * FROM publication";
    $ris=$conn->query($sql);
    $output=array();
    $i=0;
    if(mysqli_num_rows($ris)>0)
    {
    while($row=$ris->fetch_assoc())
                        {
                           
                          
                        $output[$i]=$row["idpublication"];         
                      
                         $i++;   
                       
                        }
                        $output=json_encode ($output);
                        echo $output;
                    }
    }
    public static function GetMyPost($id)
    {
        require_once("../config/get_credezialies.php");
    
        $obj=new getCredenziales();
        $cred=array($obj->getHost(),$obj->getUsername(),$obj->getPassword(),$obj->getDatabase_Name());//$obj->getUsername(),$obj->getPassword(),$obj->getDatabase_Name());
        //echo $cred[1];
        $conn=new MySqli($cred[0],$cred[1],$cred[2],$cred[3]);
        $sql="SELECT * FROM publication WHERE idpublication=$id";
    $ris=$conn->query($sql);
    if(mysqli_num_rows($ris)>0)
    {
    while($row=$ris->fetch_assoc())
                        {
                           
                              
                            $sql="SELECT * FROM article WHERE idarticle=".$row['content'];
                            $ris_art=$conn->query($sql);
                            $row_art=$ris_art->fetch_assoc();
                                
                                    
                                
                                echo $row_art["content"];
                              
                                
                            
                       
                        }
                    }
    }
    public static function GetPostTitle($id)
    {
        $sql="SELECT * FROM publication WHERE idpublication=$id";
        require_once("../config/get_credezialies.php");
    
    $obj=new getCredenziales();
    $cred=array($obj->getHost(),$obj->getUsername(),$obj->getPassword(),$obj->getDatabase_Name());//$obj->getUsername(),$obj->getPassword(),$obj->getDatabase_Name());
    //echo $cred[1];
    $conn=new MySqli($cred[0],$cred[1],$cred[2],$cred[3]);
        $ris=$conn->query($sql);
        if(mysqli_num_rows($ris)>0)
        {
        while($row=$ris->fetch_assoc())
                            {
                            echo $row["title"];
                            }
                        }
      
    }
    public static function GetPostContent($id)
    {
        $sql="SELECT * FROM publication WHERE idpublication=$id";
        require_once("../config/get_credezialies.php");
    
    $obj=new getCredenziales();
    $cred=array($obj->getHost(),$obj->getUsername(),$obj->getPassword(),$obj->getDatabase_Name());//$obj->getUsername(),$obj->getPassword(),$obj->getDatabase_Name());
    //echo $cred[1];
    $conn=new MySqli($cred[0],$cred[1],$cred[2],$cred[3]);
        $ris=$conn->query($sql);
        if(mysqli_num_rows($ris)>0)
        {
        while($row=$ris->fetch_assoc())
                            {
                                echo $row["content"];
                              
                            }
                        }
      
    }
    public static function Search($target)
    {
 
       

        require_once("../config/get_credezialies.php");
    $json=array();
    $titles=array();
    $obj=new getCredenziales();
    $cred=array($obj->getHost(),$obj->getUsername(),$obj->getPassword(),$obj->getDatabase_Name());//$obj->getUsername(),$obj->getPassword(),$obj->getDatabase_Name());
    //echo $cred[1];
    $conn=new MySqli($cred[0],$cred[1],$cred[2],$cred[3]);
   
    $sql="SELECT idTag FROM tag where name like '%$target%'";
    $ris=$conn->query($sql);
        if(mysqli_num_rows($ris)>0)
        {
            $count=0;
        while($row=$ris->fetch_assoc())
             {
                 
                 $titles[]=$row["idTag"];//get titles of retrived by research
             }
             
             $idsTag=array();
             
             //Research article with target tag name and add to id string, it will be bind with the main query
             foreach($titles as $e)
             {
                $_sql="SELECT publication_idPublication FROM tag_has_publication WHERE Tag_idTag=$e";//check if there are article with tags similar $target
                //echo $_sql;
                $ris=$conn->query($_sql);
               if(mysqli_num_rows($ris)>0)//if there are print else check title 
                {
                    //Create a string with conditions of sql query
                   
                  $i=0;
                    while($row=$ris->fetch_assoc())
                    {
                        $idsTag[$i]=$row["publication_idPublication"];
                    }                   
               } 
              }
              $condition="";
              if(sizeof($idsTag)>0)
              {
              $condition="OR idPublication in(";
            //  print_r($idsTag);
              $cont=0;//counter
              foreach($idsTag as $id)
              {

                $condition.=$id;
                if($cont<sizeof($idsTag)-1)
                {
                    $condition.=",";
                     
                }
               $cont++;
              }
              $condition.=")";
              
              }
            }
                //Search article by username
              $userId=array();

              //get user's id with username like target
              $usrSql="SELECT idUser,username FROM user where username like '%$target%'";
              if($usernameRis=$conn->query($usrSql))
              {
                if(mysqli_num_rows($usernameRis)>0)
                {
                     $index=0;
                while($row=$usernameRis->fetch_assoc())
                {
                    $userId[$index]=$row["idUser"];
                }
                //search and get publication' and article' items with author field equal userid element
                $publicationIdwithAuthor=array();//it contains the publication' id that has author id equal userid's array element
                foreach($userId as $e)
                {
                $sqlUserPublication="SELECT publication.idpublication,publication.content,article.author from publication inner join article on publication.content=article.idarticle where article.author=$e";
               
                if($pubUserID=$conn->query($sqlUserPublication))
                {
                    $index=0;
                    while($row=$pubUserID->fetch_assoc())
                    {
                        $publicationIdwithAuthor[$index]=$row["idpublication"];
                        $index++;
                    }
                } 
                }
         

                //edit condition
                $condition.=" OR idpublication in(";
                $cont=0;//counter
                foreach($publicationIdwithAuthor as $id)
                {
  
                  $condition.=$id;
                  if($cont<sizeof($publicationIdwithAuthor)-1)
                  {
                      $condition.=",";
                       
                  }
                 $cont++;
                }
                $condition.=")";
                //*-----------------------------

                }
                 

              }
                
              //if target is void select all publication
                if($target==""||$target==null)
                {
                    $sql="SELECT * from publication inner join article on publication.content=article.idarticle";
                   
                }
                else
                {

                    $sql="SELECT * from publication inner join article on publication.content=article.idarticle WHERE title like '%$target%' ".$condition;
                }
                
 //echo $sql;
                 $ris=$conn->query($sql);
              

                 //search name of author id
                 $count=0;
                 if(mysqli_num_rows($ris)>0)
                 {
                    while($row=$ris->fetch_assoc())
                    {

                        //Load data inside json array
                     
                        $json[$count]=array();
                        $json[$count]["id"]=$row["idPublication"];
                        $json[$count]["title"]=$row["title"];
                        $json[$count]["date"]=$row["date"];
                        $sqlPreview="SELECT * from images where idimage=".$row["preview"];
                      
                        if($imgName=$conn->query($sqlPreview))
                        {
                            $name=$imgName->fetch_assoc();
                            $json[$count]["preview"]=$name["name"];
                        }
                        
                        $json[$count]["content"]=$row["content"]; 

                           
                           $sqlAuthor="SELECT idUser,username FROM user inner join article on user.idUser=article.author where idUser=".$row["author"];
                           if($risAuthor=$conn->query($sqlAuthor))
                           {
                            while($rowUsr=$risAuthor->fetch_assoc())//Load author name retrived from sqlAuthor query
                            {
                                $json[$count]["author"]=$rowUsr["username"];
                              
        
                            }
                           }
                  
                        $count++;

                    }
                 
              
                }
               //  echo $json;           
        
        
        $json=json_encode($json);
                            echo $json;
    }
}

?>