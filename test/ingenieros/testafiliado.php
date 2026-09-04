<?php

$url = "http://localhost:10060/api/federada/afiliado";

// Create a stream
$opts = array(
  'http'=>array(
    'method'=>"GET",
    'header'=>"Accept-language: en\r\n" .
              "Cookie: foo=bar\r\n"
  )
);

$parameters = '{"url":"https://api-test.federada.com/","metodo":"validador/v1.5.2/wsvol000","api":"x-api-key","apikey":"OmUu2GSw1R1a4QqESLaK48YdXGy90Zx62TO7TDX7"}';

$data = '{"p_Prestador":"600627",'
        . '"p_SubPrestador":"600627",'
        . '"p_IntNro":"1",'
        . '"p_NroDoc":"23501639"}';
  


$context = stream_context_create($opts);

$theurl = $url . "?id=" . $data . '&key1=' . $parameters;
 echo $theurl . '<hr>';
$file = file_get_contents($theurl, false, $context);
var_dump($file);

echo "<br>" . "---------------------------------------------" . '<br/>';

$obj=json_decode($file);  
echo $obj->o_GruNro;
echo $obj->o_NroDoc;
echo $obj->o_Apellido;
echo $obj->o_Nombres;
echo $obj->o_NroDoc;

?>

