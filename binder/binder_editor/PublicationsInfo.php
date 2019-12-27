<?php

session_start();
if(!isset($_SESSION['log']))
{
    session_destroy();
       header("location: /binder");
       die();
}
else
{
    if(isset($_GET["req"]))
    {
        $req=$_GET["req"];
        require_once("../config/get_credezialies.php");
  
        $obj=new getCredenziales();
        $cred=array($obj->getHost(),$obj->getUsername(),$obj->getPassword(),$obj->getDatabase_Name());//$obj->getUsername(),$obj->getPassword(),$obj->getDatabase_Name());
        //echo $cred[1];
        $conn=new MySqli($cred[0],$cred[1],$cred[2],$cred[3]);
        switch($req)
        {
            case "tags":
                {
                    if(isset($_GET["id"]))
                    {
                            $json=array();
                            $target=$_GET["id"];
                            $sql="SELECT * FROM publications WHERE content=$target";
                            $ris=$conn->query($sql);
                            if(mysqli_num_rows($ris)>0)
                            {
                            $row=$ris->fetch_assoc();
                                    
                            $target=$row["id"];
                            
                                   
                            $sql="SELECT * FROM TagReference WHERE IDPublication=$target";
                            //echo $sql;
                            $ris=$conn->query($sql);
                            if(mysqli_num_rows($ris)>0)
                            {
                                while($row=$ris->fetch_assoc())
                                {
                                    $json[]=$row["TagName"];
                                }
                                $json=json_encode($json);
                                echo $json;
                            }else
                            {
                                echo "not_found";
                            } 
                            }else
                            {
                                echo "not_found";
                            }
                       

                    }
                  
                    break;
                }
            case "title":
                {
                    if(isset($_GET["id"]))
                    {
                        
                        $target=$_GET["id"];
                        $sql="SELECT * FROM publications WHERE content=$target";
                        $ris=$conn->query($sql);
                            if(mysqli_num_rows($ris)>0)
                            {
                                $row=$ris->fetch_assoc();
                                    
                                        $json=$row["title"];
                                    
                                   
                                    echo $json;
                            }else
                            {
                                echo "not_found";
                            }
    
                    }
                      
                    break;
                }
            case "date":
            {
                if(isset($_GET["id"]))
                    {
                        $json;
                        $target=$_GET["id"];
                        $sql="SELECT * FROM publications WHERE content=$target";
                        $ris=$conn->query($sql);
                            if(mysqli_num_rows($ris)>0)
                                {
                                    while($row=$ris->fetch_assoc())
                                        {
                                            $json=$row["datepublish"];
                                        }
                                       
                                        echo $json;
                                }
                                else
                                    {
                                        echo "not_found";
                                    }
        
                    }
                          
                    break;
            }                 
       
            case "preview":
                {
                    if(isset($_GET["id"]))
                        {
                            $json;
                            $target=$_GET["id"];
                            $sql="SELECT * FROM publications WHERE content=$target";
                            $ris=$conn->query($sql);
                                if(mysqli_num_rows($ris)>0)
                                    {
                                        while($row=$ris->fetch_assoc())
                                            {
                                                $json=$row["Preview"];
                                            }
                                        
                                            echo $json;
                                    }
                                    else
                                        {
                                            echo "not_found";
                                        }
                                    
                        }
                              
                        break;
                }                 
            }
        }
    }



?>