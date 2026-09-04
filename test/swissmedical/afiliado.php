<?php

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

$afiliado = '{
        "creden":"8000060041969070005",
        "alta":"20200421",
        "fecdif":"20200421"
        }';

$parameters = '{"url":"https://mobileint.swissmedical.com.ar/pre/api-smg/v0/auth-login","metodo":"https://mobileint.swissmedical.com.ar/pre/api-smg/v1.0/prestadores/hl7/elegibilidad","api":"Authorization","apikey":"Basic 81ba7db467d68def9a81","username":"suap","password":"suap2018"}';              

$json_data = '{"parameters":' . $parameters . ',' .
             '"afiliado":' . $afiliado . ',' .
             '"token":' . $data . '}';

echo $json_data;


//$url = 'http://localhost:10060/api/swissmedical/afiliado';  
$url = 'http://www.bioreconquista.com.ar/api/swissmedical/afiliado';  

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

//{"status":500,"message":"Hubo un error interno en el servidor","trace":null}

if ($post) {
    echo $post;
    echo '<br/>';
    $result = json_decode($post);
    echo '<hr/>' . $result->status;
} else {
    echo "PUT failed";    
}
    
?>

