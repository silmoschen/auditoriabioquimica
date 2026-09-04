<?php
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cObsocialPlanes.php");

//variables POST
$codos     = $_REQUEST['codos'];
$id        = $_REQUEST['id'];
$descrip   = $_REQUEST['descrip'];
$codigo1   = $_REQUEST['codigo1'];
$codigo2   = $_REQUEST['codigo2'];

if (strlen(trim($codos)) == 0) {
    echo 'Obra Social Incorrecta';
}

if (strlen(trim($descrip)) == 0) {
    echo 'Descripción Incorrecta';
}

if (strlen(trim($codos)) > 0 and strlen(trim($descrip)) > 0) {
  sleep(2);
  $obj=new cObsocialPlanes();
  if ($obj->actualizar($id, $descrip, $codos, $codigo1, $codigo2)==true){
  }else{
      echo "Error de grabacion " . $obj->getSQL();
  }
}

?>