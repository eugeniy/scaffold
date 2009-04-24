<?php

abstract class Scaffold_Db
{
	public static $db = null;

	protected $table = null;
	protected $primary = null;
	
	protected $fields = array();
	
	protected $label = null;


	public function __construct()
	{
		if (self::$db == null)
			$this->Connect(Scaffold::Config('database'));
		
		$this->Table();
		$this->Primary();
	}
	
	protected function Escape($in)
	{
		switch (gettype($in))
		{
			case 'string':
				$out = addslashes(stripslashes($in));
				break;
			case 'boolean':
				$out = ($in === false) ? 0 : 1;
				break;
			default:
				$out = ($in === null) ? 'NULL' : $in;
		}
		return $out;
	}
	

	public function Table($tableName = null)
	{
		if (is_string($tableName) AND ! empty($tableName))
			$this->table = $tableName;

		elseif (Scaffold::Config('current_table') !== null)
			$this->table = Scaffold::Config('current_table');

		return $this->table;
	}


	public function Label($input = null)
	{
		if (is_string($input) AND ! empty($input))
			$this->label = $input;
		return $this->label;
	}

	abstract public function Fields();

	// Chainable
	abstract public function Connect($databaseConfig);
	
	abstract public function Primary($input = null);
}