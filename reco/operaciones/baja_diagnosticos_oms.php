<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cDiagnosticosOMS.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cAuditoria.php");

//variables POST
$oms_cod = $_REQUEST['oms_cod'];

if (strlen(trim($oms_cod)) > 0) {
  // Chequeamos integridad
  $auditoria=new cAuditoria;
  if ($auditoria->VerificarDiagnostico($oms_cod)) {
      echo "<font color='#FF0000'>*** El Diagnóstico " . $oms_cod . " tiene Ordenes Registradas - Baja Denegada ***</font>";
  } else {
    //creamos el objeto $objempleados
    //y usamos su m�todo crear
    sleep(2);
    $obj=new cDiagnosticosOMS;
    if ($obj->borrar($oms_cod)==true){
 	//echo "$id - Borrado Correctamente";

    }else{
      echo "Error al borrar - " . $obj->getSQL();
    }
  }
}

?>