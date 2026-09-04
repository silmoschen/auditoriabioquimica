<?php
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cModelos.php");

//variables POST
$codos          = $_REQUEST['codos'];
$idcontrol      = $_REQUEST['idcontrol'];
$codigo         = $_REQUEST['codigo'];
$frecuencia     = $_REQUEST['frecuencia'];
$tipofrecuencia = $_REQUEST['tipofrecuencia'];
$modo           = $_REQUEST['modo'];

if (strlen(trim($codigo)) == 6 and strlen(trim($codos)) == 6 and strlen(trim($idcontrol)) > 0 and strlen(trim($frecuencia)) > 0) {
  //creamos el objeto $objempleados
  //y usamos su m�todo crear
  
  $obj=new cModelos;

  if ($modo == 1) {
    if ($obj->crear($codos, $idcontrol, $codigo, $frecuencia, $tipofrecuencia)==true){
    }else{
    }
  }
  if ($modo == 2) {    
    if ($obj->actualizar($codos, $idcontrol, $codigo, $frecuencia, $tipofrecuencia)==true){
    } else {
    }
     
  }
}

sleep(2);


?>