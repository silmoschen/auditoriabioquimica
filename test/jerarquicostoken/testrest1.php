<?php

$json_data = '{
    "url":"https://apis.jerarquicos.com:10712/auth/External/token",
    "grant_type":"client_credentials",
    "client_id":"108A279A-7900-4B36-9EA6-698BEC77E869",
    "client_secret":"gdEIlOE0bzzdit6WmpR5xY0n9T51vpcm"
}';

$url = 'http://localhost:5054/api/jerarquicos/test';
//$url = 'http:/localhost:5437/api/prevencion/afiliado';
$context = stream_context_create(array(
    'http' => array(
        'protocol_version' => 1.1,
        'user_agent' => 'PHPExample',
        "Cookie => foo=bar\r\n",
        'method' => 'PUT',
        'header' => "Content-type: application/json\r\n" .
        "Connection: close\r\n" .
        "Content-length: " . strlen($json_data) . "\r\n",
        'content' => $json_data,
        'Expect' => '100-continue'
    ),
        ));

$post = file_get_contents($url, false, $context); //, -1, $l);

if ($post) {    

    echo $post;
    echo '<br/>';    
  
} else {
    echo "PUT failed";
}

?>