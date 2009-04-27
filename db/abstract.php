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
	

	public function Table($input = null)
	{
		if ($input !== null AND is_string($input))
			$this->table = $input;

		elseif (empty($this->table))
		{
			$table = Scaffold::Config('current_table');
			if ($table !== null) $this->table = $table;
		}
		return $this->table;
	}


	public function Label($input = null)
	{
		if ($input !== null AND is_string($input))
			$this->label = $input;

		elseif (empty($this->label))
		{
			$label = Scaffold::Config('tables',$this->table,'label');
			if ($label !== null) $this->label = $label;
		}
		return $this->label;
	}
	
	
	public function Primary($input = null)
	{
		// Set and return primary Id
		if ($input !== null AND is_numeric($input))
			$this->primary = $input;
	
		// If Id is not known, try to locate it
		elseif (empty($this->primary) AND $this->Table() !== null)
		{
			// Look in the config file
			$primary = Scaffold::Config('tables',$this->table,'primary');
			if ($primary !== null)
				$this->primary = $primary;

			// Try to automatically select the primary Id from database
			else
			{
				foreach ($this->Fields() as $key=>$field)
					if ($field['primary'])
					{
						$this->primary = $key;
						break;
					}
			}
		}
		return $this->primary;
	}
	
	

	abstract public function Fields();

	// Chainable
	abstract public function Connect($databaseConfig);
	
}