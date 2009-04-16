<?php

class Scaffold_Table extends Zend_Db_Table_Abstract
{
	protected $label;
	protected $fields;
	
	public function __construct($config = array())
	{
		if (isset($config['custom']['label']))
			$this->label = $config['custom']['label'];

		if (isset($config['custom']['fields']))
			$this->fields = $config['custom']['fields'];
		
		parent::__construct($config);
	}

	// Accessors
	public function GetLabel() { return $this->label; }
	public function GetPrimary() { return current($this->_primary); }
	public function GetFields()
	{
		// Load column data if not loaded already
		if (null === $this->_cols) $this->_getCols();
		
		foreach ($this->_cols as $col)
			$output[$col] = isset($this->fields[$col]) ? $this->fields[$col] : array();
		return $output;
	}
}