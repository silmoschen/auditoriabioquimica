<?php

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cMedicosCab.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cUtiles.php");

$utiles = new cUtiles;

//variables POST
$idprof = $utiles->LlenarIzquierda($_REQUEST['idprof'], 5, '0');
$codos = $_REQUEST['codos'];
$capitas = $_REQUEST['capitas'];
$estado = $_REQUEST['estado'];

if (strlen(trim($codos)) == 0) {
    echo 'Obra Social Incorrecta';
}

if (strlen(trim($idprof)) == 0) {
    echo 'Id. del Medico Incorrecta';
}

if (strlen(trim($capitas)) == 0) {
    echo ' Capitas Incorrectas Incorrecto';
}

if ($estado == 'S' or $estado == 'N') {

} else {
    echo ' Las Opciones son S ó N';
}

if (strlen(trim($codos)) > 0 and strlen(trim($idprof)) > 0
        and (strlen(trim($capitas)) > 0) and ($estado == 'S' or $estado == 'N')) {
    //creamos el objeto $objempleados
    //y usamos su m�todo crear
    sleep(2);
    $obj = new cMedicosCab;
    if ($obj->crear($codos, $idprof, $capitas, $estado) == true) {
        
    } else {
        echo "Error de grabacion " . $obj->getSQL();
    }
}
?>