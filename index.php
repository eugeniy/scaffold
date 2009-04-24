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


Scaffold::LoadConfig($config);

echo '<pre>'; print_r( Scaffold::Config('tables','products','fields','category_id','parent','table') ); echo '</pre>';
//echo '<pre>'; print_r( Scaffold::_Config(array("one",'two','three'),array('one'=>array('two'=>array('three'=>'YO3!!')))) ); echo '</pre>';



//$test = new Scaffold_Db_Mysql($config);


//echo '<pre>'; print_r($test->SetTable('products')); echo '</pre>';


//$view = new Scaffold_View('test.php');
//$view->name = 'tro"ut';
//echo $view;

#$page = new Scaffold_Pagination(1234, $_GET['page']);

#echo $page;




//$config['current_table'] = isset($_GET['table']) ? $_GET['table'] : 'products';


//$test = new Scaffold($config);
 
?>
 
<p>Completed: <?php echo date('r'); ?></p>