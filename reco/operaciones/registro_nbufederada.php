<?php
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cNbuFederada.php");

//variables POST
$codigo = $_REQUEST['codigo'];
$descrip = $_REQUEST['descrip'];
$nivel = $_REQUEST['nivel'];
$tope_anual = $_REQUEST['tope_anual'];

if (strlen(trim($codigo)) != 6) {
    echo 'Código Incorrecto';
}

if (strlen(trim($nivel)) == 0) {
    echo ' Nivel Incorrecta';
}

if (strlen(trim($tope_anual)) == 0) {
    echo ' Tope Incorrecto';
}


if (strlen(trim($codigo)) == 6 and strlen(trim($nivel)) > 0 and strlen(trim($tope_anual)) > 0) {
    //creamos el objeto $objempleados
    //y usamos su m�todo crear
    sleep(2);
    $obj = new cNbuFederada;
    if ($obj->crear($codigo, $descrip, $nivel, $tope_anual) == true) {
        
    } else {
        echo "Error de grabacion " . $obj->getSQL();
    }
}
?>