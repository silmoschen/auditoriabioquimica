<?
//Autorizacion
$usuario = 'WSRVSSA';
$clave = '15WSSA08';

$entidad = 135533;
$tiponroefector = 'CU';
$nroefector = 211010; // 20167261885;
$formaidafiliado = 'AS';
$afiliado = '099057601';
$modo = 'T';

$client = new SoapClient('http://e.sancorsalud.com.ar/apawe_ssa_v3.aspx?wsdl',
                    array('cache_wsdl' => WSDL_CACHE_NONE, 'trace' => TRUE));

print_r($client->__getFunctions());

echo '<br/>';

$criterio = array(
    'Modo' => $modo,
    'Entidad' => $entidad, 
    'Tiponroefector' => $tiponroefector, 
    'Nroefector' => $nroefector, 
    'Formaidafiliado' => $formaidafiliado, 
    'Afiliado' => $afiliado, 
    'Usuario' => $usuario, 
    'Clave' => $clave);

$result = $client->ELEGIBILIDAD($criterio);

$s = print_r($result, true);

echo $s;

ECHO '<br>';

echo $result->Nombreafiliado ;
  
 
?>