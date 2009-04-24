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


require_once 'config.php';
require_once 'scaffold.php';
require_once 'db/mysql.php';


$config['current_table'] = isset($_GET['table']) ? $_GET['table'] : 'products';



Scaffold::LoadConfig($config);



$test = new Scaffold_Db_Mysql();


echo '<pre>'; print_r($test->Fields()); echo '</pre>';


//$view = new Scaffold_View('test.php');
//$view->name = 'tro"ut';
//echo $view;

#$page = new Scaffold_Pagination(1234, $_GET['page']);

#echo $page;




//$config['current_table'] = isset($_GET['table']) ? $_GET['table'] : 'products';


//$test = new Scaffold($config);
 
?>
 
<p>Completed: <?php echo date('r'); ?></p>