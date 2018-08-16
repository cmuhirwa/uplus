<?php 
include_once 'class_loader.php';

$data=array();
$minagri=new Minagri();
$rows=$minagri->recordCounter('cooperatives');
echo $rows;
?>
