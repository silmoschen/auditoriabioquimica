<?php

//$url = 'http://www.shmsoft.com.ar/api/prevencion/afiliado';
$url = 'http://www.shmsoft.com.ar/api/iapos/22686435';
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
 * 
 */

$post = file_get_contents($url, false, null); //, -1, $l);

//$response = json_decode($post);

echo $post;

/*
if ($post) {
    echo $response->Nrodoc . '  ' . $response->Nombre . '  ' . $response->Retiva . '  ' . $response->Error;
    echo '<hr/>';
    echo $post;
    echo '<hr/>';
    echo $response->Mensaje;
} else {
    echo "PUT failed";    
}
 * 
 */

?>