<?php

$url = "http://localhost:10060/api/federada/test";

// Create a stream
$opts = array(
  'http'=>array(
    'method'=>"GET",
    'header'=>"Accept-language: en\r\n" .
              "Cookie: foo=bar\r\n"
  )
);

$context = stream_context_create($opts);

// Open the file using the HTTP headers set above
$file = file_get_contents($url, false, $context);
//$resultado = $rest->sendGet("http://localhost:8090/efectores", null);
var_dump($file);

echo "<br>" . "---------------------------------------------" . '<br/>';

$obj=json_decode($file);  
echo $obj->o_GruNro;
echo $obj->o_NroDoc;
echo $obj->o_Apellido;
echo $obj->o_Nombres;

?>

