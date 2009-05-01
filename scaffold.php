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
require_once 'pagination.php';
require_once 'db.php';

class Scaffold
{
	protected static $config = array();
	
	protected $table;
	
	protected $page = 1;
	protected $sort = '';

	protected $primary;
	protected $id = null;
	protected $action = 'list';


	public static function LoadConfig($config)
	{
		if (is_array($config) AND ! empty($config))
		{
			self::$config = $config;
			return true;
		}
		return false;
	}


	
	public static function Config()
	{
		$args = func_get_args();
		$count = count($args);
		$config = self::$config;
		
		while (true)
		{
			$current = current($args);

			if (isset($config[$current]))
			{
				// Base-case
				if ($count <= 1) return $config[$current];
				
				// Move to the next array level
				elseif (is_array($config[$current]))
				{
					array_shift($args);
					$config = $config[$current];
					$count--;
				}
				else return null;
			}
			else return null;
		}
	}
	




	public function __construct($config = array())
	{
		self::LoadConfig($config);


		$this->table = new Scaffold_Db();
		

		if (self::Config('auto_build'))
			echo $this->Build();
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
	



	
	public function GetList()
	{
		$view = new Scaffold_View('list.php');
		$view->primary = $this->table->Primary();
		$view->title = $this->table->Label();
		$view->count = $this->table->Count();
		
		// Set-up pagination
		$pagination = new Scaffold_Pagination($view->count, $this->page);
		$view->pagination = $pagination->Render();

		// Fetch rows
		$this->table->Limit($pagination->GetLimit(), $pagination->GetOffset());
		$view->rows = $this->table->Order($this->sort)->FetchAll();
		$view->fields = $this->table->Fields();
		
		$view->parents = $this->table->FetchParentsList();
		
		return $view->Render();
	}

	public function GetForm()
	{
		$view = new Scaffold_View('form.php');
		$view->fields = $this->table->Fields();
		$view->primary = $this->table->Primary();
		$view->title = $this->table->Label();
		
		$data = $this->table->FetchOne($this->id);
		
		// Set default values for the output
		if ( ! is_array($data)) 
			foreach ($view->fields as $key => $value)
				$data[$key] = $value['default'];

		$view->data = $data;

		return $view->Render();
	}
	
	protected function Save()
	{
		
		
		
		$status = $this->table->Save($_POST);
		
		if ($status) echo 'Saved';
		else echo 'Error!';
		
		
		/*
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
		*/
	}

}