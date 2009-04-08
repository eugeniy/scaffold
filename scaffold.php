 <?php
/**
 *
 * Scaffold Class
 *
 * @package    Scaffold
 * @author     Eugeniy Kalinin
 * @copyright  Copyright (c) 2009, Eugeniy Kalinin
 * @license    http://pandabytes.info/license
 *
 */
class Scaffold
{

	protected $table;

	//protected $fields = array();
	//protected $headers = array();
	//protected $hidden = array();

	protected $db;


	public function __construct($config = null)
	{
		// MAKE SURE $config['db']['adapter'] and $config['db'] are set
		$this->db = $this->LoadDbAdapter($config['db']['adapter']);
		$this->db->Connect($config['db']);
		
		
	}


	public function GetList($table)
	{
		echo '<table><tr>';
		foreach ($this->db->ListFields($table) as $field)
			echo "<th>{$field}</th>";
		echo '</tr></table>';
	}

	
	protected function InitFields()
	{
		require_once 'include/field/text.php';

		foreach ( $this->db->ListFields($table) as $field )
		{
			$this->fields[$field] = new Scaffold_Field_Text();
		}
	}
	

	/**
	 * Attempt to load a database adapter.
	 */
	protected function LoadDbAdapter($adapter)
	{
		// Verify the basic format of the string
		if (is_string($adapter) && !preg_match('/[^a-z0-9\\/\\\\_.-]/i', $adapter))
		{
			// Make sure the file can be opened
			$fileName = "include/db/{$adapter}.php";
			if (is_readable($fileName))
			{
				include_once 'include/db.php';
				include_once $fileName;
				
				// After an adapter was loaded, make sure it is valid
				// We do that by checking if it extends the DB abstract class
				$className = 'Scaffold_Db_'.ucfirst($adapter);
				if (is_subclass_of($className, 'Scaffold_Db'))
				{
					return new $className;
				}
				else throw new Exception("Database adapter is not valid.");
			}
			else throw new Exception("Database adapter is not accessible.");
		}
		else throw new Exception("Illegal database adapter name.");
	}

	

	protected function GenerateClassNames($inAdapter, $inType)
	{
		$top = 'Scaffold';
		$adapter = ucfirst($inAdapter);
		$type = ucfirst($inType);

		return array(
			'top' => $top,
			'parent' => "{$top}_{$type}",
			'current' => "{$top}_{$type}_{$adapter}"
		);
	}
	
}