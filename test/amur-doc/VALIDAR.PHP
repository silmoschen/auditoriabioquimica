<?
require('lib/nusoap.php');
$client = new soapclient('http://www.amur.com.ar/administracion/webservices/WSValidadorAMUR.php?wsdl', 'wsdl');
//$client = new soapclient('http://localhost/public_html/administracion/webservices/WSValidadorAMUR.php?wsdl', 'wsdl');
//prestacion
$prestacion[] = array(
	'tncl' 		=> '4',
	'cpre' 		=> '711',
	'npre' 		=> 'ORINA COMPLETA O PARCIAL',
	'cintmed' 	=> '',
	'cdien' 	=> '',
	'cdet' 		=> '00',
	'cantp' 	=> '1',
	'canta' 	=> '0',
	'mfact' 	=> '0',
	'mcose' 	=> '0',
	'motivo'	=> '',
	'estado'	=> ''
);
$prestacion[] = array(
	'tncl' 		=> '4',
	'cpre' 		=> '736',
	'npre' 		=> 'PARASITOLOGICO DE MATERIA FECAL SERIADO',
	'cintmed' 	=> '',
	'cdien' 	=> '',
	'cdet' 		=> '00',
	'cantp' 	=> '1',
	'canta' 	=> '0',
	'mfact' 	=> '0',
	'mcose' 	=> '0',
	'motivo'	=> '',
	'estado'	=> ''
);

//Autorizacion
$usuario	= 'P00031';
$pass		= 'w00031centro';
$ns			= '07835/11';
$apno		= 'ROLDAN LISANDRO ENRIQUE';
$ndoc		= '27039952';
$cuit 		= '30582312059';
$matrimed 	= '90     ';
$matritipo 	= 'PS';
$matriapno 	= 'SOLDANO ELADIO';
$diag1 		= 'Y570';
$diag2 		= '';
$diag3 		= '';
$diaga		= '[AGREGAR DIAG. AMPL.]';
$fpres		= '2015-04-14';
$tipo		= 'A';
$fint		= '0000-00-00';
$hint		= '00';
$cint		= '';
$tint		= '';
$obs		= '';

$result = $client->call('fnautoriza', array('usuario' => $usuario, 'pass' => $pass, 'ns' => $ns, 'apno' => $apno, 'ndoc' => $ndoc, 'cuit' => $cuit, 'matrimed' => $matrimed, 'matritipo' => $matritipo, 'matriapno' => $matriapno, 'diag1' => $diag1, 'diag2' => $diag2, 'diag3' => $diag3, 'diaga' => $diaga, 'fpres' => $fpres, 'tipo' => $tipo, 'fint' => $fint, 'hint' => $hint, 'cint' => $cint, 'tint' => $tint, 'obs' => $obs, 'prestaciones' => $prestacion));

if ($result['naut'] == ''){
	echo 'ESTADO: '.$result['estado'].'<BR>';
	echo 'DETALLE: '.$result['mensajes'];
}else{
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
}
?>