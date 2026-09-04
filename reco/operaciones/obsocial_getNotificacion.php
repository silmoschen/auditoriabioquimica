<?

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cObsocialNotificaciones.php");

$codos = $_REQUEST['codos'];

$obj = new cObsocialNotificaciones();

echo $obj->getObjectJSON($codos);


?>