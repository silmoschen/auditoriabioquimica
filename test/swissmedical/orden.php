<?php

/*
  $json = '{"cabecera":{"transacAlta":"20190719 16:25:01","transac":196192958,"rechaCabecera":0,"rechaCabeDeno":"Ok","apeNom":"SOCIO,PRUEBA ","gravado":"0","planCodi":"350400I ","pmi":null,"sexo":"F","edad":49,"leyimp":null,"icdDeno":null,"nomPrestad":"Centro de Bioqu","sucursal":null,"autoriz":null},"detalle":[{"transac":196192958,"canti":1,"recha":0,"denoItem":"Hemograma","valorCopa":0.0},{"transac":196192959,"canti":1,"recha":0,"denoItem":"Glucemia","valorCopa":0.0},{"transac":196192960,"canti":0,"recha":30,"denoItem":"Tope Diario - Req.HC","valorCopa":0.0}]}';

  $result = json_decode($json);
  echo '<hr/>' . $result->cabecera->transac . '<br/>';

  for ($p = 0; $p < count($result->detalle); $p++) {

  echo $result->detalle[$p]->denoItem . ' ' . $result->detalle[$p]->recha . '<br/>';
  }


  return;
 */


include_once($_SERVER['DOCUMENT_ROOT'] . "/reco/classes/cUtiles.php");
$utiles = new cUtiles;

$efector = "000001";
$ns = "8000067180171001136";
$fecha = $utiles->getFechaAAAAMMDD($utiles->getFechaActual());

$_codigos[0] = '660475';
$_codigos[1] = '660412';
$_codigos[2] = '660001';

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
"param2" : null,
"param3" : null,
"idEfector": "' . $efector . '",    
"idPrescr": "' . $efector . '"           
}';

$data = '{
  "apiKey": "81ba7db467d68def9a81",
  "usrLoginName": "suap",
  "password": "suap2018",
  "cuit": "30582312059",
  "device": {
    "messagingid" : "132H12312",
    "deviceid": "192.168.45.77",
    "devicename": "DELL-2Y0-DG",
    "bloqueado": 0,
    "recordar": 0
  }
}';

$parameters = '{"url":"https://mobileint.swissmedical.com.ar/pre/api-smg/v0/auth-login","metodo":"https://mobileint.swissmedical.com.ar/pre/api-smg/v1.0/prestadores/hl7/registracion","api":"Authorization","apikey":"Basic 81ba7db467d68def9a81","username":"suap","password":"suap2018"}';

$json_data = '{"parameters":' . $parameters . ',' .
        '"orden":' . $orden . ',' .
        '"token":' . $data . '}';

var_dump($json_data);
echo '<hr/>';

$url = 'http://localhost:10060/api/swissmedical/orden';

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
//var_dump($post);
//$response = json_decode($post);
//{"status":500,"message":"Hubo un error interno en el servidor","trace":null}

if ($post) {
    echo $post;
    echo '<br/>';

    $result = json_decode($post);
    echo '<hr/>' . $result->cabecera->transac . '<br/>';

    for ($p = 0; $p < count($result->detalle); $p++) {

        echo $result->detalle[$p]->denoItem . ' ' . $result->detalle[$p]->recha . '<br/>';
    }
} else {
    echo "PUT failed";
}
?>

