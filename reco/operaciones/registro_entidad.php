<?php
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cEntidad.php");

//variables POST
$nombre        = $_REQUEST['nombre'];
$direccion     = $_REQUEST['direccion'];
$telefono      = $_REQUEST['telefono'];
$email         = $_REQUEST['email'];
$parametro1    = $_REQUEST['parametro1'];
$parametro2    = $_REQUEST['parametro2'];
$parametro31   = $_REQUEST['parametro3'];
$parametro4    = $_REQUEST['parametro4'];
$cuit          = $_REQUEST['cuit'];
$tipo_entidad  = $_REQUEST['tipo_entidad'];
$unifica_hist1 = $_REQUEST['unifica_hist'];
$departamento  = $_REQUEST['departamento'];
$obs1          = $_REQUEST['obs1'];

if ($parametro31 == 'on') {
  $parametro3 = '1';
} else {
  $parametro3 = '0';
}

if ($unifica_hist1 == 'on') {
  $unifica_hist = '1';
} else {
  $unifica_hist = '0';
}


$l = true;

if (strlen(trim($nombre)) < 1) {
    echo 'Error en el Nombre ';
    $l = false;
}

if (strlen(trim($direccion)) < 1) {
    echo ' Error en la Dirección ';
    $l = false;
}

if (strlen(trim($telefono)) < 1) {
    echo ' Error en el Teléfono ';
    $l = false;
}

if (strlen(trim($email)) < 1) {
    echo ' Error en el Email ';
    $l = false;
}

if (strlen(trim($telefono)) < 1) {
    echo ' Error en el Teléfono ';
    $l = false;
}

if (strlen(trim($cuit)) != 13) {
    echo ' Error en el Nro. de C.U.I.T. ';
    $l = false;
}

if (strlen(trim($nombre)) > 0 and strlen(trim($direccion)) > 0 and $l) {
  //creamos el objeto $objempleados
  //y usamos su m�todo crear
  sleep(2);
  $obj=new cEntidad;
      if ($obj->crear($nombre, $direccion, $telefono, $email, $parametro1, $parametro2, $parametro3, $parametro4, $cuit, $tipo_entidad, $unifica_hist, $departamento, $obs1)==true){
  }else{
      echo "Error de Grabacion " . $obj->getSQL();
  }
}

?>