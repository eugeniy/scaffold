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
	
	protected $page = 1;
	protected $itemsPerPage = 10;

	protected $id;
	protected $action = 'list';

	public function __construct($config)
	{		
		// Load Zend
		set_include_path(get_include_path() . PATH_SEPARATOR . $config['zend_path']);
		require_once "Zend/Loader.php";
		Zend_Loader::registerAutoload();
		
		$this->view = new Zend_View();
		$this->view->setBasePath('./views');
		
		// Connect to the database
		$this->db = Zend_Db::factory($config['database']['adapter'], $config['database']['params']);


		$this->table = $this->SetupTable($config);

		if (isset($config['pagination']['items_per_page']))
			$this->itemsPerPage = (int) $config['pagination']['items_per_page'];


		// Process request data
		if (isset($_GET['page']) && is_numeric($_GET['page']))
			$this->page = (int) $_GET['page'];

		if (isset($_GET['id']) && is_numeric($_GET['id']))
			$this->id = (int) $_GET['id'];
		
		if (isset($_GET['action']))
			$this->action = $_GET['action'];


		switch ($this->action)
		{
			case 'edit':
				if ( ! empty($this->id)) echo $this->GetEdit($this->id);
				else echo $this->GetAdd();
				break;
			case 'do_edit':
				echo $this->Update(); break;

			case 'add':
				echo $this->GetAdd(); break;
			case 'do_add':
				echo $this->Insert(); break;
				
			default:
				echo $this->GetList();
		}

		//echo '<pre>'; print_r($this->table); echo '</pre>';

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
	
	protected function SetupPagination($select)
	{
		Zend_Paginator::setDefaultScrollingStyle('Sliding');
		Zend_View_Helper_PaginationControl::setDefaultViewPartial('pagination.php');
		
		$paginator = new Zend_Paginator(new Zend_Paginator_Adapter_DbTableSelect($select));
		
		$paginator->setItemCountPerPage($this->itemsPerPage);
		//$paginator->setPageRange(5);
		$paginator->setCurrentPageNumber($this->page);
		
		return $this->view->paginationControl($paginator);
	}
	
	public function GetList()
	{
		$offset = $this->itemsPerPage * ($this->page - 1);
		$select = $this->table->select()->limit($this->itemsPerPage, $offset);
		$this->view->rows = $select->query()->fetchAll();

		$this->view->fields = $this->table->GetFields();
		$this->view->primary = $this->table->GetPrimary();
		$this->view->title = $this->table->GetLabel();
		
		$this->view->pagination = $this->SetupPagination($select);

		return $this->view->render('list.php');
	}
	
	public function GetAdd()
	{
		$this->view->fields = $this->table->GetFields();
		$this->view->primary = $this->table->GetPrimary();
		$this->view->title = $this->table->GetLabel();
		$this->view->action = 'do_'.$this->action;

		return $this->view->render('form.php');
	}
	
	public function GetEdit($id)
	{
		$this->view->fields = $this->table->GetFields();
		$this->view->primary = $this->table->GetPrimary();
		$this->view->title = $this->table->GetLabel();
		$this->view->action = 'do_'.$this->action;

		$select = $this->table->select()->where("{$this->view->primary} = ?", $id);
		$this->view->data = $this->table->fetchRow($select)->toArray();

		return $this->view->render('form.php');
	}

	protected function Insert()
	{
		$this->view->fields = $this->table->GetFields();
		
		// Do validation and filtering here, if needed
		foreach ($this->view->fields as $key => $value)
			if (isset($_POST[$key]))
				$data[$key] = $_POST[$key];

		$id = $this->table->insert($data);

		if ($id) echo $this->GetEdit($id);
		else
		{
			$this->view->error = "Could not save!";
			echo $this->GetAdd();
		}

		
	}

	protected function Update()
	{
		$this->view->fields = $this->table->GetFields();
		$this->view->primary = $this->table->GetPrimary();
		
		// Do validation and filtering here, if needed
		foreach ($this->view->fields as $key => $value)
			if (isset($_POST[$key]))
				$data[$key] = $_POST[$key];
		
		$this->id = $data[$this->view->primary];

		$where = $this->table->getAdapter()->quoteInto("{$this->view->primary} = ?", $this->id);
		$count = $this->table->update($data, $where);

		if ($count) echo $this->GetEdit($this->id);
		else
		{
			$this->view->error = "Could not save!";
			echo $this->GetEdit($this->id);
		}
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