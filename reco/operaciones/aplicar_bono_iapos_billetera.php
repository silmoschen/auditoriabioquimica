<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cTransaccionesCoseguroIapos.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cAuditoria.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cNBU.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cObSocial.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cUtiles.php');

$nroauditoria = $_REQUEST['nroauditoria'];
$nrotrans = $_REQUEST['nrotrans'];
$anula = $_REQUEST['anula'];

$auditoria = new cAuditoria();
$c = new cTransaccionesCoseguroIapos();
$nbu = new cNBU();
$obsocial = new cObsocial();

$c->getUltimaTx($nroauditoria);
$auditoria->getObject($nroauditoria);

// Armamos la llamada rpc
$obsocial->verificarRPC($auditoria->getCodos());

//$dni = '00001126';
$dni = $auditoria->getNrodoc();

$u = new cUtiles();

if ($anula == 'S') {
   
    $json_data = '{"url":"' . $obsocial->_parametro6 .
            '", "usuario":"' . $obsocial->_user .
            '", "pass":"' . $obsocial->_pass .
            '", "dni":"' . $dni .
            '", "asociacion":"' . $obsocial->_parametro4 .
            '", "profesional": "' . $auditoria->getEfector() .
            '", "IdTrxBco": "' . $c->ref1 .
            '", "IdIapos": "' . $c->transiapos . '"}';  
        
    //echo $json_data . '<br/><br/>';
    
    $url = $obsocial->_parametro5 . '/anular';

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

    if ($result != null) $c->crear($result->Transaccion, $nroauditoria, 'BONOBILL', 'COSEGURO-ANULA', $u->getFechaHoraActualYYYY_MM_DD(), $result->idTrxIAPOS, $result->Res1, $result->idCoseguroDigital, $result->codigoSalida, $result->Res2, null);
    
    return;    

}

//===============================================================================

if (strlen($nroauditoria) > 0) {    
    
    $profesional = $auditoria->getEfector();
    $TRXS = '(' . $nrotrans . ',1-01,0)';

    $rs = $auditoria->getAuditoriaPracticas($nroauditoria);

    $prestaciones = '';
    while ($fila = mysql_fetch_array($rs)) {
        $nbu->getObject($fila['codigo']);
        $prestaciones = $prestaciones . '(' . $nbu->getCodigo() . ',' . $nbu->getDescrip() . ',1);';
    }

    $prestaciones = substr($prestaciones, 0, strlen($prestaciones) - 1);

    $json_data = '{"url":"' . $obsocial->_parametro3 .
            '", "usuario":"' . $obsocial->_user .
            '", "pass":"' . $obsocial->_pass .
            '", "dni":"' . $dni .
            '", "asociacion":"' . $obsocial->_parametro4 .
            '", "profesional": "' . $profesional .
            '", "TRXS": "' . $TRXS .
            '", "PRCS": "' . $prestaciones . '"}';

    echo $json_data . '<br/><br/>'; 

    $url = $obsocial->_parametro5 . '/coseguro';

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

    if ($result != null) $c->crear($result->Transaccion, $nroauditoria, 'BONOBILL', 'COSEGURO', $u->getFechaHoraActualYYYY_MM_DD(), $result->idTrxIAPOS, $result->mensajeSalida, $result->idCoseguroDigital, $result->codigoSalida, $result->Res3, null);
}
?>

