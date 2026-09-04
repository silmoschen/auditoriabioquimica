<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cAuditoria.php");

//variables POST
$id=$_REQUEST['codigo'];

if (strlen(trim($id)) > 0) {
  // Chequeamos integridad
  $auditoria=new cAuditoria;
  if ($auditoria->VerificarObraSocial($id)) {
      echo "<font color='#FF0000'>*** La Obra Social " . $id . " tiene Ordenes Registradas - Baja Denegada ***</font>";
      sleep(2);
  } else {
      //creamos el objeto $objempleados
      //y usamos su m�todo crear
    sleep(2);
    $obj = $auditoria->obrasocial;
    if ($obj->borrar($id)==true){
    }else{
	echo "Error al borrar";
    }
  }
}

?>