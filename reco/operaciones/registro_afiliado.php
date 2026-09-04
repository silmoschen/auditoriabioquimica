<?php

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cAfiliados.php");

//variables POST
$codos = $_REQUEST['codos'];
$nrodoc = $_REQUEST['nrodoc'];
$nombre = $_REQUEST['nombre'];
$observac = $_REQUEST['observacion'];
$fechanac = $_REQUEST['fechanac'];
$depto = $_REQUEST['depto'];
$direccion = $_REQUEST['direccion'];
$id_beneficio = $_REQUEST['id_beneficio'];
$id_parentesco = $_REQUEST['id_parentesco'];
$sexo = $_REQUEST['sexo'];
$tipo_doc = $_REQUEST['tipo_doc'];
$retiva = $_REQUEST['retiva'];
$idos = $_REQUEST['idos'];
$diferido = 'N';

if (strlen(trim($nrodoc)) == 0) {
    echo 'El Nro. de Documento es Incorrecto';
}

if (strlen(trim($nombre)) == 0) {
    echo ' El Nombre es Incorrecto';
}

if (strlen($depto) == 0) {
    $depto = 'S';
}

if ($depto == 'S' or $depto == 'N') {
    
} else {
    echo ' Las Opciones son S ó N';
}

if ($depto == 'S') {
    $diferido = 'S';
} else {
    $diferido = 'N';
}

if (strlen(trim($sexo)) > 0) {
    if ($sexo == 'F' or $sexo == 'M') {
        
    } else {
        echo ' Las Opciones son F ó M';
    }
}

$nombre = str_replace('%', ' ', $nombre);
$observac = str_replace('%', ' ', $observac);

if (strlen(trim($codos)) > 0 and strlen(trim($nombre)) > 0 and ($depto != 'S' or $depto != 'N')) {
    //creamos el objeto $objempleados
    //y usamos su m�todo crear
    sleep(2);
    $obj = new cAfiliados;
    if ($obj->crear2($codos, $nrodoc, strtoupper($nombre), $observac, $fechanac, strtoupper($depto), strtoupper($diferido), strtoupper($direccion), $id_beneficio, $id_parentesco, strtoupper($sexo), strtoupper($tipo_doc), $retiva, $idos) == true) {
        
    } else {
        echo "Error de grabacion " . $obj->getSQL();
    }
}
?>