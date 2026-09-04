<?

session_start();
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cAuditoria.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cUtiles.php");

$a = new cAuditoria();

$nro =' 00000120200317057208292572723';

$a->aplicarCoseguroReglas($nro, '121291', '57208292');

?>

