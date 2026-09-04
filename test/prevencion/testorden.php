<?php

$url = "http://localhost:10060/api/prevencion/orden";

$_codigos[0] = '660475';
$_codigos[1] = '660412';
//$_codigos[2] = '660415';
$_codigos[2] = '660711';
$_codigos[3] = '660001';

$l = 0;
for ($i = 0; $i <= 100; $i++) {
    $codd = $_codigos[$i];
    if (strlen($codd) < 4)
        break;

    $l++;

    $practicas = $practicas . '{"codigo":"' . $codd . '", "items":' . $l . '},';
}

$prestaciones = $prestaciones . '[' . substr($practicas, 0, strlen($practicas) - 1) . ']';

$json_data = '{"pUser": "IA000001", "pPwd": "IA000001", "IdentificadorAfiliado": "98902020", "IDPrestador": "30700357194", "iddiag": "R50.9", "diagnostico": "FIEBRE, NO ESPECIFICADA", "parametro1": "TRIA00000001" , "practicas":' . $prestaciones . '}';

//echo $json_data;




$url = 'http://localhost:10060/api/prevencion/orden';
$url = 'http://www.shmsoft.com.ar/api/prevencion/orden';
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

echo $response->transaccion;

if ($post) {
    //echo $post;
} else {
    echo "PUT failed";    
}
?>

