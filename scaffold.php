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
	protected $table;


	public function __construct($config)
	{		
		// Load Zend
		set_include_path(get_include_path() . PATH_SEPARATOR . $config['zend_path']);
		require_once "Zend/Loader.php";
		Zend_Loader::registerAutoload();
		
		$this->view = new Zend_View();
		$this->view->setBasePath('./views');
		
		Zend_Paginator::setDefaultScrollingStyle('Sliding');
		Zend_View_Helper_PaginationControl::setDefaultViewPartial('pagination.php');
		
		// Connect to the database
		$this->db = Zend_Db::factory($config['database']['adapter'], $config['database']['params']);


		$this->table = $this->SetupTable($config);

		$this->DisplayList();

		//echo '<pre>'; print_r($this->DisplayList()); echo '</pre>';
	}
	
	protected function SetupTable($config)
	{
		require_once "table.php";
		
		$setup['db'] = $this->db;
		$setup['name'] = $config['current_table'];

		// Pass user-defined custom table data
		if (isset($config['tables'][$config['current_table']]))
			$setup['custom'] = $config['tables'][$config['current_table']];

		// Pass primary field name if it is given
		if (isset($setup['custom']['primary']))
			$setup['primary'] = $setup['custom']['primary'];
		
		return new Scaffold_Table($setup);
	}
	
	public function DisplayList()
	{
		$select = $this->table->select();
		$this->view->rows = $this->table->fetchAll()->toArray();

		$this->view->fields = $this->table->GetFields();
		$this->view->primary = $this->table->GetPrimary();
		$this->view->title = $this->table->GetLabel();
		
		$paginator = new Zend_Paginator(new Zend_Paginator_Adapter_DbTableSelect($select));
		
		$paginator->setItemCountPerPage(5);
		//$paginator->setPageRange(5);
		$paginator->setCurrentPageNumber(2);
		
		$this->view->pagination = $this->view->paginationControl($paginator);

		echo $this->view->render('list.php');
		
		
		
		//->order($order)->limit($count, $offset);
		
		

		
		
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