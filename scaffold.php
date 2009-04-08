<?php
/**
 *
 * Scaffold Class
 *
 * @package    Scaffold
 * @author     Eugeniy Kalinin
 * @copyright  Copyright (c) 2009, Eugeniy Kalinin
 * @license    http://pandabytes.info/license
 *
 */
class Scaffold
{
	public function __construct($config = null)
	{
		// MAKE SURE $config['db']['adapter'] and $config['db'] are set
		$db = $this->LoadDbAdapter($config['db']['adapter']);
		$db->Connect($config['db']);
		
	}

	/**
	 * Attempt to load a database adapter.
	 */
	protected function LoadDbAdapter($adapter)
	{
		// Verify the basic format of the string
		if (is_string($adapter) && !preg_match('/[^a-z0-9\\/\\\\_.-]/i', $adapter))
		{
			// Make sure the file can be opened
			$fileName = "db/{$adapter}.php";
			if (is_readable($fileName))
			{
				include_once 'db.php';
				include_once $fileName;
				
				// After an adapter was loaded, make sure it is valid
				// We do that by checking if it extends the DB abstract class
				$className = 'Scaffold_Db_'.ucfirst($adapter);
				if (is_subclass_of($className, 'Scaffold_Db'))
				{
					return new $className;
				}
				else throw new Exception("Database adapter is not valid.");
			}
			else throw new Exception("Database adapter is not accessible.");
		}
		else throw new Exception("Illegal database adapter name.");
	}
}