<?php

$data = '{
    "url":"https://apis.jerarquicos.com:10712/auth/External/token",
    "grant_type":"client_credentials",
    "client_id":"108A279A-7900-4B36-9EA6-698BEC77E869",
    "client_secret":"gdEIlOE0bzzdit6WmpR5xY0n9T51vpcm"
}';

$json_data = '{"data":' . $data . '}';
/*
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
*/
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

    $file = file_get_contents('http://www.shmsoft.com.ar/api/jerarquicos/test1', false, $context);


echo $file . '<hr/>';

/*
$url = 'http://www.shmsoft.com.ar/api/jerarquicos/token';
$url = 'http://localhost:5054/api/jerarquicos/test1';
$url = 'http://localhost:5054/api/test';
$url = 'http://localhost:5054/api/jerarquicos/test1';

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

var_dump($post);

//$response = json_decode($post);
//echo $response;

if ($post) {
    //echo $post;
    //echo '<br/>';
    $result = json_decode($post);
    //echo $post;
    //echo 'TOKEN: ' . $result->token;
    /*
    for ($p = 0; $p < count($result->Prestaciones); $p++) {
        echo $result->Prestaciones[$p]->Codigo . '<br/>'; 
        //PrestacionesRtaItem[$p]->Prestacion;
    }
      
     
    
} else {
    echo "PUT failed";    
}
    */
?>

