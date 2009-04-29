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
					$default = Scaffold::Config('tables',$this->table,'fields',$col['Field'],'default');

					$output[$col['Field']] = array(
						'label' => ($label === null) ? $this->FormatLabel($col['Field']) : $label,
						'type' => Scaffold::Config('tables',$this->table,'fields',$col['Field'],'type'),
						'sortable' => ($sortable === false) ? false : true,
						'sort' => "{$col['Field']} asc",
						'default' => ($default === null) ? '' : $default,
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
	
	
	
	public function FetchOne($id)
	{
		if (is_numeric($id) AND ( ! isset($this->data[$id]) OR ! $this->useCache))
		{
			$query = "SELECT * FROM {$this->table} WHERE {$this->primary} = {$id} LIMIT 1";
			$result = self::$db->query($query);
			$this->data[$id] = $result->fetch(PDO::FETCH_ASSOC);
		}
		return $this->data;
	}
	
	


	public function FetchAll()
	{
		if ($this->data == null OR ! $this->useCache)
		{
			$query = "SELECT * FROM {$this->table}{$this->_order}{$this->_limit}";
			$result = self::$db->query($query);
			// Data is indexed by the primary Id
			foreach ($result->fetchAll(PDO::FETCH_ASSOC) as $data)
				$this->data[$data[$this->primary]] = $data;
		}
		return $this->data;
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
	
	

	public function Count()
	{
		$table = $this->Escape($this->table);
		$result = self::$db->query("SELECT COUNT(*) FROM {$table}");
		
		if ( ! empty($result)) return $result->fetchColumn();
		else return null;
	}
}