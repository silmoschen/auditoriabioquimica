<?php

$data = '{
  "apiKey": "06715fdb87c4adb2c176",
  "usrLoginName": "hl7ApiUser",
  "password": "Swiss1234",
  "cuit": "30582312059",
  "device": {
    "messagingid" : "132H12312",
    "deviceid": "192.168.45.77",
    "devicename": "DELL-2Y0-DG",
    "bloqueado": 0,
    "recordar": 0
  }
}';

$parameters = '{"url":"https://mobileint.swissmedical.com.ar/pre/api-smg/v0/auth-login","metodo":"","api":"Authorization","apikey":"Basic 81ba7db467d68def9a81","username":"suap","password":"suap2018"}';
$parameters = '{"url":"https://mobileint.swissmedical.com.ar/pre/api-smg/v0/auth-login","metodo":"","api":"Authorization","apikey":"Basic 06715fdb87c4adb2c176","username":"hl7ApiUser","password":"Swiss1234"}';
$parameters = '{"url":"https://mobile.swissmedical.com.ar/pre/api-smg/v0/auth-login","metodo":"https://mobile.swissmedical.com.ar/pre/api-smg/v1.0/prestadores/hl7/elegibilidad","api":"Authorization","apikey":"06715fdb87c4adb2c176","username":"hl7ApiUser","password":"Swiss1234"}';

$json_data = '{"parameters":' . $parameters . ',' .
             '"token":' . $data . '}';

echo $json_data . '<hr/>';


$url = 'http://localhost:10060/api/swissmedical/token'; 
$url = 'http://www.bioreconquista.com.ar/api/swissmedical/token'; 
$url = 'http://www.shmsoft.com.ar/api/swissmedical/token'; 
$url = 'http://www.cblitoralnorte.com.ar/api/swissmedical/token';

$context = stream_context_create(array(
     'http' => array(
        'protocol_version' => 1.1,
        'user_agent'       => 'PHPExample',
        "Cookie => foo=bar\r\n",
        'method'           => 'PUT',
        'header'           => "Content-type: application/json\r\n" .
                              "Connection: close\r\n" .
                              "Content-length: " . strlen($json_data) . "\r\n",                              
        'content'          => $json_data,
        'Expect' => '100-continue'        
    ),
));

$post = file_get_contents($url, false, $context); //, -1, $l);

//var_dump($post);

//$response = json_decode($post);
//echo $response;

if ($post) {
    //echo $post;
    //echo '<br/>';
    $result = json_decode($post);
    //echo $post;
    echo 'TOKEN: ' . $result->token;
    /*
    for ($p = 0; $p < count($result->Prestaciones); $p++) {
        echo $result->Prestaciones[$p]->Codigo . '<br/>'; 
        //PrestacionesRtaItem[$p]->Prestacion;
    }
     * 
     */
    
} else {
    echo "PUT failed";    
}
    
?>

