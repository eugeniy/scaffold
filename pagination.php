<?php

class Scaffold_Pagination
{
	protected $itemsPerPage = 20;
	protected $itemCount;
	protected $page;
	protected $pageCount;
	protected $previous;
	protected $next;
	protected $offset;
	protected $sql;


	public function __construct($itemCount, $page = 1)
	{
		$this->itemCount = (int) max(0, $itemCount);
		$this->pageCount = ceil($this->itemCount / $this->itemsPerPage);
		$this->page = (int) min(max(1, $this->page), $this->pageCount);
		$this->previous = ($this->page > 1) ? $this->page - 1 : false;
		$this->next = ($this->page < $this->pageCount) ? $this->page + 1 : false;
		$this->offset = ($this->page - 1) * $this->itemsPerPage;
		$this->sql = sprintf(' LIMIT %d OFFSET %d ', $this->itemsPerPage, $this->offset);
	}

	public function Render()
	{
		return '';
	}

	public function __toString()
	{
		return $this->Render();
	}

}