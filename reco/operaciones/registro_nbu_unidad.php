<?php
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cNBU.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cUtiles.php");

//variables POST
$codigo      = $_REQUEST['codigo'];
$periodo    = $_REQUEST['periodo'];
$unidad     = $_REQUEST['unidad'];
$utiles     = new cUtiles;
$l          = true;

if ($utiles->validarPeriodo($periodo)) {
} else {
    $l = false;
    echo " El Periodo $periodo es Incorrecto";
}

if ($utiles->validarNumero($unidad)) {
} else {
    $l = false;
    echo " La Unidad $unidad es Incorrecto";
}

if (strlen(trim($_REQUEST[codigo])) > 0 and $l) {
  //creamos el objeto $objempleados
  //y usamos su m�todo crear
  sleep(2);
  $obj=new cNBU;
  if ($obj->addUnidad($codigo, $periodo, $unidad)==true){
  }else{
	echo "Error de grabacion - " . $obj->getSQL();
  }
}

?>