<?php

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

$nroauditoria = $_REQUEST['nroauditoria'];
$token = $_REQUEST['token'];

// Testing
//$nroauditoria = '00000120200728171001136129486';
//$token = '555';
//echo $nroauditoria . ' ' . $token;

date_default_timezone_set('America/Argentina/Ushuaia');

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

$auditoria->getObject($nroauditoria);

// Armamos la llamada rpc
$obsocial->verificarRPC($auditoria->getCodos());
$ef->getObject($auditoria->getEfector());


// Verificamos si se ingresa automaticamente el 660001
$obj = new cEntidad();
$obj->getObject();


//==============================================================================
// SWISS MEDICAL
if ($obsocial->_reglaNegocio == 9) {

    $url = $obsocial->_url;
    $usuario = $obsocial->_user;
    $pass = $obsocial->_pass;
    $cuit = str_replace('-', '', $obsocial->_parametro5);

    $metodo = 'orden';
    
    // saneamos el nro. de afiliado
    $nrodoc = str_replace("800006", "", $auditoria->getNrodoc());

    $ns = $nrodoc . '|' . $token;

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

    $matricula = $auditoria->getEfector(); // $efector;
    if ($ef->parametro1 != '')
        $matricula = $ef->parametro1;


    $l = 0;
    $practicas = '';
    $res = $auditoria->getDeterminaciones("'" . $nroauditoria . "'");
    while ($fila = mysql_fetch_array($res)) {            
        $practicas = $practicas . '*' . $fila['codigo'] . '*1**|';   
        $l++;
    }    

    $practicas = substr($practicas, 0, -1);

    $practicas = $l . '^' . $practicas;

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
                        "idPrescr": "' . $auditoria->getEfector() . '"           
                    }';

    //$metodoext = $obsocial->_parametro7 . '/v1.0/prestadores/hl7/registracion';
    //$parameters = '{"url":"' . $turl . '","metodo":"' . $metodoext . '","api":"Authorization","apikey":"' . $obsocial->_parametro1 . '","username":"' . $usuario . '","password":"' . $pass . '"}';

    $json_data = '{"parameters":' . $parameters . ',' .
            '"orden":' . $orden . ',' .
            '"token":' . $data . '}';

    //echo $json_data . '<hr/>';
    
    $wsres->crear1($codos, $ef->getCodigo(), $nrodoc, $json_data, 6, '');
    

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

    $file = file_get_contents($theurl, false, $context);

    $result = json_decode($file);
    
    //==========================================================

    if ($result->cabecera->rechaCabeDeno == 'Ok') {
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
        $wsres->crear1($codos, $ef->getCodigo(), $ns, $file, 9, $result->cabecera->transac);
    }
    
    echo $result->cabecera->rechaCabeDeno;
 
}
?>
