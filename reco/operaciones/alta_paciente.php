<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cAfiliados.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cEquivalenciaPadrones.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cObSocial.php');

$codos = $_REQUEST['codos'];
$nrodoc = $_REQUEST['nrodoc'];
$ndoc = "[" . $_REQUEST['nrodoc'] . "]";
$alta = "DarAltaPaciente(" . $_REQUEST['codos'] . "," . $_REQUEST['nrodoc'] . ")";

$os = new cObsocial();
$os->getObject($codos);

// Verificamos si la obra social no utiliza el padrón de otra
$eq = new cEquivalenciaPadrones();
$found = $eq->getObject($codos);
if ($found) {
    // Si existe un código equivalente, modificamos la Obra Social
    $codos = $eq->getCodigo2();
}

$obj = new cAfiliados;

if ($obj->getObject($codos, $nrodoc) and $obj->inactivo != 'S') {
    echo '  Paciente: ' . $obj->getNrodoc() . ' - ' . substr($obj->getNombre(), 0, 19);
} else {
    if ($os->getAltaPaciente() != 'S') {
        echo '<p align="center">';
        echo "<FIELDSET>";
        echo "<LEGEND>Error en Datos del Afiliado</LEGEND>";
        echo '<p align="center">';
        echo '<font color="#FF0000">';
        echo "<b>El Afiliado $ndoc está fuera de Padrón ó Inactivo.</b><br>Debe Enviar Fotocopia Carnet y ultimo Recibo de Haberes.";
        echo "</b><br>No es Posible Avazanzar con la Carga de la Orden.<br><br>";
        echo '<input type="button" name="ok" id="ok" class="button gray small" value="Regresar" onClick="CancelarAltaPaciente(); return false" />';
        echo '</p></font>';
        echo "</FIELDSET>";
        echo '</p>';
    }
    if ($os->getAltaPaciente() == 'S') {
        echo '<p align="center">';
        echo "<FIELDSET>";
        echo "<LEGEND>Afiliado Inexistente</LEGEND>";
        echo '<p align="center">';
        echo '<font color="#FF0000">';
        echo "El Afiliado $ndoc No Existe. Desea Darlo de Alta ?<br><br>";
        echo '<input type="button" id="ok" name="ok" class="button gray small" value="Sí" onClick=DarAltaPaciente(' . '"' . $codos . '"' . ',' . '"' . $nrodoc . '"' . '); return false" />';
        echo '        ';
        echo '<input type="button" name="No" class="button gray small" value="No" onClick="CancelarAltaPaciente(); return false" />';
        echo '</p></font>';
        echo "</FIELDSET>";
        echo '</p>';
    }
}
?>