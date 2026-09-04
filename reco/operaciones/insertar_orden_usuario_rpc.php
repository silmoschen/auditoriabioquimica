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
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cWsRespuestas.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cNBU.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cEquivalenciaCodigosNBU.php");
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cNbuCodigosDif.php');

$nroauditoria = $_REQUEST['nrotrans'];
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
$medicocab = $_REQUEST['medicocab'];
$codos_reg = $codos;
$ttoken = $_REQUEST['token'];
$ttoken2 = $_REQUEST['token2'];

$nrodoc = str_replace('-', '', $nrodoc);

date_default_timezone_set('America/Argentina/Ushuaia');

$fechaing = $fecha;
$fechapedido = $fepedido;

$nbudif = new CNbuCodigosDif();
$auditoria = new cAuditoria;
$utiles = new cUtiles;
$afiliado = new cAfiliados;           // Afiliados
$medico = new cMedicos;               // Medicos
$dx = new cDiagnosticosOMS;           // Diagnosticos
$ef = new cEfector();                 // Efectores
$obsocial = new cObSocial();          // Obras Sociales
$medcab = new cMedicosCab();          // Medicos de Cabecera
$wsres = new cWsRespuestas();
$nbu = new cNBU();
$equivalencia = new cEquivalenciaCodigosNBU();
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

$ef->getObject($efector);

// Armamos la llamada rpc
$obsocial->verificarRPC($codos);

// Verificamos si se ingresa automaticamente el 660001
$obj = new cEntidad();
$obj->getObject();



//==============================================================================
// AVALIAN
if ($obsocial->_reglaNegocio == 1) {
    date_default_timezone_set('America/Argentina/Ushuaia');
    $datetime = new DateTime();

    $url = $obsocial->_url;
    $prefijo = $obsocial->_parametro2;
    $prefijoinverso = $obsocial->_parametro3;

//------------------------------------------------------------------------------
// Procesamiento de Ordenes que se autorizan directamente
// separamos los códigos

    $linea = '';

    $c = '';
    $j = 0;
    $k = 0;
    $autoriza = true;
    for ($i = 0; $i < 500; $i++) {
        $c = substr($codigos, $j, 6);
        $j = $j + 6;
        if ($c == 0) {
            break;
        }

        $auditoria->detalle->AgregarItems($c);

        // Generamos el xml
        $_cod = '';
        $longitud = strlen($prefijo);
        if ($longitud == 0) {
            $_cod = $c;
        } else {
            $_cod = substr($c, $longitud, strlen($c) - $longitud);
            $_cod = $prefijo . $_cod;
        }

        $linea = $linea .
                '<PR>
                    <TIPO>P</TIPO>
                    <ID>' . $_cod . '</ID>
                    <CANT>1</CANT>
                </PR>';
    }

    if (strlen($obj->getParametro4()) > 0 and $obsocial->getIncluye_ab() == 'S') {
        $auditoria->detalle->AgregarItems($obj->getParametro4());

        $_cod = ''; //$obj->getParametro4();
        $c = $obj->getParametro4();
        $longitud = strlen($prefijo);
        if ($longitud == 0)
            $_cod = $c;
        else {
            $_cod = substr($c, $longitud, strlen($c) - $longitud);
            $_cod = $prefijo . $_cod;
        }

        $_codigos[$k] = $c;
        $k++;

        $linea = $linea .
                '<PR>
                    <TIPO>P</TIPO>
                        <ID>' . $_cod . '</ID>
                        <CANT>1</CANT>
                </PR>';
    }

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
    // sin todos los controles son  ok, registramos la orden

    if ($sin_errores) {
        if ($auditoria->VerificarOrdenPaciente($nrodoc, $fechaing, $iddiag)) {
            if ($afiliado->getObject($codos, $nrodoc)) {

                // Creamos una tranzacción ELG que nos permite determinar las prácticas autorizadas              
                $rpc_xml = '<SOLICITUD>
                        <EMISOR>
                            <ID>0121TA</ID>
                            <PROT>CA_V20</PROT>
                            <MSGID>75343255</MSGID>
                            <TER>TA</TER>
                            <APP>HMS_CAWeb</APP>
                            <TIME>' . $datetime->format('Y-m-d H:i:s') . '</TIME>
                        </EMISOR>
                        <SEGURIDAD>
                            <TIPOAUT>U</TIPOAUT>
                             <USRID>' . $obsocial->_user . '</USRID>
                             <USRPASS>' . $obsocial->_pass . '</USRPASS>
                        </SEGURIDAD>
                        <OPER>
                            <TIPO>ELG</TIPO>
                            <FECHA>' . $datetime->format('Y-m-d') . '</FECHA>
                            <IDASEG>' . $obsocial->_parametro7 . '</IDASEG>
                            <IDPRESTADOR>' . $obsocial->_user . '</IDPRESTADOR>
                        </OPER>
                        <PID>
                            <ID>' . $nrodoc . '</ID>
                            <VERIFID>MANUAL</VERIFID>
                        </PID>
                        <CONTEXTO>
                            <TIPO>A</TIPO>
                        </CONTEXTO>' . $linea .
                        '</SOLICITUD>';


                $json_data = '{"nrodoc":"' . $nrodoc . '", "xmlrpc":"' . $rpc_xml . '", "url":"' . $obsocial->_parametro1 . '"}';

                $url = $obsocial->_url . 'orden';
                $context = stream_context_create(array(
                    'http' => array(
                        'protocol_version' => 1.1,
                        'user_agent' => 'PHPExample',
                        "Cookie => foo=bar\r\n",
                        'method' => 'PUT',
                        'header' => "Content-type: application/json\r\n" .
                        "Connection: close\r\n" .
                        "Content-length: " . strlen($json_data) . "\r\n",
                        'content' => $json_data,
                        'Expect' => '100-continue'
                    ),
                ));

                $xml = simplexml_load_string($rpc_xml);

                $post = file_get_contents($url, false, $context); //, -1, $l);

                $response = json_decode($post);

                if ($post) {

                    $r = $response->Mensaje;

                    // Saneamiento del String
                    $r1 = '<S:Envelope xmlns:S="http://schemas.xmlsoap.org/soap/envelope/"><S:Body><ns0:transaccionstrResponse xmlns:ns0="http://caws/ServiciosProd.wsdl"><return>';
                    $r2 = '</return></ns0:transaccionstrResponse></S:Body></S:Envelope>';

                    echo $r;

                    $r = str_replace("&lt;", "<", $r);
                    $r = str_replace("&gt;", ">", $r);
                    $r = str_replace($r1, "", $r);
                    $r = str_replace($r2, "", $r);

                    $lector = simplexml_load_string($r) or die("Error: Cannot create object");

                    $aut = 'S';

                    $erroras = "";

                    if ($lector->RSPMSGGADIC != '')
                        $erroras = $lector->RSPMSGGADIC;

                    // capturamos el resultado y agregamos los items con los datos de lo auditado
                    // 12/07/2018
                    $auditoria->crearPR($nroauditoria, $efector, $fechaing, $codos_reg, $idzona, $nrodoc, $idprof, $observacion, $iddiag, $idperfil, $fechapedido, $aut, $medicocab);

                    $diferida = 'N';
                    $linea_trans = '';
                    $lista_rechazados = '';

                    // Ahora, a partir de la respuesta, marcamos las prácticas autorizadas
                    for ($i = 0; $i <= 100; $i++) {
                        if (strlen($lector->PR[$i]->ID) < 4)
                            break;
                        $_es = 'R';
                        if ($lector->PR[$i]->STATUS == 'OK') {
                            $_es = 'A';
                        } else
                            $lista_rechazados = $lista_rechazados . $lector->PR[$i]->ID . '  ';

                        if ($prefijo == '') {
                            $_cod = $lector->PR[$i]->ID;
                        } else {
                            $c = $lector->PR[$i]->ID;
                            $_cod = $prefijoinverso . substr($c, $longitud, strlen($c) - $longitud);
                        }

                        if ($_es == 'R')
                            $diferida = 'S';

                        if ($diferida == 'N') {
                            $linea_trans = $linea_trans .
                                    '<PR>
                                <TIPO>P</TIPO>
                                <ID>' . $lector->PR[$i]->ID . '</ID>
                                <CANT>1</CANT>
                            </PR>';
                        }

                        //echo '<i>' . $_cod . ' ' . $diferida . '</i><br>';
                        $auditoria->actualizarDeterminacion($nroauditoria, $_cod, $_es);
                    }

                    $matricula_medico = $medico->getMatricula() . '/011' . $utiles->LlenarIzquierda($medico->getLibro(), 2, '0') . $utiles->LlenarIzquierda($medico->getFolio(), 3, '0');

                    // Creamos una tranzacción AP que incluye solo las prácticas autorizadas
                    $rpc_xml = '<SOLICITUD>
                        <EMISOR>
                            <ID>0121TA</ID>
                            <PROT>CA_V20</PROT>
                            <MSGID>75343255</MSGID>
                            <TER>TA</TER>
                            <APP>HMS_CAWeb</APP>
                            <TIME>' . $datetime->format('Y-m-d H:i:s') . '</TIME>
                        </EMISOR>
                        <SEGURIDAD>
                            <TIPOAUT>U</TIPOAUT>
                             <USRID>' . $obsocial->_user . '</USRID>
                             <USRPASS>' . $obsocial->_pass . '</USRPASS>
                        </SEGURIDAD>
                        <OPER>
                            <TIPO>AP</TIPO>
                            <FECHA>' . $datetime->format('Y-m-d') . '</FECHA>
                            <IDASEG>' . $obsocial->_parametro7 . '</IDASEG>
                            <IDPRESTADOR>' . $obsocial->_user . '</IDPRESTADOR>
                        </OPER>
                        <PID>
                            <ID>' . $nrodoc . '</ID>
                            <VERIFID>MANUAL</VERIFID>
                        </PID>
                        
                        <PRESCRIP>
                                <ORG>MP S</ORG>
                                <MAT>' . $matricula_medico . '</MAT>
                                <FECHA>' . $datetime->format('Y-m-d') . '</FECHA>
                        </PRESCRIP>
                        
                        <CONTEXTO>
                            <TIPO>A</TIPO>
                        </CONTEXTO>' . $linea .
                            '</SOLICITUD>';


                    //==========================================================

                    if ($lista_rechazados != '') {
                        echo 'Prácticas Rechazadas: <font color = "Navy">' . $lista_rechazados . '</font>';
                    }

                    $json_data = '{"nrodoc":"' . $nrodoc . '", "xmlrpc":"' . $rpc_xml . '", "url":"' . $obsocial->_parametro1 . '"}';

                    $url = $obsocial->_url . 'orden';
                    $context = stream_context_create(array(
                        'http' => array(
                            'protocol_version' => 1.1,
                            'user_agent' => 'PHPExample',
                            "Cookie => foo=bar\r\n",
                            'method' => 'PUT',
                            'header' => "Content-type: application/json\r\n" .
                            "Connection: close\r\n" .
                            "Content-length: " . strlen($json_data) . "\r\n",
                            'content' => $json_data,
                            'Expect' => '100-continue'
                        ),
                    ));

                    $xml = simplexml_load_string($rpc_xml) or die("Error: Cannot create object");

                    $post = file_get_contents($url, false, $context); //, -1, $l);

                    $response = json_decode($post);

                    if ($post) {

                        $r = $response->Mensaje;

                        // Saneamiento del String
                        $r1 = '<S:Envelope xmlns:S="http://schemas.xmlsoap.org/soap/envelope/"><S:Body><ns0:transaccionstrResponse xmlns:ns0="http://caws/ServiciosProd.wsdl"><return>';
                        $r2 = '</return></ns0:transaccionstrResponse></S:Body></S:Envelope>';

                        $r = str_replace("&lt;", "<", $r);
                        $r = str_replace("&gt;", ">", $r);
                        $r = str_replace($r1, "", $r);
                        $r = str_replace($r2, "", $r);

                        $lector = simplexml_load_string($r) or die("Error: Cannot create object");

                        if ($lector->RSPMSGG == 'AP APROBADA') {
                            $auditoria->actualizarTransaccionAutorizada($nroauditoria, $lector->IDTRAN, 'N', $lector->IDAUT);

                            $s = print_r($lector, true);
                            $wsres->crear($codos, $efector, $nrodoc, $s, 3);
                        } else {
                            $auditoria->actualizarTransaccion($nroauditoria, $lector->IDTRAN, 'S');

                            echo $resultados;
                            echo '<br>';
                            echo '<font color = "#FF0000">';
                            echo "La Transaccion " . $lector->IDTRAN . " Ha Sido Rechazada por el Servidor. Ingrese Nuevamente la Orden<br>";
                            echo "<H2> " . $erroras . " <H2>";
                            echo '</font>';
                            echo "<p align='center'><h1>Orden Rechazada</h1></p>";

                            $s = print_r($lector, true);
                            $wsres->crear1($codos, $efector, $nrodoc, $s, 4, $nroauditoria);

                            $auditoria->anularPracticas($nroauditoria);
                            $auditoria->AnularOrden($nroauditoria);
                        }
                    }
                } else {
                    echo '<br>';
                    echo '<font color = "#FF0000">';
                    echo "El Número de Documento $nrodoc No está en el Padrón.<br>";
                    echo '</font>';
                    echo "<p align='center'><h1>Orden Rechazada</h1></p>";
                }
            } else {
                echo '<br>';
                echo '<font color = "#FF0000">';
                echo "La Orden con el Número de Documento $nrodoc y el Diagnóstico $iddiag<br>";
                echo "en la Fecha $fecha1 ya fué Registrada.<br>";
                echo '</font>';
                echo "<p align='center'><h1>Orden Rechazada</h1></p>";
            }
        }

        exit;
    }
}
//==============================================================================
// SANCOR SALUD - v2
if ($obsocial->_reglaNegocio == 5) {

    $linea = '';

    $c = '';
    $j = 0;
    $k = 0;
    $autoriza = true;

    for ($i = 0; $i < 500; $i++) {
        $c = substr($codigos, $j, 6);
        $j = $j + 6;
        if ($c == 0) {
            break;
        }

        $auditoria->detalle->AgregarItems($c);

        $_codigos[$k] = $c;
        $k++;
    }

    if (strlen($obj->getParametro4()) > 0 and $obsocial->getIncluye_ab() == 'S') {
        $auditoria->detalle->AgregarItems($obj->getParametro4());
        $_codigos[$k] = $obj->getParametro4();
        $k++;
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
    // sin todos los controles son  ok, registramos la orden

    if ($sin_errores) {
        if ($auditoria->VerificarOrdenPaciente($nrodoc, $fechaing, $iddiag)) {
            if ($afiliado->getObject($codos, $nrodoc)) {

                $aut = false;
                // capturamos el resultado y agregamos los items con los datos de lo auditado
                $auditoria->crear($nroauditoria, $efector, $fechaing, $codos_reg, $idzona, $nrodoc, $idprof, $observacion, $iddiag, $idperfil, $fechapedido, $aut, $medicocab);

                //==============================================================

                try {

                    $url = $obsocial->_url . 'orden';
                    $prestadorCuit = str_replace('-', '', $obsocial->_parametro7);
                    $parametro1 = str_replace('-', '', $ef->getNrocuit());
                    $parametro2 = '000';

                    $fecha = $utiles->getFechaAAAAMMDD($utiles->getFechaActual());

                    $practicas = '';
                    $l = 0;
                    for ($i = 0; $i <= 100; $i++) {
                        $codd = $_codigos[$i];
                        if (strlen($codd) < 4)
                            break;

                        // Verificamos que la práctica no se repita en el día - ACTO BIOQUÍMICO
                        if ($obj->parametro4 == $codd) {
                            $__practicarepetidadia = $auditoria->verificarCantidadPracticaDia($codos, $utiles->getFechaActual(), $nrodoc, $codd);

                            if ($__practicarepetidadia < 2) {  // Solo enviamos aquellas prácticas que no tengan consumo en el día
                                $l++;

                                $practicas = $practicas . '{"codigo":"' . $codd . '", "items":' . $l . '},';
                            }
                        } else {
                            $practicas = $practicas . '{"codigo":"' . $codd . '", "items":' . $l . '},';
                        }
                    }

                    $practicas = substr($practicas, 0, -1);

                    $prestaciones = $prestaciones . '[' . substr($practicas, 0, strlen($practicas) - 1) . '}]';

                    $json_data = '{"IdentificadorAfiliado":"' . $nrodoc . '", "IDPrestador":"' . $prestadorCuit . '", "parametro1":"' . $parametro1 . '", "iddiag": "' . $iddiag . '", "parametro2": "' . $parametro2 . '", "parametro3": "' . $ttoken . '", "practicas":' . $prestaciones . '}';

                    //$auditoria->borrarOrden($nroauditoria);
                    //echo $json_data . '<hr/>';                    
                    //exit;


                    $context = stream_context_create(array(
                        'http' => array(
                            'protocol_version' => 1.1,
                            'user_agent' => 'PHPExample',
                            "Cookie => foo=bar\r\n",
                            'method' => 'PUT',
                            'header' => "Content-type: application/json\r\n" .
                            "Connection: close\r\n" .
                            "Content-length: " . strlen($json_data) . "\r\n",
                            'content' => $json_data,
                            'Expect' => '100-continue'
                        ),
                    ));

                    $file = file_get_contents($url, false, $context);

                    $result = json_decode($file);

                    echo $file;

                    //==========================================================                    
                    if ($result->Estado == 'OK') {

                        $it = 0;
                        for ($p = 0; $p < count($result->practicas); $p++) {
                            $_es = 'R';
                            if ($result->practicas[$p]->estado == 'A')
                                $_es = 'A';

                            $it++;
                            $item = $utiles->LlenarIzquierda($it, 3, '0');

                            //$auditoria->actualizarDeterminacionPorItem($nroauditoria, $item, $_es, $result->practicas[$p]->transaccion);
                            $auditoria->actualizarDeterminacionPorCodigo($nroauditoria, $result->practicas[$p]->codigo, $_es, $result->practicas[$p]->transaccion);
                        }

                        $diferida = 'N';

                        //$auditoria->actualizarTransaccionAutorizada($nroauditoria, '', $diferida, $result->transaccion);
                        $auditoria->actualizarTransaccionAutorizadaToken($nroauditoria, '', $diferida, $result->transaccion, $ttoken);

                        $s = print_r($result, true);
                        $wsres->crear1($codos, $ef->getCodigo(), $s, $file, 2, $result->transaccion);

                        print_r($result);
                    }

                    // Si la transacción falla
                    if ($result->Estado != 'OK') {
                        $auditoria->borrarOrden($nroauditoria);
                        echo '<h1>Se Produjo un Error al Autorizar la Orden</h1>';
                        echo '<h2>Vuelva a Ingresarla</h2>';
                    }

                    echo '<hr/>';
                } catch (Exception $e) {
                    trigger_error($e->getMessage(), E_USER_WARNING);
                    $auditoria->borrarOrden($nroauditoria);
                    echo '<h1>Se Produjo un Error al Autorizar la Orden</h1>';
                    echo '<h2>Vuelva a Ingresarla</h2>';
                }

                //==============================================================
            } else {
                echo '<br>';
                echo '<font color = "#FF0000">';
                echo "El Número de Documento $nrodoc No está en el Padrón.<br>";
                echo '</font>';
                echo "<p align='center'><h1>Orden Rechazada</h1></p>";
            }
        } else {
            echo '<br>';
            echo '<font color = "#FF0000">';
            echo "La Orden con el Número de Documento $nrodoc y el Diagnóstico $iddiag<br>";
            echo "en la Fecha $fecha1 ya fué Registrada.<br>";
            echo '</font>';
            echo "<p align='center'><h1>Orden Rechazada</h1></p>";
        }
    }

    exit;
}

//==============================================================================
// ACA SALUD
if ($obsocial->_reglaNegocio == -1) {
    date_default_timezone_set('America/Argentina/Ushuaia');
    $datetime = new DateTime();

    $url = $obsocial->_url;
    //$options["connection_timeout"] = 25;
    //$options["location"] = $url;
    //$options['trace'] = 1;
    $prefijo = $obsocial->_parametro2;
    $prefijoinverso = $obsocial->_parametro3;

//------------------------------------------------------------------------------
// Procesamiento de Ordenes que se autorizan directamente
// separamos los códigos

    $linea = '';

    $c = '';
    $j = 0;
    $k = 0;
    $autoriza = true;
    for ($i = 0; $i < 500; $i++) {
        $c = substr($codigos, $j, 6);
        $j = $j + 6;
        if ($c == 0) {
            break;
        }

        $auditoria->detalle->AgregarItems($c);

// Generamos el xml
        $_cod = '';
        $longitud = strlen($prefijo);
        if ($longitud == 0) {
            $_cod = $c;
        } else {
            $_cod = substr($c, $longitud, strlen($c) - $longitud);
            $_cod = $prefijo . $_cod;
        }

        $linea = $linea .
                '<PR>
                    <TIPO>P</TIPO>
                    <ID>' . $_cod . '</ID>
                    <CANT>1</CANT>
                </PR>';
    }

    if (strlen($obj->getParametro4()) > 0 and $obsocial->getIncluye_ab() == 'S') {
        $auditoria->detalle->AgregarItems($obj->getParametro4());

        $_cod = ''; //$obj->getParametro4();
        $c = $obj->getParametro4();
        $longitud = strlen($prefijo);
        if ($longitud == 0)
            $_cod = $c;
        else {
            $_cod = substr($c, $longitud, strlen($c) - $longitud);
            $_cod = $prefijo . $_cod;
        }

        $_codigos[$k] = $c;
        $k++;

        $linea = $linea .
                '<PR>
                    <TIPO>P</TIPO>
                        <ID>' . $_cod . '</ID>
                        <CANT>1</CANT>
                </PR>';
    }

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
// sin todos los controles son  ok, registramos la orden

    if ($sin_errores) {
        if ($auditoria->VerificarOrdenPaciente($nrodoc, $fechaing, $iddiag)) {
            if ($afiliado->getObject($codos, $nrodoc)) {

// Creamos una tranzacción ELG que nos permite determinar las prácticas autorizadas
                $rpc_xml = '<SOLICITUD>
                            <EMISOR>
                                <ID>00001-22222</ID>
                                <PROT>CA_V20</PROT>
                                <MSGID>10001</MSGID>
                                <TER>Web</TER>
                                <APP>HMS_CAWeb</APP>
                                <TIME>' . $datetime->format('Y-m-d H:i:s') . '</TIME>
                            </EMISOR>
                            <SEGURIDAD>
                                <TIPOAUT>U</TIPOAUT>
                                <USRID>' . $obsocial->_user . '</USRID>
                                <USRPASS>' . $obsocial->_pass . '</USRPASS>
                            </SEGURIDAD>
                            <OPER>
                                <TIPO>ELG</TIPO>
                                <FECHA>' . $datetime->format('Y-m-d') . '</FECHA>
                                <IDASEG>' . $obsocial->_parametro7 . '</IDASEG>
                                <IDPRESTADOR>' . $obsocial->_user . '</IDPRESTADOR>
                            </OPER>
                            <PID>
                                <ID>' . $nrodoc . '</ID>
                                <VERIFID>MANUAL</VERIFID>
                            </PID>

                            <CONTEXTO>
                                <TIPO>A</TIPO>
                            </CONTEXTO>' . $linea .
                        '</SOLICITUD>';

                // ===== 09/03/2019 ============================================
                $opts = array(
                    'ssl' => array(
                        'ciphers' => 'RC4-SHA',
                        'verify_peer' => false,
                        'verify_peer_name' => false
                    )
                );
                $params = array(
                    'encoding' => 'UTF-8',
                    'verifypeer' => false,
                    'verifyhost' => false,
                    // 'soap_version' => SOAP_1_2,
                    'trace' => 1,
                    'location' => $url,
                    'exceptions' => 1,
                    'connection_timeout' => 180,
                    'stream_context' => stream_context_create($opts)
                );

                $client = new SoapClient($url, $params); //$client = new SoapClient($url, $options);
                // ===== 09/03/2019 END ========================================

                $wsres->crear($codos, $efector, $nrodoc, $rpc_xml, 5);

                $resultados = $client->transaccionStr($rpc_xml);

                $lector = new SimpleXMLElement($resultados);

                $aut = 'S';

                $erroras = "";

                if ($lector->RSPMSGGADIC != '')
                    $erroras = $lector->RSPMSGGADIC;

// capturamos el resultado y agregamos los items con los datos de lo auditado
// 12/07/2018
                $auditoria->crearPR($nroauditoria, $efector, $fechaing, $codos_reg, $idzona, $nrodoc, $idprof, $observacion, $iddiag, $idperfil, $fechapedido, $aut, $medicocab);

//$auditoria->marcarPracticasStanBy($nroauditoria);  // 05/07/2018

                $diferida = 'N';
                $linea_trans = '';
                $lista_rechazados = '';
// Ahora, a partir de la respuesta, marcamos las prácticas autorizadas
                for ($i = 0; $i <= 100; $i++) {
                    if (strlen($lector->PR[$i]->ID) < 4)
                        break;
                    $_es = 'R';
                    if ($lector->PR[$i]->STATUS == 'OK') {
                        $_es = 'A';
                    } else
                        $lista_rechazados = $lista_rechazados . $lector->PR[$i]->ID . '  ';

                    if ($prefijo == '') {
                        $_cod = $lector->PR[$i]->ID;
                    } else {
                        $c = $lector->PR[$i]->ID;
                        $_cod = $prefijoinverso . substr($c, $longitud, strlen($c) - $longitud);
                    }

                    if ($_es == 'R')
                        $diferida = 'S';

                    if ($diferida == 'N') {
                        $linea_trans = $linea_trans .
                                '<PR>
                                <TIPO>P</TIPO>
                                <ID>' . $lector->PR[$i]->ID . '</ID>
                                <CANT>1</CANT>
                            </PR>';
                    }

//echo '<i>' . $_cod . ' ' . $diferida . '</i><br>';
                    $auditoria->actualizarDeterminacion($nroauditoria, $_cod, $_es);
                }

                $wsres->crear($codos, $efector, $nrodoc, $resultados, 2);

                $matricula_medico = $medico->getMatricula() . '/011' . $utiles->LlenarIzquierda($medico->getLibro(), 2, '0') . $utiles->LlenarIzquierda($medico->getFolio(), 3, '0');

// Creamos una tranzacción AP que incluye solo las prácticas autorizadas
                $rpc_xml = '<SOLICITUD>
                            <EMISOR>
                                <ID>00001-22222</ID>
                                <PROT>CA_V20</PROT>
                                <MSGID>10001</MSGID>
                                <TER>Web</TER>
                                <APP>HMS_CAWeb</APP>
                                <TIME>' . $datetime->format('Y-m-d H:i:s') . '</TIME>
                            </EMISOR>
                            <SEGURIDAD>
                                <TIPOAUT>U</TIPOAUT>
                                <USRID>' . $obsocial->_user . '</USRID>
                                <USRPASS>' . $obsocial->_pass . '</USRPASS>
                            </SEGURIDAD>
                            <OPER>
                                <TIPO>AP</TIPO>
                                <FECHA>' . $datetime->format('Y-m-d') . '</FECHA>
                                <IDASEG>' . $obsocial->_parametro7 . '</IDASEG>
                                <IDPRESTADOR>' . $obsocial->_user . '</IDPRESTADOR>
                            </OPER>
                            <PID>
                                <ID>' . $nrodoc . '</ID>
                                <VERIFID>MANUAL</VERIFID>
                            </PID>
                            
                            <PRESCRIP>
                                <ORG>MP S</ORG>
                                <MAT>' . $matricula_medico . '</MAT>
                                <FECHA>' . $datetime->format('Y-m-d') . '</FECHA>
                            </PRESCRIP>

                            <CONTEXTO>
                                <TIPO>A</TIPO>
                            </CONTEXTO>' . $linea_trans .
                        '</SOLICITUD>';

                $resultados = $client->transaccionStr($rpc_xml);

                if ($lista_rechazados != '') {
                    echo 'Prácticas Rechazadas: <font color = "Navy">' . $lista_rechazados . '</font>';
                }

                $lector = new SimpleXMLElement($resultados);

                if ($lector->RSPMSGG == 'AP APROBADA') {
                    $auditoria->actualizarTransaccionAutorizada($nroauditoria, $lector->IDTRAN, 'N', $lector->IDAUT);

                    $wsres->crear($codos, $efector, $nrodoc, $resultados, 3);
                } else {
                    $auditoria->actualizarTransaccion($nroauditoria, $lector->IDTRAN, 'S');

                    echo $resultados;
                    echo '<br>';
                    echo '<font color = "#FF0000">';
                    echo "La Transaccion " . $lector->IDTRAN . " Ha Sido Rechazada por el Servidor. Ingrese Nuevamente la Orden<br>";
                    echo "<H2> " . $erroras . " <H2>";
                    echo '</font>';
                    echo "<p align='center'><h1>Orden Rechazada</h1></p>";

                    $wsres->crear1($codos, $efector, $nrodoc, $resultados, 4, $nroauditoria);

                    $auditoria->anularPracticas($nroauditoria);
                    $auditoria->AnularOrden($nroauditoria);
                }
            } else {
                echo '<br>';
                echo '<font color = "#FF0000">';
                echo "El Número de Documento $nrodoc No está en el Padrón.<br>";
                echo '</font>';
                echo "<p align='center'><h1>Orden Rechazada</h1></p>";
            }
        } else {
            echo '<br>';
            echo '<font color = "#FF0000">';
            echo "La Orden con el Número de Documento $nrodoc y el Diagnóstico $iddiag<br>";
            echo "en la Fecha $fecha1 ya fué Registrada.<br>";
            echo '</font>';
            echo "<p align='center'><h1>Orden Rechazada</h1></p>";
        }
    }

    exit;
}

//==============================================================================
// OSDE
if ($obsocial->_reglaNegocio == 2) {

    date_default_timezone_set('America/Argentina/Ushuaia');
    $datetime = new DateTime();

    $cuitentidad = str_replace('-', '', $obj->getCuit());
    $cuitprestador = str_replace('-', '', $ef->getNrocuit());

// TESTING -----
    //$cuitentidad = '30546741253';
    //$cuitprestador = $obsocial->_parametro4;
    //$codigofinanciador = '11';
//--------------

    $codigofinanciador = $ef->parametro2;
    $terminal = $ef->parametro3;
    $cuitentidad = str_replace('-', '', $obsocial->_parametro4);
    $cuitprestador = str_replace('-', '', $ef->nrocuit);

//------------------------------------------------------------------------------
// Procesamiento de Ordenes que se autorizan directamente
// separamos los códigos

    $prefijo = $obsocial->_parametro2;
    $prefijo = '';
    $prefijoinverso = $obsocial->_parametro3;
    $version = $obsocial->_parametro5;

    $ftrans = $utiles->getFechaAAAAMMDD($fecha);
    $htrans = date('H:m:s');
    $htrans = str_replace(':', '', $htrans);

    //$codigofinanciador = '11';

    $afiliado->getObject($codos, $nrodoc);
    
     $spp = ''; // Para Efectores con varios puntos de atención    
    if ($ef->getNivel2()) $spp = $terminal;

    $track = '';
    $versioncredencial = $afiliado->getIdOS();  // track
    //echo '<h1>xx' . $versioncredencial . '</h1>';    
    //return;   

    $linea = '';

    $c = '';
    $j = 0;
    $k = 0;
    $ln = 0;
    $autoriza = true;
    for ($i = 0; $i < 500; $i++) {
        $c = substr($codigos, $j, 6);
        $j = $j + 6;
        if ($c == 0) {
            break;
        }

        $auditoria->detalle->AgregarItems($c);

// Generamos el xml
        $_cod = '';
        $longitud = strlen($prefijo);
        if ($longitud == 0) {
            $_cod = $c;
        } else {
            $_cod = substr($c, $longitud, strlen($c) - $longitud);
            $_cod = $_cod;  // quitamos las posiciones del prefijo

            $_cod = $c;  //11/12/2018
        }

        $ln++;
        $linea = $linea .
                '<DetalleProcedimientos>' .
                '<NroItem>' . $ln . '</NroItem>' .
                '<CodPrestacion>' . $_cod . '</CodPrestacion>' .
                '<TipoPrestacion>1</TipoPrestacion>' .
                '<ArancelPrestacion>0</ArancelPrestacion>' .
                '<CantidadSolicitada>1</CantidadSolicitada>' .
                '<DescripcionPrestacion></DescripcionPrestacion>' .
                '</DetalleProcedimientos>';
    }

// Verificamos si se ingresa automaticamente el 660001

    if (strlen($obj->getParametro4()) > 0 and $obsocial->getIncluye_ab() == 'S') {

        $auditoria->detalle->AgregarItems($obj->getParametro4());

        $_cod = $obj->getParametro4();

        /*
          echo '<h1>' . $_cod . '</h1>';

          $_c = $obj->getParametro4();

          $longitud = strlen($prefijo);
          if ($longitud == 0)
          $_cod = $c;
          else {
          $_cod = substr($c, $longitud, strlen($c) - $longitud);
          $_cod = $_cod;

          $_cod = $c;  //11/12/2018
          }
         * 
         */

        //$_codigos[$k] = $c;
        $_codigos[$k] = $_cod;
        $k++;

        $ln++;

        $linea = $linea .
                '<DetalleProcedimientos>' .
                '<NroItem>' . $ln . '</NroItem>' .
                '<CodPrestacion>' . $_cod . '</CodPrestacion>' .
                '<TipoPrestacion>1</TipoPrestacion>' .
                '<ArancelPrestacion>0</ArancelPrestacion>' .
                '<CantidadSolicitada>1</CantidadSolicitada>' .
                '<DescripcionPrestacion></DescripcionPrestacion>' .
                '</DetalleProcedimientos>';

        //echo '<pre>' . $linea . '</pre>';
    }

    $s = print_r($linea, true);
    $wsres->crear($codos, $efector, $nrodoc, $s, 8);

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
// sin todos los controles son  ok, registramos la orden

    if ($sin_errores) {
        $ok = true;
        if ($ok) {
            //if ($auditoria->VerificarOrdenPaciente($nrodoc, $fechaing, $iddiag)) {                        
            if ($afiliado->getObject($codos, $nrodoc)) {

                $idmsg = rand(1, 9999);

                $msg = "<Mensaje>
                            <EncabezadoMensaje>
                                   <VersionMsj>" . $version . "</VersionMsj>
                                   <TipoTransaccion>02L</TipoTransaccion>
                                   <IdMsj> $idmsg </IdMsj>
                                   <InicioTrx>
                                           <FechaTrx>" . $ftrans . "</FechaTrx>
                                           <HoraTrx>" . $htrans . "</HoraTrx>
                                   </InicioTrx>
                                   <Financiador>
                                           <CodigoFinanciador>" . $codigofinanciador . "</CodigoFinanciador>
                                           <CuitFinanciador>" . $cuitentidad . "</CuitFinanciador>
                                   </Financiador>
                                   <Prestador>
                                           <CuitPrestador>" . $cuitprestador . "</CuitPrestador>
                                           <SucursalPrestador>" . $spp . "</SucursalPrestador>     
                                           <RazonSocial>OSDE</RazonSocial>
                                           <CodigoParaFinanciador>" . $codigofinanciador . "</CodigoParaFinanciador>
                                           <NroTransaccionInterno/>
                                   </Prestador>
                           </EncabezadoMensaje>
                           <EncabezadoAtencion>
                                   <Efector/>

                                   <Prescriptor>
                                           <FechaReceta>$ftrans</FechaReceta>
                                           <ApellidoPrescriptor>" . str_replace('Ñ', 'N', $medico->getNombre()) . "</ApellidoPrescriptor>
                                           <NombrePrescriptor>" . str_replace('Ñ', 'N', $medico->getNombre()) . "</NombrePrescriptor>
                                           <ProvinciaPrescriptor>S</ProvinciaPrescriptor>
                                           <TipoPrescriptor>M</TipoPrescriptor>
                                           <NroMatriculaPrescriptor>" . $medico->getMatricula() . "</NroMatriculaPrescriptor>
                                   </Prescriptor>
                                   <Credencial>
                                           <NumeroCredencial>" . $nrodoc . "</NumeroCredencial>
                                           <Track>" . $track . "</Track> 
                                           <VersionCredencial>" . $versioncredencial . "</VersionCredencial>  
                                   </Credencial>
                                   <Preautorizacion/>
                                   <Documentacion/>
                                   <Atencion>
                                           <FechaAtencion>$ftrans</FechaAtencion>
                                           <HoraAtencion>$htrans</HoraAtencion>
                                   </Atencion>
                                   <Diagnostico/>
                                   <CodFinalizacionTratamiento/>
                                   <MensajeParaFinanciador/>
                           </EncabezadoAtencion>" .
                        $linea .
                        "</Mensaje>";

                //echo $msg;
                //exit;
                

                $wsres->crear($codos, $efector, $nrodoc, $msg, 8);

                //$fp = fopen("xml.xml", "w");
                //fwrite($fp, $msg . " \r\n");
                //fclose($fp);

                $msg = str_replace('<', '%3C', $msg);
                $msg = str_replace('>', '%3E', $msg);
                $msg = str_replace(' ', '', $msg);
                $msg = preg_replace('[\s+]', '', $msg);

                //$url = "http://ws.itcsoluciones.com:48080/jSitelServlet/Do?pas=32dbf220f1ab2303592b4a076162c221600ef704&msj=" . $msg; // url de la pagina que queremos obtener  
                //echo '1 ' . $url . '</br>';
                $url = $obsocial->_url . $msg;

                $ok = false;

                $aut = 'S';

                $auditoria->crear($nroauditoria, $efector, $fechaing, $codos_reg, $idzona, $nrodoc, $idprof, $observacion, $iddiag, $idperfil, $fechapedido, $aut, $medicocab);

                $url_content = '';
                $file = @fopen($url, 'r');

                if ($file) {
                    while (!feof($file)) {
                        $url_content .= @fgets($file, 4096);
                    }
                    fclose($file);

                    $lector = new SimpleXMLElement($url_content);

                    // capturamos el resultado y agregamos los items con los datos de lo auditado
                    //$auditoria->crear($nroauditoria, $efector, $fechaing, $codos_reg, $idzona, $nrodoc, $idprof, $observacion, $iddiag, $idperfil, $fechapedido, $aut, $medicocab);
                    // Marcamos todas como rechazadas
                    $auditoria->anularPracticas($nroauditoria);

                    // Capturamos autorización / Rechazo
                    $mensajeDisplay = $lector->EncabezadoMensaje->Rta->MensajeDisplay;
                    $mensajePrinter = $lector->EncabezadoMensaje->Rta->MensajePrinter;
                    $estadoOrden = '';
                    $diferida = 'N';

                    if (strpos($m, "SOLICITUD RECHAZADA") > 0)
                        $estadoOrden = 'R';

                    // Ahora, a partir de la respuesta, marcamos las prácticas autorizadas
                    for ($i = 0; $i <= 100; $i++) {
                        if ($lector->DetalleProcedimientos[$i]->NroItem == '')
                            break;
                        $_es = 'R';
                        if ($lector->DetalleProcedimientos[$i]->MensajeRta == 'Autorizado')
                            $_es = 'A';

                        if ($prefijo == '') {
                            $_cod = $lector->DetalleProcedimientos[$i]->CodPrestacion;
                        } else {
                            $c = $lector->DetalleProcedimientos[$i]->CodPrestacion;
                            $_cod = $prefijoinverso . substr($c, $longitud, strlen($c) - $longitud);
                        }

                        $_cod = $lector->DetalleProcedimientos[$i]->CodPrestacion;  // 19/09/2023
                        // ANULACION TOTAL
                        if ($estadoOrden == 'R')
                            $_es = 'R';

                        if ($_es == 'R')
                            $diferida = 'S';

                        $auditoria->actualizarDeterminacion($nroauditoria, $_cod, $_es);
                    }

                    $auditoria->actualizarTransaccion($nroauditoria, $lector->EncabezadoMensaje->NroReferencia, $diferida);

                    echo $url_content . '<br/>';
                    $wsres->crear($codos, $efector, $nrodoc, $url_content, 2);

                    $ok = true;

                    if ($url_content == '1001-3: Pasaporte incorrecto') {
                        $auditoria->borrarOrden($nroauditoria);
                        echo '<h1>Se ha Producido un Error.</h1><h2>Vuelva a Ingresar la Orden</h2>';
                    }

                    if ($estadoOrden == 'R') {
                        echo '<h3>' . $mensajeDisplay . '</h3>';
                        $auditoria->AnularOrden($nroauditoria);
                    }
                } else {
                    $auditoria->borrarOrden($nroauditoria);
                    echo '<br>';
                    echo '<font color = "#FF0000">';
                    echo "El Número de Documento $nrodoc No está en el Padrón.<br>";
                    echo '</font>';
                    echo "<p align='center'><h1>Orden Rechazada</h1></p>";
                }

                if (!$ok) {
                    echo "<p align='center'><h1>Orden Rechazada</h1></p>";
                    echo "<p align='center'><h2>Vuelva a Ingresarla</h2></p>";
                    $auditoria->borrarOrden($nroauditoria);
                }
            } else {
                $auditoria->borrarOrden($nroauditoria);
                echo '<br>';
                echo '<font color = "#FF0000">';
                echo "La Orden con el Número de Documento $nrodoc y el Diagnóstico $iddiag<br>";
                echo "en la Fecha $fecha1 ya fué Registrada.<br>";
                echo '</font>';
                echo "<p align='center'><h1>Orden Rechazada</h1></p>";
            }
        }
    }

    exit;
}

//==============================================================================
// JERARQUICO
if ($obsocial->_reglaNegocio == 3) {

    $url = $obsocial->_url;

//------------------------------------------------------------------------------
// Procesamiento de Ordenes que se autorizan directamente
// separamos los códigos

    $linea = '';

    $c = '';
    $j = 0;
    $k = 0;
    $autoriza = true;
    for ($i = 0; $i < 500; $i++) {
        $c = substr($codigos, $j, 6);
        $j = $j + 6;
        if ($c == 0) {
            break;
        }

        $auditoria->detalle->AgregarItems($c);

        $_codigos[$k] = $c;
        $k++;
    }

    if (strlen($obj->getParametro4()) > 0 and $obsocial->getIncluye_ab() == 'S') {
        $auditoria->detalle->AgregarItems($obj->getParametro4());
        $_codigos[$k] = $obj->getParametro4();
        $k++;
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
    
    if ($afiliado->getObject($codos, $nrodoc) == false) {
        echo "El Afiliado $nrodoc es Incorrecto.<br>";
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
// sin todos los controles son  ok, registramos la orden

    if ($sin_errores) {
        if ($auditoria->VerificarOrdenPaciente($nrodoc, $fechaing, $iddiag)) {
            if ($afiliado->getObject($codos, $nrodoc)) {
                $aut = 'S';
                // capturamos el resultado y agregamos los items con los datos de lo auditado
                
                $numerodoc = $afiliado->getNumerodoc();
                                
                //$auditoria->crear($nroauditoria, $efector, $fechaing, $codos_reg, $idzona, $nrodoc, $idprof, $observacion, $iddiag, $idperfil, $fechapedido, $aut, $medicocab);
                $auditoria->crear2($nroauditoria, $efector, $fechaing, $codos_reg, $idzona, $nrodoc, $idprof, $observacion, $iddiag, $idperfil, $fechapedido, $aut, $medicocab, $ttoken2, $numerodoc);

                $diferida = 'N';

                try {

                    $client = new SoapClient($url, array('cache_wsdl' => WSDL_CACHE_NONE, 'trace' => TRUE));

// Ahora, a partir de la respuesta, marcamos las prácticas autorizadas
                    for ($i = 0; $i <= 100; $i++) {

                        $codd = $_codigos[$i];

                        if (strlen($codd) < 4)
                            break;

// 22/05/2014 : se eliminan los . a partir del convenio
                        $cc = substr($codd, 0, 2) . '.' . substr($codd, 2, 2) . '.' . substr($codd, 4, 2);

                        $cc = $codd;
                        $convenio = $obsocial->getParametro3();

                        $CriterioPracticaRequiereAutorizacion = array(
                            'CodigoNomencladorConvenio' => $cc,
                            'IdConvenio' => $convenio,
                            'IdPlan' => $afiliado->getId_beneficio());


                        $SolicitudValidacionPracticaRequiereAutorizacion = array('CriterioPracticaRequiereAutorizacion' => $CriterioPracticaRequiereAutorizacion);

                        //Llamada al servicio pasando el parámetro
                        $ready = $client->ValidarPracticaRequiereAutorizacion(array('solicitudValidacionPracticaRequiereAutorizacion' => $SolicitudValidacionPracticaRequiereAutorizacion));

                        $res = $ready->ValidarPracticaRequiereAutorizacionResult->DTOSerializado;

                        $obj = json_decode($res);

                        //print_r($ready) . '<br/>';
                        //echo "Práctica: <i>" . $obj->{'CodigoNomencladorConvenio'}  . "<i><br/>";
                        echo $res . '<br/>' . "Práctica: <i>" . $obj->{'CodigoNomencladorConvenio'} . "</i><br/>";

                        $_es = 'R';
                        if (!$obj->{'RequiereAutorizacion'})
                            $_es = 'A';

                        if ($res == '')
                            $_es = 'R';

                        //echo $codos . '    ' . $ef->getCodigo(). '    ' . $nrodoc. '    ' . $res. '    ' . '5' . '    ' . $obj->{'CodigoNomencladorConvenio'};
                        // 19/07/2019 -  Si la práctica no es del PMO va a diferida                        

                        /*  16/12/2019
                          if ($nbu->getPracticaAdicional($codos, $codd) == false) {
                          $_es = 'R';
                          }
                         * 
                         */

                        // 03/08/2020 -> códigos restringidos
                        if ($nbudif->getCodigoIncluido($codos, $codd) != '')
                            $_es = 'R';

                        // 04/04/2023

                        if (substr($codd, 0, 3) != '660')
                            $_es = 'R';

                        // 24/04/2023
                        if ($diferida != 'S') {
                            if ($codd == '661035' || $codd == '661040')
                                $_es = 'A';
                        }

                        if ($_es == 'R')
                            $diferida = 'S';


                        $auditoria->actualizarDeterminacion($nroauditoria, $codd, $_es);

                        //if ($obj == null) $res = $codd . ' : No convenida - ' . $ready;
                        //$wsres->crear($codos, $efector, $nrodoc, $res, 2);
                        //$wsres->crear($codos, $efector, $nrodoc, $obj->{'CodigoNomencladorConvenio'}, 5);
                        $wsres->crear1($codos, $ef->getCodigo(), $nrodoc, $res, 2, $obj->{'CodigoNomencladorConvenio'});
                        //echo $codos . '    ' . $ef->getCodigo(). '    ' . $nrodoc. '    ' . $res. '    ' . '5' . '    ' . $obj->{'CodigoNomencladorConvenio'};


                        $res = '';
                    }

                    $auditoria->actualizarTransaccion($nroauditoria, '', $diferida);
                } catch (Exception $e) {
                    trigger_error($e->getMessage(), E_USER_WARNING);
                }
            } else {
                echo '<br>';
                echo '<font color = "#FF0000">';
                echo "El Número de Documento $nrodoc No está en el Padrón.<br>";
                echo '</font>';
                echo "<p align='center'><h1>Orden Rechazada</h1></p>";
            }
        } else {
            echo '<br>';
            echo '<font color = "#FF0000">';
            echo "La Orden con el Número de Documento $nrodoc y el Diagnóstico $iddiag<br>";
            echo "en la Fecha $fecha1 ya fué Registrada.<br>";
            echo '</font>';
            echo "<p align='center'><h1>Orden Rechazada</h1></p>";
        }
    }

    exit;
}

//==============================================================================
// AMUR
if ($obsocial->_reglaNegocio == 4) {

//------------------------------------------------------------------------------
// Procesamiento de Ordenes que se autorizan directamente
// separamos los códigos

    $linea = '';

    $c = '';
    $j = 0;
    $k = 0;
    $autoriza = true;
    for ($i = 0; $i < 500; $i++) {
        $c = substr($codigos, $j, 6);
        $j = $j + 6;
        if ($c == 0) {
            break;
        }

        $auditoria->detalle->AgregarItems($c);

        $_codigos[$k] = $c;
        $k++;
    }

    if (strlen($obj->getParametro4()) > 0 and $obsocial->getIncluye_ab() == 'S') {
        $auditoria->detalle->AgregarItems($obj->getParametro4());
        $_codigos[$k] = $obj->getParametro4();
        $k++;
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
// sin todos los controles son  ok, registramos la orden

    if ($sin_errores) {
        if ($auditoria->VerificarOrdenPaciente($nrodoc, $fechaing, $iddiag)) {
            if ($afiliado->getObject($codos, $nrodoc)) {

                $aut = false;
// capturamos el resultado y agregamos los items con los datos de lo auditado
                $auditoria->crear($nroauditoria, $efector, $fechaing, $codos_reg, $idzona, $nrodoc, $idprof, $observacion, $iddiag, $idperfil, $fechapedido, $aut, $medicocab);

                $diferida = 'N';

//==============================================================

                try {

                    $codigo_ant = $obsocial->_parametro1;
// Envoltura RPC
//Autorizacion
                    $url = $obsocial->_url;
                    $usuario = $obsocial->_user;
                    $pass = $obsocial->_pass;
                    $ns = substr($_REQUEST['nrodoc'], 0, 7);
//$ndoc = substr($_REQUEST['nrodoc'], 7, 8);                    
                    $ndoc = '';
                    $fecha = $utiles->getFechaAAAA_MM_DD($fecha);
                    $apno = $afiliado->getNombre();

                    $cuit = str_replace("-", "", $ef->getNrocuit()); // '30582312059';
                    $matrimed = $medico->getMatricula();
                    $matritipo = 'PS';
                    $matriapno = $medico->getNombre();
                    $diag1 = str_replace(".", "", $iddiag); // 'Y570';
                    $diag2 = '';
                    $diag3 = '';
                    $diaga = $observacion; // '[AGREGAR DIAG. AMPL.]';
                    $fpres = $fecha; //'2015-04-20';
                    $tipo = 'A';
                    $fint = '0000-00-00';
                    $hint = '00';
                    $cint = '';
                    $tint = '';
                    $nauti = '';
                    $fauti = '';
                    $obs = '';

                    $client = new SoapClient($url, array('cache_wsdl' => WSDL_CACHE_NONE, 'trace' => TRUE));


                    $res = $auditoria->getAuditoriaPracticas($nroauditoria);

                    while ($fila = mysql_fetch_array($res)) {
                        //echo $fila['codigo'] . '  ' . $auditoria->countPracticas($nroauditoria, $fila['codigo']);

                        $codd = $fila['codigo'];

                        if (strlen($codd) < 4)
                            break;

                        $c = substr($codd, 3, 3);

                        $c = $codd;

                        // Verificamos que el código no tenga equivalencias
                        $cod_eq = $equivalencia->getCodigoEquivalente($codos, $codd);
                        if ($cod_eq != '')
                            $c = $cod_eq;

                        $nbu->getObject($codd);

                        $__cantidadprestaciones = $auditoria->countPracticas($nroauditoria, $fila['codigo']);

                        if ($__cantidadprestaciones == 0)
                            $__cantidadprestaciones = 1;

                        $prestacion[] = array(
                            'tncl' => '4',
                            'cpre' => $c, //'736',
                            'npre' => $nbu->getDescrip(), // 'PARASITOLOGICO DE MATERIA FECAL SERIADO',
                            'cintmed' => '',
                            'cdien' => '',
                            'cdet' => '00',
                            'cantp' => $__cantidadprestaciones,
                            'canta' => '0',
                            'mfact' => '0',
                            'mcose' => '0',
                            'motivo' => '',
                            'estadop' => ''
                        );
                    }

                    //print_r($prestacion);                   
                    //echo '<br/> Token: ' . $ttoken;
                    //exit;


                    /*
                      print_r($prestacion);





                      for ($i = 0; $i <= 100; $i++) {

                      $codd = $_codigos[$i];

                      if (strlen($codd) < 4)
                      break;

                      $c = substr($codd, 3, 3);

                      $c = $codd;

                      // Verificamos que el código no tenga equivalencias
                      $cod_eq = $equivalencia->getCodigoEquivalente($codos, $codd);
                      if ($cod_eq != '')
                      $c = $cod_eq;

                      $nbu->getObject($codd);

                      $prestacion1[] = array(
                      'tncl' => '4',
                      'cpre' => $c, //'736',
                      'npre' => $nbu->getDescrip(), // 'PARASITOLOGICO DE MATERIA FECAL SERIADO',
                      'cintmed' => '',
                      'cdien' => '',
                      'cdet' => '00',
                      'cantp' => '1',
                      'canta' => '0',
                      'mfact' => '0',
                      'mcose' => '0',
                      'motivo' => '',
                      'estadop' => ''
                      );
                      }




                      echo '<hr/>';
                      print_r($prestacion1);

                      exit;
                     * 
                     */





// Ahora, a partir de la respuesta, marcamos las prácticas autorizadas
                    /*
                      for ($i = 0; $i <= 100; $i++) {

                      $codd = $_codigos[$i];

                      if (strlen($codd) < 4)
                      break;

                      $c = substr($codd, 3, 3);

                      $c = $codd;

                      // Verificamos que el código no tenga equivalencias
                      $cod_eq = $equivalencia->getCodigoEquivalente($codos, $codd);
                      if ($cod_eq != '')
                      $c = $cod_eq;

                      $nbu->getObject($codd);

                      $prestacion[] = array(
                      'tncl' => '4',
                      'cpre' => $c, //'736',
                      'npre' => $nbu->getDescrip(), // 'PARASITOLOGICO DE MATERIA FECAL SERIADO',
                      'cintmed' => '',
                      'cdien' => '',
                      'cdet' => '00',
                      'cantp' => '1',
                      'canta' => '0',
                      'mfact' => '0',
                      'mcose' => '0',
                      'motivo' => '',
                      'estadop' => ''
                      );
                      }
                     * 
                     */                    
                            
                    $result = $client->__soapCall('fnautoriza', array('usuario' => $usuario, 'pass' => $pass, 'ns' => $ns, 'apno' => $apno, 'ndoc' => $ndoc, 'cuit' => $cuit, 'matrimed' => $matrimed, 'matritipo' => $matritipo, 'matriapno' => $matriapno, 'diag1' => $diag1, 'diag2' => $diag2, 'diag3' => $diag3, 'diaga' => $diaga, 'fpres' => $fpres, 'tipo' => $tipo, 'fint' => $fint, 'hint' => $hint, 'cint' => $cint, 'tint' => $tint, 'obs' => $obs, 'nauti' => $nauti, 'fauti' => $fauti, 'prestaciones' => $prestacion));
                    //$result = $client->__soapCall('fnautoriza', array('usuario' => $usuario, 'pass' => $pass, 'ns' => $ns, 'apno' => $apno, 'ndoc' => $ndoc, 'cuit' => $cuit, 'matrimed' => $matrimed, 'matritipo' => $matritipo, 'matriapno' => $matriapno, 'diag1' => $diag1, 'diag2' => $diag2, 'diag3' => $diag3, 'diaga' => $diaga, 'fpres' => $fpres, 'tipo' => $tipo, 'fint' => $fint, 'hint' => $hint, 'cint' => $cint, 'tint' => $tint, 'obs' => $obs, 'nauti' => $nauti, 'fauti' => $fauti, 'prestaciones' => $prestacion, 'token' => $ttoken));

                    $call = array('usuario' => $usuario, 'pass' => $pass, 'ns' => $ns, 'apno' => $apno, 'ndoc' => $ndoc, 'cuit' => $cuit, 'matrimed' => $matrimed, 'matritipo' => $matritipo, 'matriapno' => $matriapno, 'diag1' => $diag1, 'diag2' => $diag2, 'diag3' => $diag3, 'diaga' => $diaga, 'fpres' => $fpres, 'tipo' => $tipo, 'fint' => $fint, 'hint' => $hint, 'cint' => $cint, 'tint' => $tint, 'obs' => $obs, 'nauti' => $nauti, 'fauti' => $fauti);

                    $diferida = 'S';
                    if ($result->estado == 'AA')
                        $diferida = 'N';

                    if ($result->naut != '')
                        $auditoria->actualizarTransaccionToken($nroauditoria, $result->naut, $diferida, $ttoken);
// Si devuelve vacio la borramos
                    if ($result->naut == '')
                        $auditoria->borrarTransaccion($nroauditoria);

                    $prestacion = $result->prestaciones;
                    for ($p = 0; $p < count($prestacion); $p++) {
                        $_es = 'R';
                        if ($prestacion[$p]->estadop == "AA")
                            $_es = 'A';
                        $__c = trim($codigo_ant) . $prestacion[$p]->cpre;

// 21/08/2015
                        $__c = $prestacion[$p]->cpre;
                        $auditoria->actualizarDeterminacion($nroauditoria, $__c, $_es);
                    }

                    $s = print_r($result, true);
                    $wsres->crear($codos, $efector, $nrodoc, $s, 2);

                    if ($result->mensajes != '')
                        $auditoria->guardarMensajeRPC($nroauditoria, $result->mensajes);
                    
                    // 02/10/2025 - si el token es inválido la anulamos
                    /*
                    if ($result->mensajes == 'NO SE ENCONTRO EL TOKEN') {
                        $auditoria->AnularOrden($nroauditoria);
                        echo '<h1>' . $result->mensajes . '</h1>';
                    }
                     * 
                     */

                    print_r($result);
                } catch (Exception $e) {
                    trigger_error($e->getMessage(), E_USER_WARNING);
                }

//==============================================================
            } else {
                echo '<br>';
                echo '<font color = "#FF0000">';
                echo "El Número de Documento $nrodoc No está en el Padrón.<br>";
                echo '</font>';
                echo "<p align='center'><h1>Orden Rechazada</h1></p>";
            }
        } else {
            echo '<br>';
            echo '<font color = "#FF0000">';
            echo "La Orden con el Número de Documento $nrodoc y el Diagnóstico $iddiag<br>";
            echo "en la Fecha $fecha1 ya fué Registrada.<br>";
            echo '</font>';
            echo "<p align='center'><h1>Orden Rechazada</h1></p>";
        }
    }

    exit;
}

//==============================================================================
// SANCOR
if ($obsocial->_reglaNegocio == 5) {

//------------------------------------------------------------------------------
// Procesamiento de Ordenes que se autorizan directamente
// separamos los códigos

    $linea = '';

    $c = '';
    $j = 0;
    $k = 0;
    $autoriza = true;

    for ($i = 0; $i < 500; $i++) {
        $c = substr($codigos, $j, 6);
        $j = $j + 6;
        if ($c == 0) {
            break;
        }

        $auditoria->detalle->AgregarItems($c);

        $_codigos[$k] = $c;
        $k++;
    }

    if (strlen($obj->getParametro4()) > 0 and $obsocial->getIncluye_ab() == 'S') {
        $auditoria->detalle->AgregarItems($obj->getParametro4());
        $_codigos[$k] = $obj->getParametro4();
        $k++;
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
// sin todos los controles son  ok, registramos la orden

    if ($sin_errores) {
        if ($auditoria->VerificarOrdenPaciente($nrodoc, $fechaing, $iddiag)) {
            if ($afiliado->getObject($codos, $nrodoc)) {

                $aut = false;
                // capturamos el resultado y agregamos los items con los datos de lo auditado
                $auditoria->crear($nroauditoria, $efector, $fechaing, $codos_reg, $idzona, $nrodoc, $idprof, $observacion, $iddiag, $idperfil, $fechapedido, $aut, $medicocab);

                $diferida = 'N';

                //==============================================================

                try {

                    $url = $obsocial->_url;
                    $usuario = $obsocial->_user;
                    $clave = $obsocial->_pass;

                    $nroorden = 0;
                    $entidad = $obsocial->_parametro1;
                    $tiponroefector = $obsocial->_parametro3; // 'CU';    
                    $e = str_replace('-', '', $ef->getNrocuit());
                    $nroefector = (float) $e; //20167261885; //20167261885;    
                    $formaidafiliado = $obsocial->_parametro4; //'AS';
                    $afiliado = $nrodoc; //'12129700';
                    $modo = $obsocial->getParametro2();
                    $tiponroefector = $obsocial->getParametro3();
                    $Matriculaprescribiente = trim($medico->getMatricula() . $medico->getLibro() . $medico->getFolio());
                    $Descripcionprescribiente = $medico->getNombre();

                    // Generamos las prácticas
                    for ($i = 0; $i <= 100; $i++) {

                        $codd = $_codigos[$i];

                        if (strlen($codd) < 4)
                            break;

                        $prestacionesItems[$i] = array('TipoNomenclador' => $obsocial->_parametro5, 'Prestacion' => $codd, 'Cantidad' => 1, 'Formulario' => 0);
                    }

                    // Método VALIDARPRACTICA - Determina que prácticas se autorizan

                    $client = new SoapClient($url, array('cache_wsdl' => WSDL_CACHE_NONE, 'trace' => TRUE));

                    $criterio = array(
                        'Modo' => $modo,
                        'Entidad' => $entidad,
                        'Formaidafiliado' => $formaidafiliado,
                        'Afiliado' => $afiliado,
                        'Prestacionesvalidar' => array('PrestacionesValidar' => $prestacionesItems),
                        'Usuario' => $usuario,
                        'Clave' => $clave);

                    $s = print_r($criterio, true);
                    $wsres->crear($codos, $efector, $nrodoc, $s, 8);
                    echo 'send: -> ' . $s . '<br/>';

                    $result = $client->VALIDARPRACTICA($criterio);

                    $prestaciones[] = $result->Prestacionesvalidarrta;

                    $prestacionesItemsAutorizados[] = '';

                    $j = 0;

                    for ($p = 0; $p < count($prestaciones[0]->PrestacionesValidarRta); $p++) {
                        $prestacion = $prestaciones[0]->PrestacionesValidarRta[$p]->Prestacion;
                        $requiere = $prestaciones[0]->PrestacionesValidarRta[$p]->Requiere;

                        $_es = 'R';
                        if ($requiere == 'N') {
                            $_es = 'A';
                            $prestacionesItemsAutorizados[$j] = array('TipoNomenclador' => $obsocial->_parametro5, 'Prestacion' => $prestacion, 'Cantidad' => 1, 'Formulario' => 0);
                            $j++;
                        } else {
                            $diferida = 'S';
                        }

                        $auditoria->actualizarDeterminacionObservaciones($nroauditoria, $prestacion, $_es, $errorprestacion . '  ' . $descriperrorprestacion);
                    }

                    if ($j > 0)
                        $diferida = 'N';

                    $s = print_r($result, true);
                    $wsres->crear($codos, $efector, $nrodoc, $s, 3);
                    print_r($result);
                    echo '<hr>';

                    // Método AUTORIZACION - Generamos la orden con lo autorizado

                    $criterio = array(
                        'Modo' => $modo,
                        'Nroorden' => $nroorden,
                        'Entidad' => $entidad,
                        'Tiponroefector' => $tiponroefector,
                        'Nroefector' => $nroefector,
                        'Formaidafiliado' => $formaidafiliado,
                        'Afiliado' => $afiliado,
                        'Matriculaprescribiente' => $Matriculaprescribiente,
                        'Descripcionprescribiente' => $Descripcionprescribiente,
                        'Prestaciones' => array('PrestacionesItem' => $prestacionesItemsAutorizados),
                        'Usuario' => $usuario,
                        'Clave' => $clave);

                    $result = $client->AUTORIZACION($criterio);

                    // 06/03/2019 - Interceptamos si se generó o no la orden

                    if ($result->Nroautorizacion > '0') {
                        $auditoria->actualizarTransaccionAutorizada($nroauditoria, '', $diferida, $result->Nroautorizacion);
                    } else {
                        //$auditoria->borrarOrden($nroauditoria);

                        echo '<h1>Se Produjo un Error al Autorizar la Orden</h1>';
                        echo '<h2>Vuelva a Ingresarla</h2>';
                    }

                    $s = print_r($result, true);
                    $wsres->crear($codos, $efector, $nrodoc, $s, 2);

                    print_r($result);
                    echo '<hr>';
                } catch (Exception $e) {
                    trigger_error($e->getMessage(), E_USER_WARNING);
                    //$auditoria->borrarOrden($nroauditoria);
                    $auditoria->AnularOrden($nroauditoria);
                    echo '<h1>Se Produjo un Error al Autorizar la Orden</h1>';
                    echo '<h2>Vuelva a Ingresarla</h2>';
                }

                //==============================================================
            } else {
                echo '<br>';
                echo '<font color = "#FF0000">';
                echo "El Número de Documento $nrodoc No está en el Padrón.<br>";
                echo '</font>';
                echo "<p align='center'><h1>Orden Rechazada</h1></p>";
            }
        } else {
            echo '<br>';
            echo '<font color = "#FF0000">';
            echo "La Orden con el Número de Documento $nrodoc y el Diagnóstico $iddiag<br>";
            echo "en la Fecha $fecha1 ya fué Registrada.<br>";
            echo '</font>';
            echo "<p align='center'><h1>Orden Rechazada</h1></p>";
        }
    }

    exit;
}

//==============================================================================
// FEDERADA
if ($obsocial->_reglaNegocio == 6) {

//------------------------------------------------------------------------------
// Procesamiento de Ordenes que se autorizan directamente
// separamos los códigos

    $linea = '';

    $c = '';
    $j = 0;
    $k = 0;
    $autoriza = true;

    for ($i = 0; $i < 500; $i++) {
        $c = substr($codigos, $j, 6);
        $j = $j + 6;
        if ($c == 0) {
            break;
        }

        $auditoria->detalle->AgregarItems($c);

        $_codigos[$k] = $c;
        $k++;
    }

    if (strlen($obj->getParametro4()) > 0 and $obsocial->getIncluye_ab() == 'S') {
        $auditoria->detalle->AgregarItems($obj->getParametro4());
        $_codigos[$k] = $obj->getParametro4();
        $k++;
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
// sin todos los controles son  ok, registramos la orden

    if ($sin_errores) {
        if ($auditoria->VerificarOrdenPaciente($nrodoc, $fechaing, $iddiag)) {
            if ($afiliado->getObject($codos, $nrodoc)) {

                $aut = false;
                // capturamos el resultado y agregamos los items con los datos de lo auditado
                $auditoria->crear($nroauditoria, $efector, $fechaing, $codos_reg, $idzona, $nrodoc, $idprof, $observacion, $iddiag, $idperfil, $fechapedido, $aut, $medicocab);

                //==============================================================

                try {

                    $url = $obsocial->_url;
                    $usuario = $obsocial->_user;
                    $pass = $obsocial->_pass;
                    $ns = $_REQUEST['nrodoc'];

                    $metodo = 'orden';

                    $version = $obsocial->_parametro8;
                    $version = 'v1.5.2';

                    $parameters = '{"url":"' . $obsocial->_parametro7 . '/","metodo":"validador/' . $version . '/wsvol001","api":"x-api-key","apikey":"' . $pass . '"}';

                    // Diagnóstico
                    $dx1 = substr($dx->getoms_cod(), 0, 3);

                    // Practicas -----------------------------------------------  
                    $nrocuit = str_replace('-', '', $ef->getNrocuit());
                    //$nrocuit = '600627';

                    $prestaciones = '[';
                    $l = 0;
                    for ($i = 0; $i <= 100; $i++) {

                        $codd = $_codigos[$i];

                        if (strlen($codd) < 4)
                            break;

                        $l++;

                        $linea = '{"NroLinea":"' . $l . '","FecPre":"' . $utiles->getFechaAAAAMMDD($fechaing) . '","PtiCod":"B","PstCod":"' . $codd . '","Cantidad":"1","CodDiagno":"' . $dx1 . '","DesDiagno":"-","ComPresta":"test","CUITProf":null}';

                        $prestaciones = $prestaciones . $linea . ',';
                    }
                    $prestaciones = $prestaciones . '],';

                    $practicas = str_replace(',]', ']', $prestaciones);

                    //----------------------------------------------------------

                    $cuit = str_replace('-', '', $obsocial->_parametro5); // CUIT de la Asociacion

                    $data = '{"p_Modo":"A",'
                            . '"p_Prestador":"' . $obsocial->_parametro3 . '",'
                            . '"p_SubPrestador":"' . $obsocial->_parametro4 . '",'
                            . '"p_SubPreCUIT":"' . $cuit . '",'
                            . '"p_IntNro":"' . $obsocial->_parametro2 . '",'
                            . '"p_NroDoc":"' . $nrodoc . '",'
                            . '"p_Situacion":"0",'
                            . '"p_ListaPrestaciones":' . $practicas
                            . '"p_Archivos":[]}';

                    $json_data = '{"parameters":' . $parameters . ',' .
                            '"data":' . $data . '}"';

                    $context = stream_context_create(array(
                        'http' => array(
                            'protocol_version' => 1.1,
                            'user_agent' => 'PHPExample',
                            "Cookie => foo=bar\r\n",
                            'method' => 'PUT',
                            'header' => "Content-type: application/json\r\n" .
                            "Connection: close\r\n" .
                            "Content-length: " . strlen($json_data) . "\r\n",
                            'content' => $json_data,
                            'Expect' => '100-continue'
                        ),
                    ));

                    $theurl = $url . $metodo; // . '?id=' . $data . '&key1=' . $parameters;

                    $file = file_get_contents($theurl, false, $context);
                    var_dump($file);

                    $array = json_decode($file);

                    //{"message": "Endpoint request timed out"}

                    for ($p = 0; $p < count($array->o_ListaPrestacionesValidadas); $p++) {
                        $_es = 'R';
                        if ($array->o_ListaPrestacionesValidadas[$p]->StatusPre == 'SI')
                            $_es = 'A';
                        $__c = $array->o_ListaPrestacionesValidadas[$p]->PstCod;
                        $auditoria->actualizarDeterminacion($nroauditoria, $__c, $_es);
                    }

                    // 24/04/2019 - Interceptamos si se generó o no la orden

                    if ($array->message == "Endpoint request timed out") {

                        $auditoria->borrarOrden($nroauditoria);
                        echo '<h1>Se Produjo un Error al Autorizar la Orden</h1>';
                        echo '<h2>Vuelva a Ingresarla</h2>';

                        return;
                    }

                    // 07/03/2019 - Interceptamos si se generó o no la orden

                    if ($array->o_NroSolicitud != '0') {

                        $diferida = 'S';

                        if ($array->o_Status == 'SI' || $array->o_Status == 'AA' || $array->o_Status == 'R1' || $array->o_Status == 'MA')
                            $diferida = 'N';

                        $auditoria->actualizarTransaccionAutorizada($nroauditoria, $array->o_NroSolicitud, $diferida, $array->NroAutorizacion);

                        $s = print_r($array, true);

                        $wsres->crear1($codos, $efector, $nrodoc, $file, 2, $array->o_NroSolicitud);

                        print_r($result);

                        echo '<b>' . $array->o_Comentario . '</b><br/>';
                    } else {

                        $auditoria->borrarOrden($nroauditoria);
                        echo '<h1>Se Produjo un Error al Autorizar la Orden</h1>';
                        echo '<h2>Vuelva a Ingresarla</h2>';
                    }


                    echo '<hr>';
                } catch (Exception $e) {
                    trigger_error($e->getMessage(), E_USER_WARNING);
                    $auditoria->borrarOrden($nroauditoria);
                    echo '<h1>Se Produjo un Error al Autorizar la Orden</h1>';
                    echo '<h2>Vuelva a Ingresarla</h2>';
                }

                //==============================================================
            } else {
                echo '<br>';
                echo '<font color = "#FF0000">';
                echo "El Número de Documento $nrodoc No está en el Padrón.<br>";
                echo '</font>';
                echo "<p align='center'><h1>Orden Rechazada</h1></p>";
            }
        } else {
            echo '<br>';
            echo '<font color = "#FF0000">';
            echo "La Orden con el Número de Documento $nrodoc y el Diagnóstico $iddiag<br>";
            echo "en la Fecha $fecha1 ya fué Registrada.<br>";
            echo '</font>';
            echo "<p align='center'><h1>Orden Rechazada</h1></p>";
        }
    }

    exit;
}

//==============================================================================
// INGENIEROS
if ($obsocial->_reglaNegocio == 8) {

//------------------------------------------------------------------------------
// Procesamiento de Ordenes que se autorizan directamente
// separamos los códigos

    $linea = '';

    $c = '';
    $j = 0;
    $k = 0;
    $autoriza = true;

    for ($i = 0; $i < 500; $i++) {
        $c = substr($codigos, $j, 6);
        $j = $j + 6;
        if ($c == 0) {
            break;
        }

        $auditoria->detalle->AgregarItems($c);

        $_codigos[$k] = $c;
        $k++;
    }

    if (strlen($obj->getParametro4()) > 0 and $obsocial->getIncluye_ab() == 'S') {
        $auditoria->detalle->AgregarItems($obj->getParametro4());
        $_codigos[$k] = $obj->getParametro4();
        $k++;
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
    // sin todos los controles son  ok, registramos la orden

    if ($sin_errores) {
        if ($auditoria->VerificarOrdenPaciente($nrodoc, $fechaing, $iddiag)) {
            if ($afiliado->getObject($codos, $nrodoc)) {

                $aut = false;
                // capturamos el resultado y agregamos los items con los datos de lo auditado
                $auditoria->crear($nroauditoria, $efector, $fechaing, $codos_reg, $idzona, $nrodoc, $idprof, $observacion, $iddiag, $idperfil, $fechapedido, $aut, $medicocab);

                //==============================================================

                try {

                    $url = $obsocial->_url;
                    $usuario = $obsocial->_user;
                    $pass = $obsocial->_pass;
                    $ns = $_REQUEST['nrodoc'];

                    // Practicas -----------------------------------------------  
                    $nrocuit = str_replace('-', '', $ef->getNrocuit());
                    //$nrocuit = '600627';

                    $prestaciones = '[';
                    $l = 0;
                    for ($i = 0; $i <= 100; $i++) {

                        $codd = $_codigos[$i];

                        if (strlen($codd) < 4)
                            break;

                        $l++;

                        $linea = '{"NroLinea":"' . $l . '","FecPre":"' . $utiles->getFechaAAAAMMDD($fechaing) . '","PtiCod":"N","PstCod":"' . $codd . '","Cantidad":"1","CodDiagno":"' . $dx1 . '","DesDiagno":"-","ComPresta":"test","CUITProf":null}';

                        $prestaciones = $prestaciones . $linea . ',';
                    }
                    $prestaciones = $prestaciones . '],';

                    $practicas = str_replace(',]', ']', $prestaciones);

                    //----------------------------------------------------------

                    $ef->getObject($efector);
                    $email = $ef->email;
                    if ($email == '')
                        $email = 'foo@bar.com.ar';
                    //echo $ef->nrocuit;
                    //echo '<hr/>';

                    $cuit = str_replace('-', '', $obsocial->_parametro5); // CUIT de la Asociacion
                    $nombrec = $obsocial->_parametro6;

                    $compania = '"Compania":{"Nombre":"' . $nombrec . '","Cuit":' . $cuit . '}';

                    $afiliado = '"Afiliado":{"Id":0,"Apellido":"","Nombre":"","Sexo":"","EstadoCivil":"","FechaNacimiento":"' . $afiliado->getFechanac() . '","DocumentoNumero":"' . $afiliado->getNrodoc() . '","DocumentoTipo":"' . $afiliado->getTipo_doc() . '","EstadoRegimenAsistencial":"","NumeroAfiliado":""}';

                    $prestaciones = '"Prestaciones":[';
                    $l = 0;
                    for ($i = 0; $i <= 100; $i++) {

                        $codd = $_codigos[$i];

                        if (strlen($codd) < 4)
                            break;

                        $l++;

                        $linea = '{"Cantidad":"1","Codigo":"' . $codd . '","Autorizado":null,"Zona":"","Subzona":""}';

                        $prestaciones = $prestaciones . $linea . ',';
                    }
                    $prestaciones = $prestaciones . '],';

                    $practicas = str_replace(',]', ']', $prestaciones);

                    $prescriptor = '"Prescriptor":{"Apellido":"' . $medico->getNombre() . '","Nombre":"' . $medico->getNombre() . '","Especialidad":"PRO","Matricula":"' . $medico->getIdprof() . '"}';

                    $efector = '"Efector":{"PrestadorId":false,"Especialidad":"BIO","Matricula":"' . $ef->getCodigo() . '","Persona":{"PrestadorId":false,"Apellido":"' . $ef->getNombre() . '","Nombre":"' . $ef->getNombre() . '","Sexo":"M","EstadoCivil":"C","FechaNacimiento":"1980-08-05T00:00:00","DocumentoNumero":"' . $ef->nrocuit . '","DocumentoTipo":"DNI","DomicilioCalle":"XX","DomicilioNumero":345,"CUILCUIT":"' . $ef->nrocuit . '","Localidad":"XX","Departamento":"XX","Telefono":"0000","Celular":"00","Mail":"' . $email . '","CodPostal": 3560}}}';

                    $data = trim('{"PrestadorId":false,"Numero":0,"Diagnostico":"' . $dx->getoms_cod() . '","Observacion":"' . $dx->getDescrip() . '","Estado":"","Fecha":"2009-05-31","Tipo":"BIO",' . $compania . ',' . $afiliado . ',' . $practicas . $prescriptor . ',' . $efector);

                    // Envoltura
                    $metodo = 'orden';

                    $turl = $obsocial->_parametro7 . '/Orden';
                    $parameters = '{"url":"' . $turl . '","metodo":"","api":"Authorization","apikey":"' . $obsocial->_parametro1 . '","username":"' . $usuario . '","password":"' . $pass . '"}';

                    $json_data = '{"parameters":' . $parameters . ',' .
                            '"orden":' . $data . '}';

                    $context = stream_context_create(array(
                        'http' => array(
                            'protocol_version' => 1.1,
                            'user_agent' => 'PHPExample',
                            "Cookie => foo=bar\r\n",
                            'method' => 'PUT',
                            'header' => "Content-type: application/json\r\n" .
                            "Connection: close\r\n" .
                            "Content-length: " . strlen($json_data) . "\r\n",
                            'content' => $json_data,
                            'Expect' => '100-continue'
                        ),
                    ));

                    $theurl = $url . $metodo;

                    $file = file_get_contents($theurl, false, $context); //, -1, $l);

                    $result = json_decode($file);

                    //echo $file;
                    //$auditoria->delete($nroauditoria);
                    //exit;

                    if ($result->Numero == '' || $result->Numero == 0) {
                        $auditoria->borrarOrden($nroauditoria);
                        echo '<h1>Se Produjo un Error al Autorizar la Orden</h1>';
                        echo '<h2>Vuelva a Ingresarla</h2>';
                        return;
                        exit;
                    }

                    for ($p = 0; $p < count($result->Prestaciones); $p++) {
                        $_es = 'R';
                        if ($result->Prestaciones[$p]->Autorizado == 'S')
                            $_es = 'A';
                        $__c = $result->Prestaciones[$p]->Codigo;
                        $auditoria->actualizarDeterminacion($nroauditoria, $__c, $_es);
                    }

                    $diferida = 'S';

                    if ($result->Estado == 'APROBADA')  // VER !!!
                        $diferida = 'N';

                    $auditoria->actualizarTransaccionAutorizada($nroauditoria, '', $diferida, $result->Numero);

                    $s = print_r($result, true);
                    $wsres->crear1($codos, $ef->getCodigo(), $ns, $file, 2, $result->Numero);

                    print_r($result);
                    echo '<hr/>';
                    if ($result->Estado == 'RECHAZADA')
                        echo '<h1>ORDEN RECHAZADA</h1>';
                    if ($result->Estado == 'PENDIENTE')
                        echo '<h1>ORDEN PENDIENTE</h1><h2>Deberá chequear el estado de la misma, desde Validar, con hasta 48hs de Posterioridad</h2>';
                    if ($result->Estado == 'ANULADA') {
                        echo '<h1>ORDEN ANULADA</h1>';
                        $auditoria->AnularOrden($nroauditoria);
                    }
                    if ($result->Estado != 'APROBADA')
                        echo '<hr/>';
                } catch (Exception $e) {
                    trigger_error($e->getMessage(), E_USER_WARNING);
                    $auditoria->borrarOrden($nroauditoria);
                    echo '<h1>Se Produjo un Error al Autorizar la Orden</h1>';
                    echo '<h2>Vuelva a Ingresarla</h2>';
                }

                //==============================================================
            } else {
                echo '<br>';
                echo '<font color = "#FF0000">';
                echo "El Número de Documento $nrodoc No está en el Padrón.<br>";
                echo '</font>';
                echo "<p align='center'><h1>Orden Rechazada</h1></p>";
            }
        } else {
            echo '<br>';
            echo '<font color = "#FF0000">';
            echo "La Orden con el Número de Documento $nrodoc y el Diagnóstico $iddiag<br>";
            echo "en la Fecha $fecha1 ya fué Registrada.<br>";
            echo '</font>';
            echo "<p align='center'><h1>Orden Rechazada</h1></p>";
        }
    }

    exit;
}

//==============================================================================
// SWISS MEDICAL
if ($obsocial->_reglaNegocio == 9) {

//------------------------------------------------------------------------------
// Procesamiento de Ordenes que se autorizan directamente
// separamos los códigos
    // Generamos un número random de auditoria
    /*
      $rnro = rand(1, 999999999);
      if ($rnro < 0) $rnro = $rnro * (-1);
      $h = getdate();
      $hora = $h[hours]. $h[minutes] . $h[seconds];

      // Nuevo Número // Para los documentos demasiados extensos
      $nro = $nroauditoria;
      $nnro = substr($nro, 0, 14) . STR_PAD($rnro, 9, '0', STR_PAD_LEFT) . $hora;
      $nroauditoria = $nnro;
     * 
     */

    $linea = '';

    $c = '';
    $j = 0;
    $k = 0;
    $autoriza = true;

    for ($i = 0; $i < 500; $i++) {
        $c = substr($codigos, $j, 6);
        $j = $j + 6;
        if ($c == 0) {
            break;
        }

        $auditoria->detalle->AgregarItems($c);

        $_codigos[$k] = $c;
        $k++;
    }

    if (strlen($obj->getParametro4()) > 0 and $obsocial->getIncluye_ab() == 'S') {
        $auditoria->detalle->AgregarItems($obj->getParametro4());
        $_codigos[$k] = $obj->getParametro4();
        $k++;
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
    // sin todos los controles son  ok, registramos la orden

    if ($sin_errores) {
        if ($auditoria->VerificarOrdenPaciente($nrodoc, $fechaing, $iddiag)) {
            if ($afiliado->getObject($codos, $nrodoc)) {

                $aut = false;
                // capturamos el resultado y agregamos los items con los datos de lo auditado
                $auditoria->crear($nroauditoria, $efector, $fechaing, $codos_reg, $idzona, $nrodoc, $idprof, $observacion, $iddiag, $idperfil, $fechapedido, $aut, $medicocab);

                //==============================================================

                try {

                    $url = $obsocial->_url;
                    $usuario = $obsocial->_user;
                    $pass = $obsocial->_pass;
                    $cuit = str_replace('-', '', $obsocial->_parametro5);

                    $metodo = 'orden';

                    $ns = $nrodoc;

                    $fecha = $utiles->getFechaAAAAMMDD($utiles->getFechaActual());

                    $turl = $obsocial->_parametro7 . '/v0/auth-login';
                    $metodoext = $obsocial->_parametro7 . '/v1.0/prestadores/hl7/registracion';
                    $parameters = '{"url":"' . $turl . '","metodo":"' . $metodoext . '","api":"Authorization","apikey":"' . $obsocial->_parametro1 . '","username":"' . $usuario . '","password":"' . $pass . '"}';

                    $data = '{
                        "apiKey": "' . $obsocial->_parametro1 . '",
                        "usrLoginName": "' . $usuario . '",
                        "password":  "' . $pass . '",
                        "cuit": "' . $cuit . '",
                        "device": {
                            "messagingid" : "132H12312",
                            "deviceid": "192.168.45.77",
                            "devicename": "DELL-2Y0-DG",
                            "bloqueado": 0,
                            "recordar": 0
                        }
                    }';

                    $matricula = $efector;
                    if ($ef->parametro1 != '')
                        $matricula = $ef->parametro1;
                    
                    $cuitEfector = str_replace('-', '', $ef->getNrocuit());

                    $practicas = '';
                    $l = 0;
                    for ($i = 0; $i <= 100; $i++) {
                        $codd = $_codigos[$i];
                        if (strlen($codd) < 4)
                            break;

                        $l++;

                        $practicas = $practicas . '*' . $codd . '*1**|';
                    }

                    $practicas = substr($practicas, 0, -1);

                    $practicas = $l . '^' . $practicas;                    
                    
                    /*
                    $orden = '{
                        "creden": "' . $ns . '",   
                        "alta": "' . $fecha . '",    
                        "fecdif" : null,
                        "manual" : "0" ,
                        "ticketExt" : 0 ,
                        "interNro" : 2,
                        "cuit" : null, 
                        "autoriz" : 0 ,
                        "rechaExt" : 0,
                        "param1": "' . $practicas . '",        
                        "param2" : " ",
                        "param3" : " ",
                        "idEfector": "' . $matricula . '",    
                        "idPrescr": "' . $efector . '"           
                    }';                      
                    */
                    
                    $tf = 'MNMS';
                    
                    $orden = '{
                        "creden": "' . $ns . '",   
                        "alta": "' . $fecha . '",    
                        "fecdif" : null,
                        "manual" : "0" ,
                        "ticketExt" : 0 ,
                        "interNro" : 2,
                        "cuit" : null, 
                        "autoriz" : 0 ,
                        "rechaExt" : 0,
                        "param1": "' . $practicas . '",        
                        "param2" : " ",
                        "param3" : " ",                        
                        "tipoEfector" : "' . $tf . '",
                        "idEfector": "' . $matricula . '",   
                        "tipoPrescr" : "' . $tf . '",     
                        "idPrescr": "' . $efector . '"           
                    }';           

                    //$metodoext = $obsocial->_parametro7 . '/v1.0/prestadores/hl7/registracion';
                    //$parameters = '{"url":"' . $turl . '","metodo":"' . $metodoext . '","api":"Authorization","apikey":"' . $obsocial->_parametro1 . '","username":"' . $usuario . '","password":"' . $pass . '"}';

                    $json_data = '{"parameters":' . $parameters . ',' .
                            '"orden":' . $orden . ',' .
                            '"token":' . $data . '}';

                    //echo $json_data . '<hr/>';
                    
                    $theurl = $url . $metodo;
                    //echo $json_data;
                    $context = stream_context_create(array(
                        'http' => array(
                            'protocol_version' => 1.1,
                            'user_agent' => 'PHPExample',
                            "Cookie => foo=bar\r\n",
                            'method' => 'PUT',
                            'header' => "Content-type: application/json\r\n" .
                            "Connection: close\r\n" .
                            "Content-length: " . strlen($json_data) . "\r\n",
                            'content' => $json_data,
                            'Expect' => '100-continue'
                        ),
                    ));

                    $file = file_get_contents($theurl, false, $context);

                    $result = json_decode($file);

                    //==========================================================
                    // Verificamos si requiere TOKEN
                    if ($result->cabecera->rechaCabeDeno == 'Requiere Token') {

                        $wsres->crear1($codos, $ef->getCodigo(), $ns, $orden, 0, $nroauditoria);

                        //$auditoria->borrarOrden($nroauditoria);

                        echo 'REQUIERE TOKEN';

                        return false;
                    }

                    //==========================================================

                    $it = 0;
                    for ($p = 0; $p < count($result->detalle); $p++) {
                        $_es = 'R';
                        if ($result->detalle[$p]->recha == 0)
                            $_es = 'A';

                        $it++;
                        $item = $utiles->LlenarIzquierda($it, 3, '0');

                        $auditoria->actualizarDeterminacionPorItem($nroauditoria, $item, $_es, $result->detalle[$p]->transac);
                    }

                    $diferida = 'N';

                    $auditoria->actualizarTransaccionAutorizada($nroauditoria, '', $diferida, $result->cabecera->transac);

                    $s = print_r($result, true);
                    $wsres->crear1($codos, $ef->getCodigo(), $ns, $file, 2, $result->cabecera->transac);

                    print_r($result);

                    // Si la transacción falla
                    if ($result->status == 500) {
                        $auditoria->borrarOrden($nroauditoria);
                        echo '<h1>Se Produjo un Error al Autorizar la Orden</h1>';
                        echo '<h2>Vuelva a Ingresarla</h2>';
                    }

                    echo '<hr/>';
                } catch (Exception $e) {
                    trigger_error($e->getMessage(), E_USER_WARNING);
                    $auditoria->borrarOrden($nroauditoria);
                    echo '<h1>Se Produjo un Error al Autorizar la Orden</h1>';
                    echo '<h2>Vuelva a Ingresarla</h2>';
                }

                //==============================================================
            } else {
                echo '<br>';
                echo '<font color = "#FF0000">';
                echo "El Número de Documento $nrodoc No está en el Padrón.<br>";
                echo '</font>';
                echo "<p align='center'><h1>Orden Rechazada</h1></p>";
            }
        } else {
            echo '<br>';
            echo '<font color = "#FF0000">';
            echo "La Orden con el Número de Documento $nrodoc y el Diagnóstico $iddiag<br>";
            echo "en la Fecha $fecha1 ya fué Registrada.<br>";
            echo '</font>';
            echo "<p align='center'><h1>Orden Rechazada</h1></p>";
        }
    }

    exit;
}

//==============================================================================
// PREVENCIÓN SALUD
if ($obsocial->_reglaNegocio == 12) {

    $linea = '';

    $c = '';
    $j = 0;
    $k = 0;
    $autoriza = true;

    for ($i = 0; $i < 500; $i++) {
        $c = substr($codigos, $j, 6);
        $j = $j + 6;
        if ($c == 0) {
            break;
        }

        $auditoria->detalle->AgregarItems($c);

        $_codigos[$k] = $c;
        $k++;
    }

    if (strlen($obj->getParametro4()) > 0 and $obsocial->getIncluye_ab() == 'S') {
        $auditoria->detalle->AgregarItems($obj->getParametro4());
        $_codigos[$k] = $obj->getParametro4();
        $k++;
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
    // sin todos los controles son  ok, registramos la orden

    if ($sin_errores) {
        if ($auditoria->VerificarOrdenPaciente($nrodoc, $fechaing, $iddiag)) {
            if ($afiliado->getObject($codos, $nrodoc)) {

                $aut = false;
                // capturamos el resultado y agregamos los items con los datos de lo auditado
                $auditoria->crear($nroauditoria, $efector, $fechaing, $codos_reg, $idzona, $nrodoc, $idprof, $observacion, $iddiag, $idperfil, $fechapedido, $aut, $medicocab);

                //==============================================================

                try {

                    $url = $obsocial->_url . '/orden';
                    $usuario = $obsocial->_user;
                    $clave = $obsocial->_pass;
                    $prestador = str_replace('-', '', $obsocial->_parametro5);
                    $sitioemisor = $obsocial->_parametro1;

                    $fecha = $utiles->getFechaAAAAMMDD($utiles->getFechaActual());

                    $practicas = '';
                    $l = 0;
                    for ($i = 0; $i <= 100; $i++) {
                        $codd = $_codigos[$i];
                        if (strlen($codd) < 4)
                            break;

                        $l++;

                        $practicas = $practicas . '{"codigo":"' . $codd . '", "items":' . $l . '},';
                    }

                    $practicas = substr($practicas, 0, -1);

                    $prestaciones = $prestaciones . '[' . substr($practicas, 0, strlen($practicas) - 1) . '}]';

                    $json_data = '{"pUser":"' . $usuario . '", "pPwd":"' . $clave . '", "IdentificadorAfiliado":"' . $nrodoc . '", "IDPrestador":"' . $prestador . '", "parametro1":"' . $sitioemisor . '", "iddiag": "' . $iddiag . '", "diagnostico": "' . $iddiag . '", "parametro2": "' . $ttoken . '", "practicas":' . $prestaciones . '}';

                    //echo $json_data;
                    //exit;

                    $context = stream_context_create(array(
                        'http' => array(
                            'protocol_version' => 1.1,
                            'user_agent' => 'PHPExample',
                            "Cookie => foo=bar\r\n",
                            'method' => 'PUT',
                            'header' => "Content-type: application/json\r\n" .
                            "Connection: close\r\n" .
                            "Content-length: " . strlen($json_data) . "\r\n",
                            'content' => $json_data,
                            'Expect' => '100-continue'
                        ),
                    ));

                    $file = file_get_contents($url, false, $context);

                    $result = json_decode($file);

                    echo $file;

                    //==========================================================                    
                    if ($result->Estado == 'OK') {

                        $it = 0;
                        for ($p = 0; $p < count($result->practicas); $p++) {
                            $_es = 'R';
                            if ($result->practicas[$p]->estado == 'A')
                                $_es = 'A';

                            $it++;
                            $item = $utiles->LlenarIzquierda($it, 3, '0');

                            $auditoria->actualizarDeterminacionPorItem($nroauditoria, $item, $_es, $result->practicas[$p]->transaccion);
                        }

                        $diferida = 'N';

                        //$auditoria->actualizarTransaccionAutorizada($nroauditoria, '', $diferida, $result->transaccion);
                        $auditoria->actualizarTransaccionAutorizadaToken($nroauditoria, '', $diferida, $result->transaccion, $ttoken);

                        $s = print_r($result, true);
                        $wsres->crear1($codos, $ef->getCodigo(), $s, $file, 2, $result->transaccion);

                        print_r($result);
                    }

                    // Si la transacción falla
                    if ($result->Estado != 'OK') {
                        //$auditoria->borrarOrden($nroauditoria);
                        print_r($result);
                        $auditoria->AnularOrden($nroauditoria);
                        echo '<h1>Se Produjo un Error al Autorizar la Orden</h1>';
                        echo '<h2>ANULADA</h2>';
                    }

                    echo '<hr/>';
                } catch (Exception $e) {
                    trigger_error($e->getMessage(), E_USER_WARNING);
                    $auditoria->borrarOrden($nroauditoria);
                    echo '<h1>Se Produjo un Error al Autorizar la Orden</h1>';
                    echo '<h2>Vuelva a Ingresarla</h2>';
                }

                //==============================================================
            } else {
                echo '<br>';
                echo '<font color = "#FF0000">';
                echo "El Número de Documento $nrodoc No está en el Padrón.<br>";
                echo '</font>';
                echo "<p align='center'><h1>Orden Rechazada</h1></p>";
            }
        } else {
            echo '<br>';
            echo '<font color = "#FF0000">';
            echo "La Orden con el Número de Documento $nrodoc y el Diagnóstico $iddiag<br>";
            echo "en la Fecha $fecha1 ya fué Registrada.<br>";
            echo '</font>';
            echo "<p align='center'><h1>Orden Rechazada</h1></p>";
        }
    }

    exit;
}

//==============================================================================
// MEDIFE
if ($obsocial->_reglaNegocio == 14) {

    $linea = '';

    $c = '';
    $j = 0;
    $k = 0;
    $autoriza = true;

    for ($i = 0; $i < 500; $i++) {
        $c = substr($codigos, $j, 6);
        $j = $j + 6;
        if ($c == 0) {
            break;
        }

        $auditoria->detalle->AgregarItems($c);

        $_codigos[$k] = $c;
        $k++;
    }

    if (strlen($obj->getParametro4()) > 0 and $obsocial->getIncluye_ab() == 'S') {
        $auditoria->detalle->AgregarItems($obj->getParametro4());
        $_codigos[$k] = $obj->getParametro4();
        $k++;
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
    // sin todos los controles son  ok, registramos la orden

    if ($sin_errores) {
        if ($auditoria->VerificarOrdenPaciente($nrodoc, $fechaing, $iddiag)) {
            if ($afiliado->getObject($codos, $nrodoc)) {

                $aut = false;
                // capturamos el resultado y agregamos los items con los datos de lo auditado
                $auditoria->crear($nroauditoria, $efector, $fechaing, $codos_reg, $idzona, $nrodoc, $idprof, $observacion, $iddiag, $idperfil, $fechapedido, $aut, $medicocab);

                //==============================================================

                try {

                    $url = $obsocial->_url . '/orden';
                    $usuario = $obsocial->_user;
                    $clave = $obsocial->_pass;
                    $prestador = str_replace('-', '', $obsocial->_parametro5);
                    $sitioemisor = $obsocial->_parametro1;

                    $fecha = $utiles->getFechaAAAAMMDD($utiles->getFechaActual());

                    $practicas = '';
                    $l = 0;
                    for ($i = 0; $i <= 100; $i++) {
                        $codd = $_codigos[$i];
                        if (strlen($codd) < 4)
                            break;

                        $l++;

                        // Equivalencia                        
                        $prefijo = '0';
                        $cod_eq = $equivalencia->getCodigoEquivalente($codos, $codd);
                        if ($cod_eq != '') {
                            $codd = $cod_eq;
                            $prefijo = $equivalencia->prefijo;
                        }


                        $practicas = $practicas . '{"codigo":"' . $codd . '", "items":' . $l . ', "estado":"' . $prefijo . '"},';
                    }

                    $practicas = substr($practicas, 0, -1);

                    $prestaciones = $prestaciones . '[' . substr($practicas, 0, strlen($practicas) - 1) . '}]';

                    $json_data = '{"pUser":"' . $usuario . '", "pPwd":"' . $clave . '", "IdentificadorAfiliado":"' . $nrodoc . '", "IDPrestador":"' . $prestador . '", "parametro1":"' . $sitioemisor . '", "iddiag": "' . $iddiag . '", "diagnostico": "' . $iddiag . '", "practicas":' . $prestaciones . '}';

                    //echo $json_data;
                    //return;

                    $context = stream_context_create(array(
                        'http' => array(
                            'protocol_version' => 1.1,
                            'user_agent' => 'PHPExample',
                            "Cookie => foo=bar\r\n",
                            'method' => 'PUT',
                            'header' => "Content-type: application/json\r\n" .
                            "Connection: close\r\n" .
                            "Content-length: " . strlen($json_data) . "\r\n",
                            'content' => $json_data,
                            'Expect' => '100-continue'
                        ),
                    ));

                    $file = file_get_contents($url, false, $context);

                    $result = json_decode($file);

                    echo $file;

                    //==========================================================                    
                    if ($result->Estado == 'OK') {

                        $it = 0;
                        for ($p = 0; $p < count($result->practicas); $p++) {
                            $_es = 'R';
                            if ($result->practicas[$p]->estado == 'A')
                                $_es = 'A';

                            $it++;
                            $item = $utiles->LlenarIzquierda($it, 3, '0');

                            //$auditoria->actualizarDeterminacionPorItem($nroauditoria, $item, $_es, $result->practicas[$p]->transaccion);

                            $auditoria->actualizarDeterminacionPorCodigo($nroauditoria, $result->practicas[$p]->codigo, $_es, $result->practicas[$p]->transaccion);

                            /*
                              $__cod = $equivalencia->getCodigoInverso($codos, $result->practicas[$p]->codigo);  // 11/07/2023, verifica autorización por equivalencia
                              if ($__cod != '') {
                              $auditoria->actualizarDeterminacionPorCodigo($nroauditoria, $__cod, $_es, $result->practicas[$p]->transaccion);
                              }
                             */

                            $__cod = $equivalencia->getCodigoInverso2C($codos, $result->practicas[$p]->codigo, $_codigos[$p]);  // 23/05/2025, verifica autorización por equivalencia, teniendo en cuenta que los codigos se pueden repetir
                            if ($__cod != '') {
                                $auditoria->actualizarDeterminacionPorCodigo($nroauditoria, $__cod, $_es, $result->practicas[$p]->transaccion);
                            }

                            //$auditoria->actualizarDeterminacionPorCodigo($nroauditoria, $result->practicas[$p]->codigo, $_es, $result->practicas[$p]->transaccion);
                        }

                        $diferida = 'N';

                        $auditoria->actualizarTransaccionAutorizada($nroauditoria, '', $diferida, $result->transaccion);

                        $s = print_r($result, true);
                        //$wsres->crear1($codos, $ef->getCodigo(), $s, $file, 2, $result->transaccion);
                        $wsres->crear1($codos, $efector, $nrodoc, $s, 4, $nroauditoria);

                        print_r($result);
                    }

                    // Si la transacción falla
                    if ($result->Estado != 'OK') {
                        $auditoria->borrarOrden($nroauditoria);
                        echo '<h1>Se Produjo un Error al Autorizar la Orden</h1>';
                        echo '<h2>Vuelva a Ingresarla</h2>';
                    }

                    echo '<hr/>';
                } catch (Exception $e) {
                    trigger_error($e->getMessage(), E_USER_WARNING);
                    $auditoria->borrarOrden($nroauditoria);
                    echo '<h1>Se Produjo un Error al Autorizar la Orden</h1>';
                    echo '<h2>Vuelva a Ingresarla</h2>';
                }

                //==============================================================
            } else {
                echo '<br>';
                echo '<font color = "#FF0000">';
                echo "El Número de Documento $nrodoc No está en el Padrón.<br>";
                echo '</font>';
                echo "<p align='center'><h1>Orden Rechazada</h1></p>";
            }
        } else {
            echo '<br>';
            echo '<font color = "#FF0000">';
            echo "La Orden con el Número de Documento $nrodoc y el Diagnóstico $iddiag<br>";
            echo "en la Fecha $fecha1 ya fué Registrada.<br>";
            echo '</font>';
            echo "<p align='center'><h1>Orden Rechazada</h1></p>";
        }
    }

    exit;
}
?>