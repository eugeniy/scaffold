 <?php

require_once 'abstract.php';

class Scaffold_Db_Mysql extends Scaffold_Db
{
	public function Connect($config)
	{
		if (is_array($config) AND ! empty($config))
		{
			try
			{
				$host = isset($config['host']) ? $config['host'] : 'localhost';
				$user = isset($config['user']) ? $config['user'] : '';
				$dbname = isset($config['dbname']) ? $config['dbname'] : $user;
				$password = isset($config['password']) ? $config['password'] : '';
				$port = isset($config['port']) ? ";port={$config['port']}" : '';
				$dsn = "mysql:host={$host};dbname={$dbname}{$port}";

				$connection = new PDO($dsn, $user, $password);
				
				if ($connection) self::$db = $connection;
			}
			catch (PDOException $e) { }
		}
		return $this;
	}
	
	
	public function GetPrimary()
	{
		if ( ! empty($this->primary))
			return $this->primary;
		
		//elseif ( ! empty($this->table) AND isset())
		
			
	}
	
	//SHOW COLUMNS FROM 
	
}