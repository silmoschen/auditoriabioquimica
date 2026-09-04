<?php

$json_data = '{
    "url":"https://apis.jerarquicos.com:10712",
    "grant_type":"client_credentials",
    "client_id":"108A279A-7900-4B36-9EA6-698BEC77E869",
    "client_secret":"gdEIlOE0bzzdit6WmpR5xY0n9T51vpcm",
    "numerosocio":"201933",
    "ordensocio":"0",
    "token":"E2AA4F"
}';


$url = 'http://localhost:5054/api/jerarquicos/token';
$url = 'http://www.shmsoft.com.ar/api/jerarquicos/token';
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

} else {
    echo "PUT failed";
}
?>

