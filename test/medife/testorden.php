<?php

$_codigos[0] = '660475';
$_codigos[1] = '660412';
//$_codigos[2] = '666168';
//$_codigos[3] = '660001';

$l = 0;
for ($i = 0; $i <= 100; $i++) {
    $codd = $_codigos[$i];
    if (strlen($codd) < 4)
        break;

    $l++;

    $practicas = $practicas . '{"codigo":"' . $codd . '", "items":' . $l . '},';
}

$prestaciones = $prestaciones . '[' . substr($practicas, 0, strlen($practicas) - 1) . ']';

//$json_data = '{"pUser": "IA010044 ", "pPwd": "IA010044 ", "IdentificadorAfiliado": "11111100000018", "IDPrestador": "99682737650", "parametro1": "TRIA00010044" }';
$json_data = '{"pUser": "IA010044", "pPwd": "IA010044", "IdentificadorAfiliado": "30115445503331", "IDPrestador": "30582312059", "iddiag": "R50.9", "diagnostico": "FIEBRE, NO ESPECIFICADA", "parametro1": "TRIA00010044" , "practicas":' . $prestaciones . '}';
//$json_data = '{"pUser": "IA000001", "pPwd": "IA000001", "IdentificadorAfiliado": "11111100000018", "IDPrestador": "99682737650", "iddiag": "R50.9", "diagnostico": "FIEBRE, NO ESPECIFICADA", "parametro1": "TRIA00000001" , "practicas":' . $prestaciones . '}';

//echo $json_data;

//$url = 'http://localhost:10060/api/medifetest/orden';
$url = 'http://localhost:10060/api/medife/orden';
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

echo $response->transaccion;

if ($post) {
    echo '<hr/>';
    echo $post;
    echo '<hr/>';
    echo $response->Error;
    echo '<hr/>';
    echo $response->Mensaje;
    echo '<hr/>';
    echo 'parametro1: <br/>';
    echo $response->parametro1;
    echo '<hr/>';
    echo 'parametro2: <br/>';
    echo $response->parametro2;
    echo '<hr/>';
    echo 'parametro3: <br/>';
    echo $response->parametro3;
    echo '<hr/>'; 
    echo 'parametro4: <br/>';
    echo $response->parametro4;
    echo '<hr/>';
/*
    if ($response->Estado == 'OK') {

        $it = 0;
        for ($p = 0; $p < count($response->practicas); $p++) {
            echo $response->practicas[$p]->codigo;
            echo '<br/>';    
        }
        */

} else {
    echo "PUT failed";    
}
?>