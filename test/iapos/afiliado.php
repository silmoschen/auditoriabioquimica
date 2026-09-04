<?php

$url = 'https://aswe.santafe.gov.ar/proxy.php/iapos/afiliados?wsdl';

$client = new SoapClient($url, array('cache_wsdl' => WSDL_CACHE_NONE, 'trace' => TRUE));

$nrodoc = '22686435';

$params = array(
    "Usuario" => 'ctobioqlitoralnorte',
    "Passwd" => '12345',
    "Nafiliado" => $nrodoc,
    "Badocnumdo" => null,
    "Tidocodigo_de_documento" => null,
    "Ogorcodigo" => null,
    "Fechpresta" => null
);

$opts = array(
    'ssl' => array(
        'ciphers' => 'RC4-SHA',
        'verify_peer' => false,
        'verify_peer_name' => false
    )
);

$params = array(
    "Usuario" => 'ctobioqlitoralnorte',
    "Passwd" => '12345',
    "Nafiliado" => $nrodoc,
    "Badocnumdo" => null,
    "Tidocodigo_de_documento" => null,
    "Ogorcodigo" => null,
    "Fechpresta" => null,
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


$response = $client->__soapCall("Execute", array($params));

print_r($response);

echo '<hr/>';


print $response->{'Apenom'} . '  ' . $response->{'Estado'};
?>