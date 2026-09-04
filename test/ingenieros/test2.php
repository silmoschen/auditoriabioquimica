<?

$hoy = time();

$article = new stdClass();
$article->title = "An example article";
$article->summary = "An example of posting JSON encoded data to a web service";

//$json_data = json_encode($article);
//$json_data = '{"p_Modo":"A.' . $hoy . '","p_Prestador":"600627","p_SubPrestador":"600627","p_SubPreCUIT":"30582312059","p_IntNro":"1","p_NroDoc":"56625207","p_Situacion":"0","p_ListaPrestaciones":[{"NroLinea":"1","FecPre":"20190218","PtiCod":"N","PstCod":"660475","Cantidad":"1","CodDiagno":"E88","DesDiagno":"-","ComPresta":"test","CUITProf":"27217218611"},{"NroLinea":"2","FecPre":"20190218","PtiCod":"N","PstCod":"660412","Cantidad":"1","CodDiagno":"E88","DesDiagno":"-","ComPresta":"test","CUITProf":"27217218611"},{"NroLinea":"3","FecPre":"20190218","PtiCod":"N","PstCod":"660481","Cantidad":"1","CodDiagno":"E88","DesDiagno":"-","ComPresta":"test","CUITProf":"27217218611"},{"NroLinea":"4","FecPre":"20190218","PtiCod":"N","PstCod":"660902","Cantidad":"1","CodDiagno":"E88","DesDiagno":"-","ComPresta":"test","CUITProf":"27217218611"},{"NroLinea":"5","FecPre":"20190218","PtiCod":"N","PstCod":"660746","Cantidad":"1","CodDiagno":"E88","DesDiagno":"-","ComPresta":"test","CUITProf":"27217218611"},{"NroLinea":"6","FecPre":"20190218","PtiCod":"N","PstCod":"660711","Cantidad":"1","CodDiagno":"E88","DesDiagno":"-","ComPresta":"test","CUITProf":"27217218611"},{"NroLinea":"7","FecPre":"20190218","PtiCod":"N","PstCod":"660736","Cantidad":"1","CodDiagno":"E88","DesDiagno":"-","ComPresta":"test","CUITProf":"27217218611"},{"NroLinea":"8","FecPre":"20190218","PtiCod":"N","PstCod":"660015","Cantidad":"1","CodDiagno":"E88","DesDiagno":"-","ComPresta":"test","CUITProf":"27217218611"},{"NroLinea":"9","FecPre":"20190218","PtiCod":"N","PstCod":"664632","Cantidad":"1","CodDiagno":"E88","DesDiagno":"-","ComPresta":"test","CUITProf":"27217218611"},{"NroLinea":"10","FecPre":"20190218","PtiCod":"N","PstCod":"664640","Cantidad":"1","CodDiagno":"E88","DesDiagno":"-","ComPresta":"test","CUITProf":"27217218611"},{"NroLinea":"11","FecPre":"20190218","PtiCod":"N","PstCod":"669622","Cantidad":"1","CodDiagno":"E88","DesDiagno":"-","ComPresta":"test","CUITProf":"27217218611"},{"NroLinea":"12","FecPre":"20190218","PtiCod":"N","PstCod":"669631","Cantidad":"1","CodDiagno":"E88","DesDiagno":"-","ComPresta":"test","CUITProf":"27217218611"},{"NroLinea":"13","FecPre":"20190218","PtiCod":"N","PstCod":"665572","Cantidad":"1","CodDiagno":"E88","DesDiagno":"-","ComPresta":"test","CUITProf":"27217218611"},{"NroLinea":"14","FecPre":"20190218","PtiCod":"N","PstCod":"665580","Cantidad":"1","CodDiagno":"E88","DesDiagno":"-","ComPresta":"test","CUITProf":"27217218611"},{"NroLinea":"15","FecPre":"20190218","PtiCod":"N","PstCod":"660001","Cantidad":"1","CodDiagno":"E88","DesDiagno":"-","ComPresta":"test","CUITProf":"27217218611"}],"p_Archivos":[]}';

$parameters = '{"url":"https://api-test.federada.com/","metodo":"validador/v1.5.2/wsvol000","api":"x-api-key","apikey":"OmUu2GSw1R1a4QqESLaK48YdXGy90Zx62TO7TDX7"}';

$data = '{"p_Prestador":"600627",'
        . '"p_SubPrestador":"600627",'
        . '"p_IntNro":"1",'
        . '"p_NroDoc":"23501639"}';

$json_data = '{"parameters":' . $parameters . ',' .
             '"afiliado":' . $data . '}"';
//echo $json_data;

$url = 'http://www.bioreconquista.com.ar/api/federada/afiliado';
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

?>

