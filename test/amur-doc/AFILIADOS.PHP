<?
require('lib/nusoap.php');
$client = new soapclient('http://www.amur.com.ar/administracion/webservices/WSValidadorAMUR.php?wsdl', 'wsdl');
//$client = new soapclient('http://localhost/public_html/administracion/webservices/WSValidadorAMUR.php?wsdl', 'wsdl');

//Autorizacion
$usuario = 'P00031';
$pass = 'w00031centro';
$ns = '5708701';
$nd = '31069532';
$fecha = '2015-04-14';

$result = $client->call('fnafiliado', array('usuario' => $usuario, 'pass' => $pass, 'ns' => $ns, 'nd' => $nd, 'fecha' => $fecha));
				
// Verificación de los parametros
if ($cliente->fault){
	echo 'ERROR: '.print_r($result);
}else{
 	echo 'USUARIO:              '.$result['usuario'].'<br>';
	echo 'FECHA DE LA CONSULTA: '.$result['fecha'].'<br>';
	echo 'COD. RTA GENERAL:     '.$result['respuesta'].'<br>';
	echo 'DESCRIPCIÓN:          '.$result['detalle'].'<br>';
	echo 'AFILIADO:             '.$result['apno'].'<br>';
	echo 'SEXO:                 '.$result['sexo'].'<br>';
	echo 'LOCALIDAD:            '.$result['cp'].' - '.$result['localidad'].'<br>';
	echo 'PROVINCIA             '.$result['provincia'].'<br>';
	echo 'PAIS:                 '.$result['pais'].'<br>';
	echo 'GRAV. O EXENTO:       '.$result['tiva'].'<br>';
	echo 'NRO. AFILIADO:        '.$result['ns'].'<br>';
	echo 'TIPO ADHERENTE:       '.$result['tipoAdherente'].'<br>';
	echo 'TIPO DOCUMENTO:       '.$result['tipoDocumento'].'<br>';
	echo 'NRO. DOCUMENTO:       '.$result['nroDocumento'].'<br>';
	echo 'PLAN:                 '.$result['plan'].'<br>';
	echo 'OBRA SOCIAL:          '.$result['obraSocial'];
}

/*/ Display the request and response
echo '<h2>Request</h2>';
echo '<pre>' . htmlspecialchars($cliente->request, ENT_QUOTES) . '</pre>';
echo '<h2>Response</h2>';
echo '<pre>' . htmlspecialchars($cliente->response, ENT_QUOTES) . '</pre>';
// Display the debug messages
echo '<h2>Debug</h2>';
echo '<pre>' . htmlspecialchars($cliente->debug_str, ENT_QUOTES) . '</pre>';
/*/

?>