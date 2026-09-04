<?php

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/CUsuariosAuditoresTramos.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/CUsuarios.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cUtiles.php');

$usuario = new CUsuarios;
$us = new CUsuariosAuditoresTramos;
$utiles = new cUtiles;

$Resultado = $us->getUsuariosTramos();

echo "<table border='0px' width='400px' align='center'>";
echo "<tr>";
echo "<td width='50px' align='left'><b>Desde</b></td>";
echo "<td width='50px' align='left'><b>Hasta</b></td>";
echo "<td width='150px' align='left'><b>Auditor 1</b></td>";
echo "<td width='150px' align='left'><b>Auditor 2</b></td>";
echo "<td width='40px' align='left'><b>Baja</b></td>";

echo "</tr>";

while ($MostrarFila = mysql_fetch_array($Resultado)) {
    echo "<tbody align = 'left'>";
    echo "<tr>";
    echo "<td width='50px'>" . $utiles->getFechaDDMMAA($MostrarFila['desde']) . "</td>";
    echo "<td width='50px'>" . $utiles->getFechaDDMMAA($MostrarFila['hasta']) . "</td>";

    $usuario->getObject($MostrarFila['fk_auditor1']);
    echo "<td width='50px'>" . $usuario->getUsuario() . "</td>";

    $usuario->getObject($MostrarFila['fk_auditor2']);
    echo "<td width='50px'>" . $usuario->getUsuario() . "</td>";

    $borra = "Borrar(" . $MostrarFila['ID'] . ")";
    echo '<td width="40px" align="left"><a href="javascript://" onclick="' . $borra . '">Borrar</a></td>';
    echo "</tr>";
    echo "</tbody>";
}
echo "</table>";
?>
