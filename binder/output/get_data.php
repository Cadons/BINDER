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
        $sql="SELECT * FROM publications";
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
        $sql="SELECT * FROM publications";
    $ris=$conn->query($sql);
    $output=array();
    $i=0;
    if(mysqli_num_rows($ris)>0)
    {
    while($row=$ris->fetch_assoc())
                        {
                           
                          
                        $output[$i]=$row["id"];         
                      
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
        $sql="SELECT * FROM publications WHERE id=$id";
    $ris=$conn->query($sql);
    if(mysqli_num_rows($ris)>0)
    {
    while($row=$ris->fetch_assoc())
                        {
                           
                              
                            $sql="SELECT * FROM articles WHERE id=".$row['content'];
                            $ris_art=$conn->query($sql);
                            $row_art=$ris_art->fetch_assoc();
                                
                                    
                                
                                echo $row_art["content"];
                              
                                
                            
                       
                        }
                    }
    }
    public static function GetPostTitle($id)
    {
        $sql="SELECT * FROM publications WHERE id=$id";
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
    public static function GetPostContenet($id)
    {
        $sql="SELECT * FROM publications WHERE id=$id";
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
        $sql="SELECT title FROM publications WHERE title like '%$target%' OR author like '%$target%' UNION SELECT TagName FROM TagReference WHERE TagName like '%$target%' UNION SELECT name FROM sections WHERE name like '%$target%' ";
        require_once("../config/get_credezialies.php");
    $json=array();
    $titles=array();
    $obj=new getCredenziales();
    $cred=array($obj->getHost(),$obj->getUsername(),$obj->getPassword(),$obj->getDatabase_Name());//$obj->getUsername(),$obj->getPassword(),$obj->getDatabase_Name());
    //echo $cred[1];
    $conn=new MySqli($cred[0],$cred[1],$cred[2],$cred[3]);


    $ris=$conn->query($sql);
        if(mysqli_num_rows($ris)>0)
        {
            $count=0;
        while($row=$ris->fetch_assoc())
             {
                 $titles[]=$row["title"];
             }
             
             $idString="";
             foreach($titles as $e)
             {
                $sql="SELECT TagName,IDPublication FROM TagReference WHERE TagName='$e'";
                $ris=$conn->query($sql);
                
                if(mysqli_num_rows($ris)>0)
                {
                   while($row=$ris->fetch_assoc())
                   {
                     $idString.=" OR id=".$row['IDPublication'];
                   }
               }
            
                 $sql="SELECT * from publications WHERE title='$e' ".$idString;
                 $ris=$conn->query($sql);
                 if(mysqli_num_rows($ris)>0)
                 {
                    while($row=$ris->fetch_assoc())
                    {
                      
                        $json[$count]=array();

                        $json[$count][]=$row["id"];
                        $json[$count][]=$row["title"];
                        $json[$count][]=$row["datepublish"];
                        $json[$count][]=$row["author"];
                        $json[$count][]=$row["Preview"];
                        $json[$count][]=$row["content"]; 
                        $count++;

                    }
                }
             }                  
        } 
        
        $json=json_encode($json);
                            echo $json;
    }
}

?>