<?php
/**
 *
 * Mysql Database Adapter
 *
 * @package    Scaffold_Db
 * @author     Eugeniy Kalinin
 * @copyright  Copyright (c) 2009, Eugeniy Kalinin
 * @license    http://pandabytes.info/license
 *
 */
class Scaffold_Db_Mysql extends Scaffold_Db
{
	protected $handle;

	public function Connect($dbInfo)
	{
		if (isset($dbInfo['username']) && isset($dbInfo['password']))
		{
			// Server name defaults to 'localhost' if none given
			if ( ! isset($dbInfo['server'])) $dbInfo['server'] = 'localhost';

			// If the database name wasn't given, try to use the username
			if ( ! isset($dbInfo['database'])) $dbInfo['database'] = $dbInfo['username'];
		
			$this->handle = mysql_connect($dbInfo['server'], $dbInfo['username'], $dbInfo['password']);
				if ( ! $this->handle) throw new Exception('Cannot connect to the Mysql server.');

			if ( ! mysql_select_db($dbInfo['database'], $this->handle))
				throw new Exception('Mysql database cannot be selected.');
		}
		else throw new Exception('Mysql connection settings were not given.');
	}


	

}