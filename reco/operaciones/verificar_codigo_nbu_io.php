<?

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cNBU.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cCodigosExcluidos.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cObSocial.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cEntidad.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cCodigosRestringidos.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cNbuReglas.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cUtiles.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cNbuCodigosDif.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cEfector.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cAuditoria.php');

$codigo = $_REQUEST['codigo'];
$codos = $_REQUEST['codos'];
$nrodoc = $_REQUEST['nrodoc'];
$ef = $_REQUEST['efector'];

$entidad = new cEntidad();
$obsocial = new cObsocial();
$regla = new cNbuReglas();
$utiles = new cUtiles();
$ccodrest = new cCodigosRestringidos();

$entidad->getObject();
$obsocial->getObject($codos);
$regla->getObject($codos);
$obj = new cNBU();
$nbudif = new cNbuCodigosDif();


// Validaciones Externas -----------------------------------------------
// Verificamos código inactivo
$obj->getObject($codigo);
if ($obj->inactivo == 1) {
    echo '*** El Código ' . $codigo . ' está dado de Baja ***';
    exit();
}

// Verificamos que no este restringido
$__cod = $ccodrest->getCodigoRestringido($codos, $codigo);
if ($__cod) {
    echo '*** El Código ' . $codigo . ' esta Restringido ***';
    return;
}

// Validaciones por WebServices ----------------------------------------
// Armamos la llamada rpc
$obsocial->verificarRPC($codos);

//==== VALIDACIONES POR WEBSERVICES
// SANCOR
//------------------------------------------------------------------------------
// SANCOR SALUD v2
if ($obsocial->_reglaNegocio == 5) {

    $auditoria1 = new cAuditoria();
    $efector = new cEfector();
    $efector->getObject($ef);

    // Verificamos que la práctica no se repita en el día
    $__practicarepetidadia = $auditoria1->verificarPracticaDia($codos, $utiles->getFechaActual(), $nrodoc, $codigo);

    if ($__practicarepetidadia) {
        echo '*** El Código ' . $codigo . ' NO ES VÁLIDO *** ' . ' PRÁCTICA REPETIDA EN EL DÍA';
        return;
    }

    // Verificamos que no este restringido
    $__cod = $ccodrest->getCodigoRestringido($codos, $codigo);
    if ($__cod) {
        echo '*** El Código ' . $codigo . ' esta Restringido ***';
        return;
    }

    $url = $obsocial->_url . 'validacion';
    $prestadorCuit = str_replace('-', '', $obsocial->_parametro7);
    $parametro1 = str_replace('-', '', $efector->getNrocuit());
    $parametro2 = '000';
    $iddiag = 'I20';
    $prestaciones = '[{"items":1, "codigo":"' . $codigo . '"}]';

    $json_data = '{"IdentificadorAfiliado":"' . $nrodoc . '", "IDPrestador":"' . $prestadorCuit . '", "parametro1":"' . $parametro1 . '", "iddiag": "' . $iddiag . '", "parametro2": "' . $parametro2 . '", "practicas":' . $prestaciones . '}';

    //echo $json_data;
    $context = stream_context_create(array(
        'http' => array(
            'protocol_version' => 1.1,
            'user_agent' => 'PHPExample',
            "Cookie => foo=bar\r\n",
            'method' => 'PUT',
            'header' => "Content-type: application/json\r\n" .
            "Connection: close\r\n" .
            "Content-length: " . strlen($json_data) . "\r\n",
            'content' => $json_data,
            'Expect' => '100-continue'
        ),
    ));

    $file = file_get_contents($url, false, $context);

    $result = json_decode($file);

    $it = 0;
    $_es = 'A';
    $msg = '';
    for ($p = 0; $p < count($result->practicas); $p++) {
        $_es = $result->practicas[$p]->estado;
        $msg = $result->practicas[$p]->mensaje;
        break;
    }

    if ($_es == 'A' || $_es == 'R') {   // || $_es == 'R' - 11/02/2025
        $ep = ' [OK]';
        $obj->getObject($codigo);
        echo $obj->getDescrip() . ' ' . $ep;
    } else {
        echo '*** El Código ' . $codigo . ' NO ES VÁLIDO *** ' . $msg;
    }

    exit;
}

//------------------------------------------------------------------------------

if ($obsocial->_reglaNegocio == 5) {

    $url = $obsocial->_url;
    $usuario = $obsocial->_user;
    $clave = $obsocial->_pass;

    try {

        $nroorden = 0;
        $entidad = $obsocial->_parametro1;
        $afiliado = $nrodoc; //'12129700';
        $modo = $obsocial->getParametro2();
        $formaidafiliado = $obsocial->_parametro4; //'AS';
        // Práctica
        $codd = $codigo;
        $prestacionesItems[0] = array('TipoNomenclador' => $obsocial->_parametro5, 'Prestacion' => $codd, 'Cantidad' => 1, 'Formulario' => 0);

        // Método VALIDARPRACTICA - Determina que prácticas se autorizan

        $client = new SoapClient($url, array('cache_wsdl' => WSDL_CACHE_NONE, 'trace' => TRUE));

        $criterio = array(
            'Modo' => $modo,
            'Entidad' => $entidad,
            'Formaidafiliado' => $formaidafiliado,
            'Afiliado' => $afiliado,
            'Prestacionesvalidar' => array('PrestacionesValidar' => $prestacionesItems),
            'Usuario' => $usuario,
            'Clave' => $clave);

        $result = $client->VALIDARPRACTICA($criterio);

        $prestaciones[] = $result->Prestacionesvalidarrta;

        $requiere = '';
        $aviso = '';
        for ($p = 0; $p < count($prestaciones[0]->PrestacionesValidarRta); $p++) {
            $prestacion = $prestaciones[0]->PrestacionesValidarRta->Prestacion;
            $requiere = $prestaciones[0]->PrestacionesValidarRta->Requiere;
            $aviso = $prestaciones[0]->PrestacionesValidarRta->Aviso;
        }

        if ($requiere == 'N') {
            $ep = ' [OK]';
            $obj->getObject($codigo);
            echo $obj->getDescrip() . ' ' . $ep . ' ' . $aviso;
        } else {
            echo '*** El Código ' . $codigo . ' NO ES VÁLIDO *** ' . $aviso;
        }

        exit;
    } catch (Exception $ex) {
        
    }
}

//----------------------------------------------------------------------
// INGENIEROS
if ($obsocial->_reglaNegocio == 8) {

    $url = $obsocial->_url;
    $usuario = $obsocial->_user;
    $pass = $obsocial->_pass;

    $metodo = 'practica';

    $turl = $obsocial->_parametro7 . '/Practica?tipo=B&codigo=' . $codigo;
    $parameters = '{"url":"' . $turl . '","metodo":"","api":"Authorization","apikey":"' . $obsocial->_parametro1 . '","username":"' . $usuario . '","password":"' . $pass . '"}';

    $json_data = '{"parameters":' . $parameters . '}';

    $theurl = $url . $metodo;

    $context = stream_context_create(array(
        'http' => array(
            'protocol_version' => 1.1,
            'user_agent' => 'PHPExample',
            "Cookie => foo=bar\r\n",
            'method' => 'PUT',
            'header' => "Content-type: application/json\r\n" .
            "Connection: close\r\n" .
            "Content-length: " . strlen($json_data) . "\r\n",
            'content' => $json_data,
            'Expect' => '100-continue'
        ),
    ));

    $file = @file_get_contents($theurl, false, $context);

    $result = json_decode($file);

    if ($result->Codigo != '') {
        $ep = ' [OK]';
        $obj->getObject($codigo);
        echo $obj->getDescrip() . ' ' . $ep;
    } else {
        echo '*** El Código ' . $codigo . ' NO ES VÁLIDO *** ';
    }

    exit;
}

//------------------------------------------------------------------------------
// FEDERADA
if ($obsocial->_reglaNegocio == 6) {

    $url = $obsocial->_url;
    $usuario = $obsocial->_user;
    $pass = $obsocial->_pass;
    $ns = $_REQUEST['nrodoc'];

    $metodo = 'practica';

    $version = $obsocial->_parametro8;
    $version = 'v1.5.2';

    $parameters = '{"url":"' . $obsocial->_parametro7 . '/","metodo":"validador/' . $version . '/wsvol001","api":"x-api-key","apikey":"' . $pass . '"}';
    //$parameters = '{"url":"' . $obsocial->_parametro7 . '/","metodo":"validador/v1.5.2/wsvol001","api":"x-api-key","apikey":"' . $pass . '"}';
    // Diagnóstico
    $dx1 = 'R10';

    // Practicas -----------------------------------------------  
    $nrocuit = str_replace('-', '', $obsocial->_parametro5);

    $prestaciones = '[';
    $codd = $codigo;
    $l = 1;

    $linea = '{"NroLinea":"' . $l . '","FecPre":"' . $utiles->getFechaAAAAMMDD($utiles->getFechaActual()) . '","PtiCod":"B","PstCod":"' . $codd . '","Cantidad":"1","CodDiagno":"' . $dx1 . '","DesDiagno":"-","ComPresta":"test","CUITProf":null}';

    $prestaciones = $prestaciones . $linea . ',';

    $prestaciones = $prestaciones . '],';

    $practicas = str_replace(',]', ']', $prestaciones);

    //----------------------------------------------------------

    $cuit = str_replace('-', '', $obsocial->_parametro5); // CUIT de la Asociacion

    $data = '{"p_Modo":"V",'
            . '"p_Prestador":"' . $obsocial->_parametro3 . '",'
            . '"p_SubPrestador":"' . $obsocial->_parametro4 . '",'
            . '"p_SubPreCUIT":"' . $cuit . '",'
            . '"p_IntNro":"' . $obsocial->_parametro2 . '",'
            . '"p_NroDoc":"' . $nrodoc . '",'
            . '"p_Situacion":"0",'
            . '"p_ListaPrestaciones":' . $practicas
            . '"p_Archivos":[]}';

    $json_data = '{"parameters":' . $parameters . ',' .
            '"data":' . $data . '}"';

    $context = stream_context_create(array(
        'http' => array(
            'protocol_version' => 1.1,
            'user_agent' => 'PHPExample',
            "Cookie => foo=bar\r\n",
            'method' => 'PUT',
            'header' => "Content-type: application/json\r\n" .
            "Connection: close\r\n" .
            "Content-length: " . strlen($json_data) . "\r\n",
            'content' => $json_data,
            'Expect' => '100-continue'
        ),
    ));

    $theurl = $url . $metodo; // . '?id=' . $data . '&key1=' . $parameters;

    $file = file_get_contents($theurl, false, $context);
    //var_dump($file);

    $array = json_decode($file);

    $estado = "";
    $ep = "";
    for ($p = 0; $p < count($array->o_ListaPrestacionesValidadas); $p++) {
        if ($codigo == $array->o_ListaPrestacionesValidadas[$p]->PstCod) {
            $ep = ' - ' . $array->o_ListaPrestacionesValidadas[$p]->Comentario;
            if ($array->o_ListaPrestacionesValidadas[$p]->StatusPre == 'ER') {
                $estado = '*** El Código ' . $codigo . ' NO ES VÁLIDO *** ' . $ep;
                echo $estado;
                exit;
            }
        }
    }

    $obj->getObject($codigo);
    echo $obj->getDescrip() . $ep;

    exit;
}

if ($regla != null) {

    // IAPOS
    if ($regla->regla == 1) {
        $resultado = $regla->getDeterminacionR1($codos, $codigo);

        $found = false;
        while ($fila = mysql_fetch_array($resultado)) {
            $r = $obj->getObject($fila['codigo']);
            if ($r != null) {
                echo $obj->getDescrip();
                $found = true;
            }
        }

        if ($found == false) {
            echo '*** El Código ' . $codigo . ' NO ES VÁLIDO PARA IAPOS  ***';
        }

        exit;
    }

    //--------------------------------------------------------------------------
    // INGENIEROS
    if ($regla->regla == 2) {
        $resultado = $regla->getDeterminacionR1($codos, $codigo);

        $found = false;
        while ($fila = mysql_fetch_array($resultado)) {
            $r = $obj->getObject($fila['codigo']);
            if ($r != null) {
                echo $obj->getDescrip();
                $found = true;
            }
        }

        if ($found == false) {
            echo '*** El Código ' . $codigo . ' NO ES VÁLIDO PARA INGENIEROS. DEBERÁ GESTIONARLO POR REINTEGRO  ***';
        }

        exit;
    }
}

// SWISS MEDICAL - COVID
if ($obsocial->_reglaNegocio == 13) {
    $resultado = $nbudif->getCodigoIncluido($codos, $codigo);

    if ($resultado != '') {

        $r = $obj->getObject($codigo);
        if ($r != null) {
            echo $obj->getDescrip();
            $found = true;
        }
    } else {
        echo '*** El Código ' . $codigo . ' NO ES VÁLIDO PARA ESTE PLAN  ***';
    }

    exit;
}

// ---------------------------------------------------------------------

if (strlen(trim($codigo)) > 0) {

    $codigos = $_REQUEST['codigos'];

    $ce = new cCodigosExcluidos();

    $r = $obj->getObject($codigo);

    if ($r != null) {

        // Ahora verificamos que ese código no esté incluido en otro
        $incluidoencodigo = '';
        $j = 0;
        for ($i = 0; $i < 500; $i++) {
            $c = substr($codigos, $j, 6);
            $j = $j + 6;
            if ($c == 0) {
                break;
            }

            $incluidoencodigo = $ce->getCodigoIncluido($codigo, $c);

            if (strlen($incluidoencodigo > 0)) {
                break;
            }
        }

        // Ahora verificamos que el codigo no este restringido
        $cres = new cCodigosRestringidos();
        if (strlen($cres->getCodigoRestringido($codos, $codigo)) > 0) {
            echo '*** El Código ' . $codigo . ' está Restringido en esta Obra Social ***';
            exit();
        }

        // Ahora verificamos que el codigo no este bloqueado
        if ($obsocial->getIncluye_ab() != 'S' and $entidad->getParametro4() == $codigo) {
            echo '*** El Código ' . $codigo . ' no está Permitido en esta Obra Social ***';
        } else {

            // Ahora verificamos que el codigo esta admitido
            if ($obj->getInactivo() == 1) {
                echo '*** El Código ' . $codigo . ' ' . substr($obj->getDescrip(), 0, 25) . ' está Inactivo ***';
            } else {

                if (strlen($incluidoencodigo == 0)) {
                    echo $obj->getDescrip();
                } else {
                    //echo '*** El Código ' . $codigo . ' de Determinación está Incluído en el Cód. ' . $incluidoencodigo . ' ***';
                    echo $obj->getDescrip();
                }
            }
        }
    } else {
        echo '*** Código de Determinación Inexistente ***';
    }
} else {
    echo '*** Código de Determinación Inexistente ***';
}
?>