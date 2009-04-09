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
	protected $pdo;
	protected $table;
	
	protected $columns = null;
	//protected $driver;


	public function __construct($pdo, $table)
	{
		$this->pdo = $pdo;
		$this->table = $table;
		
		//$this->driver = $this->pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
	}
	


	public function DisplayList()
	{
		echo "<table>\n";

		$list = $this->pdo->prepare("SELECT * FROM `{$this->table}`");
		$list->execute();
		$list->setFetchMode(PDO::FETCH_ASSOC);
		
		foreach ($list->fetchAll() as $row)
		{
			// On the first pass, set and display columns
			if ($this->columns === null)
			{
				$this->SetColumns($row);
				echo '<tr>';
				foreach ($this->columns as $key => $value)
					echo "<th>{$key}</th>";
				echo "</tr>\n";
			}
		
			// Display the rest of records
			echo '<tr>';
			
			foreach ($row as $key => $value)
				echo "<td>{$value}</td>";
				
			echo "</tr>\n";
		}
		
		echo "</table>\n";
	}


	protected function SetColumns($keys)
	{
		$this->columns = array_fill_keys(array_keys($keys), null);
	}
	



	


	
}