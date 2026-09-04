<?php

date_default_timezone_set('America/Argentina/Ushuaia');
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
<ID>12504718</ID>
<VERIFID>MANUAL</VERIFID>
</PID>

<CONTEXTO>
<TIPO>A</TIPO>
</CONTEXTO>

</SOLICITUD>';

$url = "https://caws.acasalud.com.ar:4444/cawsProd/Servicios?WSDL";
$opts = array(
    'ssl' => array(
        'ciphers' => 'RC4-SHA',
        'verify_peer' => false,
        'verify_peer_name' => false
    )
);
$params = array(
    'encoding' => 'UTF-8',
    'verifypeer' => false,
    'verifyhost' => false,
    // 'soap_version' => SOAP_1_2,
    'trace' => 1,
    'location' => $url,
    'exceptions' => 1,
    'connection_timeout' => 180,
    'stream_context' => stream_context_create($opts)
);

try {
    $client = new SoapClient($url, $params);


    //$client = new SoapClient($url, $options);

    $resultados = $client->transaccionStr($rpc_xml);

    $lector = new SimpleXMLElement($resultados);

    var_dump($resultados);

    echo $lector->AFIAPE . ' ' . $lector->AFINOM;

    echo '<p/> Fin Test - OK';
} catch (Exception $e) {
    echo ($e->getMessage());
    echo ($client->__getLastRequest());
    echo ($client->__getLastResponse());
}
?>

