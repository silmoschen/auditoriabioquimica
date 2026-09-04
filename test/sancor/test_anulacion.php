<?
//Autorizacion
$usuario = 'WSRVSSA';
$clave = '15WSSA08';

$entidad = 135533;
$tiponroefector = 'CU';
$nroefector = 20167261885;
$formaidafiliado = 'AS';
$afiliado = '12129700';
$modo = 'T';
$tipoanulacion = '';
$nroautorizacion = '0';

$client = new SoapClient('http://e.sancorsalud.com.ar/apawe_ssa_v3.aspx?wsdl',
                    array('cache_wsdl' => WSDL_CACHE_NONE, 'trace' => TRUE));

print_r($client->__getFunctions());

echo '<br/>';

$criterio = array(
    'Modo' => $modo,
    'Tipoanulacion' => $tipoanulacion,
    'Nroautorizacion' => $nroautorizacion,
    'Entidad' => $entidad, 
    'Usuario' => $usuario, 
    'Clave' => $clave);

$result = $client->ANULACION($criterio);

$s = print_r($result, true);

echo $s;

ECHO '<hr>';

echo 'Codigo respuesta ' . $result->Codigorespuesta ;
  
 
?>