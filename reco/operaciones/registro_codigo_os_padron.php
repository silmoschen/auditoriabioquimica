<?php
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cEquivalenciaPadrones.php");

//variables POST
$codigo1 = $_REQUEST['codigo1'];
$codigo2 = $_REQUEST['codigo2'];
$modo = $_REQUEST['modo'];

if (strlen(trim($codigo1)) == 0) {
    echo 'Obra Social Incorrecta';
}

if (strlen(trim($codigo2)) == 0) {
    echo 'Obra Social Incorrecta';
}

if (strlen(trim($codigo1)) > 0 && strlen(trim($codigo2)) > 0) {
    //creamos el objeto $objempleados
    //y usamos su m�todo crear
    $obj = new cEquivalenciaPadrones;
    if ($modo == 2) {
        if ($obj->actualizar($codigo1, $codigo2) == true) {
            
        } else {
            echo "Error de grabacion - " . $obj->getSQL();
        }
    }

    if ($modo == 1) {
        if ($obj->crear($codigo1, $codigo2) == true) {
            
        } else {
            echo "Error de grabacion - " . $obj->getSQL();
        }
    }

    sleep(2);
}
?>
