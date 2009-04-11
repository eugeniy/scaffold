<p>Started: <?php echo date('r'); ?></p>

<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');


$path = './include';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);




require_once 'scaffold.php';






$config['database'] = array(
	'adapter' => 'Mysqli',
	'params'  => array(
		'host'     => 'localhost',
		'dbname'   => 'eugeniy_scaffold',
		'username' => 'eugeniy_scaffold',
		'password' => 'fraru9ax',
	)
);

$config['table'] = 'products';


$test = new Scaffold($config);

$test->DisplayList();



?>

<p>Completed: <?php echo date('r'); ?></p>