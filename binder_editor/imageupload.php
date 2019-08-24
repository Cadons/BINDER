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
		$temp_name= $_FILES[$file]['tmp_name']; //nella prima parentesi mettere il nome del elemento form di type filefile
	
			if(!empty($name))
			{      //verifica che la variabile name non sia vuota    
				   //Destinzazione File  
				if(move_uploaded_file($temp_name, $location.$name))
				{//verifica dello spostamento dalla cartella temporanea alla cartella di destinazione finale
					//echo 'File uploaded successfully';
					return "ok";
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