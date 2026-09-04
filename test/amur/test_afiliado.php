<?

//Autorizacion
$usuario = 'P00031';
$pass = 'w00031centro';
//$ns = '5708701';
//$nd = '31069532';
$fecha = '2020-03-19';

// nuevos valores
//$ns = '21311/01';   // nro. afiliado
$ns = '0795201';   // nro. afiliado
$nd = ''; // nro. documento

$url = 'http://www.amur.com.ar/administracion/webservices/WSValidadorAMUR.php?wsdl';

 $client = new SoapClient($url, array('cache_wsdl' => WSDL_CACHE_NONE, 'trace' => TRUE));

 $result = $client->__soapCall('fnafiliado', array('usuario' => $usuario, 'pass' => $pass, 'ns' => $ns, 'nd' => $nd, 'fecha' => $fecha));

/*
$url = 'http://www.amur.com.ar/administracion/webservices/WSValidadorAMUR.php?wsdl';
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
    
     $client = new SoapClient($url, $params); //$client = new SoapClient($url, $options);
*/

$result = $client->__soapCall('fnafiliado', array('usuario' => $usuario, 'pass' => $pass, 'ns' => $ns, 'nd' => $nd, 'fecha' => $fecha));



$s = print_r($result, true);


ECHO '<br>';

echo $s;

?>