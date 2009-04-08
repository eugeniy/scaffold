<?php
/**
 *
 * Text Field
 *
 * @package    Scaffold_Field
 * @author     Eugeniy Kalinin
 * @copyright  Copyright (c) 2009, Eugeniy Kalinin
 * @license    http://pandabytes.info/license
 *
 */
class Scaffold_Field_Text extends Scaffold_Field
{
	protected $maxLength;
	
	public function GetField()
	{
		$label = empty($this->label) ? '' : "<label>{$this->label}</label>";
		
		
		return "{$label}<input type=\"text\" />";
	}
	
	


}