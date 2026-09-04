<?php
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cEfector.php");

//variables POST
$usuario = $_REQUEST['usuario'];
$pass    = $_REQUEST['pass'];

//creamos el objeto $objempleados
//y usamos su m�todo crear
if (strlen($usuario) > 0 and strlen($pass) > 0) {
  sleep(2);
  $obj=new cEfector;
  if ($obj->verificarUsuario($usuario, $pass)==true){
      echo "Login OK - ";
      echo '<a href="javascript://" onclick="IngresarDUS()">Proceder al Ingreso de Datos</a></td>';
      //document.location='secure.php';
      }else{
	  echo "Login Incorrecto - Verifique Nombre de Usuario y Contraseña";
  }
} else {
    echo "Login Incorrecto - Verifique Nombre de Usuario y Contraseña";
}

?>