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

					$output[$col['Field']] = array(
						'label' => ($label === null) ? $this->FormatLabel($col['Field']) : $label,
						'type' => Scaffold::Config('tables',$this->table,'fields',$col['Field'],'type'),
						'sortable' => ($sortable === false) ? false : true,
						'sort' => "{$col['Field']} asc",
						'primary' => (($col['Key'] == 'PRI' AND empty($this->primary)) OR $col['Field'] == $this->primary) ? true : false
					);
				}
				$this->fields = $output;
			}
		}
		return $this->fields;
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
			$this->_order = " ORDER BY {$parts[0]} {$direction} ";
			// Switch the direction in the field information
			$direction = ($direction == 'asc') ? 'desc' : 'asc';
			$this->fields[$parts[0]]['sort'] = "{$parts[0]} {$direction}";
		}
		return $this;
	}
	
	
	public function FetchAll()
	{
		if ($this->data == null OR ! $this->useCache)
		{
			$primary = $this->Primary();
			$table = $this->Escape($this->table);
			$result = self::$db->query("SELECT * FROM {$table}{$this->_order}{$this->_limit}");
			$this->data = $result->fetchAll(PDO::FETCH_ASSOC);
		}
		return $this->data;
	}
	

	public function Count()
	{
		$table = $this->Escape($this->table);
		$result = self::$db->query("SELECT COUNT(*) FROM {$table}");
		
		if ( ! empty($result)) return $result->fetchColumn();
		else return null;
	}
}