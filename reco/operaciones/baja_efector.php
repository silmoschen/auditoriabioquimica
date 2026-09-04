<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cEfector.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cAuditoria.php");

//variables POST
$codigo=$_REQUEST['codigo'];

if (strlen(trim($codigo)) > 0) {
  // Chequeamos integridad
  $auditoria=new cAuditoria;
  if ($auditoria->VerificarEfector($codigo)) {
      echo "<font color='#FF0000'>*** El Efector " . $codigo . " tiene Ordenes Registradas - Baja Denegada ***</font>";
      sleep(2);
  } else {
    //creamos el objeto $objempleados
    //y usamos su m�todo crear
    sleep(2);
    $obj=new cEfector;
    if ($obj->borrar($codigo)==true){
	//echo "$id - Borrado Correctamente";

    }else{
      echo "Error al borrar " . $obj->getSQL();
    }
  }
}

?>