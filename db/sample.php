<?php
require_once 'db.class.php';

/*
$db = KtDB::connect('mysql://mt02_user:mt02_pass@ATSDB01:80/mt02');
$db = KtDB::connect('mysql://mt02_user@ATSDB01:80/mt02');
$db = KtDB::connect('mysql://ATSDB01/mt02');
$db = KtDB::connect('mysql://ATSDB01');
*/
$db = KtDB::connect('mysql://root:deganjue@localhost/mt2');

$Res = $db->query("show tables;");

if($Res->hasError( )){
	die($Res->getError());
}
while($row = $Res->fetch( )){
	var_dump($row);
}

//var_dump($db->getLogs());
?>
