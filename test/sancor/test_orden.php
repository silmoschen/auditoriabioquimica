<?

//Autorizacion
$usuario = 'WSRVSSA';
$clave = '15WSSA08';

$nroorden = 0;
$entidad = 135533;
$tiponroefector = 'CU';
$nroefector = 20167261885;
$formaidafiliado = 'AS';
$afiliado = '12129700';
$Matriculaprescribiente = 22492108;
$Descripcionprescribiente = 'DELSSIN WALTER ANDRES';
$modo = 'T';

$client = new SoapClient('http://e.sancorsalud.com.ar/apawe_ssa_v3.aspx?wsdl', array('cache_wsdl' => WSDL_CACHE_NONE, 'trace' => TRUE));

print_r($client->__getFunctions());

echo '<br/>';

$prestacionesItems[0] = array('TipoNomenclador' => 'NU', 'Prestacion' => '660475', 'Cantidad' => 1, 'Formulario' => 0);
$prestacionesItems[1] = array('TipoNomenclador' => 'NU', 'Prestacion' => '660412', 'Cantidad' => 1, 'Formulario' => 0);
/*
$prestacionesItems[2] = array('TipoNomenclador' => 'NU', 'Prestacion' => '660005', 'Cantidad' => 1, 'Formulario' => 0);
$prestacionesItems[3] = array('TipoNomenclador' => 'NU', 'Prestacion' => '660100', 'Cantidad' => 1, 'Formulario' => 0);
$prestacionesItems[4] = array('TipoNomenclador' => 'NU', 'Prestacion' => '668009', 'Cantidad' => 1, 'Formulario' => 0);
$prestacionesItems[5] = array('TipoNomenclador' => 'NU', 'Prestacion' => '668024', 'Cantidad' => 1, 'Formulario' => 0);
$prestacionesItems[6] = array('TipoNomenclador' => 'NU', 'Prestacion' => '660001', 'Cantidad' => 1, 'Formulario' => 0);
$prestacionesItems[7] = array('TipoNomenclador' => 'NU', 'Prestacion' => '666666', 'Cantidad' => 1, 'Formulario' => 0);
 * 
 */

$criterio = array(
    'Modo' => $modo,
    'Nroorden' => $nroorden,
    'Entidad' => $entidad,
    'Tiponroefector' => $tiponroefector,
    'Nroefector' => $nroefector,
    'Formaidafiliado' => $formaidafiliado,
    'Afiliado' => $afiliado,
    'Matriculaprescribiente' => $Matriculaprescribiente,
    'Descripcionprescribiente' => $Descripcionprescribiente,
    'Prestaciones' => array('PrestacionesItem' => $prestacionesItems),
    'Usuario' => $usuario,
    'Clave' => $clave);

$result = $client->AUTORIZACION($criterio);

echo '<hr>';

$s = print_r($result, true);

echo $s;

echo '<hr>';

echo 'Entidad: ' . $result->Nombreentidad . ' Autorizacion: ' . $result->Nroautorizacion . '<br>';


$prestaciones[] = $result->Prestacionesrtav2;
$prestaciones1[] = $prestaciones[0]->PrestacionesRta_PrestacionesRtaItem;

echo '<hr/>';

echo $prestaciones2;

for ($p = 0; $p < count($prestaciones1[0]->PrestacionesRtaItem); $p++) {
    $prestacion = $prestaciones1[0]->PrestacionesRtaItem[$p]->Prestacion;
    $errorprestacion = $prestaciones1[0]->PrestacionesRtaItem[$p]->ErrorPrestacion;
    $descriperrorprestacion = $prestaciones1[0]->PrestacionesRtaItem[$p]->DescripcionErrorPrest;
    echo $prestacion . ' ' . $errorprestacion . ' ' . $descriperrorprestacion;

    echo '<br/>';
}

ECHO '<br>';

//echo $result->Nombreafiliado;
?>