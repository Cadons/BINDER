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
                           
                              
                     
                            
                                echo $row["content"];
                              
                                
                            
                       
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
}

?>