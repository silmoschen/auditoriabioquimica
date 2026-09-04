<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cNBU.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cItemsAuditoria.php");

//variables POST
$id=$_REQUEST['codigo'];

if (strlen(trim($id)) > 0) {
  // Chequeamos integridad
  $itemsauditoria=new cItemsAuditoria;
  if ($itemsauditoria->VerificarCodigo($id)) {
      echo "<font color='#FF0000'>*** El Código " . $id . " tiene Ordenes Registradas - Baja Denegada ***</font>";
      sleep(2);
  } else {
    //creamos el objeto $objempleados
    //y usamos su m�todo crear
    sleep(2);
    $obj=new cNBU;
    if ($obj->borrar($id)==true){
	//echo "$id - Borrado Correctamente";

    }else{
	echo "Error al borrar";
    }
  }
}

?>