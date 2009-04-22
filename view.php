<?php

class Scaffold_View
{
	protected $_path = './views';
	protected $_file = null;
	protected $_data = array();

	public function __construct($name = null)
	{
		if (is_string($name) AND $name !== '')
			if (is_readable($this->_path.DIRECTORY_SEPARATOR.$name))
				$this->_file = $this->_path.DIRECTORY_SEPARATOR.$name;
	}

	public function Render()
	{
		// Start capturing the output
		ob_start();
		if (isset($this->_file))
		{
			// Import variables into the namespace
			extract($this->_data, EXTR_SKIP);
			// Include the view, allow access to class instance
			include $this->_file;
		}
		// Dump the buffer and return the output
		return ob_get_clean();
	}

	public function Escape($value)
	{
		return htmlentities($value);
	}

	public function SetPath($value)
	{
		if (is_dir($value))
			$this->_path = $value;
	}

	public function __toString()
	{
		return $this->Render();
	}

	public function __set($key, $value)
	{
		$this->_data[$key] = $value;
	}

	public function __get($key)
	{
		if (isset($this->_data[$key]))
			return $this->_data[$key];
	}
}