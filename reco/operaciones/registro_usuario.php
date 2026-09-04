<?php
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/CUsuarios.php");

//variables POST
$usuario     = $_REQUEST['usuario'];
$pass        = $_REQUEST['pass'];
$observacion = $_REQUEST['observacion'];
$nivel       = $_REQUEST['nivel'];
$auditor     = $_REQUEST['auditor'];
if ($auditor == '0') $auditor = '';

$l = true;

if (strlen(trim($usuario)) < 1) {
    echo 'Error en el Usuario ';
    $l = false;
}

if (strlen(trim($pass)) < 1) {
    echo ' Error en la Contraseña ';
    $l = false;
}

if (strlen(trim($usuario)) > 0 and strlen(trim($pass)) > 0 and $l) {
  //creamos el objeto $objempleados
  //y usamos su m�todo crear
  sleep(2);
  $obj = new CUsuarios();
  if ($obj->crear($usuario, $pass, $nivel, $observacion, $auditor)==true){
        
  }else{
      echo "Error de grabacion ";
  }
}

?>