<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

ini_set("max_execution_time", 1000);

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cItemsAuditoria.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cObSocial.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cAuditoria.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cAfiliados.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cEntidad.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cMedicos.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cEfector.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cUtiles.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cNBU.php');

//variables POST
$codos = $_REQUEST['codos'];
$desde = $_REQUEST['desde'];
$hasta = $_REQUEST['hasta'];

$entidad = new cEntidad();
$utiles = new cUtiles();
$auditoria = new cAuditoria();
$items = new cItemsAuditoria();
$medico = new cMedicos();
$efector = new cEfector();
$afiliado = new cAfiliados();
$dx = new cDiagnosticosOMS();
$obsocial = new cObsocial();
$utiles = new cUtiles();
$nbu = new cNBU();

$entidad->getObject();

$res = array();

$resultado = $auditoria->getDetalleOperaciones($desde, $hasta, $codos);

$obsocial->getObject($codos);
$obsocial->verificarRPC($codos);

// IAPOS
if ($obsocial->_reglaNegocio == 7) {

    $archivo = '../actualizar/detalle_prestaciones.csv';
    $arch = fopen($archivo, 'w');

    while ($fila = mysql_fetch_array($resultado)) {

        if ($fila['items'] == '001') {
            $afiliado->getObject($fila['codos'], $fila['nrodoc']);
            $efector->getObject($fila['efector']);
            $medico->getObject($fila['codos'], $fila['idprof']);
            $dx->getObject($fila['iddiag']);
            $t = $fila['monto'];
        }

        $nbu->getObject($fila['codigo']);
        
        $m = $fila['monto'];
        $m2 = str_replace('.', ',', $m);

        $linea = $fila['nrodoc'] . ';' . // nrodoc
                $afiliado->getNombre() . ';' . // nombre
                ';' . // domicilio .
                $utiles->getFechaDDMMAAAA($fila['fecha']) . ';' . // fecha 1
                $utiles->getFechaDDMMAAAA($fila['fecha']) . ';' . // fecha 2
                $fila['codigo'] . ';' . // codigo determinacion
                $nbu->getDescrip() . ';' . // nombre determinacion
                ';' . // responsable
                $efector->getCodigo() . ';' . // efector
                '1' . ';' . // cantidad consumida
                $m2 . ';' .
                $fila['iddiag'] . ';' . // código dx
                $dx->getDescrip() . ';' . // dx descrip
                ';' . // domicilio del prestador
                $medico->getMatricula() . ';' . // matricula del medico
                ';' . // espcialidad del profesional
                $medico->getNombre() . ';' . // nombre del profesional
                ';' . // domicilio del profesional
                $efector->getNrocuit() . ';' . // cuit efector
                $efector->getNombre() . // Nombre del efector

                " \r\n";

        fwrite($arch, $linea);

        $c++;
    }

    fclose($arch);

    echo 'Proceso Finalizado - ' . $c . ' Prácticas Exportadas';

    if ($c > 0) {
        echo '<br/><br/>';        
      
        $root = "../actualizar/";
        $file = "detalle_prestaciones.csv";

        echo '<a href="/' . $ruta . '/operaciones/download.php?root=' . $root . '&file=' . $file . '">Descargar Archivo</a></td>';
    }

    return;
}

header("Content-type: application/vnd.ms-excel; name='excel'");
header("Content-Disposition: attachment; filename=detalle_ordenes.xls");
header("Pragma: no-cache");
header("Expires: 0");

echo "<table border='0' align='left'>";


echo "<tr>";
echo "<td>Orden</td>";
echo "<td>Fecha</td>";
echo "<td>Departamento</td>";
echo "<td>Afiliado</td>";
echo "<td>Mat.Medico</td>";
echo "<td>Nombre del Médico</td>";
echo "<td>Cod. Bioq.</td>";
echo "<td>Nombre del Bioquimico</td>";
echo "<td>Urgencia</td>";
echo "<td>Cod. Patologia</td>";
echo "<td>Diagnostico</td>";
echo "<td>Cant.Det.</td>";
echo "<td>U.B. Totales</td>";
echo "<td>U.B. Autorizadas</td>";
echo "<td>U.B. Denegadas</td>";
echo "</tr>";

$i = 0;
$determinaciones = 0;
$unidades = 0;
$unidades_autorizadas = 0;
$unidades_rechazadas = 0;

while ($fila = mysql_fetch_array($resultado)) {
    if ($fila['items'] == '001') {
        $afiliado->getObject($fila['codos'], $fila['nrodoc']);
        $efector->getObject($fila['efector']);
        $medico->getObject($fila['codos'], $fila['idprof']);
        $dx->getObject($fila['iddiag']);
        $res[$i][0] = $fila['nroauditoria'];
        $res[$i][1] = $utiles->getFechaDDMMAAAA($fila['fecha']);
        $res[$i][2] = $entidad->getNombre();
        $res[$i][3] = $afiliado->getNrodoc();
        $res[$i][4] = $medico->getMatricula();
        $res[$i][5] = $medico->getNombre();
        $res[$i][6] = $efector->getCodigo();
        $res[$i][7] = $efector->getNombre();
        $res[$i][8] = 'N';
        $res[$i][9] = '';
        $res[$i][10] = $dx->getDescrip();

        if ($i > 0) {
            $res[$i - 1][11] = $determinaciones;
            $res[$i - 1][12] = $unidades;
            $res[$i - 1][13] = $unidades_autorizadas;
            $res[$i - 1][14] = $unidades_rechazadas;
        }

        $determinaciones = 0;
        $unidades = 0;
        $unidades_autorizadas = 0;
        $unidades_rechazadas = 0;

        $i++;
    }

    $determinaciones++;
    $unidades = $unidades + $fila['unidad'];
    if ($fila['estado'] == 'A')
        $unidades_autorizadas = $unidades_autorizadas + $fila['unidad'];
    if ($fila['estado'] == 'R')
        $unidades_rechazadas = $unidades_rechazadas + $fila['unidad'];
}

if ($i > 0) {
    $res[$i - 1][11] = $determinaciones;
    $res[$i - 1][12] = $unidades;
    $res[$i - 1][13] = $unidades_autorizadas;
    $res[$i - 1][14] = $unidades_rechazadas;
}

for ($j = 0; $j <= $i - 1; $j++) {
    echo "<tr>";
    echo "<td>'" . $res[$j][0] . "</td>";
    echo "<td>" . $res[$j][1] . "</td>";
    echo "<td>" . $res[$j][2] . "</td>";
    echo "<td>" . $res[$j][3] . "</td>";
    echo "<td>" . $res[$j][4] . "</td>";
    echo "<td>" . $res[$j][5] . "</td>";
    echo "<td>" . $res[$j][6] . "</td>";
    echo "<td>" . $res[$j][7] . "</td>";
    echo "<td>" . $res[$j][8] . "</td>";
    echo "<td>" . $res[$j][9] . "</td>";
    echo "<td>" . $res[$j][10] . "</td>";
    echo "<td align='right'>" . $res[$j][11] . "</td>";
    echo "<td align='right'>" . $res[$j][12] . "</td>";
    echo "<td align='right'>" . $res[$j][13] . "</td>";
    echo "<td align='right'>" . $res[$j][14] . "</td>";
    echo "</tr>";
}

echo "</table>";
?>