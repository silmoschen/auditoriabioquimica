<?

$parameters = '{}';

$json_data = '{"url":"https://aswe.santafe.gov.ar/proxy.php/iapos/afiliados", "usuario":"ctobioqlitoralnorte","pass":"12345","nrodoc":"22686435"}';


$url = 'http://www.cblitoralnorte.com.ar/api/iapos/afiliado';
$url = 'http://localhost:10060/api/iapos/afiliado';
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

$post = file_get_contents($url, false, $context);

$response = json_decode($post);

echo  $response->Nombre . '  ' . $response->Estado;

if ($post) {
    echo '<br/>';
    echo $post;
} else {
    echo "PUT failed";    
}

?>