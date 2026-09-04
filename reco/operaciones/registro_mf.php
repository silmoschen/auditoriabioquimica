<?php
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cMFBoletas.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cUtiles.php");

//variables POST
$codos      = $_REQUEST['codos'];
$periodo    = $_REQUEST['periodo'];
$monto      = $_REQUEST['monto'];
$tipo       = $_REQUEST['tipo'];
$concepto   = $_REQUEST['concepto'];
$fecha      = $_REQUEST['fecha'];
$utiles     = new cUtiles;
$l          = true;

if ($utiles->validarPeriodo($periodo)) {
} else {
    $l = false;
    echo " El Periodo $periodo es Incorrecto";
}

if ($utiles->validarNumero($monto)) {
} else {
    $l = false;
    echo " El Monto $monto es Incorrecto";
}

if ($utiles->sionost($tipo, '12')) {
} else {
    $l = false;
    echo " Las Opcion $tipo es Incorrecta";
}

if (strlen(trim($_REQUEST[codos])) > 0 and $l) {
  //creamos el objeto $objempleados
  //y usamos su m�todo crear
  sleep(2);
  $obj=new cMFBoletas();
  if ($obj->crear($codos, $periodo, $monto, $tipo, $concepto, $fecha)==true){
  }else{
	echo "Error de grabacion - " . $obj->getSQL();
  }
}

?>