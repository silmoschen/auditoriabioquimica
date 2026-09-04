<?

$parameters = '{}';

$json_data = '{ "pUser": "IA010044 ", "pPwd": "IA010044 ", "IdentificadorAfiliado": "30115445503331", "IDPrestador": "30582312059", "parametro1": "TRIA00010044" }';


//$url = 'http://www.shmsoft.com.ar/api/prevencion/afiliado';
$url = 'http://localhost:10060/api/medife/afiliado';
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

if ($post) {
    echo $response->Nrodoc . '  ' . $response->Nombre . '  ' . $response->Retiva . '  ' . $response->Error;
    echo '<hr/>';
    echo $post;
    echo '<hr/>';
    echo $response->Mensaje;
} else {
    echo "PUT failed";    
}

?>

