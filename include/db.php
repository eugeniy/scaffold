<?php
/**
 *
 * Database Abstract Adapter
 *
 * @package    Scaffold
 * @author     Eugeniy Kalinin
 * @copyright  Copyright (c) 2009, Eugeniy Kalinin
 * @license    http://pandabytes.info/license
 *
 *
 * NOTE: adapter filenames are all lowercase, class name is in format Scaffold_Db_Mysql
 *
 */
abstract class Scaffold_Db
{

	protected $fields = array();

	abstract public function Connect($dbInfoArray);
	
	abstract public function ListFields($table);
	
	//abstract public function Show($dbInfoArray);

}