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
	public static $db;
	protected $table;
	
	protected $page = 1;
	protected $itemsPerPage = 10;

	protected $primary;
	protected $id;
	protected $action = 'list';

	public function __construct($config = array())
	{
		try
		{
			// Set the PATH to Zend
			if (isset($config['zend_path']) && is_dir($config['zend_path']))
				set_include_path(get_include_path().PATH_SEPARATOR.$config['zend_path']);
			
			// Load Zend Framework
			if (@include('Zend/Loader.php')) Zend_Loader::registerAutoload();
			else throw new Exception('Required Zend Framework cannot be loaded.');
	
			$this->view = new Zend_View();
			$this->view->setBasePath('./views');

			// Connect to the database
			if (isset($config['database']) && $this->SetupDatabase($config['database']))
				// Setup the table
				$this->SetupTable($config);


			if (isset($config['pagination']['items_per_page']))
				$this->itemsPerPage = (int) $config['pagination']['items_per_page'];
	
	
			if ( ! empty($config['auto_build']))
				echo $this->Build();
	
			//echo '<pre>'; print_r($this->table); echo '</pre>';
		}
		catch (Exception $e)
		{
			self::GetError('Unknown error.', $e);
		}
	}
	
	public function Build()
	{
		if (isset($_GET['action']))
			$this->action = $_GET['action'];
		
		if (isset($_GET['page']) && is_numeric($_GET['page']))
				$this->page = (int) $_GET['page'];
		
		if (isset($_REQUEST[$this->primary]) && is_numeric($_REQUEST[$this->primary]))
			$this->id = (int) $_REQUEST[$this->primary];

		switch ($this->action)
		{
			case 'add':
			case 'edit':
				return $this->GetForm();
			
			case 'save':
				return $this->Save();
			
			default:
				return $this->GetList();
		}

	}
	
	
	public static function GetError($message, $exception=null)
	{
		echo "<div class=\"scaffold-error\">{$message}</div>";
		return false;
	}
	
	
	public function SetupDatabase($config)
	{
		try
		{
			if ( ! isset($config['adapter']) OR ! isset($config['params']))
				throw new Exception('Connection adapter or parameters are missing.');

			self::$db = Zend_Db::factory($config['adapter'], $config['params']);
		}
		catch (Exception $e)
		{
			return self::GetError('Database connection failed.', $e);
		}
		return true;
	}
	
	public function SetupTable($config)
	{
		require_once 'includes/table.php';
		try
		{
			if ( ! isset(self::$db))
				throw new Exception('Database connection is not setup.');
			
			if ( ! isset($config['current_table']))
				throw new Exception('Current table name is not given.');
	
			$setup['db'] = self::$db;
			$setup['name'] = $config['current_table'];
	
			// Pass user-defined custom table data
			if (isset($config['tables'][$config['current_table']]))
				$setup['custom'] = $config['tables'][$config['current_table']];
	
			// Pass primary field name if it is given
			if (isset($setup['custom']['primary']))
				$setup['primary'] = $setup['custom']['primary'];
			
			$this->table = new Scaffold_Table($setup);
			$this->primary = $this->table->GetPrimary();
		}
		catch (Exception $e)
		{
			return self::GetError('Failed to load the table.', $e);
		}
		return true;
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
		$this->view->primary = $this->primary;
		$this->view->title = $this->table->GetLabel();
		
		$this->view->pagination = $this->SetupPagination($select);

		return $this->view->render('list.php');
	}

	public function GetForm()
	{
		$this->view->fields = $this->table->GetFields();
		$this->view->primary = $this->primary;
		$this->view->title = $this->table->GetLabel();
		$this->view->action = 'save';

		if (isset($this->id))
		{
			$select = $this->table->select()->where("{$this->primary} = ?", $this->id);
			$this->view->data = $this->table->fetchRow($select)->toArray();
		}
		
		return $this->view->render('form.php');
	}
	
	protected function Save()
	{
		$this->view->fields = $this->table->GetFields();
		$this->view->primary = $this->primary;
		
		// Do validation and filtering here, if needed
		foreach ($this->view->fields as $key => $value)
			if (isset($_POST[$key]))
				$data[$key] = $_POST[$key];

		if (isset($this->id))
		{
			$where = $this->table->getAdapter()->quoteInto("{$this->primary} = ?", $this->id);
			if ( ! $this->table->update($data, $where))
				$this->view->error = "Could not save!";
		}
		else
		{
			$id = $this->table->insert($data);
			if ($id) $this->id = $id;
			else $this->view->error = "Could not save!";
		}
		
		return $this->GetForm();
	}

}