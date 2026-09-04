<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cEntidad.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cTipoPrestadores.php");

//variables POST
$codigo=$_REQUEST['codigo'];

if (strlen(trim($codigo)) > 0) {
  // Chequeamos integridad
  $entidad=new cEntidad;
  if ($entidad->verificarTipoPrestador($codigo)) {
      echo "<font color='#FF0000'>*** El Tipo de Prestador " . $codigo . " tiene Entidades Afectados - Baja Denegada ***</font>";
      sleep(2);
  } else {
    //creamos el objeto $objempleados
    //y usamos su m�todo crear
    sleep(2);
    $obj=new cTipoPrestadores;
    if ($obj->borrar($codigo)==true){
    }else{
      echo "Error al borrar " . $obj->getSQL();
    }
  }
}

?>
