<?php

class Scaffold_Db
{
	public static $db = null;
	protected static $depth = 2; // Recursion depth for parents
	
	protected $useCache = true;

	protected $table = null;
	protected $primary = null;
	
	protected $_limit = '';
	protected $_order = '';
	protected $_where = '';
	
	protected $data = null;

	protected $fields = array();

	protected $label = null;


	protected $parents = array();
	protected $children = array();


	public function __construct($table = null)
	{
		if (self::$db == null)
			$this->Connect(Scaffold::Config('database'));
		
		$this->Table($table);
		$this->Primary();
		$this->Label();
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
	
	protected function FormatLabel($input = '')
	{
		return ucwords(str_replace(array('_','-'), ' ', $input));
	}
	

	public function Table($input = null)
	{
		if ($input !== null AND is_string($input))
			$this->table = $input;

		elseif ($input === null AND empty($this->table))
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

		elseif (empty($this->label) AND $this->Table() !== null)
		{
			$label = Scaffold::Config('tables',$this->table,'label');
			if ($label !== null) $this->label = $label;
			else $this->label = $this->FormatLabel($this->table);
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
	
	



	public function Limit($limit, $offset)
	{
		$this->_limit = sprintf(' LIMIT %d OFFSET %d ', $limit, $offset);
		return $this;
	}
	
	
	public function Order($sort)
	{
		$parts = explode(' ', $sort);
		if (array_key_exists($parts[0], $this->Fields()) AND isset($parts[1]))
		{
			$direction = ($parts[1] == 'desc') ? 'desc' : 'asc';
			$this->_order = " ORDER BY {$this->table}.{$parts[0]} {$direction} ";
			// Switch the direction in the field information
			$direction = ($direction == 'asc') ? 'desc' : 'asc';
			$this->fields[$parts[0]]['sort'] = "{$parts[0]} {$direction}";
		}
		return $this;
	}
	
	public function FetchOne($id)
	{
		if (is_numeric($id) AND ( ! isset($this->data[$id]) OR ! $this->useCache))
		{
			$query = "SELECT * FROM {$this->table} WHERE {$this->primary} = {$id} LIMIT 1";
			$result = self::$db->query($query);
			$this->data[$id] = $result->fetch(PDO::FETCH_ASSOC);
		}
		if (is_array($this->data)) return $this->data[$id];
		else return $this->data;
	}
	
	public function FetchAll()
	{
		if ($this->data == null OR ! $this->useCache)
		{
			// Create arrays with field names and field count
			// Faster to do it now than to call these from inside the loop
			$keys[$this->primary] = array_keys($this->Fields());
			$fieldCount[$this->primary] = count($keys[$this->primary]);

			// Join parent tables
			$join = '';
			foreach ($this->parents as $key => $parent)
			{
				// We need aliases in case several same tables are joined
				$alias = "{$key}_".$parent->Table();
				$join .= ' LEFT JOIN '.$parent->Table()." AS {$alias} ON {$this->table}.{$key} = {$alias}.".$parent->Primary().' ';

				$keys[$key] = array_keys($parent->Fields());
				$fieldCount[$key] = count($keys[$key]);
			}

			// Fetch the data
			$query = "SELECT * FROM {$this->table}{$join}{$this->_order}{$this->_limit}";
			$result = self::$db->query($query);
			
			// PDO messes up when JOINed column has same column names
			// Ugly code to place current and parent table data in right places
			if ( ! empty($result)) 
				// Data is an integer indexed data array
				// Fields are listed in the order they were joined
				foreach ($result->fetchAll(PDO::FETCH_NUM) as $data)
					// Iterate through "groups" of fields
					foreach ($fieldCount as $key => $count)
					{
						// Cut the $count elements from the front of data array
						// Out contains data for one of tables
						$out = array_combine($keys[$key], array_splice($data, 0, $count));
						
						// These values go to the current tables' data
						// Data is indexed by the primary Id
						if ($this->primary == $key)
							$this->data[$out[$this->primary]] = $out;
						
						// These will go to the parent table data
						else
							$parentData[$key][$out[$this->parents[$key]->Primary()]] = $out;
					}

			// Set the parent data
			if (isset($parentData) AND is_array($parentData))
				foreach ($parentData as $key => $value)
					$this->parents[$key]->Data($value);
		}
		return $this->data;
	}
	
	
	public function FetchParentsList()
	{
		$output = array();
		foreach ($this->parents as $field=>$parent)
		{
			$label = Scaffold::Config('tables',$this->Table(),'fields',$field,'parent_display');
			foreach ($parent->Data() as $id=>$value)
			{
				if (is_array($value))
					$output[$field][$id] = array_key_exists($label, $value) ? $value[$label] : current($value);
				else $output[$field][$id] = '';
			}
		}
		return $output;
	}
	
	
	public function Data($data = null)
	{
		if ($data !== null AND is_array($data))
			$this->data = $data;
		return $this->data;
	}

	
	public function Fields()
	{
		if ((empty($this->fields) OR ! $this->useCache) AND self::$db !== null AND $this->Table() !== null)
		{
			$table = $this->Escape($this->table);
			$output = array();
			$result = self::$db->query("SHOW COLUMNS FROM {$table}");
			
			if ( ! empty($result))
			{
				foreach ($result as $col)
				{
					$sortable = Scaffold::Config('tables',$this->table,'fields',$col['Field'],'sortable');
					$label = Scaffold::Config('tables',$this->table,'fields',$col['Field'],'label');
					$default = Scaffold::Config('tables',$this->table,'fields',$col['Field'],'default');
					
					// Load the parent table
					$parentTable = Scaffold::Config('tables',$this->table,'fields',$col['Field'],'parent');
					if ($parentTable !== null AND self::$depth > 0)
					{
						self::$depth--;
						$this->parents[$col['Field']] = new Scaffold_Db($parentTable);
					}
						

					$output[$col['Field']] = array(
						'label' => ($label === null) ? $this->FormatLabel($col['Field']) : $label,
						'type' => Scaffold::Config('tables',$this->table,'fields',$col['Field'],'type'),
						'sortable' => ($sortable === false) ? false : true,
						'sort' => "{$col['Field']} asc",
						'default' => ($default === null) ? '' : $default,
						'primary' => (($col['Key'] == 'PRI' AND empty($this->primary)) OR $col['Field'] == $this->primary) ? true : false,
						'parent' => ($parentTable === null) ? '' : $parentTable
					);
				}
				$this->fields = $output;
			}
		}
		return $this->fields;
	}


	public function Save($input)
	{
		$primary = $this->Primary();
		// Only keep fields that are in the database
		foreach ($this->Fields() as $key => $value)
			// Do not append a primary Id
			if (isset($input[$key]) AND $key !== $primary)
			{
				$keys[] = "{$key}=?";
				$values[] = $input[$key];
			}

		// Prepare SQL statement, let PDO deal with escaping
		if (isset($input[$primary]) AND ! empty($input[$primary]))
		{
			$action = 'UPDATE ';
			$condition = " WHERE {$primary}=? LIMIT 1";
			$values[] = $input[$primary];
		}
		else
		{
			$action = 'INSERT INTO ';
			$condition = '';
		}
		$query = $action.$this->Table().' SET '.implode(',', $keys).$condition;
		$statement = self::$db->prepare($query);

		return $statement->execute($values);
	}


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
	
	public function Count()
	{
		$table = $this->Escape($this->table);
		$result = self::$db->query("SELECT COUNT(*) FROM {$table}");
		
		if ( ! empty($result)) return $result->fetchColumn();
		else return null;
	}
	
}