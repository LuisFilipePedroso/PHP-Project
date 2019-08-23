<?php 

require('./class/Institution.php');

$institution = new Institution('UFSC');
$result = $institution->getById(1);
echo $result->id;