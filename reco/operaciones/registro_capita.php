<?php
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cCapitas.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cUtiles.php");

//variables POST
$codos      = $_REQUEST['codos'];
$periodo    = $_REQUEST['periodo'];
$capita     = $_REQUEST['capita'];
$capita2    = $_REQUEST['capita2'];
$capita3    = $_REQUEST['capita3'];
$utiles     = new cUtiles;
$l          = true;

if ($utiles->validarPeriodo($periodo)) {
} else {
    $l = false;
    echo " El Periodo $periodo es Incorrecto";
}

if ($utiles->validarNumero($capita)) {
} else {
    $l = false;
    echo " La Cápita $capita es Incorrecto";
}

if ($utiles->validarNumero($capita2)) {
} else {
    $l = false;
    echo " La Cápita2 $capita es Incorrecto";
}

if ($utiles->validarNumero($capita3)) {
} else {
    $l = false;
    echo " La Cápita3 $capita es Incorrecto";
}


if (strlen(trim($_REQUEST[codos])) > 0 and $l) {
  //creamos el objeto $objempleados
  //y usamos su m�todo crear
  sleep(2);
  $obj=new cCapitas;
  if ($obj->crear($codos, $periodo, $capita, $capita2, $capita3)==true){
  }else{
	echo "Error de grabacion - " . $obj->getSQL();
  }
}

?>