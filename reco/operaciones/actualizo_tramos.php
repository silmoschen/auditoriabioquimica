<?php
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cTramos.php");

//variables POST
$codos     = $_REQUEST['codos'];
$id        = $_REQUEST['id'];
$descrip   = $_REQUEST['descrip'];
$tope      = $_REQUEST['tope'];
$cantbonos = $_REQUEST['cantbonos'];

if (strlen(trim($codos)) == 0) {
    echo 'Obra Social Incorrecta';
}
if (strlen(trim($id)) == 0) {
    echo 'Id. de Tramo Incorrecta';
}
if (strlen(trim($descrip)) == 0) {
    echo 'Descripción Incorrecta';
}
if (strlen(trim($tope)) == 0) {
    echo 'Tope Incorrecto';
}
if (strlen(trim($cantbonos)) == 0) {
    echo 'Cant. de Bonos Incorrecta';
}


if (strlen(trim($id)) > 0 and strlen(trim($descrip)) > 0 and strlen(trim($codos)) > 0
    and strlen(trim($tope)) > 0 and strlen(trim($cantbonos)) > 0) {
  sleep(2);
  $obj=new cTramos;
  if ($obj->actualizar($codos, $id, $descrip, $tope, $cantbonos)==true){
  }else{
      echo "Error de grabacion " . $obj->getSQL();
  }
}

?>