<?php

$response = file_get_contents('http://localhost:5054/api/restroles');
$json = json_decode($response, false);

echo $response . '<hr/>';

echo $json[0]->descrip . '<hr/>';

$json_data = '{
    "id":"10",
    "descrip":"Rol PUT",
    "nivel":2
}';


$url = 'http://localhost:5054/api/restroles';
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

    $i = 0;
    $j = 0;
    for ($x = 0; $x <= strlen($post); $x++) {
        $cc = substr($post, $x, 1);

        if ($cc == '{' && $i == 0)
            $i = $x;
        if ($cc == '}')
            $j = $x;
    }

    $json = substr($post, $i, $j - ($i - 1));

    echo $json;
    echo '<br/>';
    
    $result = json_decode($json);
    echo utf8_decode($result->descrip);
    
/*

    $json_output = json_encode($post);
    $rs = json_decode($json_output);




    $result = json_decode($post);
    $r = substr($post, 3, 50);
    $s = trim(str_replace("0", "", $r));

    $result = json_decode($json);

    var_dump($result);

    echo '<br/>';

    echo $result->descrip;

    echo '<br/>';
 * 
 */
} else {
    echo "PUT failed";
}
?>

