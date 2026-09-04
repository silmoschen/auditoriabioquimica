<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

ini_set("max_execution_time", 2000);

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cExportOrdenes.php');

echo "<table border='0px' width='550px'>";

//variables POST
$codos = $_REQUEST['codos'];
$desde = $_REQUEST['desde'];
$hasta = $_REQUEST['hasta'];

// Variables Globales
$nro_emulacion = 1;

$exportar = new cExportOrdenes();

// Transferimos los datos al archivo
$salida = '../actualizar/ordenes_sm.txt';
$archivo = fopen($salida, 'w');
//----------------------------------------------------------
echo '<tr><td align="center">*** RESUMEN DE PROCESOS CORRIDOS ***</td></tr>';
echo '<tr><td align="left">Transfiriendo CABECERA :: ' . date('H:i:s') . '</td></tr>';
fwrite($archivo, 'CABECERA' . " \r\n");
$trans = $exportar->getLineas('CABECERA');
if ($trans) {
    while ($fila = mysql_fetch_array($trans)) {
        fwrite($archivo, $fila['linea'] . " \r\n");
    }
}
//----------------------------------------------------------
echo '<tr><td align="left">Transfiriendo RED :: ' . date('H:i:s') . '</td></tr>';
fwrite($archivo, 'RED' . " \r\n");
$trans = $exportar->getLineas('RED');
if ($trans) {
    while ($fila = mysql_fetch_array($trans)) {
        fwrite($archivo, $fila['linea'] . " \r\n");
    }
}
//----------------------------------------------------------
echo '<tr><td align="left">Transfiriendo PROFESIONAL :: ' . date('H:i:s') . '</td></tr>';
fwrite($archivo, 'PROFESIONAL' . " \r\n");
$trans = $exportar->getLineaUnica('PROFESIONAL');
if ($trans) {
    while ($fila = mysql_fetch_array($trans)) {
        fwrite($archivo, $fila['linea'] . " \r\n");
    }
}
//----------------------------------------------------------
echo '<tr><td align="left">Transfiriendo PRESTADOR :: ' . date('H:i:s') . '</td></tr>';
fwrite($archivo, 'PRESTADOR' . " \r\n");
$trans = $exportar->getLineaUnica('PRESTADOR');
if ($trans) {
    while ($fila = mysql_fetch_array($trans)) {
        fwrite($archivo, $fila['linea'] . " \r\n");
    }
}
//----------------------------------------------------------
echo '<tr><td align="left">Transfiriendo REL_PROFESIONALESXPRESTADOR :: ' . date('H:i:s') . '</td></tr>';
fwrite($archivo, 'REL_PROFESIONALESXPRESTADOR' . " \r\n");
$trans = $exportar->getLineaUnica('REL_PROFESIONALESXPRESTADOR');
if ($trans) {
    while ($fila = mysql_fetch_array($trans)) {
        fwrite($archivo, $fila['linea'] . " \r\n");
    }
    //----------------------------------------------------------
    echo '<tr><td align="left">Transfiriendo BOCA_ATENCION :: ' . date('H:i:s') . '</td></tr>';
    fwrite($archivo, 'BOCA_ATENCION' . " \r\n");
    $trans = $exportar->getLineaUnica('BOCA_ATENCION');
    if ($trans) {
        while ($fila = mysql_fetch_array($trans)) {
            fwrite($archivo, $fila['linea'] . " \r\n");
        }
    }
    //----------------------------------------------------------
    echo '<tr><td align="left">Transfiriendo REL_MODULOXPRESTADOR :: ' . date('H:i:s') . '</td></tr>';
    fwrite($archivo, 'REL_MODULOSXPRESTADOR' . " \r\n");
    $trans = $exportar->getLineaUnica('REL_MODULOSXPRESTADOR');
    if ($trans) {
        while ($fila = mysql_fetch_array($trans)) {
            fwrite($archivo, $fila['linea'] . " \r\n");
        }
    }
    //----------------------------------------------------------
    echo '<tr><td align="left">Transfiriendo REL_PRESTADORESXRED :: ' . date('H:i:s') . '</td></tr>';
    fwrite($archivo, 'REL_PRESTADORESXRED' . " \r\n");
    $trans = $exportar->getLineaUnica('REL_PRESTADORESXRED');
    if ($trans) {
        while ($fila = mysql_fetch_array($trans)) {
            fwrite($archivo, $fila['linea'] . " \r\n");
        }
    }
    //----------------------------------------------------------
    echo '<tr><td align="left">Transfiriendo BENEFICIO :: ' . date('H:i:s') . '</td></tr>';
    fwrite($archivo, 'BENEFICIO' . " \r\n");
    $trans = $exportar->getLineaUnica('BENEFICIO');
    while ($fila = mysql_fetch_array($trans)) {
        fwrite($archivo, $fila['linea'] . " \r\n");
    }
    //----------------------------------------------------------
    echo '<tr><td align="left">Transfiriendo AFILIADO :: ' . date('H:i:s') . '</td></tr>';
    fwrite($archivo, 'AFILIADO' . " \r\n");
    $trans = $exportar->getLineasDatosPaciente();
    if ($trans) {
        while ($fila = mysql_fetch_array($trans)) {
            fwrite($archivo, $fila['linea'] . " \r\n");
        }
    }
    //----------------------------------------------------------
    // Parte Movil

    echo '<tr><td align="left">Transfiriendo INICIO PRACTICAS SOLICITADAS/AUTORIZADAS :: ' . date('H:i:s') . '</td></tr>';

    $trans = $exportar->getListaPacientes();
    if ($trans) {
        while ($f = mysql_fetch_array($trans)) {

            // Verificamos que la Orden tenga Items
            $cant_it = $exportar->getCantItemsPaciente('AMBULATORIO', $f['tipo_doc'], $f['nrodoc']);

            // Verificamos que los items sean ok
            $it_ok = false;
            //$items = $exportar->getPracticasPaciente('REL_PRACTICASSOLICITADASXAMBULATORIO', $f['tipo_doc'], $f['nrodoc']);
            $items = $exportar->getPracticasPaciente('REL_PRACTICASREALIZADASXAMBULATORIO', $f['tipo_doc'], $f['nrodoc']);
            while ($l = mysql_fetch_array($items)) {
                if ($exportar->validarPractica($codos, $l['codigo'])) {
                    $it_ok = true;
                    break;
                }
            }

            if ($cant_it > 0 and $it_ok == true) {
                //fwrite($archivo, 'PRESTACIONES' . " \r\n");
                //fwrite($archivo, 'AMBULATORIO' . " \r\n");

                $modulo_anter = '';
                $condanter = '';
                $cc = 0;
                $ll = '';
                $unmov = 0;
                $pos = 0;
                fwrite($archivo, 'PRESTACIONES' . " \r\n");
                $rs = $exportar->getOrdenesPaciente($f['tipo_doc'], $f['nrodoc']);
                while ($l1 = mysql_fetch_array($rs)) {

                    if ($l1['modulo'] == 'AMBULATORIO' && $pos > 0) {
                        fwrite($archivo, 'FIN AMBULATORIO' . " \r\n");
                        fwrite($archivo, 'PRESTACIONES' . " \r\n");
                    }

                    if ($l1['modulo'] != $modulo_anter)
                        fwrite($archivo, $l1['modulo'] . " \r\n");

                    $fin_linea = '';
                    if ($l1['modulo'] == 'REL_PRACTICASREALIZADASXAMBULATORIO')
                        $fin_linea = '1;1;';
                    if ($l1['modulo'] == 'REL_PRACTICASSOLICITADASXAMBULATORIO') $fin_linea = $l1['cant'] + 1 . ';0;';

                    fwrite($archivo, $l1['linea'] . $fin_linea . " \r\n");
                    $modulo_anter = $l1['modulo'];
                    $pos++;
                }
                fwrite($archivo, 'FIN AMBULATORIO' . " \r\n");















                /*



                  $items = $exportar->getItemsPaciente('AMBULATORIO', $f['tipo_doc'], $f['nrodoc']);
                  while($l1=mysql_fetch_array($items)) {
                  fwrite($archivo, $l1['linea'] . " \r\n");
                  }

                  $codanter = '';

                  fwrite($archivo, 'REL_DIAGNOSTICOSXAMBULATORIO' . " \r\n");
                  $items = $exportar->getItemsPaciente('REL_DIAGNOSTICOSXAMBULATORIO', $f['tipo_doc'], $f['nrodoc']);
                  while($l2=mysql_fetch_array($items)) {
                  if ($l2['linea'] != $codanter) {
                  fwrite($archivo, $l2['linea'] . " \r\n");
                  }
                  $codanter = $l2['linea'];
                  }

                  $codanter = ''; $cc = 0; $ll = ''; $unmov = 0;

                  fwrite($archivo, 'REL_PRACTICASREALIZADASXAMBULATORIO' . " \r\n");
                  $items = $exportar->getPracticasPaciente('REL_PRACTICASREALIZADASXAMBULATORIO', $f['tipo_doc'], $f['nrodoc']);
                  while($l3=mysql_fetch_array($items)) {
                  if ($l3['codigo'] == $codanter or $codanter == '') { $cc++; } else {
                  fwrite($archivo, $ll . $cc . ';1;' . " \r\n");
                  $cc = 1;
                  $unmov = 1;
                  }
                  $ll = $l3['linea'];
                  $codanter = $l3['codigo'];
                  }
                  if ($cc >= 1 or $unmov == 0) { fwrite($archivo, $ll . $cc . ';1;' . " \r\n");  }

                  //-----------------------------------------------------------------------------

                  $codanter = ''; $cc = 0; $ll = ''; $unmov = 0;

                  fwrite($archivo, 'REL_PRACTICASSOLICITADASXAMBULATORIO' . " \r\n");

                  $items = $exportar->getPracticasPaciente('REL_PRACTICASSOLICITADASXAMBULATORIO', $f['tipo_doc'], $f['nrodoc']);
                  while($l3=mysql_fetch_array($items)) {
                  if ($l3['codigo'] == $codanter or $codanter == '') { $cc++; } else {
                  fwrite($archivo, $ll . $cc . ';0;' . " \r\n");
                  $cc = 1;
                  $unmov = 1;
                  }
                  $ll = $l3['linea'];
                  $codanter = $l3['codigo'];
                  }
                  if ($cc >= 1 or $unmov == 0) { fwrite($archivo, $ll . $cc . ';0;' . " \r\n");  }
                  fwrite($archivo, 'FIN AMBULATORIO' . " \r\n");
                 * 
                 * 
                 */
            }
        }
    }
}
fclose($archivo);

echo '<tr><td align="left">Transfiriendo FIN PRACTICAS REALIZADAS/AUTORIZADAS:: ' . date('H:i:s') . '</td></tr></table>';

echo '<hr>';

if ($exportar->VerificarErrores()) {
    $mensaje_final = '<font color="#FF0000">' . '*** Hay Adventencias *** &nbsp;&nbsp;&nbsp;' . '</font><a href="javascript://" onclick="VerErrores()">Ver Detalle    >></a>';
} else {
    $mensaje_final = '<font color="#FF0000">' . '*** No hay Adventencias ***</font>';
}

$root = "../actualizar/";
$file = "ordenes_sm.txt";

echo "<table border='0px' width='550px' align='center'>";
echo '<tr>';
echo '<td width="100px">Finalizado ...!</td>';
echo '<td width="350px" align="center">' . $mensaje_final . '</td>';
echo '<td width="100px"><div id="ver_ocultar"></div></td>';
echo '</tr>';
// Link para Descargamos el Archivo
echo '<tr>';
echo '<td width="100px"></td>';

$rt = $ruta . '/operaciones/download.php?root=';

echo '<td width="350px" align="center"><a href="/' . $rt . $root . '&file=' . $file . '">Descargar Archivo con las Ordenes Exportadas</a></td>';
echo '<td width="100px"></td>';
echo '<tr>';
echo '</table>';
?>
