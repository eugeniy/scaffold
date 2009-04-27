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
			$output = array();

			foreach (self::$db->query("SHOW COLUMNS FROM {$table}") as $col)
			{
				$sortable = Scaffold::Config('tables',$this->table,'fields',$col['Field'],'sortable');
		
				$output[$col['Field']] = array(
					'label' => Scaffold::Config('tables',$this->table,'fields',$col['Field'],'label'),
					'type' => Scaffold::Config('tables',$this->table,'fields',$col['Field'],'type'),
					'sortable' => ($sortable === false) ? false : true,
					'primary' => (($col['Key'] == 'PRI' AND empty($this->primary)) OR $col['Field'] == $this->primary) ? true : false
				);
			}
			$this->fields = $output;
		}
		return $this->fields;
	}


}