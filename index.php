<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Scaffold Example</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<style type="text/css" media="screen">
	body {
		background-color: #111;
		color: #ddd;
		font-family: Arial, Helvetica, sans-serif;
		font-size: 80%;
	}
	
	a {
		color: #7587a6;
		text-decoration: none;
	}
	a:hover { text-decoration: underline; }
	
	table {
		font-size: 100%;
		border-spacing: 1px;

	}
	
	caption {
		text-align: left;
		padding: 5px 10px;
		background-color: #333;
		font-weight: bold;
		margin: 0 0 5px 0;
	}
	
	caption a { color: #ddd; }
	
	caption .actions {
		float: right;
	}

	thead a {
		color: #aaa;
		text-decoration: none;
		border-bottom: 1px dotted #aaa;
	}
	thead th {
		color: #aaa;
		background-color: #222;
		padding: 5px 10px;
	}
	tbody td {
		padding: 2px 10px;
		background-color: #111;
	}
	
	pre, code {
		background-color: #222;
		font-size: 90%;
		color: #ccc;
		font-family: Consolas, sans-serif;
		padding: 10px 20px;
	}
</style>


</head>

<body>

<p>Started: <?php echo date('r'); ?></p>
 
<?php
 
error_reporting(E_ALL);
ini_set('display_errors', '1');


require_once 'config.php';
require_once 'scaffold.php';


$config['current_table'] = isset($_GET['table']) ? $_GET['table'] : 'products';


$test = new Scaffold($config);


echo '<pre>'; print_r($test); echo '</pre>';

?>
 
<p>Completed: <?php echo date('r'); ?></p>

</body>
</html>