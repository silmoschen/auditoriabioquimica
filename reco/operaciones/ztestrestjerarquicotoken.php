<?php

//http://localhost/reco/operaciones/ztestrestjerarquicotoken.php?codos=121057&nrodoc=20193300&token=E2AA4F
//http://66.97.35.221/reco/operaciones/ztestrestjerarquicotoken.php?codos=121057&nrodoc=20193300&token=E2AA4F

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cObSocial.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cUtiles.php");

$token = $_REQUEST['token'];
$nrodoc = $_REQUEST['nrodoc'];
$codos = $_REQUEST['codos'];

//$codos='121057';
//$numerosocio = '201933';
//$ordensocio = '0';
//$token = 'E2AA4F';

//$n = '20193300';

//$i = strlen($nrodoc);
//echo substr($n, 0, $i-2) .  '   ' . substr($n, $i-2, $i-1);

$i = strlen($nrodoc);
$numerosocio = substr($nrodoc, 0, $i-2); 
$ordensocio = substr($nrodoc, $i-2, $i-1);

$obsocial = new cObsocial();
$utiles = new cUtiles();

$obsocial->verificarRPC($codos);

$json_data = '{
    "url":"' . $obsocial->getParametro13() . '",
    "grant_type":"client_credentials",
    "client_id":"' .  $obsocial->getParametro11() . '",
    "client_secret":"' . $obsocial->getParametro12() .'",
    "numerosocio":"' . $numerosocio . '",
    "ordensocio":"' . $ordensocio . '",
    "token":"' . $token . '"
}';





/*
echo $json_data;
echo ' <br/>';

$json_data = '{
    "url":"https://apis.jerarquicos.com:10712/auth/External/token",
    "grant_type":"client_credentials",
    "client_id":"108A279A-7900-4B36-9EA6-698BEC77E869",
    "client_secret":"gdEIlOE0bzzdit6WmpR5xY0n9T51vpcm",
    "numerosocio":"201933",
    "ordensocio":"0",
    "token":"E2AA4F"
}';


echo $json_data;
*/ 

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

$post = file_get_contents($url, false, $context); //, -1, $l);

if ($post) {

    $json = $utiles->parseJson($post);
    
    echo $json;
    echo '<br/>';
    
    $result = json_decode($json);
    echo utf8_decode($result->token);

} else {
    echo "PUT failed";
}

?>

