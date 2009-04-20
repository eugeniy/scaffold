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
	public function GetMeta() { return $this->_metadata; }
	public function GetPrimary()
	{
		if (null === $this->_primary) $this->_setupPrimaryKey();

		if  (is_array($this->_primary) OR is_object($this->_primary))
			return current($this->_primary);
		else return $this->_primary;
	}
	public function GetFields()
	{
		// Load column data if not loaded already
		if (null === $this->_cols) $this->_getCols();
		
		foreach ($this->_cols as $col)
		{
			$output[$col] = isset($this->fields[$col]) ? $this->fields[$col] : array();
			
			// Try to detect field type automatically
			if ( ! isset($output[$col]['type']))
			{
				if (isset($this->_metadata[$col]['DATA_TYPE']))
					$output[$col]['type'] = 'auto_'.$this->_metadata[$col]['DATA_TYPE'];
				else $output[$col]['type'] = '';
			}
			
		}
			
		return $output;
	}
}