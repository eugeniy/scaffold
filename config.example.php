<?php

$config['zend_path'] = './includes';



$config['database'] = array(
	'adapter' => 'Mysqli',
	'params' => array(
		'host' => 'localhost',
		'dbname' => 'scaffold',
		'username' => 'scaffold',
		'password' => 'passw0rd1',
	)
);

$config['auto_build'] = true;

$config['current_table'] = 'products';


$config['tables']['products'] = array(
	
	'label' => 'Products',
	//'primary' => 'category_id',

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
