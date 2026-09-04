<?

$parameters = '{}';

$json_data = '{ "pUser": "IA000001", "pPwd": "IA000001", "IdentificadorAfiliado": "98902020", "IDPrestador": "30700357194", "parametro1": "TRIA00000001" }';


$url = 'http://www.bioreconquista.com.ar/api/prevencion/afiliado';
//$url = 'http:/localhost:5437/api/prevencion/afiliado';
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

echo $response->Nrodoc . '  ' . $response->Nombre;

if ($post) {
    //echo $post;
} else {
    echo "PUT failed";    
}

?>

