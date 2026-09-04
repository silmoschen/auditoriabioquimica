<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cEfector.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cEspecialidades.php");

//variables POST
$codigo=$_REQUEST['codigo'];

if (strlen(trim($codigo)) > 0) {
  // Chequeamos integridad
  $efector=new cEfector;
  if ($efector->verificarEspecialidad($codigo)) {
      echo "<font color='#FF0000'>*** La Especialidad " . $codigo . " tiene Profesionales Afectados - Baja Denegada ***</font>";
      sleep(2);
  } else {
    //creamos el objeto $objempleados
    //y usamos su m�todo crear
    sleep(2);
    $obj=new cEspecialidades;
    if ($obj->borrar($codigo)==true){
    }else{
      echo "Error al borrar " . $obj->getSQL();
    }
  }
}

?>