<?

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/CUsuarios.php");

$us = new CUsuarios;

$a = $_REQUEST['auditor'];

if ($us->findUsuario($a))
    echo 'okkk1989';
else
    echo 'error';
?>