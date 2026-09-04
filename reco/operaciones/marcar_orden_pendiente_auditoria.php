<?

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cAuditoria.php');
$auditoria = new cAuditoria;

$nroauditoria = $_REQUEST['nrotrans'];
$auditor      = $_REQUEST['usuario'];
$observacion  = $_REQUEST['observacion'];

$auditoria->MarcarComoPendiente($nroauditoria, $auditor, $observacion);
?>