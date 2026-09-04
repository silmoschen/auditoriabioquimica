<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cAuditoria.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cObSocial.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cMedicos.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cUtiles.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cWsRespuestas.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cEfector.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cAfiliados.php");

$wsres = new cWsRespuestas();
$utiles = new cUtiles();

$nroauditoria = $_REQUEST['nrotrans'];

$auditoria = new cAuditoria;
$obsocial = new cObSocial();
$medico = new cMedicos();
$efector = new cEfector();
$afiliado = new cAfiliados();

$auditoria->getObject($nroauditoria);
$efector->getObject($auditoria->getEfector());
$codos = $auditoria->codos;
$obsocial->getObject($codos);
$obsocial->verificarRPC($codos);

if ($obsocial->_rpc == false || $obsocial->_parametro1 == 'padrononly' || $obsocial->_parametro9 == 'padrononly') {

    $auditoria->AnularOrden($nroauditoria);
    sleep(2);

    return;
} else {

    if ($obsocial->_reglaNegocio == 5) {  //    SANCOR - v2
        try {

            $url = $obsocial->_url . 'anular';
            $prestador = str_replace('-', '', $obsocial->_parametro7);
            $parametro1 = str_replace('-', '', $efector->getNrocuit());

            $fecha = $utiles->getFechaAAAAMMDD($utiles->getFechaActual());

            $auditoria->getObject($nroauditoria);

            $transaccion = $auditoria->nroautorizacion;
            $nrodoc = $auditoria->getNrodoc();

            $anulada = false;

            $json_data = '{"IdentificadorAfiliado":"' . $nrodoc . '", "IDPrestador":"' . $prestador . '", "parametro1":"' . $parametro1 . '", "transaccion": "' . $transaccion . '"' . '}';

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

            echo $json_data;

            $post = file_get_contents($url, false, $context); //, -1, $l);

            $response = json_decode($post);

            if ($response->Estado == 'OK') {
                $auditoria->AnularOrden($nroauditoria);
            }

            echo $post;
        } catch (Exception $e) {
            trigger_error($e->getMessage(), E_USER_WARNING);
        }

        return;
    }

    //==========================================================================

    if ($obsocial->_reglaNegocio == 1) {  // AVALIAN
        $auditoria->getObject($nroauditoria);

        date_default_timezone_set('America/Argentina/Ushuaia');
        $datetime = new DateTime();

        $url = $obsocial->_url;
        $prefijo = $obsocial->_parametro2;
        $prefijoinverso = $obsocial->_parametro3;

        $transaccion = $auditoria->transaccion;

        $medico->getObject($auditoria->getCodos(), $auditoria->getIdprof());

        $matricula_medico = $medico->getMatricula() . '/011' . $utiles->LlenarIzquierda($medico->getLibro(), 2, '0') . $utiles->LlenarIzquierda($medico->getFolio(), 3, '0');

        $rpc_xml = '                             
            <SOLICITUD>
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
            <TIPO>AAP</TIPO>
            <FECHA>' . $datetime->format('Y-m-d') . '</FECHA>
            <IDASEG>' . $obsocial->_parametro7 . '</IDASEG>
            <IDPRESTADOR>' . $obsocial->_user . '</IDPRESTADOR>
            <IDANUL>' . $transaccion . '</IDANUL>
            <ACID>REVERSO</ACID>    
            </OPER>
            <PID>
            <ID>' . $auditoria->getNrodoc() . '</ID>
            </PID>

            <PRESCRIP>
             <ORG>MP S</ORG>
             <MAT>' . $matricula_medico . '</MAT>
             <FECHA>' . $datetime->format('Y-m-d') . '</FECHA>
            </PRESCRIP>

            <CONTEXTO>
            <TIPO>A</TIPO>
            </CONTEXTO>

            </SOLICITUD>        
            ';

        $json_data = '{"nrodoc":"' . $nrodoc . '", "xmlrpc":"' . $rpc_xml . '", "url":"' . $obsocial->_parametro1 . '"}';

        $url = $obsocial->_url . 'anular';
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

        $post = file_get_contents($url, false, $context);

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

            if ($lector->RSPMSGG == 'ANULACION APROBADA') {
                $auditoria->AnularOrden($nroauditoria);

                $tex = $lector->IDTRAN;

                echo 'Orden Anulada. Transacción Externa: ' . $tex;

                $s = print_r($lector, true);
                $wsres->crear1($codos, $auditoria->getEfector(), $auditoria->getNrodoc(), $s, 10, $tex);
            } else {
                echo $resultados;
                echo '<br>';
                echo '<font color = "#FF0000">';
                echo "La Transaccion " . $lector->IDTRAN . " Ha Sido Rechazada por el Servidor. Ingrese Nuevamente la Orden<br>";
                echo '</font>';
                echo "<p align='center'><h1>Orden Rechazada</h1></p>";
            }

            sleep(2);
            return;
        }
    }

    if ($obsocial->_reglaNegocio == -1) {  // ACA SALUD
        $auditoria->getObject($nroauditoria);

        date_default_timezone_set('America/Argentina/Ushuaia');
        $datetime = new DateTime();

        $url = $obsocial->_url;
        //$options["connection_timeout"] = 25;
        //$options["location"] = $url;
        //$options['trace'] = 1;
        $prefijo = $obsocial->_parametro2;
        $prefijoinverso = $obsocial->_parametro3;

        $transaccion = $auditoria->transaccion;

        $medico->getObject($auditoria->getCodos(), $auditoria->getIdprof());

        $matricula_medico = $medico->getMatricula() . '/011' . $utiles->LlenarIzquierda($medico->getLibro(), 2, '0') . $utiles->LlenarIzquierda($medico->getFolio(), 3, '0');

        $rpc_xml = '                             
            <SOLICITUD>
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
            <TIPO>AAP</TIPO>
            <FECHA>' . $datetime->format('Y-m-d') . '</FECHA>
            <IDASEG>' . $obsocial->_parametro7 . '</IDASEG>
            <IDPRESTADOR>' . $obsocial->_user . '</IDPRESTADOR>
            <IDANUL>' . $transaccion . '</IDANUL>
            <ACID>REVERSO</ACID>    
            </OPER>
            <PID>
            <ID>' . $auditoria->getNrodoc() . '</ID>
            </PID>

            <PRESCRIP>
             <ORG>MP S</ORG>
             <MAT>' . $matricula_medico . '</MAT>
             <FECHA>' . $datetime->format('Y-m-d') . '</FECHA>
            </PRESCRIP>

            <CONTEXTO>
            <TIPO>A</TIPO>
            </CONTEXTO>

            </SOLICITUD>        
            ';

        $url = $os->_url;
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

        $resultados = $client->transaccionStr($rpc_xml);

        $lector = new SimpleXMLElement($resultados);

        if ($lector->RSPMSGG == 'ANULACION APROBADA') {
            $auditoria->AnularOrden($nroauditoria);

            $tex = $lector->IDTRAN;

            echo 'Orden Anulada. Transacción Externa: ' . $tex;

            $wsres->crear1($codos, $auditoria->getEfector(), $auditoria->getNrodoc(), $resultados, 10, $tex);
        } else {
            echo $resultados;
            echo '<br>';
            echo '<font color = "#FF0000">';
            echo "La Transaccion " . $lector->IDTRAN . " Ha Sido Rechazada por el Servidor. Ingrese Nuevamente la Orden<br>";
            echo '</font>';
            echo "<p align='center'><h1>Orden Rechazada</h1></p>";
        }

        sleep(2);
        return;
    }

    //==========================================================================
    if ($obsocial->_reglaNegocio == 2) {  // OSDE
        $fecha = $utiles->getFechaActual();
        $ftrans = $utiles->getFechaAAAAMMDD($fecha);
        $htrans = date('H:m:s');
        $htrans = str_replace(':', '', $htrans);
        $version = $obsocial->_parametro5;
        $codigofinanciador = '11';
        $cuitprestador = $obsocial->_parametro4;
        $cuitentidad = '30546741253';
        $fechaAtencion = $utiles->getFechaAAAAMMDD($auditoria->getFecha());

        date_default_timezone_set('America/Argentina/Ushuaia');
        $datetime = new DateTime();

        $afiliado->getObject($auditoria->getCodos(), $auditoria->getNrodoc());

        $efector->getObject($auditoria->getEfector());

        $codigofinanciador = $efector->parametro2;
        $terminal = $efector->parametro3;
        $cuitentidad = str_replace('-', '', $obsocial->_parametro4);
        $cuitprestador = str_replace('-', '', $efector->getNrocuit());

        $idmsg = rand(1, 9999);

        $track = '';
        $versioncredencial = $afiliado->getIdOS();  // track
        
        $spp = ''; // Para Efectores con varios puntos de atención    
        if ($efector->getNivel2()) $spp = $terminal;

        $msg = "<Mensaje>
                <EncabezadoMensaje>
                        <VersionMsj>" . $version . "</VersionMsj>
                        <NroReferenciaCancel>" . $auditoria->transaccion . "</NroReferenciaCancel>
                        <TipoTransaccion>04A</TipoTransaccion>
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
                        </Prestador>
                </EncabezadoMensaje>
                <EncabezadoAtencion>
                        <Efector/>
                        <Prescriptor/>
                        <Credencial>
                                <NumeroCredencial>" . $auditoria->getNrodoc() . "</NumeroCredencial>
                                <Track>" . $track . "</Track> 
                                <VersionCredencial>" . $versioncredencial . "</VersionCredencial>  
                        </Credencial>
                        <Atencion>
                                <FechaAtencion>" . $fechaAtencion . "</FechaAtencion>
                        </Atencion>
                </EncabezadoAtencion>
                </Mensaje>";

        $s = print_r($msg, true);
        $wsres->crear($codos, $auditoria->getEfector(), $nrodoc, $s, 9);

        $msg = str_replace('<', '%3C', $msg);
        $msg = str_replace('>', '%3E', $msg);
        $msg = str_replace(' ', '', $msg);
        $msg = preg_replace('[\s+]', '', $msg);

        $url = $obsocial->_url . $msg; // url RPC Servlet  

        $url_content = '';
        $file = @fopen($url, 'r');
        if ($file) {                // Parseamos la respuesta
            while (!feof($file)) {
                $url_content .= @fgets($file, 4096);
            }
            fclose($file);

            $lector = new SimpleXMLElement($url_content);

            if (substr($lector->EncabezadoMensaje->Rta->MensajeDisplay, 0, 2) == 'OK') {
                $transaccion = $lector->EncabezadoMensaje->NroReferencia;

                $auditoria->actualizarTransaccionAnulada($nroauditoria, $transaccion);
            }

            echo $url_content . '<br/>';

            sleep(2);

            return;
        }
    }

    if ($obsocial->_reglaNegocio == 3) {  // JERARQUICO
        $auditoria->AnularOrden($nroauditoria);
        sleep(2);

        return;
    }

    //==========================================================================
    if ($obsocial->_reglaNegocio == 4) {  // AMUR
        // Envoltura RPC
        // Anulación
        $url = $obsocial->_url;
        $usuario = $obsocial->_user;
        $pass = $obsocial->_pass;
        $tipo = 'T';
        $naut = str_replace("WS", "", $auditoria->transaccion);
        $faut = $utiles->getFechaAAAA_MM_DD($auditoria->getFecha());

        $client = new SoapClient($url, array('cache_wsdl' => WSDL_CACHE_NONE, 'trace' => TRUE));

        $result = $client->__soapCall('fnanular', array('usuario' => $usuario, 'pass' => $pass, 'naut' => $naut, 'faut' => $faut, 'tipo' => $tipo));

        if ($result->estado == 'OK') {
            $auditoria->AnularOrden($nroauditoria);

            $s = print_r($result, true);
            $wsres->crear1($codos, $auditoria->getEfector(), $auditoria->getNrodoc(), $s, 10, '');
            echo 'DETALLE: ' . $result->estado . ': ' . $result->mensajes;
        } else {
            print_r($result);
            $s = print_r($result, true);
            $wsres->crear1($codos, $auditoria->getEfector(), $auditoria->getNrodoc(), $s, 10, '');
        }

        sleep(2);

        return;
    }

    //==========================================================================
    if ($obsocial->_reglaNegocio == 5) {  // SANCOR
        // Envoltura RPC
        // Anulación
        $url = $obsocial->_url;
        $usuario = $obsocial->_user;
        $clave = $obsocial->_pass;

        $modo = $obsocial->getParametro2();
        $tipoanulacion = '';
        $nroautorizacion = $auditoria->nroautorizacion;
        $entidad = $obsocial->_parametro1;

        $client = new SoapClient($url, array('cache_wsdl' => WSDL_CACHE_NONE, 'trace' => TRUE));

        $criterio = array(
            'Modo' => $modo,
            'Tipoanulacion' => $tipoanulacion,
            'Nroautorizacion' => $nroautorizacion,
            'Entidad' => $entidad,
            'Usuario' => $usuario,
            'Clave' => $clave);

        $result = $client->ANULACION($criterio);

        print_r($criterio);
        echo '<br/>';

        $s = print_r($result, true);

        if ($result->Codigorespuesta == 35) {
            $auditoria->AnularOrden($nroauditoria);

            $s = print_r($result, true);
            $wsres->crear1($codos, $auditoria->getEfector(), $auditoria->getNrodoc(), $s, 10, '');
            echo 'DETALLE: ' . $result->Descripcionrespuesta;
        } else {
            print_r($result);
            $s = print_r($result, true);
            $wsres->crear1($codos, $auditoria->getEfector(), $auditoria->getNrodoc(), $s, 10, '');
        }

        sleep(2);

        return;
    }

    //==========================================================================

    if ($obsocial->_reglaNegocio == 6) {  // FEDERADA
        try {

            $url = $obsocial->_url;
            $usuario = $obsocial->_user;
            $pass = $obsocial->_pass;
            $ns = $_REQUEST['nrodoc'];

            $metodo = 'anular';

            $parameters = '{"url":"' . $obsocial->_parametro7 . '/","metodo":"validador/' . $obsocial->_parametro8 . '/wsvolas1","api":"x-api-key","apikey":"' . $pass . '"}';

            $cuit = str_replace('-', '', $obsocial->_parametro5);
            //$cuit = '600627';

            $nroautorizacion = $auditoria->transaccion;

            $data = '{"p_Prestador":"' . $obsocial->_parametro3 . '",'
                    . '"p_SubPrestador":"' . $obsocial->_parametro4 . '",'
                    . '"p_SubPreCUIT":"' . $cuit . '",'
                    . '"p_NroSolicitud":"' . $nroautorizacion . '"}';

            //echo $data; echo '<hr/>';

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

            if ($array->o_Status == 'OK') {
                $auditoria->AnularOrden($nroauditoria);

                $s = print_r($array, true);
                $wsres->crear1($codos, $auditoria->getEfector(), $auditoria->getNrodoc(), $s, 10, '');
                echo 'DETALLE: ' . $array->o_Comentario;
            }
        } catch (Exception $e) {
            trigger_error($e->getMessage(), E_USER_WARNING);
        }
    }

    //==========================================================================

    if ($obsocial->_reglaNegocio == 8) {  // INGENIEROS
        try {

            $url = $obsocial->_url;
            $usuario = $obsocial->_user;
            $pass = $obsocial->_pass;

            $metodo = 'anularorden';

            //$auditoria->nroautorizacion = '1010387359';

            $turl = $obsocial->_parametro7 . '/Orden/Anular/' . $auditoria->nroautorizacion . '?cuit=' . $obsocial->_parametro5;
            $parameters = '{"url":"' . $turl . '","metodo":"","api":"Authorization","apikey":"' . $obsocial->_parametro1 . '","username":"' . $usuario . '","password":"' . $pass . '"}';

            $data = '{}';
            $json_data = '{"parameters":' . $parameters . ',' .
                    '"orden":' . $data . '}"';

            $theurl = $url . $metodo;

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

            $file = @file_get_contents($theurl, false, $context);
            var_dump($file);

            $array = json_decode($file);

            if ($array->Message == '') {
                $auditoria->AnularOrden($nroauditoria);

                $s = print_r($array, true);
                $wsres->crear1($codos, $auditoria->getEfector(), $auditoria->getNrodoc(), $s, 10, '');
                echo 'DETALLE: ' . ' Petición de Anulación Procesada Correctamente';
            } else {
                echo 'DETALLE: ' . $array->Message;
            }
        } catch (Exception $e) {
            trigger_error($e->getMessage(), E_USER_WARNING);
        }
    }

    //==========================================================================

    if ($obsocial->_reglaNegocio == 9) {  //    SWISS MEDICAL
        try {

            $url = $obsocial->_url;
            $usuario = $obsocial->_user;
            $pass = $obsocial->_pass;
            $cuit = str_replace('-', '', $obsocial->_parametro5);

            $metodo = 'anular';

            $anulada = false;

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

            $auditoria->getObject($nroauditoria);

            $tr = '';
            $ms = '';

            $resultado = $auditoria->getDeterminaciones($nroauditoria);
            while ($fila = mysql_fetch_array($resultado)) {

                $practica = $fila['codigo'];
                $creden = $auditoria->getNrodoc();
                $alta = $utiles->getFechaAAAAMMDD($auditoria->getFecha());
                $transac = $auditoria->nroautorizacion;

                if ($fila['items'] == '001') {   // Solo una vez mandamos la petición
                    $orden = '{"creden":"' . $creden . '",' . '"alta":"' . $alta . '",' . '"ticketExt":' . $transac . ',"cuit": null, "param1":"' . $practica . '"}';

                    $turl = $obsocial->_parametro7 . '/v0/auth-login';
                    $metodoext = $obsocial->_parametro7 . '/v1.1/prestadores/hl7/cancela-prestacion';
                    $parameters = '{"url":"' . $turl . '","metodo":"' . $metodoext . '","api":"Authorization","apikey":"' . $obsocial->_parametro1 . '","username":"' . $usuario . '","password":"' . $pass . '"}';

                    $json_data = '{"parameters":' . $parameters . ',' .
                            '"orden":' . $orden . ',' .
                            '"token":' . $data . '}';

                    $theurl = $url . $metodo;

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

                    $file = @file_get_contents($theurl, false, $context);
                    var_dump($file);

                    $array = json_decode($file);

                    $tr = $array->cabecera->transac;
                    $ms = $array->cabecera->rechaCabeDeno;

                    $s = print_r($array, true);
                    $wsres->crear1($codos, $auditoria->getEfector(), $auditoria->getNrodoc(), $s, 10, '');
                    $json_data = '';

                    $auditoria->actualizarDeterminacionPorItemAnulada($nroauditoria, $fila['items'], $tr);

                    echo '(' . $fila['codigo'] . ') ' . $tr . ' -> MESSAGE: ' . $ms . '<br/> ';

                    $anulada = true;

                    break;
                }

                //$auditoria->actualizarDeterminacionPorItemAnulada($nroauditoria, $fila['items'], $tr);                
                //echo '(' . $fila['codigo'] . ') ' . $tr . ' -> MESSAGE: ' . $ms . '<br/> ';
                //$anulada = true;
            }

            if ($anulada) {
                $auditoria->AnularOrden($nroauditoria);
                echo 'DETALLE: ' . ' Petición de Anulación Procesada Correctamente';
            }
        } catch (Exception $e) {
            trigger_error($e->getMessage(), E_USER_WARNING);
        }
    }

    //==========================================================================

    if ($obsocial->_reglaNegocio == 12) {  //    PREVENCIÓN SALUD
        try {

            $url = $obsocial->_url . '/anular';
            $usuario = $obsocial->_user;
            $clave = $obsocial->_pass;
            $prestador = str_replace('-', '', $obsocial->_parametro5);
            $sitioemisor = $obsocial->_parametro1;

            $fecha = $utiles->getFechaAAAAMMDD($utiles->getFechaActual());

            $auditoria->getObject($nroauditoria);

            $transaccion = $auditoria->nroautorizacion;
            $nrodoc = $auditoria->getNrodoc();

            $anulada = false;

            $json_data = '{"pUser":"' . $usuario . '", "pPwd":"' . $clave . '", "IdentificadorAfiliado":"' . $nrodoc . '", "IDPrestador":"' . $prestador . '", "parametro1":"' . $sitioemisor . '", "parametro2": "' . $transaccion . '"' . '}';

            //echo  $json_data;
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

            echo $json_data;

            $post = file_get_contents($url, false, $context); //, -1, $l);

            $response = json_decode($post);

            if ($response->Estado = 'OK') {
                $auditoria->AnularOrden($nroauditoria);
            }

            echo $post;
        } catch (Exception $e) {
            trigger_error($e->getMessage(), E_USER_WARNING);
        }
    }

    //==========================================================================

    if ($obsocial->_reglaNegocio == 14) {  //    MEDIFE
        try {

            $url = $obsocial->_url . '/anular';
            $usuario = $obsocial->_user;
            $clave = $obsocial->_pass;
            $prestador = str_replace('-', '', $obsocial->_parametro5);
            $sitioemisor = $obsocial->_parametro1;

            $fecha = $utiles->getFechaAAAAMMDD($utiles->getFechaActual());

            $auditoria->getObject($nroauditoria);

            $transaccion = $auditoria->nroautorizacion;
            $nrodoc = $auditoria->getNrodoc();

            $anulada = false;

            $json_data = '{"pUser":"' . $usuario . '", "pPwd":"' . $clave . '", "IdentificadorAfiliado":"' . $nrodoc . '", "IDPrestador":"' . $prestador . '", "parametro1":"' . $sitioemisor . '", "parametro2": "' . $transaccion . '"' . '}';

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

            echo $json_data;

            $post = file_get_contents($url, false, $context); //, -1, $l);

            $response = json_decode($post);

            if ($response->Estado = 'OK') {
                $auditoria->AnularOrden($nroauditoria);
            }

            echo $post;
        } catch (Exception $e) {
            trigger_error($e->getMessage(), E_USER_WARNING);
        }
    }
}
?>
