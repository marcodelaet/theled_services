<?php
//checking current page to load resources and api
$currentPage    = 'inventory-upload';
if(key_exists("p",$_GET))
    $currentPage    = base64_decode($_GET['p']);
$aCurrentPage   = explode("-",$currentPage);

$currentModule  = 'index';
if(key_exists("m",$_GET))
    $currentModule = base64_decode($_GET['m']);

$subfoldering = (count($aCurrentPage) > 1) ? " : ".ucfirst($aCurrentPage[1]) : "";

$tracking = ucfirst($aCurrentPage[0]).$subfoldering; 

?>