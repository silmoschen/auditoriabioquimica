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
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cItemsAuditoria.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cAfiliados.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cEntidad.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cMedicos.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cEfector.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cUtiles.php');

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

$entidad->getObject();

$procesoOK = false;
$cant = 0;

$archivo_datos = '../actualizar/ordenes_iapos.txt';
$archivo = fopen($archivo_datos, 'w');

$resultado = $auditoria->getConsultaOrdenes($codos, '', $desde, $hasta, 4, 1, 500000);

fwrite($archivo, "Nro. de Auditoria;Fecha;Nro. Documento;Nombre del Afiliado;Departamento;Medico;Efector;Diagnostico;Practicas" . " \r\n");

while ($fila = mysql_fetch_array($resultado)) {
    $afiliado->getObject($fila['codos'], $fila['nrodoc']);
    $efector->getObject($fila['efector']);
    $medico->getObject($fila['codos'], $fila['idprof']);
    $dx->getObject($fila['iddiag']);

    $linea1 = '';
    $linea2 = '';
    $linea3 = '';    
    $procesoOK = true;

    $linea1 =
            "'" . $fila['nroauditoria'] . ';' .
            $utiles->getFechaDDMMAAAA($fila['fecha']) . ';' .
            $fila['nrodoc'] . ';' .
            $afiliado->getNombre() . ';' .
            $entidad->getDepartamento() . ';' .
            $medico->getNombre() . ';' .
            $efector->getNombre() . ';' .
            $dx->getDescrip() . ';';

    $nroauditoria = '"' . $fila['nroauditoria'] . '"';
    $determinaciones = $auditoria->detalle->getItems($nroauditoria);
    while ($it = mysql_fetch_array($determinaciones)) {
        if ($it['estado'] == 'A') {
            $linea2 = $linea2 . $it['codigo'] . ' ' . ';';
        } else {
            $linea3 = $linea3 . $it['codigo'] . ' R' . ';';
        }
    }

    if (strlen($linea2) > 0 or strlen($linea3) > 0) {
        fwrite($archivo, $linea1 . $linea2 . $linea3 . " \r\n");
        $cant = $cant + 1;
    }
}

fclose($archivo);

if ($procesoOK) {
    $root = "../actualizar/";
    $file = "ordenes_iapos.txt";
    echo '<p align = "center">';
    echo $cant . ' Ordenes Procesadas :: ' . '<a href="/operaciones/download.php?root=' . $root . '&file=' . $file . '">Descargar Archivo con las Ordenes Exportadas</a>';
    echo '</p>';
}
?>