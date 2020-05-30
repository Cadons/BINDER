<?php

class UploadImage
{
function __constructor()
	{
	
	}
public function Upload($file,$Dir)
{	
      
		$location = $Dir; 
	
		$name= $_FILES[$file]['name'];  //nella prima parentesi mettere il nome del elemento form di type 
		
		//Generate random name for the image
		$extension="";
		$name=str_split($name);//transforms string in array
		$ext=false;//if for cycle has found . char enable ext and load extension name in extension
		for($i=0;$i<count($name);$i++)
		{
		
			if($name[$i]=='.')//check extension
			{
				$ext=true;
			}
			if($ext)//load extension chars
			{
				$extension.=$name[$i];
			}
			
			
		}
		$randName=rand(0,30000);//generates random number; it will be used as name of file
		$name=$randName.$extension;//compose name and .extension
		

		$temp_name= $_FILES[$file]['tmp_name']; //nella prima parentesi mettere il nome del elemento form di type filefile
	
			if(!empty($name))
			{      //verifica che la variabile name non sia vuota    
				   //Destinzazione File  
				if(move_uploaded_file($temp_name, $location.$name))
				{//verifica dello spostamento dalla cartella temporanea alla cartella di destinazione finale
					//echo 'File uploaded successfully';
					//add images name to database

					require_once("../config/get_credezialies.php");
  
					$obj=new getCredenziales();
					$cred=array($obj->getHost(),$obj->getUsername(),$obj->getPassword(),$obj->getDatabase_Name());//$obj->getUsername(),$obj->getPassword(),$obj->getDatabase_Name());

					$conn=new MySqli($cred[0],$cred[1],$cred[2],$cred[3]);
					$sql="INSERT INTO image(name) values('$name')";
					$conn->query($sql);


					return $name;
					//echo 'Loaded';
				}else
				{
					return "error 1";
				}
			
		
				
			
			}
			else
			//	echo 'no file';
				

					//echo 'File uploaded successfully ';
						 return "error 0";
	}
} 
?>