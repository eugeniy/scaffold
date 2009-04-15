<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<title>index</title>
	<style type="text/css" media="screen">
		body {
			background-color: #000;
			color: #ccc;
			font-family: Consolas, sans-serif;
		}
		pre, code {
			background-color: #222;
			font-size: 80%;
			color: #ccc;
			font-family: Consolas, sans-serif;
			padding: 10px 20px;
		}
	</style>
</head>
<body>
	
</body>
</html>

<p>Started: <?php echo date('r'); ?></p>
 
<?php
 
error_reporting(E_ALL);
ini_set('display_errors', '1');
 
 
require_once 'scaffold.php';



$config['zend_path'] = './includes';







$config['database'] = array(
	'adapter' => 'Mysqli',
	'params' => array(
		'host' => 'localhost',
		'dbname' => 'eugeniy_scaffold',
		'username' => 'eugeniy_scaffold',
		'password' => 'fraru9ax',
	)
);


$config['current_table'] = 'products';


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
		/*'description' => array(
			'label' => 'Description',
			'type' => 'text'
		),*/
		'image' => array(
			'label' => 'Image',
			'type' => 'text',
			'sortable' => false
		),
	)
);







 
$test = new Scaffold($config);

 
//$test->DisplayList();
 
 
 
?>
 
<p>Completed: <?php echo date('r'); ?></p>