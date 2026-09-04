<?php

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cAfiliados.php");

//variables POST
$codos = $_REQUEST['codos'];
$nrodoc = $_REQUEST['nrodoc'];
$nombre = $_REQUEST['nombre'];
$observacion = $_REQUEST['observacion'];
$fechanac = $_REQUEST['fechanac'];
$direccion = $_REQUEST['direccion'];
$depto = $_REQUEST['depto'];
$id_beneficio = $_REQUEST['id_beneficio'];
$id_parentesco = $_REQUEST['id_parentesco'];
$sexo = $_REQUEST['sexo'];
$tipo_doc = $_REQUEST['tipo_doc'];
$inactivo = $_REQUEST['inactivo'];
$retiva = $_REQUEST['retiva'];
$idos = $_REQUEST['idos'];

if (strlen(trim($nrodoc)) == 0) {
    echo 'El Nro. de Documento es Incorrecto';
}

if (strlen(trim($nombre)) == 0) {
    echo ' El Nombre es Incorrecto';
}

if ($depto == 'S' or $depto == 'N') {

} else {
    echo ' Las Opciones son S ó N';
}

if (strlen(trim($sexo)) > 0) {
    if ($sexo == 'F' or $sexo == 'M') {

    } else {
        echo ' Las Opciones son F ó M';
    }
}

if (strlen(trim($codos)) > 0 and strlen(trim($nombre)) > 0) {
    //creamos el objeto $objempleados
    //y usamos su m�todo crear
    sleep(2);
    $obj = new cAfiliados;
    if ($obj->actualizar2($codos, $nrodoc, $nombre, $observacion, $fechanac, $direccion, $id_beneficio, $id_parentesco, strtoupper($sexo), strtoupper($tipo_doc), $inactivo, $retiva, $idos) == true) {
        
    } else {
        echo "Error de grabacion - " . $obj->getSQL();
    }
}
?>