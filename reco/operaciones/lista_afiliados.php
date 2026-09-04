<?php

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cAfiliados.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cEquivalenciaPadrones.php');
$RegistrosAMostrar = 15;

//estos valores los recibo por GET
if (isset($_GET['pag'])) {
    $RegistrosAEmpezar = ($_GET['pag'] - 1) * $RegistrosAMostrar;
    $PagAct = $_GET['pag'];
//caso contrario los iniciamos
} else {
    $RegistrosAEmpezar = 0;
    $PagAct = 1;
}

$afiliado = new cAfiliados();

$codos = $_GET['codos'];
$tipof = $_GET['tipofiltro'];

// Verificamos si la obra social no utiliza el padrón de otra
$eq = new cEquivalenciaPadrones();
$found = $eq->getObject($codos);
if ($found) {
    // Si existe un código equivalente, modificamos la Obra Social
    $codos = $eq->getCodigo2();
}

$filtro = $_GET['filtro'];
if ($_GET['edbaja'] != '') {
    $edbaja = $_GET['edbaja'];
}

$resultado = $afiliado->getConsultaAfiliados($codos, $filtro, $tipof, $RegistrosAEmpezar, $RegistrosAMostrar);

if ($edbaja == '' or $edbaja == 'S') {
    echo '<hr>';
}

echo "<table border='0px'>";
echo "<tbody align = 'left'>";
echo "<tr>";
echo "<td width='30px' align='left'><b>Nro.Doc.</b></td>";
echo "<td width='400px' align='left'><b>Nombre</b></td>";
echo "</tr>";

if ($edbaja == 'S') {
    echo "</table>";
    echo '<hr>';
    echo "<table border='0px'>";
    echo "<tbody align = 'left'>";
}


while ($MostrarFila = mysql_fetch_array($resultado)) {
    echo "<tr>";
    echo "<td width='30px'>" . $MostrarFila['nrodoc'] . "</td>";
    echo "<td width='400px'>" . $MostrarFila['nombre'] . "</td>";
    if ($edbaja == 'B' or $edbaja == 'T') {
        $borra = "Borrar(" . "'" . $MostrarFila['nrodoc'] . "'" . ")";
        echo '<td><a href="javascript://" onclick="' . $borra . '">Borrar</a></td>';
    }
    if ($edbaja == 'E' or $edbaja == 'T') {
        $edita = "EditarRegistro(" . "'" . $MostrarFila['nrodoc'] . "'" . ")";
        echo '<td><a href="javascript://" onclick="' . $edita . '">Editar</a></td>';
    }
    if ($edbaja == 'S') {
        $edita = "SelectRegistro(" . "'" . $MostrarFila['nrodoc'] . "'" . ")";
        echo '<td><a href="javascript://" onclick="' . $edita . '">Sel</a></td>';
    }

    echo "</tr>";
}
echo "</tbody>";
echo "</table>";
//******--------determinar las p�ginas---------******//

$NroRegistros = mysql_num_rows($afiliado->getConsultaAfiliados($codos, $filtro, $tipof, 0, 1000000000));

$PagAnt = $PagAct - 1;
$PagSig = $PagAct + 1;
$PagUlt = $NroRegistros / $RegistrosAMostrar;

//verificamos residuo para ver si llevar� decimales
$Res = $NroRegistros % $RegistrosAMostrar;
// si hay residuo usamos funcion floor para que me
// devuelva la parte entera, SIN REDONDEAR, y le sumamos
// una unidad para obtener la ultima pagina
if ($Res > 0)
    $PagUlt = floor($PagUlt) + 1;

if ($edbaja != 'S') {
    echo "<FIELDSET>";
} else {
    echo "<hr>";
}

//desplazamiento

echo "<a onclick=\"PagAfiliado('1', '$filtro','$codos','$edbaja', '$filtro')\">Primero</a> ";
if ($PagAct > 1)
    echo "<a onclick=\"PagAfiliado('$PagAnt', '$filtro','$codos','$edbaja', '$filtro')\">Anterior</a> ";
echo "<strong>Pagina " . $PagAct . "/" . $PagUlt . "</strong>";
if ($PagAct < $PagUlt)
    echo " <a onclick=\"PagAfiliado('$PagSig', '$filtro', '$codos','$edbaja', '$filtro')\">Siguiente</a> ";
echo "<a onclick=\"PagAfiliado('$PagUlt', '$filtro','$codos','$edbaja', '$filtro')\">Ultimo</a>";

if ($edbaja != 'S') {
    echo "<br>";
    echo "<br>";
    echo "</FIELDSET>";
}
?>
