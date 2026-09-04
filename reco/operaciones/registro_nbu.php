<?php
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cNBU.php");

//variables POST
$codigo = $_REQUEST['codigo'];
$descrip = $_REQUEST['descrip'];
$unidad = $_REQUEST['unidad'];
$tramo = $_REQUEST['tramo'];
$estado = $_REQUEST['estado'];
$nivel = $_REQUEST['nivel'];

if (strlen(trim($codigo)) != 6) {
    echo 'Código Incorrecto';
}
if (strlen(trim($descrip)) == 0) {
    echo ' Descripción Incorrecta';
}

if (strlen(trim($unidad)) == 0) {
    echo ' Unidad Incorrecta';
}

if (strlen(trim($tramo)) == 0) {
    echo ' Tramo Incorrecto';
}


if (strlen(trim($codigo)) == 6 and strlen(trim($descrip)) > 0 and strlen(trim($unidad)) > 0 and strlen(trim($tramo)) > 0) {
    //creamos el objeto $objempleados
    //y usamos su m�todo crear
    sleep(2);
    $obj = new cNBU;
    if ($obj->crear($codigo, $descrip, $unidad, $tramo, $estado, $nivel) == true) {
        
    } else {
        echo "Error de grabacion " . $obj->getSQL();
    }
}
?>