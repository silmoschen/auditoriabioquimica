<?

$parameters = '{}';

$data = '{"nrodoc":"22125240"}';

$json_data = $data;
//echo $json_data;

$url = 'http://www.bioreconquista.com.ar/api/fesalud/afiliado';
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

//$response = json_decode($post);

if ($post) {
    echo $post;
} else {
    echo "PUT failed";    
}

?>

