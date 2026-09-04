<?php
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cArancelesNBU.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cUtiles.php");

//variables POST
$codos      = $_REQUEST['codos'];
$periodo    = $_REQUEST['periodo'];
$arancel    = $_REQUEST['arancel'];
$aranceldif = $_REQUEST['aranceldif'];
$modulo     = $_REQUEST['modulo'];
$nbu_os     = $_REQUEST['nbu_os'];
$utiles     = new cUtiles;
$l          = true;

if ($utiles->validarPeriodo($periodo)) {
} else {
    $l = false;
    echo " El Periodo $periodo es Incorrecto";
}

if ($utiles->validarNumero($arancel)) {
} else {
    $l = false;
    echo " El Arancel $arancel es Incorrecto";
}

if ($utiles->validarNumero($aranceldif)) {
} else {
    $l = false;
    echo " El Arancel Diferencial $aranceldif es Incorrecto";
}

if ($utiles->validarNumero($modulo)) {
} else {
    $l = false;
    echo " El Arancel Modulo $modulo es Incorrecto";
}

if (strlen(trim($_REQUEST[codos])) > 0 and $l) {
  //creamos el objeto $objempleados
  //y usamos su m�todo crear
  
  $obj=new cArancelNBU;
  if ($obj->crear($codos, $periodo, $arancel, $aranceldif, $modulo, $nbu_os)==true){
  }else{
	echo "Error de grabacion - " . $obj->getSQL();
  }

  sleep(2);
}

?>