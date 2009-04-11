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
	
	protected $columns;


	protected $db;
	protected $view;


	public function __construct($config)
	{

		$this->table = $config['table'];
		
		require_once "Zend/Loader.php";
		Zend_Loader::registerAutoload();
		
		$this->db = Zend_Db::factory($config['database']['adapter'], $config['database']['params']);
		
		 $this->columns = $this->db->describeTable($this->table);
		
		$this->view = new Zend_View();
		$this->view->setBasePath('./views');


	}


	public function DisplayList()
	{		
		
		$this->view->title = 'List';
		$this->view->columns = $this->columns;
		
		$this->view->rows = $this->db->select()->from($this->table)->query()->fetchAll();
		
		echo $this->view->render('list.php');
	}
	

/*
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

*/

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