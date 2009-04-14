<?php defined('SYSPATH') OR die('No direct access allowed.');

class Scaffold_Controller extends Controller
{
	protected $db;
	
	
	
	protected $table;
	protected $primary;
	

	protected $fields;
	
	
	
	

	
	// This basically acts as our constructor
	public function __call($table, $args = NULL)
	{
		$this->table = $this->input->xss_clean($table);
		
		// Make sure table is defined in the config file
		// This is needed for security reasons
		if (array_key_exists($this->table, Kohana::config('scaffold.tables')))
		{
			// Make sure the table actually exists in the database
			$this->db = new Database();
			if ($this->db->table_exists($this->table))
			{
				$this->primary = Kohana::config("scaffold.tables.{$this->table}.primary");
			
				// Load field configuration from the config
				$this->fields = Kohana::config("scaffold.tables.{$this->table}.fields");
				
				// Query database for metadata
				foreach ($this->db->field_data($this->table) as $meta)
				{
					$this->fields[$meta->Field]['meta'] = array(
						'type' => $meta->Type,
						'null' => $meta->Null,
						'key' => $meta->Key,
						'default' => $meta->Default,
						'extra' => $meta->Extra
					);

					// Try to set the primary field if none given in the config
					if ($meta->Key == 'PRI' && empty($this->primary))
						$this->primary = $meta->Field;
				}
				
				// Set the first field as primary if everything else failed
				if (empty($this->primary)) $this->primary = key($this->fields);


				switch ($this->uri->segment(3))
				{
					case 'add':
						$this->_add(); break;
					case 'insert':
						$this->_insert(); break;
						
					case 'edit':
						if (isset($args[1])) $this->_edit((int) $args[1]);
						else $this->_add();
						break;
					case 'update':
						$this->_update(); break;

					default:
						$this->_list();
				}

			}
			else
			{
				// ERROR: Table in config, but not database
			}
		}
		else
		{
			// ERROR: Can't access table
		}

	}
	



	public function index()
	{

	}


	protected function _list()
	{
		
		$view = new View('scaffold/list');
		
		$view->count = $this->db->count_records($this->table);
		$items_per_page = Kohana::config("scaffold.pagination.items_per_page");
		
		$view->pagination = new Pagination(array(
			'uri_segment'    => 'page',
			'total_items'    => $view->count,
			'items_per_page' => $items_per_page,
			'style'          => Kohana::config("scaffold.pagination.style")
		));
		
		$offset = $view->pagination->sql_offset;


		// Default Sorting
		foreach ($this->fields as $key=>$value)
			if ( ! isset($value['sortable']) || $value['sortable'] !== false)
				$sortable[$key] = "{$key}+asc";

		$sort_by = $this->input->get('sort');

		// User Defined Sorting
		if ( ! empty($sort_by))
		{
			$sort = explode(' ', $sort_by);
			if (isset($sort[0]) && isset($sort[1]))
				if (array_key_exists($sort[0], $sortable) && ($sort[1] == 'asc' || $sort[1] == 'desc'))
				{
					$this->db->orderby($sort[0], $sort[1]);
					$sortable[$sort[0]] = ($sort[1] == 'desc') ? "{$sort[0]}+asc" : "{$sort[0]}+desc";
				}
		}
		$view->sortable = empty($sortable) ? array() : $sortable;

		$view->table = $this->table;
		$view->title = Kohana::config("scaffold.tables.{$this->table}.label");
		$view->primary = $this->primary;
		$view->fields = $this->fields;
		$view->rows = $this->db->get($this->table,$items_per_page,$offset)->result_array(FALSE);

		$view->render(TRUE);
	}


	protected function _add()
	{
		$view = new View('scaffold/add');

		$view->primary = $this->primary;
		$view->fields = $this->fields;
		
		$view->render(TRUE);
	}
	
	protected function _edit($id)
	{
		$view = new View('scaffold/edit');

		$view->primary = $this->primary;
		$view->fields = $this->fields;
		
		$query = $this->db->from($this->table)->where($this->primary, $id)->get()->result_array(FALSE);
		$view->data = $query[0];
		
		$view->render(TRUE);
	}

	
	protected function _insert()
	{
		// Pre and post processing will go here
		foreach ($this->fields as $key => $value)
			$input[$key] = $this->input->post($key, null, true);
		
		$status = $this->db->insert($this->table, $input);
		
		if ($status)
		{
			url::redirect("scaffold/{$this->table}/edit/".$status->insert_id());
		}
		else
		{
			$view = new View('scaffold/add');
			$view->error = 'Failed!';
			$view->primary = $this->primary;
			$view->fields = $this->fields;
			$view->render(TRUE);
		}
		
		
	}
	
	protected function _update()
	{
		// Pre and post processing will go here
		foreach ($this->fields as $key => $value)
			$input[$key] = $this->input->post($key, null, true);
		
		$status = $this->db->update($this->table, $input, array($this->primary => $input[$this->primary]));
		
		if ($status)
		{
			url::redirect("scaffold/{$this->table}/edit/{$input[$this->primary]}");
		}
		else
		{
			$view = new View('scaffold/edit');
			$view->error = 'Failed!';
			$view->primary = $this->primary;
			$view->fields = $this->fields;
			$view->render(TRUE);
		}
		
		
	}



} // End Scaffold Controller