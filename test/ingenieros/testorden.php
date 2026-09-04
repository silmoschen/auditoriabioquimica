<?php

$url = "http://localhost:10060/api/federada/orden";

$parameters = '{"url":"https://api-test.federada.com/","metodo":"validador/v1.5.2/wsvol001","api":"x-api-key","apikey":"OmUu2GSw1R1a4QqESLaK48YdXGy90Zx62TO7TDX7"}';

$prestaciones = '[';

//$linea = '{"NroLinea":"1","FecPre":"20190125","PtiCod":"N","PstCod":"660475","Cantidad":"1","CodDiagno":"R10","DesDiagno":"Fiebre","ComPresta":"test","CUITProf":null}';
//$linea = '{"NroLinea":"1","FecPre":"20190125","PtiCod":"N","PstCod":"660475","Cantidad":"1","CodDiagno":"R10"}';  // ,"DesDiagno":"Fiebre","ComPresta":"test","CUITProf":null}';
$linea = '{"NroLinea":"1","FecPre":"20190218","PtiCod":"N","PstCod":"660475","Cantidad":"1","CodDiagno":"R10","DesDiagno":"-","ComPresta":"test","CUITProf":null}';
$prestaciones = $prestaciones . $linea . ',';

//$linea = '{"NroLinea":"2","FecPre":"20190125","PtiCod":"N","PstCod":"660001","Cantidad":"1","CodDiagno":"R10"}'; //,"DesDiagno":"Fiebre","ComPresta":"test","CUITProf":null}';
$linea = '{"NroLinea":"2","FecPre":"20190218","PtiCod":"N","PstCod":"660001","Cantidad":"1","CodDiagno":"R10","DesDiagno":"-","ComPresta":"test","CUITProf":null}';
$prestaciones = $prestaciones . $linea;

$prestaciones = $prestaciones .'],';


$data = '{"p_Modo":"A",'        
        . '"p_Prestador":"600627",'
        . '"p_SubPrestador":"600627",'
        . '"p_SubPreCUIT":"600627",'             
        . '"p_IntNro":"1",'
        . '"p_NroDoc":"31136977",'
        . '"p_Situacion":"0",'        
        . '"p_ListaPrestaciones":' . $prestaciones 
        . '"p_Archivos":[]}';



$json_data = '{"parameters":' . $parameters . ',' .
             '"data":' . $data . '}"';
//echo $json_data;

$url = 'http://www.bioreconquista.com.ar/api/federada/orden';

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


return;
























// Create a stream
$opts = array(
  'http'=>array(
    'method'=>"GET",
    'header'=>"Accept-language: en\r\n" .
              "Cookie: foo=bar\r\n"
  )
);

$parameters = '{"url":"https://api-test.federada.com/","metodo":"validador/v1.5.2/wsvol001","api":"x-api-key","apikey":"OmUu2GSw1R1a4QqESLaK48YdXGy90Zx62TO7TDX7"}';

$prestaciones = '[';

//$linea = '{"NroLinea":"1","FecPre":"20190125","PtiCod":"N","PstCod":"660475","Cantidad":"1","CodDiagno":"R10","DesDiagno":"Fiebre","ComPresta":"test","CUITProf":null}';
//$linea = '{"NroLinea":"1","FecPre":"20190125","PtiCod":"N","PstCod":"660475","Cantidad":"1","CodDiagno":"R10"}';  // ,"DesDiagno":"Fiebre","ComPresta":"test","CUITProf":null}';
$linea = '{"NroLinea":"1","FecPre":"20190125","PtiCod":"N","PstCod":"660475","Cantidad":"1","CodDiagno":"R10","DesDiagno":"-","ComPresta":"test","CUITProf":null}';
$prestaciones = $prestaciones . $linea . ',';

//$linea = '{"NroLinea":"2","FecPre":"20190125","PtiCod":"N","PstCod":"660001","Cantidad":"1","CodDiagno":"R10"}'; //,"DesDiagno":"Fiebre","ComPresta":"test","CUITProf":null}';
$linea = '{"NroLinea":"2","FecPre":"20190125","PtiCod":"N","PstCod":"660001","Cantidad":"1","CodDiagno":"R10","DesDiagno":"-","ComPresta":"test","CUITProf":null}';
$prestaciones = $prestaciones . $linea;

$prestaciones = $prestaciones .'],';


$data = '{"p_Modo":"A",'        
        . '"p_Prestador":"600627",'
        . '"p_SubPrestador":"600627",'
        . '"p_SubPreCUIT":"600627",'             
        . '"p_IntNro":"1",'
        . '"p_NroDoc":"23501639",'
        . '"p_Situacion":"0",'        
        . '"p_ListaPrestaciones":' . $prestaciones 
        . '"p_Archivos":[]}';

echo $data;



//$data = '{"p_Modo":"V","p_Prestador":"600627","p_SubPrestador":"600627","p_SubPreCUIT":"600627","p_GruNro":"182105","p_IntNro":"1","p_NroDoc":"23501639","p_Situacion":"0","p_ListaPrestaciones":[{"NroLinea":"1","FecPre":"20190125","PtiCod":"N","PstCod":"660475","Cantidad":"1","CodDiagno":"DIAG","DesDiagno":"Fiebre","ComPresta":"test","CUITProf":null},{"NroLinea":"2","FecPre":"20190125","PtiCod":"N","PstCod":"660005","Cantidad":"1","CodDiagno":"DIAG","DesDiagno":"Fiebre","ComPresta":"test","CUITProf":null}],"p_Archivos":[]}';

$theurl = 'http://localhost:10060/api/federada/orden?id=' . $data . '&key1={"url":"https://api-test.federada.com/","metodo":"validador/v1.5.2/wsvol001","api":"x-api-key","apikey":"OmUu2GSw1R1a4QqESLaK48YdXGy90Zx62TO7TDX7"}';

$file = file_get_contents($theurl, false, $context);

echo '<hr/>';

$array = json_decode($file);

$result = '{[o_ListaPrestacionesValidadas] => Array ( [0] => stdClass Object ( [NroLinea] => 1 [FecPre] => 2019-01-29 [PtiCod] => N [PstCod] => 660475 [PstDes] => HEMOGRAMA [Cantidad] => 1 [StatusPre] => AA [NroAutorizacion] => 1202999 [Comentario] => [512] SUPERO TOPE. Justificar Nuevo Pedido. [CodDiagno] => R10 [DesDiagno] => - [ComPresta] => test [PoseeCopCos] => N [ComentCopCos] => [ComInfoPre] => ) [1] => stdClass Object ( [NroLinea] => 2 [FecPre] => 2019-01-29 [PtiCod] => N [PstCod] => 660415 [PstDes] => GLUCOGENO, CITOQUIMICO (*) [Cantidad] => 1 [StatusPre] => SI [NroAutorizacion] => 1203000 [Comentario] => [500] PrestaciÃ³n autorizada [CodDiagno] => R10 [DesDiagno] => - [ComPresta] => test [PoseeCopCos] => N [ComentCopCos] => [ComInfoPre] => ) [2] => stdClass Object ( [NroLinea] => 3 [FecPre] => 2019-01-29 [PtiCod] => N [PstCod] => 660711 [PstDes] => ORINA COMPLETA [Cantidad] => 1 [StatusPre] => AA [NroAutorizacion] => 1203001 [Comentario] => [512] SUPERO TOPE. Justificar Nuevo Pedido. [CodDiagno] => R10 [DesDiagno] => - [ComPresta] => test [PoseeCopCos] => N [ComentCopCos] => [ComInfoPre] => ) [3] => stdClass Object ( [NroLinea] => 4 [FecPre] => 2019-01-29 [PtiCod] => N [PstCod] => 660865 [PstDes] => TSH TIROTROFINA [Cantidad] => 1 [StatusPre] => SI [NroAutorizacion] => 1203002 [Comentario] => [500] PrestaciÃ³n autorizada [CodDiagno] => R10 [DesDiagno] => - [ComPresta] => test [PoseeCopCos] => N [ComentCopCos] => [ComInfoPre] => ) [4] => stdClass Object ( [NroLinea] => 5 [FecPre] => 2019-01-29 [PtiCod] => N [PstCod] => 660001 [PstDes] => ACTO BIOQUIMICO [Cantidad] => 1 [StatusPre] => SI [NroAutorizacion] => 1203003 [Comentario] => [500] PrestaciÃ³n autorizada [CodDiagno] => R10 [DesDiagno] => - [ComPresta] => test [PoseeCopCos] => N [ComentCopCos] => [ComInfoPre] => ) ) [o_Status] => AA [o_NroSolicitud] => 2728058 [o_Comentario] => [524] Alguna prestaciÃ³n requiere autorizaciÃ³n. )}';

print_r($array);

echo "<br>" . "---------------------------------------------" . '<br/>';

echo $result->o_Comentario;


echo '<p/>';

for ($p = 0; $p < count($array->o_ListaPrestacionesValidadas); $p++) {
    echo $array->o_ListaPrestacionesValidadas[$p]->PstCod . '<br/>';
}

return;

/*
for ($p = 0; $p < count($prestaciones[0]->PrestacionesValidarRta); $p++) {
                        $prestacion = $prestaciones[0]->PrestacionesValidarRta[$p]->Prestacion;
                        $requiere = $prestaciones[0]->PrestacionesValidarRta[$p]->Requiere;
}
*/



$context = stream_context_create($opts);

$theurl = $url . "?id=" . $data . '&key1=' . $parameters;
 
$file = file_get_contents($theurl, false, $context);
var_dump($file);

echo "<br>" . "---------------------------------------------" . '<br/>';

$array = json_decode($file);  

echo $array->o_Comentario;


echo '<p/>';

for ($p = 0; $p < count($array->o_ListaPrestacionesValidadas); $p++) {
    echo $array->o_ListaPrestacionesValidadas[$p]->PstCod . '<br/>';
}


/*
echo $obj->o_GruNro;
echo $obj->o_NroDoc;
echo $obj->o_Apellido;
echo $obj->o_Nombres;
echo $obj->o_NroDoc;
 * 
 */

?>

