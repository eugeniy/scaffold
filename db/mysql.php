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
		if (empty($this->fields) AND self::$db !== null AND $this->Table() !== null)
		{
			$table = $this->Escape($this->table);

			foreach (self::$db->query("SHOW COLUMNS FROM {$table}") as $col)
			{
				$sortable = Scaffold::Config('tables',$this->table,'fields',$col['Field'],'sortable');
		
				$out[$col['Field']] = array(
					'label' => Scaffold::Config('tables',$this->table,'fields',$col['Field'],'label'),
					'type' => Scaffold::Config('tables',$this->table,'fields',$col['Field'],'type'),
					'sortable' => ($sortable === false) ? false : true,
					'primary' => ($col['Key'] == 'PRI') ? true : false
				);
			}
			return $out;
		}
		return $this->fields;
	}


	public function Primary($input = null)
	{
		// Set and return primary Id
		if ($input !== null AND is_numeric($input))
		{
			$this->primary = $input;
			return $input;
		}
	
		// Primary Id is already known, simply return it
		elseif ( ! empty($this->primary))
			return $this->primary;
		
		elseif ( ! empty($this->table))
		{
			// Look in the config file
			$primary = Scaffold::Config('tables',$this->table,'primary');
			if ($primary !== null)
			{
				$this->primary = $primary;
				return $primary;
			}

			// Try to automatically select the primary Id from database
			elseif (self::$db !== null)
			{
				$table = $this->Escape($this->table);
				foreach (self::$db->query("SHOW COLUMNS FROM {$table}") as $col)
					if ($col['Key'] == 'PRI')
					{
						$this->primary = $col['Field'];
						return $col['Field'];
					}
			}
		}
		return null;
	}

}