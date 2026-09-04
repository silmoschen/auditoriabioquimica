<?
session_start();
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cItemsAuditoria.php");

//variables POST
$codigo  = $_REQUEST['codigo'];
$codos = $_REQUEST['codos'];
$periodo  = $_REQUEST['periodo'];

echo '<h1>' . str_replace('-', '/', $periodo) . '</h1>';

$obj = new cItemsAuditoria();

echo $codigo . '  ' . $obj->getValorAnalisis($codigo, $codos, str_replace('-', '/', $periodo));

// http://localhost/test/montoanalisis/test.php?codos=121005&codigo=660475&periodo=08-2025
  
?>