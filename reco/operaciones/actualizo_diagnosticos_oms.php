<?php
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cDiagnosticosOMS.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cUtiles.php");

//variables POST
$oms_cod = $_REQUEST['oms_cod'];
$clave   = $_REQUEST['clave'];
$orden   = $_REQUEST['orden'];
$indice  = $_REQUEST['indice'];
$codrap  = $_REQUEST['codrap'];
$descrip = $_REQUEST['descrip'];
$oms1    = $_REQUEST['oms'];

$u = new cUtiles;

$l       = true;
$oms     = '';
$oms1    = strtoupper($oms1);
if ($u->sionost($oms1, 'SN')) {
    if ($oms1 == 'S') { $oms = 'T'; }
    if ($oms1 == 'N') { $oms = 'F'; }
} else {
    $l = false;
    echo 'Error - las Opciones son S ó N ...!';
}

if (strlen(trim($oms_cod)) == 0) {
    echo 'El Código OMS es Incorrecto';
    $l = false;
}

if (strlen(trim($clave)) == 0) {
    echo 'La Clave es Incorrecta';
    $l = false;
}

if (strlen(trim($descrip)) == 0) {
    echo 'La Descripción es Incorrecta';
    $l = false;
}

if (strlen(trim($oms_cod)) > 2 and strlen(trim($descrip)) > 0 and $l) {
  sleep(2);
  $obj=new cDiagnosticosOMS;
  if ($obj->actualizar($oms_cod, $clave, $orden, $indice, $codrap, $descrip, $oms)==true){            
  }else{
      echo "Error de grabacion - " . $obj->getSQL();
  }
}

?>