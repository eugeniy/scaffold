<?php

class Scaffold_View
{
	protected $_path = './views';
	protected $_file = null;
	protected $_data = array();

	public function __construct($name = null, $data = null)
	{
		$this->SetFile($name);
		$this->SetData($data);
	}
	
	public static function Factory($name = null, $data = null)
	{
		return new Scaffold_View($name, $data);
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
	
	// Chainable
	public function SetData($data)
	{
		if (is_array($data) AND ! empty($data))
			$this->_data = array_merge($this->_data, $data);
		return $this;
	}

	// Chainable
	public function SetPath($path)
	{
		if (is_string($path) AND is_dir($path))
			$this->_path = $path;
		return $this;
	}

	// Chainable
	public function SetFile($basename)
	{
		if (is_string($basename))
		{
			$file = $this->_path.DIRECTORY_SEPARATOR.$basename;
			if (is_file($file) AND is_readable($file))
				$this->_file = $file;
		}
		return $this;
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