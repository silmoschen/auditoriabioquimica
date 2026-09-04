<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

ini_set("max_execution_time", 1000);

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
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cUtiles.php');

//variables POST
$codos = $_REQUEST['codos'];
$desde = $_REQUEST['desde'];
$hasta = $_REQUEST['hasta'];

// Variables Globales
$nro_emulacion = 1;

$entidad = new cEntidad();
$utiles=new cUtiles();
$auditoria=new cAuditoria();
$items=new cItemsAuditoria();
$medico=new cMedicos();
$efector=new cEfector();
$afiliado=new cAfiliados();
$exportar=new cExportOrdenes();
$nbu=new cNBU();
$dx=new cDiagnosticosOMS();
$obsocial=new cObsocial();
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

while($fila=mysql_fetch_array($ordenes)) { 
    
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
        // Prácticas Realizadas x Ambulatorio
        $determinaciones = $auditoria->detalle->getItems($nroauditoria);

        while($it=mysql_fetch_array($determinaciones)) {
            if ($it['estado'] == 'A') {
                if ($nbu->getObject($it['codigo']) != null and $it['codigo'] != '660000' and $exportar->validarPractica($codos, $it['codigo']) ) {
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

        $linea = ';' . $entidad->getCuit() . ';;;' . '0' .';;;' . $entidad->getTipo_entidad() . ';;' . '0' .';' . $entidad->getEmail() . ';' . '01/01/1970' . ';;;;' . '0;0;0;' . $entidad->getNombre() . ';' . $entidad->getDireccion() . ';0;;;;;';
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
        $linea = $afiliado->getNombre() . ';' . $afiliado->getTipo_doc() . ';' . $nrodoc . ';;;;' . $afiliado->getDireccion() . ';' . '461' . ';;;;;' . $afiliado->getFechanac() . ';' . $afiliado->getSexo() . ';;;' . $afiliado->getId_beneficio() . ';' . $afiliado->getId_parentesco() . ';;;;;;;;';
        $exportar->addDP('AFILIADO', $linea, $nroauditoria, $afiliado->getTipo_doc(), $afiliado->getNrodoc(), '', $afiliado->getId_beneficio());
        $exportar->addPaciente($afiliado->getTipo_doc(), $afiliado->getNrodoc(), $nroauditoria, $afiliado->getId_beneficio(), $linea);

        // Exportamos Ambulatorio
        $linea = $efector->getNrocuit() . ';;' . $efector->getMatricula_nac() . ';0;0;0;1;0;' . $utiles->getFechaDDMMAAAA($fila['fecha']) .';;;' . '2;' . ';;' . $afiliado->getId_beneficio() . ';' . $afiliado->getId_parentesco();
        $exportar->addDP('AMBULATORIO', $linea, $nroauditoria, $afiliado->getTipo_doc(), $nrodoc, '', $afiliado->getId_beneficio());

        // Exportamos Relacion Diagnosticos x Ambulatorio
        $linea = ';;;' . '0;1;' . $fila['iddiag'] . ';1';
        $exportar->addDP('REL_DIAGNOSTICOSXAMBULATORIO', $linea, $nroauditoria, $afiliado->getTipo_doc(), $nrodoc, '', $afiliado->getId_beneficio());

        // Prácticas Realizadas x Ambulatorio
        $determinaciones = $auditoria->detalle->getItems($nroauditoria);

        while($it=mysql_fetch_array($determinaciones)) {
            if ($it['estado'] == 'A') {
                $cod = '';
                // Codigo Normal
                if ($nbu->getObject($it['codigo']) != null and $it['codigo'] != '660000' and $exportar->validarPractica($codos, $it['codigo']) ) {
                    $cod = $it['codigo'];
                }
                // Equivalencia
                if ($cod == '') {
                    $ceq = ($exportar->getEquivalencia($codos, $it['codigo']));
                    if ($ceq != '') {
                        $cod = $ceq;
                    }
                }
                // Si el código es válido, se anexa
                if ($cod != '') {
                    $linea = ';;;0;1;' . $cod . ';' . $utiles->getFechaDDMMAAAA($fila['fecha']) . ' 00:00;';
                    $exportar->addDP('REL_PRACTICASREALIZADASXAMBULATORIO', $linea, $nroauditoria, $afiliado->getTipo_doc(), $nrodoc, $cod, $afiliado->getId_beneficio());
                }
            }
        }

        // Prácticas Solicitadas x Ambulatorio
        $determinaciones = $auditoria->detalle->getItems($nroauditoria);

        while($it=mysql_fetch_array($determinaciones)) {
            $cod = '';
            // Codigo Normal
            if ($nbu->getObject($it['codigo']) != null and $it['codigo'] != '660000' and $exportar->validarPractica($codos, $it['codigo']) ) {
                $cod = $it['codigo'];
            }
            // Equivalencia
            if ($cod == '') {
                $ceq = ($exportar->getEquivalencia($codos, $it['codigo']));
                if ($ceq != '') {
                    $cod = $ceq;
                }
            }
            // Si el código es válido, se anexa
            if ($cod != '') {
                $linea = ';;;0;1;' . $cod . ';' . $utiles->getFechaDDMMAAAA($fila['fecha']) . ' 00:00;';
                $exportar->addDP('REL_PRACTICASSOLICITADASXAMBULATORIO', $linea, $nroauditoria, $afiliado->getTipo_doc(), $nrodoc, $cod, $afiliado->getId_beneficio());
            }

        }

    }

}


// Transferimos los datos al archivo
$salida  = '../actualizar/ordenes_sm.txt';
$archivo = fopen($salida, 'w');
//----------------------------------------------------------
fwrite($archivo, 'CABECERA' . " \r\n");
$trans = $exportar->getLineas('CABECERA');
if ($trans) {
    while($fila=mysql_fetch_array($trans)) {
        fwrite($archivo, $fila['linea'] . " \r\n");
    }
}
//----------------------------------------------------------
fwrite($archivo, 'RED' . " \r\n");
$trans = $exportar->getLineas('RED');
if ($trans) {
    while($fila=mysql_fetch_array($trans)) {
        fwrite($archivo, $fila['linea'] . " \r\n");
    }
}
//----------------------------------------------------------
fwrite($archivo, 'PROFESIONAL' . " \r\n");
$trans = $exportar->getLineaUnica('PROFESIONAL');
if ($trans) {
    while($fila=mysql_fetch_array($trans)) {
        fwrite($archivo, $fila['linea'] . " \r\n");
    }
}
//----------------------------------------------------------
fwrite($archivo, 'PRESTADOR' . " \r\n");
$trans = $exportar->getLineaUnica('PRESTADOR');
if ($trans) {
    while($fila=mysql_fetch_array($trans)) {
        fwrite($archivo, $fila['linea'] . " \r\n");
    }
}
//----------------------------------------------------------
fwrite($archivo, 'REL_PROFESIONALESXPRESTADOR' . " \r\n");
$trans = $exportar->getLineaUnica('REL_PROFESIONALESXPRESTADOR');
if ($trans) {
    while($fila=mysql_fetch_array($trans)) {
        fwrite($archivo, $fila['linea'] . " \r\n");
    }
    //----------------------------------------------------------
    fwrite($archivo, 'BOCA_ATENCION' . " \r\n");
    $trans = $exportar->getLineaUnica('BOCA_ATENCION');
    if ($trans) {
        while($fila=mysql_fetch_array($trans)) {
            fwrite($archivo, $fila['linea'] . " \r\n");
        }
    }
    //----------------------------------------------------------
    fwrite($archivo, 'REL_MODULOSXPRESTADOR' . " \r\n");
    $trans = $exportar->getLineaUnica('REL_MODULOSXPRESTADOR');
    if ($trans) {
        while($fila=mysql_fetch_array($trans)) {
            fwrite($archivo, $fila['linea'] . " \r\n");
        }
    }
    //----------------------------------------------------------
    fwrite($archivo, 'REL_PRESTADORESXRED' . " \r\n");
    $trans = $exportar->getLineaUnica('REL_PRESTADORESXRED');
    if ($trans) {
        while($fila=mysql_fetch_array($trans)) {
            fwrite($archivo, $fila['linea'] . " \r\n");
        }
    }
    //----------------------------------------------------------
    fwrite($archivo, 'BENEFICIO' . " \r\n");
    $trans = $exportar->getLineaUnica('BENEFICIO');
    while($fila=mysql_fetch_array($trans)) {
        fwrite($archivo, $fila['linea'] . " \r\n");
    }
    //----------------------------------------------------------
    fwrite($archivo, 'AFILIADO' . " \r\n");
    $trans = $exportar->getLineasDatosPaciente();
    if ($trans) {
        while($fila=mysql_fetch_array($trans)) {
            fwrite($archivo, $fila['linea'] . " \r\n");
        }
    }
    //----------------------------------------------------------

    // Parte Movil

    $trans = $exportar->getListaPacientes();
    if ($trans) {
        while($f=mysql_fetch_array($trans)) {

        // Verificamos que la Orden tenga Items
            $cant_it = $exportar->getCantItemsPaciente('AMBULATORIO', $f['tipo_doc'], $f['nrodoc']);

            // Verificamos que los items sean ok
            $it_ok = false;
            $items = $exportar->getPracticasPaciente('REL_PRACTICASSOLICITADASXAMBULATORIO', $f['tipo_doc'], $f['nrodoc']);
            while($l=mysql_fetch_array($items)) {
                if ($exportar->validarPractica($codos, $l['codigo'])) {
                    $it_ok = true;
                    break;
                }
            }

            if ($cant_it > 0 and $it_ok == true) {
                fwrite($archivo, 'PRESTACIONES' . " \r\n");
                fwrite($archivo, 'AMBULATORIO' . " \r\n");

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

                $codanter = '';

                fwrite($archivo, 'REL_PRACTICASREALIZADASXAMBULATORIO' . " \r\n");
                $items = $exportar->getPracticasPaciente('REL_PRACTICASREALIZADASXAMBULATORIO', $f['tipo_doc'], $f['nrodoc']);
                while($l3=mysql_fetch_array($items)) {
                    if ($l3['codigo'] != $codanter) {
                        fwrite($archivo, $l3['linea'] . $exportar->getCantidadPracticasPaciente('REL_PRACTICASREALIZADASXAMBULATORIO', $f['tipo_doc'], $f['nrodoc'], $l3['codigo']) . ';1;' . " \r\n");
                    }
                    $codanter = $l3['codigo'];
                }

                $codanter = '';

                fwrite($archivo, 'REL_PRACTICASSOLICITADASXAMBULATORIO' . " \r\n");
                $items = $exportar->getPracticasPaciente('REL_PRACTICASSOLICITADASXAMBULATORIO', $f['tipo_doc'], $f['nrodoc']);
                while($l3=mysql_fetch_array($items)) {
                    if ($l3['codigo'] != $codanter) {
                        fwrite($archivo, $l3['linea'] . $exportar->getCantidadPracticasPaciente('REL_PRACTICASREALIZADASXAMBULATORIO', $f['tipo_doc'], $f['nrodoc'], $l3['codigo']) . ';0;' . " \r\n");
                    }
                    $codanter = $l3['codigo'];
                }

                fwrite($archivo, 'FIN AMBULATORIO' . " \r\n");
            }

        }
    }
}
fclose($archivo);

$exportar->finalizar();

if ($exportar->it_error != 0) {
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
echo '<td width="350px" align="center"><a href="/operaciones/download.php?root=' . $root . '&file=' . $file . '">Descargar Archivo con las Ordenes Exportadas</a></td>';
echo '<td width="100px"></td>';
echo '<tr>';
echo '</table>';

?>
