<?php

$url = "http://localhost:10060/api/prevencion/orden";

$transaccion = '3144489';

$json_data = '{"pUser": "IA000001", "pPwd": "IA000001", "IdentificadorAfiliado": "98902020", "IDPrestador": "30700357194", "parametro2": "' . $transaccion . '", "parametro1": "TRIA00000001"' . '}';

echo $json_data;

echo '<hr/>';

$url = 'http://localhost:10060/api/prevencion/orden';
$url = 'http://www.shmsoft.com.ar/api/prevencion/anular';
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

$response = json_decode($post);

echo $post;

echo '<hr/>';

echo $response->Mensaje;

if ($post) {
    //echo $post;
} else {
    echo "PUT failed";    
}
?>

