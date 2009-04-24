<?php

abstract class Scaffold_Db
{
	public static $db = null;

	protected $table = null;
	protected $primary = null;

	public function __construct($config = array())
	{
		if (self::$db !== null)
			$this->connect($config['database']);
		
		if (isset($config['current_table']))
		{
			$this->SetTable($config['current_table']);
			$this->GetPrimary();
		}
	}
	
	// Chainable
	public function SetTable($tableName)
	{
		if (is_string($tableName) AND ! empty($tableName))
			$this->table = $tableName;
		return $this;
	}

	// Chainable
	abstract public function Connect($databaseConfig);
	
	abstract public function GetPrimary();
}