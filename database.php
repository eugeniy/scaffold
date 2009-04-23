<?php

class Scaffold_Database
{

	public function __construct($config = array())
	{
		$this->connect($config);
	}
	
	public function connect($config)
	{
		if (is_array($config) AND ! empty($config))
		{
			try
			{
				//if (isset($config['driver']))
	
				$dbh = new PDO($dsn, $user, $password);
				return true;
			}
			catch (PDOException $e)
			{
				return false;
			}
		}
	}
}