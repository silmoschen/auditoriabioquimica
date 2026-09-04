<?


$nrodoc = '13999553';
$turl = 'https://aoapiprueba.cajaingenieria.org.ar/api/Afiliado/' . $nrodoc ;
$parameters1 = '{"url":"' . $turl . '","metodo":"","api":"Authorization","apikey":"Basic c2lsdmlvbToxMjM0","username":"silviom","password":"1234" }';
$parameters = '{"url":"https://aoapiprueba.cajaingenieria.org.ar/api/Afiliado/13999553","metodo":"","api":"Authorization","apikey":"Basic c2lsdmlvbToxMjM0","username":"silviom","password":"1234"}';

echo $parameters1 . '<br/>' . $parameters . '<br/>';

$data = '{}';
$json_data = '{"parameters":' . $parameters . ',' .
             '"afiliado":' . $data . '}"';
//echo $json_data;



$url = 'http://localhost:10060/api/Ingenieros/afiliado';
$url = 'http://www.cblnsf.com.ar/api/Ingenieros/afiliado';
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
    $result = json_decode($post);
    echo '<br/>' . $result->EstadoRegimenAsistencial;
} else {
    echo "PUT failed";    
}

?>
