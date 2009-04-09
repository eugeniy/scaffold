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
	
	protected $primaryId = null;
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
			// Use the first cell as the primary Id
			if ($this->primaryId === null)
				$this->primaryId = key($row);
		
			// On the first pass, set and display columns
			if ($this->columns === null)
			{
				$this->SetColumns($row);
				$this->DisplayHeader();
			}
		
			// Display the rest of records
			$this->DisplayRow($row);
		}
		
		echo "</table>\n";
	}


	protected function DisplayHeader()
	{
		echo '<tr>';
		foreach ($this->columns as $key => $value)
			echo "<th>{$key}</th>";
		echo "<th>Actions</th></tr>\n";
	}
	
	protected function DisplayRow($row)
	{
		echo '<tr>';
		foreach ($row as $key => $value)
			echo "<td>{$value}</td>";
		echo "<td><a href=\"?{$row[$this->primaryId]}\">Edit</a><a href=\"?{$row[$this->primaryId]}\">Delete</a></td></tr>\n";
	}

	protected function SetColumns($keys)
	{
		$this->columns = array_fill_keys(array_keys($keys), null);
	}
	



	


	
}