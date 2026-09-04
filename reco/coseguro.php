<?

session_start();
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cAuditoria.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cUtiles.php");

$a = new cAuditoria();

$id = $_REQUEST['id'];

$nro = '00000120200318011625420931671';

$nro1 = "'" . $nro . "'";

if (id != '') $nro = $id;

//echo $nro1 . ' - ' . str_replace("'", '', $nro1);

$a->getObject($nro);

echo $a->aplicarCoseguroReglas($nro, $a->getCodos(), $a->getNrodoc());

?>

