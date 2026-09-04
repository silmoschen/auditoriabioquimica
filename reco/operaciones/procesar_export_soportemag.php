<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

ini_set("max_execution_time", 100000);

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cItemsAuditoria.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cObSocial.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cAuditoria.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cItemsAuditoria.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cAfiliados.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cEntidad.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cMedicos.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cEfector.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cNBU.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cExportOrdenes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cDiagnosticosOMS.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cTipoDoc.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cUtiles.php');

//variables POST
$codos = $_REQUEST['codos'];
$desde = $_REQUEST['desde'];
$hasta = $_REQUEST['hasta'];

// Variables Globales
$nro_emulacion = 1;
$procesoOK = false;

$entidad = new cEntidad();
$utiles = new cUtiles();
$auditoria = new cAuditoria();
$items = new cItemsAuditoria();
$medico = new cMedicos();
$efector = new cEfector();
$afiliado = new cAfiliados();
$exportar = new cExportOrdenes();
$nbu = new cNBU();
$dx = new cDiagnosticosOMS();
$obsocial = new cObsocial();
$tipodoc = new cTipoDoc();
$utiles = new cUtiles();

// Inicializamos la exportacion ...
$exportar->iniciar();

// Instanciar la Obra Social
$obsocial->getObject($codos);

// Exportamos los Datos de la Entidad
$entidad->getObject();
$linea = $entidad->getCuit() . ';' . $nro_emulacion . ';' . $hasta . ';' . $utiles->getPeriodoMM_AA($hasta) . ';' . $entidad->getNombre() . ';' . $entidad->getTipo_entidad() . ';' . $obsocial->getUsuario() . ';' . $obsocial->getPass();
$exportar->add('CABECERA', $linea, '0');

// Exportamos los Datos de la RED
$entidad->getObject();
$linea = $entidad->getCuit() . ';;;0;' . $entidad->getNombre() . ';' . $entidad->getNombre() . ';0;' . $entidad->getDireccion() . ';0;;;;' . $entidad->getTelefono();
$exportar->add('RED', $linea, '0');

$ordenes = $auditoria->getConsultaOrdenes($codos, '', $desde, $hasta, 4, 0, 10000000000);

while ($fila = mysql_fetch_array($ordenes)) {

    $ok = true;

    // Variables
    $nroauditoria = '"' . $fila['nroauditoria'] . '"';

    if ($afiliado->getObject($fila['codos'], $fila['nrodoc']) == null or
            $dx->getObject($fila['iddiag']) == null or
            $exportar->validarDx($fila['iddiag']) == false
    ) {
        $ok = false;
    }

    if (strlen($fila['iddiag']) == 0) {
        $ok = false;
    }

    if (strlen($afiliado->getId_beneficio()) == 0 or strlen($afiliado->getFechanac()) == 0 or strlen($afiliado->getId_parentesco()) == 0 or strlen($afiliado->getSexo()) == 0) {
        $ok = false;
        $exportar->addError('AFILIADO', 'El Documento ' . $afiliado->getTipo_doc() . '  ' . $afiliado->getNrodoc() . ' no tiene los Datos Suficientes.');
    }

    $efector->getObject($fila['efector']);
    if (strlen($efector->getNrocuit()) != 13) {
        $ok = false;
        $exportar->addError('EFECTOR', 'El Nro. de C.U.I.T. de ' . $efector->getNombre() . ' es Incorrecto.');
    }

    if ($ok) {
        $ok = false;

        //$ss = $items->getItemsUnionCapitas($nroauditoria);
        //echo 'RRR ' . mysql_num_rows($ss) . '<BR/>'; 
        //while ($it = mysql_fetch_array($ss)) { 
        //echo $it['codigo'] . ' - ';
        //}
        //echo '<br/>';
        // Prácticas Realizadas x Ambulatorio
        $determinaciones = $auditoria->detalle->getItemsUnionCapitas($nroauditoria);

        while ($it = mysql_fetch_array($determinaciones)) {
            if ($it['estado'] == 'A') {
                //echo $it['codigo'] . '   ';
                if ($nbu->getObject($it['codigo']) != null and $it['codigo'] != '660000' and $exportar->validarPractica($codos, $it['codigo'])) {
                    $ok = true;
                    break;
                }
            }
        }
    }

    if ($fila['anulada'] != 'S' && $ok == true) {

        // Exportamos los Profesionales
        $idefector = $fila['efector'];
        $efector->getObject($idefector);
        $linea = ';;;0;' . $efector->getNombre() . ';' . '1' . ';' . $efector->getMatricula_nac() . ';;' . 'DNI' . ';' . '0' . ';;' . 'SIN SUMINISTRAR' . ';' . '0' . ';;;;';
        $exportar->add('PROFESIONAL', $linea, $nroauditoria);

        $linea = ';' . $entidad->getCuit() . ';;;' . '0' . ';;;' . $entidad->getTipo_entidad() . ';;' . '0' . ';' . $entidad->getEmail() . ';' . '01/01/1970' . ';;;;' . '0;0;0;' . $entidad->getNombre() . ';' . $entidad->getDireccion() . ';0;;;;;';
        $exportar->add('PRESTADOR', $linea, $nroauditoria);

        // Exportamos Relaciones Profesionales por Prestador
        $linea = ';' . $entidad->getCuit() . ';' . $efector->getMatricula_nac() . ';0;0;';
        $exportar->add('REL_PROFESIONALESXPRESTADOR', $linea, $nroauditoria);

        // Exportamos Boca de Atencion
        $linea = ';' . $entidad->getCuit() . ';;0;1;10;' . $entidad->getDireccion() . ';0;;;;';
        $exportar->add('BOCA_ATENCION', $linea, $nroauditoria);

        // Exportamos Rel. Modulos por Prestador
        $linea = ';' . $entidad->getCuit() . ';;0;' . $entidad->getTipo_entidad() . ';';
        $exportar->add('REL_MODULOSXPRESTADOR', $linea, $nroauditoria);

        // Exportamos Prestadores por Red
        $linea = $entidad->getCuit() . ';' . $entidad->getCuit() . ';;0;0;';
        $exportar->add('REL_PRESTADORESXRED', $linea, $nroauditoria);

        // Exportamos Beneficio
        $afiliado->getObject($fila['codos'], $fila['nrodoc']);

        $linea = ';;;' . $afiliado->getId_beneficio() . ';;;' . '1' . ';' . $utiles->getFechaDDMMAAAA($fila['fecha']);
        $exportar->add('BENEFICIO', $linea, $nroauditoria);

        // Exportamos Afiliado
        if (substr($afiliado->getNrodoc(), 0, 1) == 'L') {
            $nrodoc = substr($afiliado->getNrodoc(), 1, 15);
        } else {
            $nrodoc = substr($afiliado->getNrodoc(), 0, 15);
        }

        //$linea = $afiliado->getNombre() . ';' . $afiliado->getTipo_doc() . ';' . $nrodoc . ';;;;' . $afiliado->getDireccion() . ';' . '461' . ';;;;;' . $afiliado->getFechanac() . ';' . $afiliado->getSexo() . ';;;' . $afiliado->getId_beneficio() . ';' . $afiliado->getId_parentesco() . ';;;;;;;;';

        $ttdoc = '';
        if (strlen($afiliado->getTipo_doc()) == 1) {
            $tipodoc->getObjectNroInt($afiliado->getTipo_doc());
            $ttdoc = $tipodoc->getId_tipo_doc();
        } else {
            $ttdoc = $afiliado->getTipo_doc();
        }

        if ($ttdoc == '') {
            $ttdoc = 'DNI';
        }

        if ($exportar->verificarNumeroAfiliado($afiliado->getId_beneficio()) == false) {    // 14/09/2011
            $linea = $afiliado->getNombre() . ';' . $ttdoc . ';' . $nrodoc . ';;;;' . $afiliado->getDireccion() . ';' . '461' . ';;;;;' . $afiliado->getFechanac() . ';' . $afiliado->getSexo() . ';;;' . $afiliado->getId_beneficio() . ';' . $afiliado->getId_parentesco() . ';;;;;;;;';
            $exportar->addDP('AFILIADO', $linea, $nroauditoria, $afiliado->getTipo_doc(), $afiliado->getNrodoc(), '', $afiliado->getId_beneficio(), '');
            $exportar->addPaciente($afiliado->getTipo_doc(), $afiliado->getNrodoc(), $nroauditoria, $afiliado->getId_beneficio(), $linea);
        }

        // Exportamos Ambulatorio
        $linea = $efector->getNrocuit() . ';;' . $efector->getMatricula_nac() . ';0;0;0;1;0;' . $utiles->getFechaDDMMAAAA($fila['fecha']) . ';;;' . '2;' . ';;' . $afiliado->getId_beneficio() . ';' . $afiliado->getId_parentesco();
        $exportar->addDP('AMBULATORIO', $linea, $nroauditoria, $afiliado->getTipo_doc(), $nrodoc, '', $afiliado->getId_beneficio(), $fila['fecha']);

        // Exportamos Relacion Diagnosticos x Ambulatorio
        $linea = ';;;' . '0;1;' . $fila['iddiag'] . ';1';
        $exportar->addDP('REL_DIAGNOSTICOSXAMBULATORIO', $linea, $nroauditoria, $afiliado->getTipo_doc(), $nrodoc, '', $afiliado->getId_beneficio(), '');

        // Prácticas Realizadas x Ambulatorio
        $determinaciones = $auditoria->detalle->getItemsUnionCapitas($nroauditoria);

        while ($it = mysql_fetch_array($determinaciones)) {
            //echo $it['codigo'] . 'xxxxxs' . '<br/>';
            if ($it['estado'] == 'A') {
                $cod = '';
                $monto = 0;
                // Codigo Normal
                if ($nbu->getObject($it['codigo']) != null and $it['codigo'] != '660000' and $exportar->validarPractica($codos, $it['codigo'])) {
                    $cod = $it['codigo'];
                    $monto = $it['monto'];
                }

                // Equivalencia            
                $ceq = ($exportar->getEquivalencia($codos, $it['codigo']));
                if ($ceq != '') {
                    $cod = $ceq;
                }

                /*
                  // Equivalencia
                  //if ($cod == '') {
                  //echo $it['codigo'] . '<br/>';
                  $ceq = ($exportar->getEquivalencia($codos, $it['codigo']));
                  if ($ceq != '') {
                  $cod = $ceq;
                  }
                  //}
                 */

                // Si el código es válido, se anexa
                if ($cod != '') {
                    $linea = ';;;0;1;' . $cod . ';' . $utiles->getFechaDDMMAAAA($fila['fecha']) . ' 00:00;';
                    $exportar->addDPM('REL_PRACTICASREALIZADASXAMBULATORIO', $linea, $nroauditoria, $afiliado->getTipo_doc(), $nrodoc, $cod, $afiliado->getId_beneficio(), $monto, $fila['fecha']);
                }
            }
        }

        // Prácticas Solicitadas x Ambulatorio
        $determinaciones = $auditoria->detalle->getItemsUnionCapitas($nroauditoria);

        while ($it = mysql_fetch_array($determinaciones)) {
            $cod = '';
            // Codigo Normal
            if ($nbu->getObject($it['codigo']) != null and $it['codigo'] != '660000' and $exportar->validarPractica($codos, $it['codigo'])) {
                $cod = $it['codigo'];
            }

            // Equivalencia            
            $ceq = ($exportar->getEquivalencia($codos, $it['codigo']));
            if ($ceq != '') {
                $cod = $ceq;
            }

            /*
              if ($cod == '') {
              $ceq = ($exportar->getEquivalencia($codos, $it['codigo']));
              if ($ceq != '') {
              $cod = $ceq;
              }
              }
             */

            // Si el código es válido, se anexa
            if ($cod != '') {
                $linea = ';;;0;1;' . $cod . ';' . $utiles->getFechaDDMMAAAA($fila['fecha']) . ' 00:00;';
                $exportar->addDP('REL_PRACTICASSOLICITADASXAMBULATORIO', $linea, $nroauditoria, $afiliado->getTipo_doc(), $nrodoc, $cod, $afiliado->getId_beneficio(), '');
            }

            $procesoOK = true;
        }
    }
}

if ($procesoOK) {
    echo 'Los Datos se han Procesado Correctamente. <br>';
    echo '<a href="javascript://" onclick="GenerarArchivoExportacion()">Generar Archivo de Exportación</a> - ';
    echo 'Exportar Determinaciones Procesadas: ';
    echo '<a href="javascript://" onclick="GenerarCantidadDeterminaciones()">TXT</a>';
    echo ' - ';
    echo '<a href="javascript://" onclick="GenerarCantidadDeterminacionesExcel()">Excel</a>';
} else {
    echo 'Se ha Producido un Error al Exportar. ';
}
?>
