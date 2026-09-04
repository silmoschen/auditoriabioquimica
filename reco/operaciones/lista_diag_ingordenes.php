<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT']. '/classes/cDiagnosticosOMS.php');

$obj = new cDiagnosticosOMS;

$resultado = $obj->getDiagnosticos();

?>

<select name="listDiagnosticos" id="listDiagnosticos" style="width:235px" disabled="true" onkeypress="javascript: if(ControlDiagnostico(event)) {listPerfil.focus()}; return true">
<?php
  while($fila=mysql_fetch_array($resultado)){
      echo '<option value =' . '"' . $fila['oms_cod'] . '"' . '>'.$fila['oms_cod'].'-'. substr($fila['descrip'], 0, 40) .'</option>';
  }
  ?>
</select>  