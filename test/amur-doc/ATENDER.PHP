<?
require('lib/nusoap.php');
$client = new soapclient('http://www.amur.com.ar/administracion/webservices/WSValidadorAMUR.php?wsdl', 'wsdl');
//$client = new soapclient('http://localhost/public_html/administracion/webservices/WSValidadorAMUR.php?wsdl', 'wsdl');
//prestacion
	
$prestacion[] = array(
	'tncl' 		=> '4',
	'cpre' 		=> '711',
	'cdien' 	=> '',
	'cdet' 		=> '00',
	'faten' 	=> '2015-04-13',
	'haten' 	=> '15'
);
$prestacion[] = array(
	'tncl' 		=> '4',
	'cpre' 		=> '736',
	'cdien' 	=> '',
	'cdet' 		=> '00',
	'faten' 	=> '2015-04-13',
	'haten' 	=> '15'
);

//Autorizacion
$usuario = 'P00031';
$pass = 'w00031centro';
$naut = '000005';
$faut = '2015-04-14';

$result = $client->call('fnatencion', array('usuario' => $usuario, 'pass' => $pass, 'naut' => $naut, 'faut' => $faut, 'prestaciones' => $prestacion));

if ($result['naut'] == ''){
	echo 'ESTADO: '.$result['estado'].'<BR>';
	echo 'DETALLE: '.$result['mensajes'];
}else{
	echo 'Nº DE AUTORIZACIÓN: '.$result['naut'].'<BR>';
	echo 'FECHA DE AUTORIZACIÓN: '.$result['faut'].'<BR>';
	echo 'DETALLE: '.$result['estado'].' : '.$result['mensajes'].'<BR>';
	echo '<table border=1>';
	echo '<tr><td>TNCL</td><td>CPRE</td><td>CDIEN</td><td>CDET</td><td>FATEN</td><td>HATEN</td></tr>';

	$prestacion = $result['prestaciones'];
	for($p = 0; $p < count($prestacion); $p++){
		echo '<tr><td>'.$prestacion[$p]['tncl'].'</td><td>'.$prestacion[$p]['cpre'].'</td>';
		echo '<td>'.$prestacion[$p]['cdien'].'</td><td>'.$prestacion[$p]['cdet'].'</td>';
		echo '<td>'.$prestacion[$p]['faten'].'</td><td>'.$prestacion[$p]['haten'].'</td></tr>';
	}
	echo '</table>';
}
?>