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
require_once 'view.php';

class Scaffold
{
	public static $db;
	protected $table;
	
	protected $page = 1;
	protected $itemsPerPage = 10;
	protected $sort;

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
	
			//$this->view = new Zend_View();
			//$this->view->setBasePath('./views');
			//$doctypeHelper = new Zend_View_Helper_Doctype();
			//$doctypeHelper->doctype('XHTML1_STRICT');
			

			// Connect to the database
			if (isset($config['database']) && $this->SetupDatabase($config['database']))
			{
				// Setup the table
				$this->table =$this->SetupTable($config);
				$this->primary = $this->table->GetPrimary();
			}


			if (isset($config['pagination']['items_per_page']))
				$this->itemsPerPage = (int) $config['pagination']['items_per_page'];
	
	
			if ( ! empty($config['auto_build']))
				echo $this->Build();
	
			//echo '<pre>'; print_r($this->parents); echo '</pre>';
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
			
		if (isset($_GET['sort']))
			$this->sort = $_GET['sort'];
		
		if (isset($_GET['page']) && is_numeric($_GET['page']))
				$this->page = (int) $_GET['page'];
		
		if (isset($_REQUEST['id']) && is_numeric($_REQUEST['id']))
			$this->id = (int) $_REQUEST['id'];

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
		echo '<pre>'; print_r($exception->getMessage()); echo '</pre>';
		return false;
	}


	public static function Url($attr = array(), $appendGetAttrs = false)
	{
		if ($appendGetAttrs)
			foreach (explode('&', $_SERVER['QUERY_STRING']) as $arg)
			{
				$parts = explode('=', $arg, 2);
				if ( ! isset($attr[$parts[0]]) && isset($parts[1]))
					$attr[$parts[0]] =  urldecode($parts[1]);
			}

		return '?'.http_build_query($attr, '', '&amp;');	
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
	
	public function SetupTable($config, $depth = 1)
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
				
			// Set-up parent tables
			if (isset($setup['custom']['fields']))
				foreach ($setup['custom']['fields'] as $key => $value)
					if (isset($value['parent']))
					{
						$config['current_table'] = $value['parent']['table'];
						$setup['parents'][$key] = $this->SetupTable($config, --$depth);
					}
			
			return new Scaffold_Table($setup);
		}
		catch (Exception $e)
		{
			return self::GetError("Failed to load the table {$config['current_table']}.", $e);
		}
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
		$this->view = new Scaffold_View('scripts/list.php');
		$this->view->fields = $this->table->GetFields();
		$this->view->primary = $this->primary;
		$this->view->title = $this->table->GetLabel();	

		$offset = $this->itemsPerPage * ($this->page - 1);
		$select = $this->table->select()->limit($this->itemsPerPage, $offset);
		
		// Default Sorting
		foreach ($this->view->fields as $key => $value)
			if ( ! isset($value['sortable']) OR $value['sortable'] !== false)
				$sortable[$key] = "{$key} asc";

			// User specified sorting
		if ( ! empty($this->sort))
		{
			$parts = explode(' ', $this->sort);
			if (array_key_exists($parts[0], $sortable) && isset($parts[1]))
			{
				$direction = ($parts[1] == 'desc') ? 'desc' : 'asc';
				$select->order("{$parts[0]} {$direction}");
				// Switch the direction for the output
				$sortable[$parts[0]] = ($direction == 'desc') ? "{$parts[0]} asc" : "{$parts[0]} desc";
			}
		}
		$this->view->sortable = empty($sortable) ? array() : $sortable;
		
		$this->view->rows = $select->query()->fetchAll();
		//$this->view->pagination = $this->SetupPagination($select);

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