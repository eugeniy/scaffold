<?php

require_once 'view.php';

class Scaffold_Pagination
{
	protected $_view = 'pagination.php';

	protected $itemsPerPage;
	protected $itemCount;
	protected $page;
	protected $pageCount;
	protected $previous;
	protected $next;
	protected $offset;

	public function __construct($itemCount, $page = 1)
	{
		$itemsPerPage = Scaffold::Config('items_per_page');
		$this->itemsPerPage = ($itemsPerPage !== null) ? (int) max(1, $itemsPerPage) : 20;
		
		$this->itemCount = (int) max(0, $itemCount);
		$this->pageCount = ceil($this->itemCount / $this->itemsPerPage);
		$this->page = (int) min(max(1, $page), $this->pageCount);
		$this->previous = ($this->page > 1) ? $this->page - 1 : false;
		$this->next = ($this->page < $this->pageCount) ? $this->page + 1 : false;
		$this->offset = ($this->page - 1) * $this->itemsPerPage;
	}
	
	public static function Factory($itemCount, $page = 1)
	{
		return new Scaffold_Pagination($itemCount, $page);
	}

	public function Render()
	{
		return Scaffold_View::Factory($this->_view, get_object_vars($this))->Render();
	}
	
	// Chainable
	public function SetView($file)
	{
		if (is_string($file) AND ! empty($file))
			$this->_view = $file;
		return $this;
	}
	
	public function GetOffset()
	{
		return $this->offset;
	}
	
	public function GetLimit()
	{
		return $this->itemsPerPage;
	}

	public function ToArray()
	{
		return get_object_vars($this);
	}

	public function __toString()
	{
		return $this->Render();
	}
}