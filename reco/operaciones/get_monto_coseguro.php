<?
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cAuditoria.php');

$codos = $_REQUEST['codos'];
$codigo = $_REQUEST['codigo'];

$auditoria = new cAuditoria();
echo $auditoria->getMontoCoseguro($codos, $codigo);
?>
