<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cObSocial.php');

$obj = new cObsocial;

$res = $obj->getObrasSociales();

echo "<table border='0px' width='550px'>";
echo "<tbody align = 'left'>";
echo "<td width='70px'><b>Código</b></td>";
echo "<td width='400px'><b>Nombre</b></td>";
$i = 0;
while ($MostrarFila = mysql_fetch_array($res)) {

    $i = $i + 1;
    if ($i % 2 != 0) {
        $ext = 'bgcolor="#CCCCCC"';
    } else {
        $ext = '';
    }
    echo "<tr>";

    $e = "";
    if ($obj->getObsocialValidacion($MostrarFila['codos']) > 0) $e = "checked='checked'";

    echo "<td width='70px'><input type='checkbox'" . $e . " name='list' id='list' value=" . $MostrarFila['codos'] . "  onClick='chequear(" . $MostrarFila["codos"] . ")' " . '>' . $MostrarFila['codos'] . "</td>";    
    echo "<td " . $ext . " width='400px'>" . $MostrarFila['nombre'] . "</td>";

    echo "</tr>";
}
echo "</tbody>";
echo "</table>";
