<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cAuditoria.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cModelos.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cItemsAuditoria.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cUtiles.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cAfiliados.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cMedicos.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cDiagnosticosOMS.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cEntidad.php");
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cEquivalenciaPadrones.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cEfector.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cObSocial.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cCodigosRestringidos.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cNbuFederada.php');

$_lineas1 = array();
$_lineas2 = array();

$nroauditoria = $_REQUEST['nrotrans'];
$nrotrans = $_REQUEST['nrotrans'];
$efector = $_REQUEST['efector'];
$fecha = $_REQUEST['fecha'];
$fecha1 = $fecha;
$fepedido = $_REQUEST['fepedido'];
$codos = $_REQUEST['codos'];
$idzona = $_REQUEST['idzona'];
$nrodoc = $_REQUEST['nrodoc'];
$idprof = $_REQUEST['idprof'];
$observacion = $_REQUEST['observacion'];
$iddiag = $_REQUEST['iddiag'];
$idperfil = $_REQUEST['idperfil'];
$codigos = $_REQUEST['codigos'];
$diferida = $_REQUEST['diferida'];
$medicocab = $_REQUEST['medicocab'];
$codos_reg = $codos;

$fechaing = $fecha;
$fechapedido = $fepedido;

$id = $_REQUEST['idperfil'];

$auditoria = new cAuditoria;
$utiles = new cUtiles;
$practicas = new cItemsAuditoria;     // todas la practicas
$autorizadas = new cItemsAuditoria;   // practicas autorizadas
$rechazadas = new cItemsAuditoria;    // practicas rechazadas
$afiliado = new cAfiliados;           // Afiliados
$medico = new cMedicos;               // Medicos
$dx = new cDiagnosticosOMS;           // Diagnosticos
$ef = new cEfector();                 // Efectores
$obsocial = new cObSocial();          // Obras Sociales
$medcab = new cMedicosCab();          // Medicos de Cabecera
$autoriza = true;                     // flag que determina si autoriza o no
// Verificamos si la obra social no utiliza el padrón de otra
$eq = new cEquivalenciaPadrones();
$found = $eq->getObject($codos);

if ($found) {
    // Si existe un código equivalente, modificamos la Obra Social
    $codos = $eq->getCodigo2();
}

$afiliado->getObject($codos, $nrodoc);

$obsocial->getObject($codos);

// Armamos la llamada rpc
$obsocial->verificarRPC($codos);

//------------------------------------------------------------------------------
// Procesamiento de Ordenes que requieren Auditoria
if ($id != '' and $obsocial->getAutorizacionDirecta() != 'S' and $obsocial->getTopeAnual() != 'S') {
    $obj = new cModelos;

    // separamos los códigos
    $c = '';
    $j = 0;
    $noautoriza = false;
    for ($i = 0; $i < 500; $i++) {
        $c = substr($codigos, $j, 6);
        $j = $j + 6;
        if ($c == 0) {
            break;
        }

        //$practicas->AgregarItems($c);
        $auditoria->detalle->AgregarItems($c);
        // agregamos al array
        $_lineas1[$i] = $c;
        $_lineas2[$i] = $c;

        // Comparar los rangos y ver si se autoriza o rechaza
        $r = $practicas->getAuditoriaMasReciente($codos, $nrodoc, $c);
        $frecuencia = $obj->getFrecuenciaEnDias($codos, $id, $c);  // recuperamos la frencuencia en dias

        $rechaza = false;

        if ($r <> null && $autoriza) {
            while ($f = mysql_fetch_array($r)) {
                // recuperamos la fecha del ultimo analisis
                if (strlen($f['ultimafecha']) == 8) {
                    $fecha = $utiles->getFechaDDMMAA($f['ultimafecha']);
                    //echo $f['ultimafecha'] . ' ' . $utiles->getFechaAAAAMMDD($utiles->getFechaActual());
                    if ($f['ultimafecha'] == $utiles->getFechaAAAAMMDD($utiles->getFechaActual())) {
                        $rechaza = true;
                    }
                } else {
                    $fecha = $utiles->getFechaActual();
                    $rechaza = false;
                }
                $intervalo = $utiles->restaFechas($fecha, $utiles->getFechaActual());
                // Si alguna practica esta fuera del intervalo, la rechaza
                if ($rechaza) {
                    $rr = 'true';
                } else {
                    $rr = 'false';
                }
                //echo 'Frecuencia: ' . $frecuencia . ' Intervalo:  ' . $intervalo . ' Ultima Fecha: ' . $fecha . '  rechaza ' . $rr;
                // Verificación a Nivel Items
                if ($intervalo == 0) {
                    
                } else {
                    if ($intervalo < $frecuencia) {
                        $autoriza = false;
                        break;
                    }
                }

                if ($rechaza) {
                    $autoriza = false;
                }
                if ($frecuencia == 0) {
                    $autoriza = false;
                }
                if ($diferida == 'S') {
                    $autoriza = false;
                }
            }
        }
    }

    // Verificamos si se ingresa automaticamente el 660001
    $obj = new cEntidad();
    $obj->getObject();
    if (strlen($obj->getParametro4()) > 0 and $obsocial->getIncluye_ab() == 'S') {
        // Verificamos que no este restringido
        $auditoria->detalle->AgregarItems($obj->getParametro4());
        // 
        // // 01/11/2018 - 660001 en PAMI
        //$cr = new cCodigosRestringidos();
        /*
        if ($cr->getCodigoRestringido($codos, $obj->getParametro4()) == '') {
            $auditoria->detalle->AgregarItems($obj->getParametro4());
        }
         * 
         */
    }
}

// Guardamos Orden
$aut = false;
if ($autoriza) {
    $aut = true;
} else {
    $aut = false;
}

//------------------------------------------------------------------------------
// Procesamiento de Ordenes que se autorizan directamente
if ($id != '' and $obsocial->getAutorizacionDirecta() == 'S' and $obsocial->getTopeAnual() != 'S') {

    // separamos los códigos
    $c = '';
    $j = 0;
    $autoriza = true;
    for ($i = 0; $i < 500; $i++) {
        $c = substr($codigos, $j, 6);
        $j = $j + 6;
        if ($c == 0) {
            break;
        }

        $auditoria->detalle->AgregarItems($c);
    }

    // Verificamos si se ingresa automaticamente el 660001
    $obj = new cEntidad();
    $obj->getObject();
    if (strlen($obj->getParametro4()) > 0 and $obsocial->getIncluye_ab() == 'S') {
        $auditoria->detalle->AgregarItems($obj->getParametro4());
    }
}


//------------------------------------------------------------------------------
// Ordernes Federada Salud

$federada = true;

if ($obsocial->getTopeAnual() == 'S') {

    $nbufede = new cNbuFederada();

    $aut = false;
    $pmo = false;
    $nopmo = false;
    $meses = 12;
    $auditoria->detalle = new cItemsAuditoria;

    for ($i = 0; $i < 500; $i++) {
        $c = substr($codigos, $j, 6);
        $j = $j + 6;
        if ($c == 0) {
            break;
        }

        // Verificamos si autoriza directamente o no
        $nbufede->getObject($c);
        if ($nbufede->getNivel() == 1) {
            $pmo = true;  // si hay alguna practica pmo se rechaza
            $federada = false;
        }

        if ($nbufede->getNivel() == 0 and $nopmo == false) {
            if ($nbufede->getTopeAnual() > 0)
                if ($auditoria->cantidadPracticasPMO($codos, $c, $nrodoc, 12) >= $nbufede->getTopeAnual()) {
                    $nopmo = true;
                    $federada = false;
                }
        }

        $auditoria->detalle->AgregarItems($c);
        // agregamos al array
        $_lineas1[$i] = $c;
        $_lineas2[$i] = $c;
    }

    if ($nompo or $pmo)
        $aut = false; else
        $aut = true;

    // Verificamos si se ingresa automaticamente el 660001
    $obj = new cEntidad();
    $obj->getObject();
    if (strlen($obj->getParametro4()) > 0 and $obsocial->getIncluye_ab() == 'S') {
        $auditoria->detalle->AgregarItems($obj->getParametro4());
    }
}
//------------------------------------------------------------------------------
// -----------------------------------------------------------------------------
// Validaciones
// -----------------------------------------------------------------------------
// Ahora verificamos que tenga consumo en un rango mayor a 7 días
$ultimafecha = $utiles->getFechaDDMMAA($auditoria->getAuditoriaMasReciente($codos, $nrodoc));
if (strlen($ultimafecha) >= 8) {
    $intervalo1 = $utiles->restaFechas($ultimafecha, $utiles->getFechaActual());
} else {
    $intervalo1 = 0;
}

if (strlen($ultimafecha) >= 8) {
    if ($intervalo1 < 7) {
        $aut = false;
    }
}

$sin_errores = true;
if ($dx->getObject($iddiag) == false) {
    echo "El Diagnóstico $iddiag es Incorrecto.<br>";
    $sin_errores = false;
}

if ($ef->getObject($efector) == false) {
    echo "El Efector $efector es Incorrecto.<br>";
    $sin_errores = false;
}

if ($medico->getObject($codos, $idprof) == false) {
    echo "El Médico $idprof es Incorrecto.<br>";
    $sin_errores = false;
}

// Validación de las fechas
if ($utiles->ValidarFecha($fechaing) == false) {
    echo "Se ha producido un Error el la Fecha $fecha de Ingreso. Ingrese nuevamente la Orden<br>";
    $sin_errores = false;
}
if ($utiles->ValidarFecha($fechapedido) == false) {
    echo "Se ha producido un Error el la Fecha $fecha1 de Pedido. Ingrese nuevamente la Orden<br>";
    $sin_errores = false;
}

if ($obsocial->getObject($codos) == false) {
    echo "La Obra Social $codos es Incorrecta.<br>";
    $sin_errores = false;
}

if ($sin_errores) {
    if ($obsocial->getMedicos_cab() == '1') {
        if ($medcab->getObject($codos, $medicocab) == false) {
            echo "El Médico de Cabecera $medicocab para la Obra Social $codos es Incorrecto.<br>";
            $sin_errores = false;
        }
    }
}

//------------------------------------------------------------------------------
// flag para las autorizaciones directas

if ($obsocial->getAutorizacionDirecta() == 'S') {
    $aut = true;
} else {
    // si es directa, verificamos que no se repitan los codigos, si se repiten, pasa a diferida
    if ($aut) {
        foreach ($_lineas1 as $val1) {
            $cant = 0;
            foreach ($_lineas2 as $val2) {
                if ($val1 == $val2) {
                    $cant++;
                }
            }

            if ($cant > 1) {
                $aut = false;
                break;
            }
        }
    }
}

//------------------------------------------------------------------------------
// sin todos los controles son  ok, registramos la orden
// moficamos el flag para las obras sociales de autorizacion directa
if ($obsocial->getTopeAnual() == 'S')
    $aut = $federada;


//==============================================================================
// Si el diagnóstico está marcado para autorización diferida, modificamos el estado de la orden

$modelo = new cModelos;
if ($modelo->VerificarAutorizacionDiferida($codos, $iddiag)) $aut = false;
    
//==============================================================================


if ($sin_errores) {
    if ($auditoria->VerificarOrdenPaciente($nrodoc, $fechaing, $iddiag)) {      
        
        if ($afiliado->getObject($codos, $nrodoc)) {
            $auditoria->crear($nroauditoria, $efector, $fechaing, $codos_reg, $idzona, $nrodoc, $idprof, $observacion, $iddiag, $idperfil, $fechapedido, $aut, $medicocab);
            
            // Registro de ordenes para el tope diario
            if ($obsocial->_parametro2 == 'TOPE') {
                $afiliado->registrarOrden($nrodoc, $fecha, $codos);
            }
            
            //==================================================================
            // Verificamos si hay que agregar manualmente nro. de autorización - 09/11/2023
            if ($obsocial->getGeneraNroAutorizacion() == 1) {
                $transaccion = $efector . '' . str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
                $auditoria->actualizarTransaccionAutorizada($nroauditoria, $transaccion, 'N', $transaccion);
            }
            
            
            //==================================================================
            
        } else {
            echo '<br>';
            echo '<font color="#FF0000">';
            echo "El Número de Documento $nrodoc No está en el Padrón.<br>";
            echo '</font>';
            echo "<p align='center'><h1>Orden Rechazada</h1></p>";
        }
    } else {
        echo '<br>';
        echo '<font color="#FF0000">';
        echo "La Orden con el Número de Documento $nrodoc y el Diagnóstico $iddiag<br>";
        echo "en la Fecha $fecha1 ya fué Registrada.<br>";
        echo '</font>';
        echo "<p align='center'><h1>Orden Rechazada</h1></p>";
    }
}
?>