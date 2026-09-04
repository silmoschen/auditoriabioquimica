<?php


include_once("cWsRespuestas.php");

$url = "http://localhost:10060/api/federada/consultapractica";
$url = "http://www.bioreconquista.com.ar/api/federada/consultapractica";

// Create a stream
$opts = array(
    'http' => array(
        'method' => "GET",
        'header' => "Accept-language: en\r\n" .
        "Cookie: foo=bar\r\n"
    )
);

$parameters = '{"url":"https://api-test.federada.com/","metodo":"validador/v1.5.2/wsvolca1","api":"x-api-key","apikey":"OmUu2GSw1R1a4QqESLaK48YdXGy90Zx62TO7TDX7"}';

$wsres = new cWsRespuestas();

$query = $wsres->getSQL1(2, '121029', '23501639', '2728061');

$respuesta = '{}';
while ($l = mysql_fetch_array($query)) {
    $respuesta = $l['respuesta'];
}

echo $respuesta;

$result = json_decode($respuesta);

echo '<hr/>';

//echo $result->o_NroSolicitud;

for ($p = 0; $p < count($result->o_ListaPrestacionesValidadas); $p++) {
    //echo $result->o_ListaPrestacionesValidadas[$p]->PstCod . ' ' . $result->o_ListaPrestacionesValidadas[$p]->NroAutorizacion  . '<br/>';
    
    $data = '{"p_Prestador":"600627",'
        . '"p_SubPrestador":"600627",'
        . '"p_SubPreCUIT":null,'
        . '"p_NroAutorizacion":"' . $result->o_ListaPrestacionesValidadas[$p]->NroAutorizacion . '"}';
    
    
    $theurl = 'http://www.bioreconquista.com.ar/api/federada/consultapractica?id=' . $data . '&key1={"url":"https://api-test.federada.com/","metodo":"validador/v1.5.2/wsvolca1","api":"x-api-key","apikey":"OmUu2GSw1R1a4QqESLaK48YdXGy90Zx62TO7TDX7"}';

    $file = file_get_contents($theurl, false, $context);
    
    $array = json_decode($file);
    
    //echo $theurl;
    
    //echo '<hr/>';
    
    echo $array->o_StatusAutorizacion . '  ' . $array->o_Comentario . ' ' . $result->o_ListaPrestacionesValidadas[$p]->PstCod . ' ' . $result->o_ListaPrestacionesValidadas[$p]->NroAutorizacion  . '<br/>'; 
           
    
}

echo '<hr/>';

?>

