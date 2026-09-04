<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cAuditoria.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cNBU.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cObSocial.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cUtiles.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cWsRespuestas.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cEquivalenciaCodigosNBU.php");

$nroauditoria = $_REQUEST['nrotrans'];

$auditoria = new cAuditoria;
$nbu = new cNBU();
$obsocial = new cObsocial();
$wsres = new cWsRespuestas();
$utiles = new cUtiles();
$equivalencia = new cEquivalenciaCodigosNBU();

$auditoria->getObject($nroauditoria);

$res = $auditoria->getDeterminaciones("'" . $nroauditoria . "'");

$codos = '';

$estado = ' ';

// TODAS
//==============================================================================

$tipo_envio = 1;

$codigo_ant = $obsocial->_parametro1;

$expediente = $auditoria->expediente;

/*
  echo "<table border='0px' width='550px'>";
  echo "<tr>";
  echo "<td width='100px'>Formulario (4) Nro.:";
  echo "<td width='300px'>";
  echo "<input type='text' id='formulario4' value= '" . $expediente . "' width='200px' /></td>";
  echo "</tr></table>";
 * 
 */

echo "<table border='0px' width='550px'>";
echo "<tr>";
echo "<tbody align = 'left'>";
echo "<td width='80px'><b>Código</b>";
echo "<td width='400px'><b>Determinación</b>";
echo "</tr>";

$requiere_aut = false;

while ($MostrarFila = mysql_fetch_array($res)) {

    $nbu->getObject($MostrarFila['codigo']);
    $e = "";
    if ($MostrarFila['estado'] == 'A') {
        $e = "checked='checked'";
        //$estado = ' disabled = "true" ';       
        $estado = '';
    } else {
        //$requiere_aut = true;
        $estado = '';
    }

    $requiere_aut = true;
    $cod_eq = $MostrarFila['codigo'];

    echo "<tr>";
    echo "<td width='80px'><input type='checkbox'" . $e . $estado . " name='list' id='list' value=" . $cod_eq . "  onClick='chequear()' " . '>' . $cod_eq . "</td>";
    echo "<td width='400px'>" . $nbu->getDescrip() . $mensaje . "</td>";
    echo "</tr>";
}

echo "</table>";

echo '<hr/>';

echo "<table>";
echo "<tr>";
echo '<td width="15" align = "left">Código</td>';
echo '<td width="20" align = "left"><input type="text" width="100px" id="txtcodigo1"/></td>';
//echo '<td width="50" align = "left"><input type="button" id="btncodigo1" value="Agregar Práctica" class="button green small" onclick="btnadd1(txtcodigo1.value, ' . "'$nroauditoria'" . ', formulario4.value);"/></td>';
echo '<td width="50" align = "left"><input type="button" id="btncodigo1" value="Agregar Práctica" class="button green small" onclick="btnadd1(' . "'$nroauditoria'" . ');"/></td>';
echo '<td align = "left"><div id="practica1"/></td>';
echo "</tr>";
echo "</table>";
echo '<div id="err"/>';

echo "<hr/>";

echo "<table>";
echo "<tr>";

echo '<td width="150" align = "left"><input type="button" class="button gray small" name="btnRegistrarConsulta" value="Registrar Cambios" onclick="AutorizarDeterminacionesF4(' . "'" . $nroauditoria . "'" . "," . $tipo_envio . '); return false" /></td>';
echo '<td width="150" align = "left"><input type="button" class="button gray small" name="btnImprimir" value="Imprimir Orden" onclick="ImprimirLista(); return false" /></td>';

echo '<td align = "left"><input type="button" class="button gray small" name="btnCancelar" value="Cerrar" onclick="CancelarDeterminaciones(); return false" /></td>';

echo "</tr>";
echo "</table>";
?>
