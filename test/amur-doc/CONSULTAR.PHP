<?
require('lib/nusoap.php');
$client = new soapclient('http://www.amur.com.ar/administracion/webservices/WSValidadorAMUR.php?wsdl', 'wsdl');
//$client = new soapclient('http://localhost/public_html/administracion/webservices/WSValidadorAMUR.php?wsdl', 'wsdl');

//Autorizacion
$usuario = 'P00031';
$pass = 'w00031centro';
$naut = '000005';
$faut = '2015-04-14';

$result = $client->call('fnconsulta', array('usuario' => $usuario, 'pass' => $pass, 'naut' => $naut, 'faut' => $faut));

echo 'Nº DE AUTORIZACIÓN: '.$result['naut'].'<BR>';
echo 'FECHA DE AUTORIZACIÓN: '.$result['faut'].'<BR>';
echo 'DETALLE: '.$result['estado'].' : '.$result['mensajes'].'<BR>';

echo '<table border=1>';
echo '<tr><td>TNCL</td><td>CPRE</td><td>NPRE</td><td>CINTMED</td><td>CDIEN</td><td>CDET</td><td>CANTP</td><td>CANTA</td><td>MONTO</td><td>COSEG</td></tr>';

$prestacion = $result['prestaciones'];
for($p = 0; $p < count($prestacion); $p++){
	echo '<tr><td>'.$prestacion[$p]['tncl'].'</td><td>'.$prestacion[$p]['cpre'].'</td>';
	echo '<td>'.$prestacion[$p]['npre'].'</td><td>'.$prestacion[$p]['cintmed'].'</td>';
	echo '<td>'.$prestacion[$p]['cdien'].'</td><td>'.$prestacion[$p]['cdet'].'</td>';
	echo '<td>'.$prestacion[$p]['cantp'].'</td><td>'.$prestacion[$p]['canta'].'</td>';
	echo '<td>'.$prestacion[$p]['mfact'].'</td><td>'.$prestacion[$p]['mcose'].'</td></tr>';
	echo '<tr><td>'.$prestacion[$p]['estado'].'</td><td colspan="9">'.$prestacion[$p]['motivo'].'</td></tr>';
}
echo '</table>';
?>