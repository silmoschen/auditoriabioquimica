<?php
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cArancelesNBU.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cUtiles.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cApfijosNBU.php");

//variables POST
$codos       = $_REQUEST['codos'];
$codanalisis = $_REQUEST['codanalisis'];
$periodo     = $_REQUEST['periodo'];
$monto       = $_REQUEST['monto'];
$perhasta    = $_REQUEST['perhasta'];
$perbaja     = $_REQUEST['perbaja'];

$utiles      = new cUtiles;
$l           = true;

//echo $codos . '  codanalisis -> '. $codanalisis . '   ' . $periodo . '   ' . $monto . '  ' . $perhasta . '   '. $perbaja;

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


if (strlen(trim($perhasta)) > 0) {
  if ($utiles->validarPeriodo($perhasta)) {
  } else {
      $l = false;
      echo " El Periodo Hasta $perhasta es Incorrecto";
  }
}

if (strlen(trim($perbaja)) > 0) {
  if ($utiles->validarPeriodo($perbaja)) {
  } else {
      $l = false;
      echo " El Periodo $perbaja es Incorrecto";
  }
}

if (strlen(trim($codos)) > 0 and strlen(trim($codanalisis)) > 0 and $l) {
  //creamos el objeto $objempleados
  //y usamos su m�todo crear
  sleep(2);
  $obj=new cApfijosNBU;
  if ($obj->crear($codos, $codanalisis, $periodo, $monto, $perhasta, $perbaja)==true){
	//echo $obj->getSQL();
    //echo "$descrip - Grabado Correctamente";
        
  }else{
	echo "Error de grabacion - " . $obj->getSQL();
  }
}

?>