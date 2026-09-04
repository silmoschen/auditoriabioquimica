<?php

//http://localhost/reco/operaciones/validar_orden_token_rj_rpc.php?codos=121057&nrodoc=20193300&token=E2AA4F
//http://66.97.35.221/reco/operaciones/validar_orden_token_rj_rpc.php?codos=121057&nrodoc=20193300&token=E2AA4F

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cObSocial.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cUtiles.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cWsRespuestas.php");

$token = $_REQUEST['token'];
$nrodoc = $_REQUEST['nrodoc'];
$codos = $_REQUEST['codos'];

$i = strlen($nrodoc);
$numerosocio = substr($nrodoc, 0, $i - 2);
$ordensocio = substr($nrodoc, $i - 2, $i - 1);

$obsocial = new cObsocial();
$utiles = new cUtiles();
$wsres = new cWsRespuestas();

$obsocial->verificarRPC($codos);

$json_data = '{
    "url":"' . $obsocial->getParametro13() . '",
    "grant_type":"client_credentials",
    "client_id":"' . $obsocial->getParametro11() . '",
    "client_secret":"' . $obsocial->getParametro12() . '",
    "numerosocio":"' . $numerosocio . '",
    "ordensocio":"' . $ordensocio . '",
    "token":"' . $token . '"
}';

//$url = 'http://localhost:5054/api/jerarquicos/token';
$url = $obsocial->getParametro10();

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


$wsres->crear($codos, '000000', $nrodoc, $json_data, 0);

//==============================================================================

//$url1 = str_replace('jerarquicos/token', 'restlogresponse/iniciar', $obsocial->getParametro10());
//$post1 = file_get_contents($url1, false, $context); //, -1, $l);

//==============================================================================

$post = file_get_contents($url, false, $context); //, -1, $l);

if ($post) {

    $json = $utiles->parseJson($post);
    
    if ($json == '') {
        echo '{"token":"ERROR GENERAL", "valido":false}';
        exit;
    }  
    
    $object = json_decode($json);
    
    if ($object->valido != 1) {
        echo '{"token":"ERROR, Token Inválido", "valido":false}';
        exit;
    }      
    
    echo $json;

    $s = print_r($json, true);
    $wsres->crear($codos, '000000', $nrodoc, $s, 0);
} else {
    echo '{"token":"ERROR de PROCESO", "valido":false}';
    $wsres->crear($codos, '000000', $nrodoc, $s, 0);
}

//==============================================================================

//$url1 = str_replace('jerarquicos/token', 'restlogresponse/finalizar', $obsocial->getParametro10());
//$post1 = file_get_contents($url1, false, $context); //, -1, $l);

//==============================================================================

?>