<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cAfiliados.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cAuditoria.php");

//variables POST
$codos  = $_REQUEST['codos'];
$nrodoc = $_REQUEST['nrodoc'];

if (strlen(trim($codos)) > 0 and strlen(trim($nrodoc)) > 0) {
  // Chequeamos integridad
  $auditoria=new cAuditoria;
  if ($auditoria->VerificarPaciente($codos, $nrodoc)) {
      echo "<font color='#FF0000'>*** El Paciente " . $nrodoc . " tiene Ordenes Registradas - Baja Denegada ***</font>";      
  } else {
    //creamos el objeto $objempleados
    //y usamos su m�todo crear
    sleep(2);
    $obj=new cAfiliados;
    if ($obj->borrar($codos, $nrodoc)==true){	
    }else{
      echo "Error al borrar - " . $obj->getSQL();
    }
  }
}

?>