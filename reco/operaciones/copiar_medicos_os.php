<?php

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cMedicos.php");

//variables POST
$codigo1 = $_REQUEST['codigo1'];
$codigo2 = $_REQUEST['codigo2'];

if (strlen(trim($codigo1)) == 0) {
    echo 'Obra Social Incorrecta';
}

if (strlen(trim($codigo2)) == 0) {
    echo 'Obra Social Incorrecta';
}

if (strlen(trim($codigo1)) > 0 && strlen(trim($codigo2)) > 0) {
    //creamos el objeto $objempleados
    //y usamos su m�todo crear
    $obj = new cMedicos;
    $obj->CopiarMedicos($codigo1, $codigo2);
}

sleep(2);
?>
