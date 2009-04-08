<p>Started: <?php echo date('r'); ?></p>

<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');


require_once 'scaffold.php';

require_once 'include/field.php';
require_once 'include/field/text.php';


$field = new Scaffold_Field_Text();

echo $field->GetField();





$config['db']['adapter'] = 'mysql';
//$config['db']['server'] = 'localhost';
$config['db']['username'] = 'eugeniy_scaffold';
$config['db']['password'] = 'fraru9ax';
//$config['db']['database'] = 'eugeniy_scaffold';


$test = new Scaffold($config);

echo $test->GetList('products');

?>

<p>Completed: <?php echo date('r'); ?></p>