<?php
/**
 *
 * Field Abstract Adapter
 *
 * @package    Scaffold
 * @author     Eugeniy Kalinin
 * @copyright  Copyright (c) 2009, Eugeniy Kalinin
 * @license    http://pandabytes.info/license
 *
 */
abstract class Scaffold_Field
{
	protected $id;
	protected $name;
	protected $value;
	protected $label;

	protected $defaultValue;

	protected $sortable = false;
	protected $disabled = false;

	protected $cellHidden = false;
	
	protected $wrapBefore = '<span>';
	protected $wrapAfter = '</span>';
	
	public function GetField()
	{
		return "<input name=\"{$this->name}\" value=\"{$this->value}\" />";
	}
	
	public function GetCell()
	{
		return $this->value;
	}
}