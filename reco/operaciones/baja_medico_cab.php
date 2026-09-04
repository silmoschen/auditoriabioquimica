<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cMedicosCab.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cAuditoria.php");

//variables POST
$idprof = $_REQUEST['idprof'];
$codos  = $_REQUEST['codos'];

if (strlen(trim($idprof)) > 0 and (strlen(trim($codos)) > 0)) {
  // Chequeamos integridad
  $auditoria=new cAuditoria;
  if ($auditoria->VerificarMedicoCabecera($idprof)) {
      echo "<font color='#FF0000'>*** El Médico de Cabecera " . $idprof . " tiene Ordenes Registradas - Baja Denegada ***</font>";
  } else {
     //creamos el objeto $objempleados
    //y usamos su m�todo crear
    sleep(2);
    $obj=new cMedicosCab;
    if ($obj->borrar($codos, $idprof)==true){
    }else{
      echo "Error al borrar - " . $obj->getSQL();
    }
  }
}

?>