<p>Started: <?php echo date('r'); ?></p>

<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');


require_once 'scaffold.php';





$dsn = 'mysql:dbname=eugeniy_scaffold;host=localhost';
$user = 'eugeniy_scaffold';
$password = 'fraru9ax';

$dbase = new PDO($dsn, $user, $password);


$test = new Scaffold($dbase, 'products');

$test->DisplayList();



?>

<p>Completed: <?php echo date('r'); ?></p>