<?php

$datetime = new DateTime();

$rpc_xml = '<SOLICITUD>
<EMISOR>
<ID>00001-22222</ID>
<PROT>CA_V20</PROT>
<MSGID>10001</MSGID>
<TER>Web</TER>
<APP>HMS_CAWeb</APP>
<TIME>' . $datetime->format('Y-m-d H:i:s') . '</TIME> 
</EMISOR>
<SEGURIDAD>
<TIPOAUT>U</TIPOAUT>
<USRID>7001110001</USRID>
<USRPASS>DAT_MGR</USRPASS>
</SEGURIDAD>
<OPER>
<TIPO>ELG</TIPO>
<FECHA>' . $datetime->format('Y-m-d') . '</FECHA>
<IDASEG>ACA_SALUD</IDASEG>
<IDPRESTADOR>7001110001</IDPRESTADOR>
</OPER>
<PID>
<ID>6980111</ID>
<VERIFID>MANUAL</VERIFID>
</PID>

<CONTEXTO>
<TIPO>A</TIPO>
</CONTEXTO>

</SOLICITUD>';

$url = 'https://ca.avalian.com:443/SSCawsProd/ServiciosProd';
$json_data = '{"nrodoc":"34105816", "xmlrpc":"' . $rpc_xml . '", "url":"' . $url . '"}';

$url = 'http://www.bioreconquista.com.ar/api/avalian/afiliado';

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

$xml = simplexml_load_string($rpc_xml);

$post = file_get_contents($url, false, $context); //, -1, $l);

$response = json_decode($post);

if ($post) {
    
    echo 'Test OK' . '<br/>';

    $r = $response->Mensaje;
    
    // Saneamiento del String
    $r1 = '<S:Envelope xmlns:S="http://schemas.xmlsoap.org/soap/envelope/"><S:Body><ns0:transaccionstrResponse xmlns:ns0="http://caws/ServiciosProd.wsdl"><return>';
    $r2 = '</return></ns0:transaccionstrResponse></S:Body></S:Envelope>';
    
    $r = str_replace("&lt;","<",$r);
    $r = str_replace($r1,"",$r);
    $r = str_replace($r2,"",$r);

    $lector = simplexml_load_string($r) or die("Error: Cannot create object");

    echo $lector->AFIAPE . ' ' . $lector->AFINOM;
    
} else {
    echo "PUT failed";
}

echo '<br/>Fin Test';    
?>
