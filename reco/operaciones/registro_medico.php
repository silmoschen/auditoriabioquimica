<?php
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cMedicos.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cUtiles.php");

$utiles = new cUtiles;

//variables POST
$idprof    = $utiles->LlenarIzquierda($_REQUEST['idprof'], 5, '0');
$codos     = $_REQUEST['codos'];
$nombre    = $_REQUEST['nombre'];
$matricula = $_REQUEST['matricula'];
$libro     = $_REQUEST['libro'];
$folio     = $_REQUEST['folio'];
$estado    = $_REQUEST['estado'];

if (strlen(trim($codos)) == 0) {
    echo 'Obra Social Incorrecta';
}

if (strlen(trim($idprof)) == 0) {
    echo 'Id. del Medico Incorrecta';
}

if (strlen(trim($nombre)) == 0) {
    echo ' Nombre del Medico Incorrecto';
}

if (strlen(trim($codos)) > 0 and strlen(trim($idprof)) > 0 and strlen(trim($nombre)) > 0) {
  //creamos el objeto $objempleados
  //y usamos su m�todo crear
  sleep(2);
  $obj=new cMedicos;
  if ($obj->crear($codos, $idprof, $nombre, $matricula, $libro, $folio, $estado)==true){
  }else{
      echo "Error de grabacion " . $obj->getSQL();
  }
}

?>