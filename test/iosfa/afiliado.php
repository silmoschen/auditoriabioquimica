<?php

$client = new SoapClient("https://validador.iosfa.gob.ar:5081/AfiliacionesPrestadores/WebServices/WSAutorizacion.asmx?wsdl",
    array(
        "trace" => 1,
        "location" => "https://validador.iosfa.gob.ar:5081/AfiliacionesPrestadores/WebServices/WSAutorizacion.asmx",
        'exceptions' => 1,
        "stream_context" => stream_context_create(
            array(
                'ssl' => array(
                    'verify_peer'       => false,
                    'verify_peer_name'  => false,
                )
            )
        )
    ) 
);

/*
$url = 'https://validador.iosfa.gob.ar:5081/AfiliacionesPrestadores/WebServices/WSAutorizacion.asmx?wsdl';

$client = new SoapClient($url, array('cache_wsdl' => WSDL_CACHE_NONE, 'trace' => TRUE));

$pass = 'Iosfa@1';
$json = "{'Request':{'Prestador':{'Cuit':'30-51943796-8','Provincia':'CORDOBA','Localidad':'CORDOBA','UnidadDeNegocio':'HOSPITAL MILITAR','Area':'CONTADURIA','EstacionDeTrabajo': '4128'}, 'Metodo':'VALIDAR_POR_DNI', 'Parametros':{'Dni':'3698387'}}}";

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


$response = $client->__soapCall("Request", array($pas, $json));

print_r($response);

echo '<hr/>';


//print $response->{'Apenom'} . '  ' . $response->{'Estado'};
 * 
 * 
 */
?>