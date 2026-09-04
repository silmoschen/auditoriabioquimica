<?php
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cEfector.php");

//variables POST
$codigo           = $_REQUEST['codigo'];
$nombre           = $_REQUEST['nombre'];
$usuario          = $_REQUEST['usuario'];
$pass             = $_REQUEST['pass'];
$direccion        = $_REQUEST['direccion'];
$nrocuit          = $_REQUEST['nrocuit'];
$email            = $_REQUEST['email'];
$id_especialidad  = $_REQUEST['id_especialidad'];
$id_tipoprestador = $_REQUEST['id_tipoprestador'];
$fechamat         = $_REQUEST['fechamat'];
$matricula_nac    = $_REQUEST['matricula_nac'];
$matricula1       = $_REQUEST['matricula1'];
$parametro2       = $_REQUEST['parametro2'];
$parametro3       = $_REQUEST['parametro3'];
$nivel2           = $_REQUEST['nivel2'];

$l = true;
if (strlen(trim($_REQUEST[codigo])) != 6) {
    echo 'Error en el Código de Efector ';
    $l = false;
}

if (strlen(trim($_REQUEST[nombre])) < 1) {
    echo ' Error en el Nombre del Efector ';
    $l = false;
}

if (strlen(trim($_REQUEST[codigo])) == 6 and strlen(trim($_REQUEST[nombre])) > 0 and $l) {
  //creamos el objeto $objempleados
  //y usamos su m�todo crear
  sleep(2);
  $obj=new cEfector;
  if ($obj->actualizar($codigo, $nombre, $usuario, $pass, $direccion, $nrocuit, $email, $id_especialidad, $id_tipoprestador, $fechamat, $matricula_nac, $matricula1, $parametro2, $parametro3, $nivel2)==true){
  }else{
      echo "Error de grabacion - " . $obj->getSQL();
  }  
}
?>