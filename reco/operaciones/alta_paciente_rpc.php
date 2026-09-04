<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cAfiliados.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cEquivalenciaPadrones.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cObSocial.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cUtiles.php");
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cEfector.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cWsRespuestas.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cEntidad.php");

$codos = $_REQUEST['codos'];
$nrodoc = $_REQUEST['nrodoc'];
$idprof = $_REQUEST['idprof'];
$ndoc = "[" . $_REQUEST['nrodoc'] . "]";
$track1 = $_REQUEST['track'];

$nrodoc = str_replace('-', '', $nrodoc);
$ndoc = $nrodoc;

$afiliado = new cAfiliados();

$utiles = new cUtiles;

$wsres = new cWsRespuestas();

$os = new cObsocial();
$os->getObject($codos);
$os->verificarRPC($codos);

$efector = new cEfector();
$efector->getObject($idprof);

$entidad = new cEntidad();
$entidad->getObject();

//==============================================================================
// AVALIAN - 30/11/2023
if ($os->_reglaNegocio == 1) {

    date_default_timezone_set('America/Argentina/Ushuaia');
    $datetime = new DateTime();

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
        <USRID>' . $os->_user . '</USRID>
        <USRPASS>' . $os->_pass . '</USRPASS>
        </SEGURIDAD>
        <OPER>
        <TIPO>ELG</TIPO>
        <FECHA>' . $datetime->format('Y-m-d') . '</FECHA>
        <IDASEG>' . $obsocial->_parametro7 . '</IDASEG>
        <IDPRESTADOR>' . $os->_user . '</IDPRESTADOR>
        </OPER>
        <PID>
        <ID>' . $nrodoc . '</ID>
        <VERIFID>MANUAL</VERIFID>
        </PID>

        <CONTEXTO>
        <TIPO>A</TIPO>
        </CONTEXTO>

        </SOLICITUD>';

    $json_data = '{"nrodoc":"' . $nrodoc . '", "xmlrpc":"' . $rpc_xml . '", "url":"' . $os->_parametro1 . '"}';

    $url = $os->_url . 'afiliado';
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

        $alta = "DarAltaPaciente(" . $_REQUEST['codos'] . "," . $_REQUEST['nrodoc'] . ")";

        $retiva = 'N';
        if ($lector->AFIAFIL == 'GRAVADO')
            $retiva = 'S';

        if ($lector->AFIAPE != '') {
            echo '  Paciente: ' . $nrodoc . ' - ' . substr($lector->AFIAPE . ' ' . $lector->AFINOM, 0, 19) . ' - Ret. IVA: ' . $retiva;

            // Lo damos de alta en nuestro padrón
            $afiliado = new cAfiliados();
            if ($afiliado->getObject($codos, $nrodoc) == false)
                $afiliado->crear2($codos, $nrodoc, strtoupper(substr($lector->AFIAPE . ' ' . $lector->AFINOM, 0, 38)), '', '', '', '', '', '', '', $lector->AFISEXO, '', $retiva, '');
            else
                $afiliado->actualizar2($codos, $nrodoc, substr($lector->AFIAPE . ' ' . $lector->AFINOM, 0, 40), '', '', '', '', '', '', $lector->AFISEXO, '', $retiva, '');

            $s = print_r($lector, true);
            $wsres->crear($codos, $idprof, $nrodoc, $s, 1);
        } else {
            if ($os->getAltaPaciente() != 'S') {
                echo '<p align="center">';
                echo "<FIELDSET>";
                echo "<LEGEND>Error en Datos del Afiliado</LEGEND>";
                echo '<p align="center">';
                print_r($resultados);
                echo '</br>';
                echo '<font color="#FF0000">';
                echo "<b>El Afiliado $ndoc está fuera de Padrón ó Inactivo.</b><br>Debe Enviar Fotocopia Carnet y ultimo Recibo de Haberes.";
                echo "</b><br>No es Posible Avazanzar con la Carga de la Orden.<br><br>";
                echo '<input type="button" name="ok" id="ok" class="button gray small" value="Regresar" onClick="CancelarAltaPaciente(); return false" />';
                echo '</p></font>';
                echo "</FIELDSET>";
                echo '</p>';
            }
        }
    } else {
        echo "Error al Validar Paciente";
    }
}

//==============================================================================
// IOSFA
if ($os->_reglaNegocio == 15) {

    $url = $os->_url . 'afiliado';
    $prestador = str_replace('-', '', $os->_parametro7);

    $json_data = '{"pPwd":"' . $os->_pass . '",' .
            '"parametro1":"' . $os->_parametro8 . '",' .
            '"Cuit":"' . $os->_parametro5 . '",' .
            '"Provincia":"' . $os->_parametro2 . '",' .
            '"Localidad":"' . $os->_parametro3 . '",' .
            '"UnidadDeNegocio":"' . $os->_parametro4 . '",' .
            '"EstacionDeTrabajo":"' . $os->_parametro7 . '",' .
            '"IdentificadorAfiliado":' . $nrodoc . ',' .
            '"SSL":' . "false" . "}";

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

    $post = file_get_contents($url, false, $context); //, -1, $l);

    $result = json_decode($post);

    $s = print_r($result, true);
    $wsres->crear($codos, $idprof, $nrodoc, $s, 1);

    $valido = true;

    if ($result->Response->Resultado == 'INDETERMINADO') {

        $n = $result->Response->Afiliados[0]->Nombre;
        $c = $result->Response->Afiliados[0]->Credencial;
    } else {

        if ($result->Response->Resultado != 'ACTIVO') {
            echo '*** ' . $result->Nombre . ' NO VÁLIDO ó HABILITADO ***';

            $valido = false;
        }

        $n = $result->Response->Afiliado;
        $c = $result->Response->Credencial;
    }

    if ($valido) {  // OK
        echo '  Paciente: ' . $nrodoc . ' - ' . $n;

        $retiva = 'N';

        // Lo damos de alta en nuestro padrón
        $afiliado = new cAfiliados();

        if ($afiliado->getObject($codos, $nrodoc) == false)
            $afiliado->crear2($codos, $nrodoc, substr($n, 0, 38), '', '', '', '', '', $c, '', 'N', '', $retiva, '');
        else
            $afiliado->actualizar2($codos, $nrodoc, substr($n, 0, 38), '', '', '', '', $c, '', 'N', '', $retiva, '');
    } else {

        if ($os->getAltaPaciente() != 'S') {
            echo '<p align="center">';
            echo "<FIELDSET>";
            echo "<LEGEND>Error en Datos del Afiliado</LEGEND>";
            echo '<p align="center">';
            echo '<font color="#FF0000">';
            echo "<b>El Afiliado Doc Nro. $nrodoc / Afiliado " . $result->Nombre . " está fuera de Padrón ó Inactivo.</b><br>Respuesta. " . $result->Mensaje . "<br/>";
            echo "</b><br>No es Posible Avazanzar con la Carga de la Orden.<br><br>";
            echo '<input type="button" name="ok" id="ok" class="button gray small" value="Regresar" onClick="CancelarAltaPaciente(); return false" />';
            echo '</p></font>';
            echo "</FIELDSET>";
            echo '</p>';
        }
    }

    return;
}

//==============================================================================
// SANCOR SALUD - v2
if ($os->_reglaNegocio == 5) {

    $url = $os->_url . 'afiliado';
    $prestador = str_replace('-', '', $os->_parametro7);

    $json_data = '{"IDPrestador":"' . $prestador . '", "IdentificadorAfiliado":"' . $nrodoc . '"}';

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

    $post = file_get_contents($url, false, $context); //, -1, $l);

    $result = json_decode($post);

    $s = print_r($result, true);
    $wsres->crear($codos, $idprof, $nrodoc, $s, 1);

    $valido = true;

    if ($result->Estado != 'OK') {
        echo '*** ' . $result->Nombre . ' NO VÁLIDO ó HABILITADO ***';

        $valido = false;
    }

    if ($valido) {  // OK
        echo '  Paciente: ' . $nrodoc . ' - ' . $result->Nombre;

        $retiva = $result->Retiva;

        // Lo damos de alta en nuestro padrón
        $afiliado = new cAfiliados();
        $n = $result->Nombre;
        if ($afiliado->getObject($codos, $nrodoc) == false)
            $afiliado->crear2($codos, $nrodoc, substr($n, 0, 38), '', '', '', '', '', '', '', 'N', '', $retiva, '');
        else
            $afiliado->actualizar2($codos, $nrodoc, substr($n, 0, 38), '', '', '', '', '', '', 'N', '', $retiva, '');
    } else {

        if ($os->getAltaPaciente() != 'S') {
            echo '<p align="center">';
            echo "<FIELDSET>";
            echo "<LEGEND>Error en Datos del Afiliado</LEGEND>";
            echo '<p align="center">';
            echo '<font color="#FF0000">';
            echo "<b>El Afiliado Doc Nro. $nrodoc / Afiliado " . $result->Nombre . " está fuera de Padrón ó Inactivo.</b><br>Respuesta. " . $result->Mensaje . "<br/>";
            echo "</b><br>No es Posible Avazanzar con la Carga de la Orden.<br><br>";
            echo '<input type="button" name="ok" id="ok" class="button gray small" value="Regresar" onClick="CancelarAltaPaciente(); return false" />';
            echo '</p></font>';
            echo "</FIELDSET>";
            echo '</p>';
        }
    }

    exit;
}

//==============================================================================
// MEDIFE
if ($os->_reglaNegocio == 14) {

    $url = $os->_url . '/afiliado';
    $usuario = $os->_user;
    $clave = $os->_pass;
    $prestador = str_replace('-', '', $os->_parametro5);
    $sitioemisor = $os->_parametro1;

    $json_data = '{"pUser":"' . $usuario . '", "pPwd":"' . $clave . '", "IdentificadorAfiliado":"' . $nrodoc . '", "IDPrestador":"' . $prestador . '", "parametro1":"' . $sitioemisor . '"}';

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

    $post = file_get_contents($url, false, $context); //, -1, $l);

    $result = json_decode($post);

    $s = print_r($result, true);
    $wsres->crear($codos, $idprof, $nrodoc, $s, 1);

    $valido = true;

    if ($result->Estado != 'OK') {
        echo '*** ' . $result->Nombre . ' NO VÁLIDO ó HABILITADO ***';

        $valido = false;
    }

    if ($valido) {  // OK
        echo '  Paciente: ' . $nrodoc . ' - ' . $result->Nombre;

        $retiva = $result->Retiva;

        // Lo damos de alta en nuestro padrón
        $afiliado = new cAfiliados();
        $n = $result->Nombre;
        if ($afiliado->getObject($codos, $nrodoc) == false)
            $afiliado->crear2($codos, $nrodoc, substr($n, 0, 38), '', '', '', '', '', '', '', 'N', '', $retiva, '');
        else
            $afiliado->actualizar2($codos, $nrodoc, substr($n, 0, 38), '', '', '', '', '', '', 'N', '', $retiva, '');
    } else {

        if ($os->getAltaPaciente() != 'S') {
            echo '<p align="center">';
            echo "<FIELDSET>";
            echo "<LEGEND>Error en Datos del Afiliado</LEGEND>";
            echo '<p align="center">';
            echo '<font color="#FF0000">';
            echo "<b>El Afiliado Doc Nro. $nrodoc / Afiliado " . $result->Nombre . " está fuera de Padrón ó Inactivo.</b><br>Respuesta. " . $result->Mensaje . "<br/>";
            echo "</b><br>No es Posible Avazanzar con la Carga de la Orden.<br><br>";
            echo '<input type="button" name="ok" id="ok" class="button gray small" value="Regresar" onClick="CancelarAltaPaciente(); return false" />';
            echo '</p></font>';
            echo "</FIELDSET>";
            echo '</p>';
        }
    }
}


//==============================================================================
// SWISS MEDICAL - COVID
if ($os->_reglaNegocio == 13) {

    $url = $os->_url;
    $usuario = $os->_user;
    $pass = $os->_pass;
    $cuit = str_replace('-', '', $os->_parametro5);
    $ns = $_REQUEST['nrodoc'];

    $metodo = 'afiliado';

    $fecha = $utiles->getFechaAAAAMMDD($utiles->getFechaActual());

    $turl = $os->_parametro7 . '/v0/auth-login';
    $metodoext = $os->_parametro7 . '/v1.0/prestadores/hl7/elegibilidad';
    $parameters = '{"url":"' . $turl . '","metodo":"' . $metodoext . '","api":"Authorization","apikey":"' . $os->_parametro1 . '","username":"' . $usuario . '","password":"' . $pass . '"}';

    $data = '{
        "apiKey": "' . $os->_parametro1 . '",
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

    $afiliado = '{
        "creden":"' . $ns . '",
        "alta":"' . $fecha . '",
        "fecdif":"' . $fecha . '"
        }';

    $json_data = '{"parameters":' . $parameters . ',' .
            '"afiliado":' . $afiliado . ',' .
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

    $result = json_decode($file);

    $s = print_r($result, true);
    $wsres->crear($codos, $idprof, $nrodoc, $file, 1);

    $valido = 'N';

    if ($result->status != 500) {
        $valido = 'N';
    }
    if ($result->apeNom == null)
        $valido = 'N';
    else
        $valido = 'S';

    if ($valido == 'N') {
        echo '*** ' . $ns . ' => ' . $result->status . ' ' . $result->message . ' NO VÁLIDO ó HABILITADO ***';
        return;
    }

    $retiva = 'N';

    if ($valido == 'S') {

        $nombre = $result->apeNom;
        echo '  Paciente: ' . $nombre;

        if ($result->gravado != '0')
            $retiva = 'S';

        // Lo damos de alta en nuestro padrón
        $afiliado = new cAfiliados();
        if ($afiliado->getObject($codos, $nrodoc) == false)
            $afiliado->crear2($codos, $nrodoc, substr($nombre, 0, 38), '', $result->edad, '', '', '', $result->icdDeno, '', $result->sexo, 'DNI', $retiva, '');
        else
            $afiliado->actualizar2($codos, $nrodoc, substr($nombre, 0, 38), '', $result->edad, '', $result->icdDeno, '', $result->sexo, 'DNI', 'N', $retiva, '');
    } else {

        if ($os->getAltaPaciente() != 'S') {
            echo '<p align="center">';
            echo "<FIELDSET>";
            echo "<LEGEND>Error en Datos del Afiliado</LEGEND>";
            echo '<p align="center">';
            echo '<font color="#FF0000">';
            echo "<b>El Afiliado Doc Nro. $ns / Afiliado " . $nd . " está fuera de Padrón ó Inactivo.</b><br>Debe Enviar Fotocopia Carnet y ultimo Recibo de Haberes.";
            echo "</b><br>No es Posible Avazanzar con la Carga de la Orden.<br><br>";
            echo '<input type="button" name="ok" id="ok" class="button gray small" value="Regresar" onClick="CancelarAltaPaciente(); return false" />';
            echo '</p></font>';
            echo "</FIELDSET>";
            echo '</p>';
        }
    }
}

//==============================================================================
// PREVENCIÓN SALUD
if ($os->_reglaNegocio == 12) {

    $url = $os->_url . '/afiliado';
    $usuario = $os->_user;
    $clave = $os->_pass;
    $prestador = str_replace('-', '', $os->_parametro5);
    $sitioemisor = $os->_parametro1;

    $json_data = '{"pUser":"' . $usuario . '", "pPwd":"' . $clave . '", "IdentificadorAfiliado":"' . $nrodoc . '", "IDPrestador":"' . $prestador . '", "parametro1":"' . $sitioemisor . '"}';

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

    $post = file_get_contents($url, false, $context); //, -1, $l);

    $result = json_decode($post);

    $s = print_r($result, true);
    $wsres->crear($codos, $idprof, $nrodoc, $s, 1);

    $valido = true;

    if ($result->Estado != 'OK') {
        echo '*** ' . $result->Nombre . ' NO VÁLIDO ó HABILITADO ***';

        $valido = false;
    }

    if (strpos($result->Mensaje, '^OK') > 0)
        $valido = true;

    if ($valido) {  // OK
        echo '  Paciente: ' . $nrodoc . ' - ' . $result->Nombre;

        $retiva = $result->Retiva;

        // Lo damos de alta en nuestro padrón
        $afiliado = new cAfiliados();
        $n = $result->Nombre;
        if ($afiliado->getObject($codos, $nrodoc) == false)
            $afiliado->crear2($codos, $nrodoc, substr($n, 0, 38), '', '', '', '', '', '', '', 'N', '', $retiva, '');
        else
            $afiliado->actualizar2($codos, $nrodoc, substr($n, 0, 38), '', '', '', '', '', '', 'N', '', $retiva, '');
    } else {

        if ($os->getAltaPaciente() != 'S') {
            echo '<p align="center">';
            echo "<FIELDSET>";
            echo "<LEGEND>Error en Datos del Afiliado</LEGEND>";
            echo '<p align="center">';
            echo '<font color="#FF0000">';
            echo "<b>El Afiliado Doc Nro. $nrodoc / Afiliado " . $result->Nombre . " está fuera de Padrón ó Inactivo.</b><br>Respuesta. " . $result->Mensaje . "<br/>";
            echo "</b><br>No es Posible Avazanzar con la Carga de la Orden.<br><br>";
            echo '<input type="button" name="ok" id="ok" class="button gray small" value="Regresar" onClick="CancelarAltaPaciente(); return false" />';
            echo '</p></font>';
            echo "</FIELDSET>";
            echo '</p>';
        }
    }
}

//==============================================================================
// SANCOR INTERNACIÓN
if ($os->_reglaNegocio == 11) {

    $url = $os->_url;
    $usuario = $os->_user;
    $clave = $os->_pass;

    $entidad = $os->getParametro8(); // 135533; // intval($os->parametro1);
    $tiponroefector = $os->getParametro3(); // 'CU';    
    $e = str_replace('-', '', $efector->getNrocuit());
    $nroefector = (float) $e; //20167261885; //20167261885;    
    $formaidafiliado = $os->getParametro4(); //'AS';
    $afiliado = $nrodoc; //'12129700';
    $modo = $os->getParametro2();
    $tiponroefector = $os->getParametro3();

    $client = new SoapClient($url, array('cache_wsdl' => WSDL_CACHE_NONE, 'trace' => TRUE));

    $criterio = array(
        'Modo' => $modo,
        'Entidad' => $entidad,
        'Tiponroefector' => $tiponroefector,
        'Nroefector' => $nroefector,
        'Formaidafiliado' => $formaidafiliado,
        'Afiliado' => $afiliado,
        'Usuario' => $usuario,
        'Clave' => $clave);

    $result = $client->ELEGIBILIDAD($criterio);

    $s = print_r($result, true);
    $wsres->crear($codos, $idprof, $nrodoc, $s, 1);

    $valido = true;

    if ($result->Descripcionrespuesta != 'AUTORIZADO') {
        echo '*** ' . $result->Nombreafiliado . ' NO VÁLIDO ó HABILITADO ***';

        $valido = false;
    }

    if ($valido) {  // OK
        echo '  Paciente: ' . $nrodoc . ' - ' . str_replace("'", " ", $result->Nombreafiliado);

        $pid = 0;
        $idos = $result->Planrta;
        $retiva = 'N';
        if ($result->Condicionafiliado == 'GRAV')
            $retiva = 'S';

        // Lo damos de alta en nuestro padrón
        $afiliado = new cAfiliados();
        $n = str_replace("'", " ", $result->Nombreafiliado);
        if ($afiliado->getObject($codos, $nrodoc) == false)
            $afiliado->crear2($codos, $nrodoc, substr($n, 0, 38), $n, '', '', '', '', $pid, '', 'N', '', $retiva, $idos);
        else
            $afiliado->actualizar2($codos, $nrodoc, substr($n, 0, 38), $n, '', '', $pid, '', '', 'N', '', $retiva, $idos);
    } else {

        if ($os->getAltaPaciente() != 'S') {
            echo '<p align="center">';
            echo "<FIELDSET>";
            echo "<LEGEND>Error en Datos del Afiliado</LEGEND>";
            echo '<p align="center">';
            echo '<font color="#FF0000">';
            echo "<b>El Afiliado Doc Nro. $nrodoc / Afiliado " . $result->Nombreafiliado . " está fuera de Padrón ó Inactivo.</b><h2>" . $result->Descripcionrespuesta . "</h2";
            echo "</b><br>No es Posible Avazanzar con la Carga de la Orden.<br><br>";
            echo '<input type="button" name="ok" id="ok" class="button gray small" value="Regresar" onClick="CancelarAltaPaciente(); return false" />';
            echo '</p></font>';
            echo "</FIELDSET>";
            echo '</p>';
        }
    }
}

//==============================================================================
// FESALUD

if ($os->_reglaNegocio == 10) {

    $url = $os->_url;
    $usuario = $os->_user;
    $pass = $os->_pass;
    $cuit = str_replace('-', '', $os->_parametro5);
    $ns = $_REQUEST['nrodoc'];
    $plan = $os->_parametro8;

    $parameters = '{}';

    $json_data = '{"nrodoc":"' . $ns . '"}';

    $url = $url . '/afiliado';

    $theurl = $url . 'afiliado';
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

    $file = @file_get_contents($url, false, $context);

    $result = json_decode($file);

    $s = print_r($result, true);
    $wsres->crear($codos, $idprof, $nrodoc, $file, 1);

    $valido = 'N';

    if ($result->inhabilitado == 1) {
        $valido = 'N';
    }
    if ($result->nombre == null || $result->inhabilitado == 1)
        $valido = 'N';
    else
        $valido = 'S';

    if ($valido == 'N') {
        echo '*** ' . $ns . ' => ' . $result->estado . ' NO VÁLIDO ó HABILITADO ***';
        return;
    }

    // Verificamos si coincide con el plan
    if ($plan != null) {
        if ($result->plan->codigo != $plan) {
            echo 'El Plan del Afiliado: <b>' . $result->nombre . '</b> es: <b>' . $result->plan->descripcion . '</b>';
            return;
        }
    }

    $retiva = 'N';
    if ($result->retiva == "1")
        $retiva = 'S';

    if ($valido == 'S') {

        $nombre = $result->nombre;
        echo '  Paciente: ' . $nombre . ' (' . $result->plan->descripcion . ') Ret. I.V.A.: ' . $retiva;

        //if ($result->gravado != '0')
        //  $retiva = 'S';
        // Lo damos de alta en nuestro padrón        
        $afiliado = new cAfiliados();
        if ($afiliado->getObject($codos, $nrodoc) == false)
        //crear2($codos, $nrodoc, $nombre, $observac, $fechanac, $depto, $diferido, $direccion, $id_beneficio, $id_parentesco, $sexo, $tipo_doc, $retiva, $idos) {
            $afiliado->crear2($codos, $nrodoc, substr($nombre, 0, 38), $result->plan->descripcion, $result->parametro1, $result->calculaCoseguro, '', '', $result->calculaCoseguro, '', $result->sexo, $result->tipodoc, $retiva, $result->plan->id);
        else
            $afiliado->actualizar2($codos, $nrodoc, substr($nombre, 0, 38), $result->plan->descripcion, $result->parametro1, '', $result->calculaCoseguro, '', $result->sexo, $result->tipodoc, 'N', $retiva, $result->plan->id);

        // Actualizamos Plan
        $afiliado->updateCoseguro($codos, $nrodoc, $result->plan->Coseguro1, $result->plan->Coseguro2, $result->plan->Coseguro3);
    } else {

        if ($os->getAltaPaciente() != 'S') {
            echo '<p align="center">';
            echo "<FIELDSET>";
            echo "<LEGEND>Error en Datos del Afiliado</LEGEND>";
            echo '<p align="center">';
            echo '<font color="#FF0000">';
            echo "<b>El Afiliado Doc Nro. $ns / Afiliado " . $nd . " está fuera de Padrón ó Inactivo.</b><br>Debe Enviar Fotocopia Carnet y ultimo Recibo de Haberes.";
            echo "</b><br>No es Posible Avazanzar con la Carga de la Orden.<br><br>";
            echo '<input type="button" name="ok" id="ok" class="button gray small" value="Regresar" onClick="CancelarAltaPaciente(); return false" />';
            echo '</p></font>';
            echo "</FIELDSET>";
            echo '</p>';
        }
    }

    return;
}


//==============================================================================
// SWISS MEDICAL
if ($os->_reglaNegocio == 9) {

    $url = $os->_url;
    $usuario = $os->_user;
    $pass = $os->_pass;
    $cuit = str_replace('-', '', $os->_parametro5);
    $ns = $_REQUEST['nrodoc'];

    $metodo = 'afiliado';

    $fecha = $utiles->getFechaAAAAMMDD($utiles->getFechaActual());

    $turl = $os->_parametro7 . '/v0/auth-login';
    $metodoext = $os->_parametro7 . '/v1.0/prestadores/hl7/elegibilidad';
    $parameters = '{"url":"' . $turl . '","metodo":"' . $metodoext . '","api":"Authorization","apikey":"' . $os->_parametro1 . '","username":"' . $usuario . '","password":"' . $pass . '"}';

    $data = '{
        "apiKey": "' . $os->_parametro1 . '",
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

    $afiliado = '{
        "creden":"' . $ns . '",
        "alta":"' . $fecha . '",
        "fecdif":"' . $fecha . '"
        }';

    $json_data = '{"parameters":' . $parameters . ',' .
            '"afiliado":' . $afiliado . ',' .
            '"token":' . $data . '}';

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

    $tturl = $os->_url . $metodo;

    $file = file_get_contents($tturl, false, $context); //, -1, $l)

    $result = json_decode($file);

    $s = print_r($result, true);
    $wsres->crear($codos, $idprof, $nrodoc, $file, 1);

    $valido = 'N';

    if ($result->status != 500) {
        $valido = 'N';
    }
    if ($result->apeNom == null)
        $valido = 'N';
    else
        $valido = 'S';

    if ($valido == 'N') {
        echo '*** ' . $ns . ' => ' . $result->status . ' ' . $result->message . ' NO VÁLIDO ó HABILITADO ***';
        return;
    }

    $retiva = 'N';

    if ($valido == 'S') {

        $nombre = $result->apeNom;

        // Rechazos
        if ($result->rechaCabecera == 2) {
            echo $nombre . ' ' . $result->rechaCabeDeno;
            return;
        }

        echo '  Paciente: ' . $nombre;

        if ($result->gravado != '0')
            $retiva = 'S';

        // Lo damos de alta en nuestro padrón
        $afiliado = new cAfiliados();
        if ($afiliado->getObject($codos, $nrodoc) == false)
            $afiliado->crear2($codos, $nrodoc, substr($nombre, 0, 38), '', $result->edad, '', '', '', $result->icdDeno, '', $result->sexo, 'DNI', $retiva, '');
        else
            $afiliado->actualizar2($codos, $nrodoc, substr($nombre, 0, 38), '', $result->edad, '', $result->icdDeno, '', $result->sexo, 'DNI', 'N', $retiva, '');
    } else {

        if ($os->getAltaPaciente() != 'S') {
            echo '<p align="center">';
            echo "<FIELDSET>";
            echo "<LEGEND>Error en Datos del Afiliado</LEGEND>";
            echo '<p align="center">';
            echo '<font color="#FF0000">';
            echo "<b>El Afiliado Doc Nro. $ns / Afiliado " . $nd . " está fuera de Padrón ó Inactivo.</b><br>Debe Enviar Fotocopia Carnet y ultimo Recibo de Haberes.";
            echo "</b><br>No es Posible Avazanzar con la Carga de la Orden.<br><br>";
            echo '<input type="button" name="ok" id="ok" class="button gray small" value="Regresar" onClick="CancelarAltaPaciente(); return false" />';
            echo '</p></font>';
            echo "</FIELDSET>";
            echo '</p>';
        }
    }
}

//==============================================================================
// INGENIEROS
if ($os->_reglaNegocio == 8) {

    $url = $os->_url;
    $usuario = $os->_user;
    $pass = $os->_pass;
    $ns = $_REQUEST['nrodoc'];

    $metodo = 'afiliado';

    $turl = $os->_parametro7 . '/Afiliado/' . $nrodoc;
    $parameters = '{"url":"' . $turl . '","metodo":"","api":"Authorization","apikey":"' . $os->_parametro1 . '","username":"' . $usuario . '","password":"' . $pass . '"}';

    $data = '{}';
    $json_data = '{"parameters":' . $parameters . ',' .
            '"afiliado":' . $data . '}"';

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

    $result = json_decode($file);

    $s = print_r($result, true);
    $wsres->crear($codos, $idprof, $nrodoc, $file, 1);

    $valido = 'N';
    if ($result->EstadoRegimenAsistencial == 'ACTIVO')
        $valido = 'S';

    if ($valido == 'N') {
        echo '*** ' . $ns . ' ' . $result->EstadoRegimenAsistencial . ' NO VÁLIDO ó HABILITADO ***';
    }

    $retiva = 'N';

    if ($valido == 'S') {

        $nombre = $result->Apellido . ' ' . $result->Nombre;
        echo '  Paciente: ' . $nombre;

        // Lo damos de alta en nuestro padrón
        $afiliado = new cAfiliados();
        if ($afiliado->getObject($codos, $nrodoc) == false)
            $afiliado->crear2($codos, $nrodoc, substr($nombre, 0, 38), '', substr($result->FechaNacimiento, 0, 10), '', '', '', $result->NumeroAfiliado, '', $result->Sexo, $result->DocumentoTipo, $retiva, '');
        else
            $afiliado->actualizar2($codos, $nrodoc, substr($nombre, 0, 38), '', substr($result->FechaNacimiento, 0, 10), '', $result->NumeroAfiliado, '', $result->Sexo, $result->DocumentoTipo, 'N', $retiva, '');
    } else {

        if ($os->getAltaPaciente() != 'S') {
            echo '<p align="center">';
            echo "<FIELDSET>";
            echo "<LEGEND>Error en Datos del Afiliado</LEGEND>";
            echo '<p align="center">';
            echo '<font color="#FF0000">';
            echo "<b>El Afiliado Doc Nro. $ns / Afiliado " . $nd . " está fuera de Padrón ó Inactivo.</b><br>Debe Enviar Fotocopia Carnet y ultimo Recibo de Haberes.";
            echo "</b><br>No es Posible Avazanzar con la Carga de la Orden.<br><br>";
            echo '<input type="button" name="ok" id="ok" class="button gray small" value="Regresar" onClick="CancelarAltaPaciente(); return false" />';
            echo '</p></font>';
            echo "</FIELDSET>";
            echo '</p>';
        }
    }
}

//==============================================================================
// IAPOS
if ($os->_reglaNegocio == 7) {

    $url = $os->_url;

    $client = new SoapClient($url, array('cache_wsdl' => WSDL_CACHE_NONE, 'trace' => TRUE));

    $opts = array(
        'ssl' => array(
            'ciphers' => 'RC4-SHA',
            'verify_peer' => false,
            'verify_peer_name' => false
        )
    );    

    $params = array(
        "Usuario" => 'ctobioqlitoralnorte',
        "Passwd" => '12345',
        "Nafiliado" => $nrodoc,
        "Badocnumdo" => null,
        "Tidocodigo_de_documento" => null,
        "Ogorcodigo" => null,
        "Fechpresta" => null,
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

    $response = $client->__soapCall("Execute", array($params));

    if ($response->{'Estado'} == 'A' || $response->{'Batithabilitado'} == 'S') {

        // Controlamos Tope
        if ($os->_parametro2 == 'TOPE') {
            $f = $utiles->getFechaActual();
            $ff = $utiles->getFechaAAAAMMDD($f);
            if ($afiliado->getOrden($nrodoc, $ff, $codos)) {
                echo $nrodoc . ' - ' . $response->{'Apenom'} . ' (SUPERA TOPE DIARIO PERMITIDO)';
                exit;
            }
        }

        $apnom = str_replace(",", "", $response->{'Apenom'});
        echo '  Paciente: ' . $nrodoc . ' - ' . $apnom;

        $afiliado = new cAfiliados();
        if ($afiliado->getObject($codos, $nrodoc) == false)
            $afiliado->crear2($codos, $nrodoc, $apnom, '', $response->{'Fechanac'}, '', '', '', '', '', $response->{'Sexo'}, '', 'N', '');
        else
            $afiliado->actualizar2($codos, $nrodoc, $apnom, '', $response->{'Fechanac'}, '', '', '', '', '', $response->{'Sexo'}, '', 'N', '');

        $s = print_r($response, true);
        $wsres->crear($codos, $idprof, $nrodoc, $s, 1);
    } else {
        echo '<p align="center">';
        echo "<FIELDSET>";
        echo "<LEGEND>Error en Datos del Afiliado</LEGEND>";
        echo '<p align="center">';
        print_r($response);
        echo '</br>';
        echo '<font color="#FF0000">';
        echo "<b>El Afiliado $ndoc está fuera de Padrón ó Inactivo.</b><br>Debe Enviar Fotocopia Carnet y ultimo Recibo de Haberes.";
        echo "</b><br>No es Posible Avazanzar con la Carga de la Orden.<br><br>";
        echo '<input type="button" name="ok" id="ok" class="button gray small" value="Regresar" onClick="CancelarAltaPaciente(); return false" />';
        echo '</p></font>';
        echo "</FIELDSET>";
        echo '</p>';
    }
}

//==============================================================================
// FEDERADA SALUD
if ($os->_reglaNegocio == 6) {

    $url = $os->_url;
    $usuario = $os->_user;
    $pass = $os->_pass;
    $ns = $_REQUEST['nrodoc'];

    $metodo = 'afiliado';

    //$parameters = '{"nrocuit":"' . $os->_parametro5 .  '","url":"' . $os->_parametro7 . '/","metodo":"validador/' . $os->_parametro8 . '/wsvol000","api":"x-api-key","apikey":"' . $pass . '"}';
    $parameters = '{"url":"' . $os->_parametro7 . '/","metodo":"validador/' . $os->_parametro8 . '/wsvol000","api":"x-api-key","apikey":"' . $pass . '"}';

    $data = '{"p_IntNro":"' . $os->_parametro2 . '",'
            . '"p_Prestador":"' . $os->_parametro3 . '",'
            . '"p_SubPrestador":"' . $os->_parametro4 . '",'
            . '"p_TipoDoc":"' . 1 . '",'
            . '"p_NroDoc":"' . $nrodoc . '"}';

    $theurl = $url . $metodo;

    $json_data = '{"parameters":' . $parameters . ',' .
            '"afiliado":' . $data . '}"';

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

    //echo $json_data;

    $file = file_get_contents($theurl, false, $context);

    $result = json_decode($file);

    $s = print_r($result, true);
    $wsres->crear($codos, $idprof, $nrodoc, $file, 1);

    $valido = 'N';
    if ($result->o_Status == 'SI')
        $valido = 'S';

    if ($valido == 'N') {
        echo '*** ' . $ns . ' ' . $result->o_Comentario . ' NO VÁLIDO ó HABILITADO ***';
    }

    $retiva = 'N';
    if ($result->o_SitFiscal == 'G')
        $retiva = 'S';
    if ($result->o_SitFiscal == 'E')
        $retiva = 'N';
    if ($result->o_SitFiscal == 'V')
        $retiva = 'S';

    if ($valido == 'S') {

        $nombre = $result->o_Apellido . ' ' . $result->o_Nombres;
        echo '  Paciente: ' . $nombre . ' Ret. I.V.A.: ' . $retiva;

        // Lo damos de alta en nuestro padrón
        $afiliado = new cAfiliados();
        if ($afiliado->getObject($codos, $nrodoc) == false)
            $afiliado->crear2($codos, $nrodoc, substr($nombre, 0, 38), $result->o_PlanDesc, $result->o_FecNac, '', '', $result->o_DescLocali, '', substr($result->o_RelFam, 0, 50), $result->o_Sexo, $result->o_TipDocTit, $retiva, '');
        else
            $afiliado->actualizar2($codos, $nrodoc, substr($nombre, 0, 38), $result->o_PlanDesc, $result->o_FecNac, '', '', '', '', $result->o_TipDocTit, 'N', $retiva, '');
    } else {

        if ($os->getAltaPaciente() != 'S') {
            echo '<p align="center">';
            echo "<FIELDSET>";
            echo "<LEGEND>Error en Datos del Afiliado</LEGEND>";
            echo '<p align="center">';
            echo '<font color="#FF0000">';
            echo "<b>El Afiliado Doc Nro. $ns / Afiliado " . $nd . " está fuera de Padrón ó Inactivo.</b><br>Debe Enviar Fotocopia Carnet y ultimo Recibo de Haberes.";
            echo "</b><br>No es Posible Avazanzar con la Carga de la Orden.<br><br>";
            echo '<input type="button" name="ok" id="ok" class="button gray small" value="Regresar" onClick="CancelarAltaPaciente(); return false" />';
            echo '</p></font>';
            echo "</FIELDSET>";
            echo '</p>';
        }
    }
}

//==============================================================================
// ACA SALUD
if ($os->_reglaNegocio == -1) {

    date_default_timezone_set('America/Argentina/Ushuaia');
    $datetime = new DateTime();
    /*
      $url = $os->_url;
      $options["connection_timeout"] = 25;
      $options["location"] = $url;
      $options['trace'] = 1;
     * 
     */

    $rpc_xml = '<pSolicitud><![CDATA[<SOLICITUD>
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
<USRID>' . $os->_user . '</USRID>
<USRPASS>' . $os->_pass . '</USRPASS>
</SEGURIDAD>
<OPER>
<TIPO>ELG</TIPO>
<FECHA>' . $datetime->format('Y-m-d') . '</FECHA>
<IDASEG>' . $obsocial->_parametro7 . '</IDASEG>
<IDPRESTADOR>' . $os->_user . '</IDPRESTADOR>
</OPER>
<PID>
<ID>' . $nrodoc . '</ID>
<VERIFID>MANUAL</VERIFID>
</PID>

<CONTEXTO>
<TIPO>A</TIPO>
</CONTEXTO>

</SOLICITUD>]]></pSolicitud>';

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
        //'soap_version' => SOAP_1_2,
        'trace' => 1,
        'location' => $url,
        'exceptions' => 1,
        'connection_timeout' => 180,
        'stream_context' => stream_context_create($opts)
    );

    $wsres->crear($codos, '000000', $nrodoc, $rpc_xml, 10);

    try {
        $client = new SoapClient($url, $params); //$client = new SoapClient($url, $options);

        $resultados = $client->transaccionStr($rpc_xml);

        $lector = new SimpleXMLElement($resultados);

        $alta = "DarAltaPaciente(" . $_REQUEST['codos'] . "," . $_REQUEST['nrodoc'] . ")";

        $retiva = 'N';
        if ($lector->AFIAFIL == 'GRAVADO')
            $retiva = 'S';

        if ($lector->AFIAPE != '') {
            echo '  Paciente: ' . $nrodoc . ' - ' . substr($lector->AFIAPE . ' ' . $lector->AFINOM, 0, 19);

            // Lo damos de alta en nuestro padrón
            $afiliado = new cAfiliados();
            if ($afiliado->getObject($codos, $nrodoc) == false)
                $afiliado->crear2($codos, $nrodoc, strtoupper(substr($lector->AFIAPE . ' ' . $lector->AFINOM, 0, 38)), '', '', '', '', '', '', '', $lector->AFISEXO, '', $retiva, '');
            else
                $afiliado->actualizar2($codos, $nrodoc, substr($lector->AFIAPE . ' ' . $lector->AFINOM, 0, 40), '', '', '', '', '', '', $lector->AFISEXO, '', $retiva, '');

            $wsres->crear($codos, '000000', $nrodoc, $resultados, 1);
        } else {
            if ($os->getAltaPaciente() != 'S') {
                echo '<p align="center">';
                echo "<FIELDSET>";
                echo "<LEGEND>Error en Datos del Afiliado</LEGEND>";
                echo '<p align="center">';
                print_r($resultados);
                echo '</br>';
                echo '<font color="#FF0000">';
                echo "<b>El Afiliado $ndoc está fuera de Padrón ó Inactivo.</b><br>Debe Enviar Fotocopia Carnet y ultimo Recibo de Haberes.";
                echo "</b><br>No es Posible Avazanzar con la Carga de la Orden.<br><br>";
                echo '<input type="button" name="ok" id="ok" class="button gray small" value="Regresar" onClick="CancelarAltaPaciente(); return false" />';
                echo '</p></font>';
                echo "</FIELDSET>";
                echo '</p>';
            }
        }
    } catch (Exception $e) {
        echo ($e->getMessage());
        echo ($client->__getLastRequest());
        echo ($client->__getLastResponse());
    }
}
//==============================================================================
// OSDE

if ($os->_reglaNegocio == 2) {

    $fecha = $utiles->getFechaActual();
    $ftrans = $utiles->getFechaAAAAMMDD($fecha);
    $htrans = date('H:m:s');
    $htrans = str_replace(':', '', $htrans);
    $cuitentidad = str_replace('-', '', $entidad->getCuit());
    //$cuitprestador = $os->_parametro4;
    $version = $os->_parametro5;
    $cuitprestador = str_replace('-', '', $efector->nrocuit);
    $codigofinanciador = $efector->parametro2;
    $terminal = $efector->parametro3;
    $cuitentidad = str_replace('-', '', $os->_parametro4);

    //echo $codigofinanciador . ' ' . $terminal . ' ';
    // TESTING -----
    //$cuitentidad = '30546741253';
    //$codigofinanciador = '11';
    //$cuitprestador = '30708402911';
    //$terminal = 1234;
    //--------------

    $idmsg = rand(1, 9999);

    /*
     * 60671956202 121
     * 60671956201 891
     * 
     * 
     */

    $track = '';
    $versioncredencial = $track1; //'891';
    
    $spp = ''; // Para Efectores con varios puntos de atención    
    if ($efector->getNivel2()) $spp = $terminal;

    $msg = "<Mensaje>
	<EncabezadoMensaje>
		<VersionMsj>" . $version . "</VersionMsj>
		<TipoTransaccion>01A</TipoTransaccion>
		<IdMsj> $idmsg </IdMsj>
		<InicioTrx>
			<FechaTrx>" . $ftrans . "</FechaTrx>
                        <HoraTrx>" . $htrans . "</HoraTrx>
		</InicioTrx>
                <Terminal>
                        <TipoTerminal>PC</TipoTerminal>
                        <NumeroTerminal>$terminal</NumeroTerminal>
                </Terminal>
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
			<NumeroCredencial>" . $nrodoc . "</NumeroCredencial>
                        <Track>" . $track . "</Track> 
                        <VersionCredencial>" . $versioncredencial . "</VersionCredencial>  
		</Credencial>
		<Preautorizacion/>
		<Documentacion/>
		<Atencion/>
		<Diagnostico/>
		<CodFinalizacionTratamiento/>
		<MensajeParaFinanciador/>
	</EncabezadoAtencion>
	<DetalleProcedimientos/>
</Mensaje>";

    //$xmlString = $msg->asXML();

    $wsres->crear($codos, $idprof, $nrodoc, $msg, 7);

    $msg = str_replace('<', '%3C', $msg);
    $msg = str_replace('>', '%3E', $msg);
    $msg = str_replace(' ', '', $msg);
    $msg = preg_replace('[\s+]', '', $msg);

    $url = $os->_url . $msg; // url RPC Servlet

    $url_content = '';
    $file = @fopen($url, 'r');

    if ($file) {                // Parseamos la respuesta
        while (!feof($file)) {
            $url_content .= @fgets($file, 4096);
        }
        fclose($file);

        //echo $url_content;        
        $wsres->crear($codos, $idprof, $nrodoc, $url_content, 10);

        $lector = new SimpleXMLElement($url_content);

        $af = $lector->EncabezadoMensaje->Rta->MensajeDisplay;

        if (substr($af, 0, 2) == 'OK') {
            //$nombre = $lector->EncabezadoMensaje->Rta->MensajeDisplay;
            $nombre = $lector->EncabezadoAtencion->Beneficiario->ApellidoBeneficiario;
            $nombre = substr($nombre, 0, 100);
            $fechanac = $lector->EncabezadoAtencion->Beneficiario->FechaNacimiento;
            $credencial = $lector->EncabezadoAtencion->Credencial->NumeroCredencial;
            $rc = $lector->EncabezadoAtencion->Credencial->CondicionIVA;

            //echo $credencial . ' - ' . $rc;

            $retiva = 'S';
            if ($rc == 'E')
                $retiva = 'N';

            //$p = strpos($nombre, ',');
            //$nombre = substr($nombre, 0, $p - 4);

            echo '  Paciente: ' . $nrodoc . ' - ' . $nombre;

            // Lo damos de alta en nuestro padrón
            $afiliado = new cAfiliados();
            if ($afiliado->getObject($codos, $nrodoc) == false)
                $afiliado->crear2($codos, $nrodoc, strtoupper(substr($nombre, 0, 90)), '', $fechanac, '', '', '', $credencial, '', '', '', $retiva, $track1);
            else
                $afiliado->actualizar2($codos, $nrodoc, strtoupper(substr($nombre, 0, 90)), '', $fechanac, '', $credencial, '', '', '', '', $retiva, $track1);

            $wsres->crear($codos, $idprof, $nrodoc, $url_content, 1);
        } else {
            if ($os->getAltaPaciente() != 'S') {
                echo $url_content;

                echo '<p align="center">';
                echo "<FIELDSET>";
                echo "<LEGEND>Error en Datos del Afiliado</LEGEND>";
                echo '<p align="center">';
                echo '<font color="#FF0000">';
                echo "<b>El Afiliado $ndoc está fuera de Padrón ó Inactivo.</b><br>Debe Enviar Fotocopia Carnet y ultimo Recibo de Haberes.";
                echo "</b><br>No es Posible Avazanzar con la Carga de la Orden.<br><br>";
                echo '<input type="button" name="ok" id="ok" class="button gray small" value="Regresar" onClick="CancelarAltaPaciente(); return false" />';
                echo '</p></font>';
                echo "</FIELDSET>";
                echo '</p>';
                
                
            }
        }
    }
}

//==============================================================================
// JERARQUICO
if ($os->_reglaNegocio == 3) {

    try {

        $us = $nrodoc;
        $so = substr($us, 0, strlen($us) - 2);
        $or = substr($us, strlen($us) - 2, 2);

        $client = new SoapClient($os->_url, array('cache_wsdl' => WSDL_CACHE_NONE, 'trace' => TRUE));

        $CriterioElegibilidadSocioServiciosSalud = array('FechaDeReferencia' => $utiles->getFechaAAAA_MM_DD($utiles->getFechaActual()),
            'NumeroSocio' => $so,
            'OrdenSocio' => $or,
            'IdTipoDocumento' => null,
            'NumeroDocumento' => null);

        $SolicitudElegibilidadSocioServiciosSalud = array('CriterioElegibilidadSocioServiciosSalud' => $CriterioElegibilidadSocioServiciosSalud);

        //Llamada al servicio pasando el parámetro
        $ready = $client->DeterminarElegibilidadSocioServiciosSalud(array('solicitudElegibilidadSocioServiciosSalud' => $SolicitudElegibilidadSocioServiciosSalud));

        //print_r($ready);

        $res = $ready->DeterminarElegibilidadSocioServiciosSaludResult->DTOSerializado;

        $obj = json_decode($res);

        $valido = 'N';
        if ($obj->{"Valido"})
            $valido = 'S';

        // Planes excluidos - 13/09/2019

        if ($pid = $obj->{"PlanVigente"}->{"PlanId"} == $os->_parametro1) {
            $valido = 'N';
            echo '*** PLAN <b>' . $obj->{"PlanVigente"}->{"PlanId"} . ' ' . $obj->{"PlanVigente"}->{"PlanNombre"} . '</b> FUERA DE CONVENIO ***';
            echo '<br/>';
            $wsres->crear($codos, $idprof, $nrodoc, $res, 1);
        }

        if ($valido == 'N') {
            echo '*** PACIENTE NO VÁLIDO ó HABILITADO ***';
            echo '<br/>';
            $wsres->crear($codos, $idprof, $nrodoc, $res, 1);
        }

        if ($obj->{'Numero'} != '' && $valido = 'S') {
            $nombre = trim($obj->{'Apellido'}) . ' ' . trim($obj->{'Nombre'});

            echo '  Paciente: ' . $obj->{'Numero'} . ' - ' . $nombre;

            //$ss = 'S';
            //if ($obj->{"PlanVigente"}->{"PlanEsAutonomo"} == false) $ss = "N";
            //echo '... ' . $ss;

            $retiva = 'S';
            if ($obj->{'EsIvaExento'})
                $retiva = 'N';

            $retiva = 'S';
            if ($obj->{"PlanVigente"}->{"PlanEsAutonomo"} == false)
                $retiva = "N"; // 29/03/2016











                
// 19/11/2019            
            if ($obj->{"EsAutonomo"} == true)
                $retiva = 'S';
            else
                $retiva = 'N';

            $idos = $obj->{'IdSocio'};

            $pnombre = $obj->{"PlanVigente"}->{"PlanNombre"};
            $pid = $obj->{"PlanVigente"}->{"PlanId"};
            $numerodoc = $obj->{'NumeroDocumento'};

            // Lo damos de alta en nuestro padrón
            $afiliado = new cAfiliados();
            if ($afiliado->getObject($codos, $nrodoc) == false)
                $afiliado->crear3($codos, $nrodoc, substr($nombre, 0, 38), $pnombre, '', '', '', '', $pid, '', 'N', '', $retiva, $idos, $numerodoc);
            else
                $afiliado->actualizar3($codos, $nrodoc, substr($nombre, 0, 38), $pnombre, '', '', $pid, '', '', 'N', '', $retiva, $idos, $numerodoc);

            $wsres->crear($codos, $idprof, $nrodoc, $res, 1);
        } else {
            if ($os->getAltaPaciente() != 'S') {
                echo '<p align="center">';
                echo "<FIELDSET>";
                echo "<LEGEND>Error en Datos del Afiliado</LEGEND>";
                echo '<p align="center">';
                echo '<font color="#FF0000">';
                echo "<b>El Afiliado $ndoc está fuera de Padrón ó Inactivo.</b><br>Debe Enviar Fotocopia Carnet y ultimo Recibo de Haberes.";
                echo "</b><br>No es Posible Avazanzar con la Carga de la Orden.<br><br>";
                echo '<input type="button" name="ok" id="ok" class="button gray small" value="Regresar" onClick="CancelarAltaPaciente(); return false" />';
                echo '</p></font>';
                echo "</FIELDSET>";
                echo '</p>';
            }
        }
    } catch (Exception $e) {
        trigger_error($e->getMessage(), E_USER_WARNING);
    }
}

//==============================================================================
// AMUR
if ($os->_reglaNegocio == 4) {

    $url = $os->_url;
    $usuario = $os->_user;
    $pass = $os->_pass;
    $ns = substr($_REQUEST['nrodoc'], 0, 7);
    $nd = substr($_REQUEST['nrodoc'], 7, 8);
    $nd = '';

    //$ns = '21311/01';

    $fecha = $utiles->getFechaAAAA_MM_DD($utiles->getFechaActual());

    $client = new SoapClient($url, array('cache_wsdl' => WSDL_CACHE_NONE, 'trace' => TRUE));

    $result = $client->__soapCall('fnafiliado', array('usuario' => $usuario, 'pass' => $pass, 'ns' => $ns, 'nd' => $nd, 'fecha' => $fecha));

    $s = print_r($result, true);
    $wsres->crear($codos, $idprof, $nrodoc, $s, 1);

    $valido = 'N';
    if ($result->detalle == 'ACTIVO')
        $valido = 'S';

    if ($valido == 'N') {
        echo '*** ' . $ns . ' ' . $nd . ' ' . $result->detalle . ' NO VÁLIDO ó HABILITADO ***';
    }

    $retiva = 'S';
    if ($result->tiva == 1)
        $retiva = 'N';

    if ($valido == 'S') {

        $nombre = $result->apno;
        echo '  Paciente: ' . $result->ns . ' - ' . $nombre;

        $pnombre = $result->obraSocial;
        $pid = $result->ns;
        $idos = $result->plan;

        // Lo damos de alta en nuestro padrón
        $afiliado = new cAfiliados();
        if ($afiliado->getObject($codos, $nrodoc) == false)
            $afiliado->crear2($codos, $nrodoc, substr($nombre, 0, 38), $pnombre, '', '', '', '', $pid, '', 'N', '', $retiva, $idos);
        else
            $afiliado->actualizar2($codos, $nrodoc, substr($nombre, 0, 38), $pnombre, '', '', $pid, '', '', 'N', '', $retiva, $idos);
    } else {

        if ($os->getAltaPaciente() != 'S') {
            echo '<p align="center">';
            echo "<FIELDSET>";
            echo "<LEGEND>Error en Datos del Afiliado</LEGEND>";
            echo '<p align="center">';
            echo '<font color="#FF0000">';
            echo "<b>El Afiliado Doc Nro. $ns / Afiliado " . $nd . " está fuera de Padrón ó Inactivo.</b><br>Debe Enviar Fotocopia Carnet y ultimo Recibo de Haberes.";
            echo "</b><br>No es Posible Avazanzar con la Carga de la Orden.<br><br>";
            echo '<input type="button" name="ok" id="ok" class="button gray small" value="Regresar" onClick="CancelarAltaPaciente(); return false" />';
            echo '</p></font>';
            echo "</FIELDSET>";
            echo '</p>';
        }
    }
}

//==============================================================================
// SANCOR
if ($os->_reglaNegocio == 5) {

    $url = $os->_url;
    $usuario = $os->_user;
    $clave = $os->_pass;

    $entidad = $os->getParametro1(); // 135533; // intval($os->parametro1);
    $tiponroefector = $os->getParametro3(); // 'CU';    
    $e = str_replace('-', '', $efector->getNrocuit());
    $nroefector = (float) $e; //20167261885; //20167261885;    
    $formaidafiliado = $os->getParametro4(); //'AS';
    $afiliado = $nrodoc; //'12129700';
    $modo = $os->getParametro2();
    $tiponroefector = $os->getParametro3();

    $client = new SoapClient($url, array('cache_wsdl' => WSDL_CACHE_NONE, 'trace' => TRUE));

    $criterio = array(
        'Modo' => $modo,
        'Entidad' => $entidad,
        'Tiponroefector' => $tiponroefector,
        'Nroefector' => $nroefector,
        'Formaidafiliado' => $formaidafiliado,
        'Afiliado' => $afiliado,
        'Usuario' => $usuario,
        'Clave' => $clave);

    $result = $client->ELEGIBILIDAD($criterio);

    $s = print_r($result, true);
    $wsres->crear($codos, $idprof, $nrodoc, $s, 1);

    $valido = true;

    if ($result->Descripcionrespuesta != 'AUTORIZADO') {
        echo '*** ' . $result->Nombreafiliado . ' NO VÁLIDO ó HABILITADO ***';

        $valido = false;
    }

    if ($valido) {  // OK
        echo '  Paciente: ' . $nrodoc . ' - ' . str_replace("'", " ", $result->Nombreafiliado);

        $pid = 0;
        $idos = $result->Planrta;
        $retiva = 'N';
        if ($result->Condicionafiliado == 'GRAV')
            $retiva = 'S';

        // Lo damos de alta en nuestro padrón
        $afiliado = new cAfiliados();
        $n = str_replace("'", " ", $result->Nombreafiliado);
        if ($afiliado->getObject($codos, $nrodoc) == false)
            $afiliado->crear2($codos, $nrodoc, substr($n, 0, 38), $n, '', '', '', '', $pid, '', 'N', '', $retiva, $idos);
        else
            $afiliado->actualizar2($codos, $nrodoc, substr($n, 0, 38), $n, '', '', $pid, '', '', 'N', '', $retiva, $idos);
    } else {

        if ($os->getAltaPaciente() != 'S') {
            echo '<p align="center">';
            echo "<FIELDSET>";
            echo "<LEGEND>Error en Datos del Afiliado</LEGEND>";
            echo '<p align="center">';
            echo '<font color="#FF0000">';
            echo "<b>El Afiliado Doc Nro. $nrodoc / Afiliado " . $result->Nombreafiliado . " está fuera de Padrón ó Inactivo.</b><h2>" . $result->Descripcionrespuesta . "</h2";
            echo "</b><br>No es Posible Avazanzar con la Carga de la Orden.<br><br>";
            echo '<input type="button" name="ok" id="ok" class="button gray small" value="Regresar" onClick="CancelarAltaPaciente(); return false" />';
            echo '</p></font>';
            echo "</FIELDSET>";
            echo '</p>';
        }
    }
}
?>