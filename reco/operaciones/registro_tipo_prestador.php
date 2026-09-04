<?php
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cTipoPrestadores.php");

//variables POST
$id = $_REQUEST['codigo'];
$descrip = $_REQUEST['descrip'];
$modo = $_REQUEST['modo'];

if (strlen(trim($id)) > 0 and strlen(trim($descrip)) > 0) {
    //creamos el objeto $objempleados
    //y usamos su m�todo crear
    sleep(2);
    $obj = new cTipoPrestadores;

    if ($modo == 1) {
        if ($obj->crear($id, $descrip) == true) {
            
        } else {

        }
    }
    if ($modo == 2) {
        if ($obj->actualizar($id, $descrip) == true) {

        } else {
            
        }
    }
}
?>