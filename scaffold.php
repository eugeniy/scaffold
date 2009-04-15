 <?php
/**
*
* Scaffold Class
*
* @package Scaffold
* @author Eugeniy Kalinin
* @copyright Copyright (c) 2009, Eugeniy Kalinin
* @license http://pandabytes.info/license
*
*/
class Scaffold
{
	protected $db;


	public function __construct($config)
	{
		//self::$configuration = $config;
		

		//$this->table = $config['current_table'];
		
		
		// Load Zend
		set_include_path(get_include_path() . PATH_SEPARATOR . $config['zend_path']);
		require_once "Zend/Loader.php";
		Zend_Loader::registerAutoload();
		
		// Connect to the database
		$this->db = Zend_Db::factory($config['database']['adapter'], $config['database']['params']);

		
		
		
		require_once "table.php";
		
		//'primary'=>'category_id'
		$this->table = new Scaffold_Table(array('db'=>$this->db,'name' => $config['current_table']));

		
		echo '<pre>'; print_r($this->table->info()); echo '</pre>';
	}

/*


    
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
  
 
 
 
  */
 
 
  
}