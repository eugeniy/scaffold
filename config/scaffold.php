<?php defined('SYSPATH') OR die('No direct access allowed.');


$config['tables']['products'] = array(
	
	'label' => 'Products',
	'primary' => 'id',

	'fields' => array(
		'id' => array(
			'type' => 'hidden'
		),
		'category_id' => array(
			'label' => 'Category',
			'type' => 'text'
		),
		'name' => array(
			'label' => 'Name',
			'type' => 'text'
		),
		'description' => array(
			'label' => 'Description',
			'type' => 'text'
		),
		'image' => array(
			'label' => 'Image',
			'type' => 'text',
			'sortable' => false
		),
	)
);

$config['tables']['users'] = array();

$config['pagination']['items_per_page'] = 8;
$config['pagination']['style'] = 'digg';