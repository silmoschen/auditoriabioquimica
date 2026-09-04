<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cAuditoria.php");
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cObSocial.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cMedicos.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cAfiliados.php');

$obrasocial = new cObSocial;
$medico = new cMedicos;
$afiliado = new cAfiliados;
$auditoria = new cAuditoria;

$resultado = $auditoria->getEstadisticas();
$mc = $auditoria->getMaxCodigos();

header("Content-type: application/vnd.ms-excel; name='excel'");
header("Content-Disposition: attachment; filename=ordenes.xls");
header("Pragma: no-cache");
header("Expires: 0");

echo "<table border='0' width='750px' align='center'>";

while ($fila = mysql_fetch_array($resultado)) {
    $obrasocial->getObject($fila['codos']);
    $medico->getObject($fila['codos'], $fila['idprof']);
    $afiliado->getObject($fila['codos'], $fila['nrodoc']);
    
    echo "<tr>";
    echo "<td width='50px'>'" . $fila['nroauditoria'] . "</td>";
    echo "<td width='100px'>" . $obrasocial->getNombre() . "</td>";
    echo "<td width='50px'>" . $fila['nrodoc'] . "</td>";
    echo "<td width=100px'>" . $afiliado->getNombre() . "</td>";
    echo "<td width=100px'>" . $medico->getNombre() . "</td>";
    
    $c = 0;
    for ($i = 0; $i < $mc; $i++){
        $c++;
        $k = 'cod' . $c;
        if ($fila[$k] != null) $v = $fila[$k]; else $v = '';
        echo "<td width='20px'>" . $v . "</td>";
    }

    echo "</tr>";
}

echo "</table>";
?>
