<?php
/*
In this class are define the methods and attribute userful for database connection and do the operation on that
*/

namespace DatabaseMenager
{
	use MySQLi;
		class Connect_db
		{
			
			
			private $connection;

			function __construct($user,$psw,$db)
			{
				
				$this->connection=new MySQLi("localhost",$user,$psw,$db);//create a conncetion with user, password and database. Theese data can be retrieve inside configuration file
				if(!$this->connection)
				{
					echo "Connection Error";
				}
				
			}
			

			/*
			This method check user login inside the menager page 
			*/
			public function Check_Admin_Credezialies($user,$psw)
			{
				
				$user=mysqli_real_escape_string($this->$connection,$user);
				$psw=mysqli_real_escape_string($this->$connection,$psw);
				$sql="select user,psw from login where user='$user' and psw='$psw'";
				$ris=$this->connection->query($sql);
			
				if(mysqli_num_rows($ris)>0)
				{
					return true;
				}
				else
				{
					return false;
				}
			}
			

	






		}
}
?>