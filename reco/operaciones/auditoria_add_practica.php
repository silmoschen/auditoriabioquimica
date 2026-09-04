<?
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cAuditoria.php");

$obj = new cAuditoria();

$nroauditoria = $_REQUEST['nroauditoria'];
$codigo = $_REQUEST['codigo'];
$nroautorizacion = $_REQUEST['nroautorizacion'];

$obj->addPractica($nroauditoria, $codigo, $nroautorizacion);

?>

