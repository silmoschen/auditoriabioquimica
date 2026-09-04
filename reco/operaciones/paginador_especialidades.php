<?php

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cEspecialidades.php');
$especialidad = new cEspecialidades();

$RegistrosAMostrar = 10;

//estos valores los recibo por GET
if (isset($_GET['pag'])) {
    $RegistrosAEmpezar = ($_GET['pag'] - 1) * $RegistrosAMostrar;
    $PagAct = $_GET['pag'];
//caso contrario los iniciamos
} else {
    $RegistrosAEmpezar = 0;
    $PagAct = 1;
}

if (isset($_GET['filtro'])) {
    $filtro = $_GET['filtro'];
} else {
    $filtro = "";
}
if (isset($_GET['edbaja'])) {
    $edbaja = $_GET['edbaja'];
} else {
    $edbaja = "";
}

$Resultado = $especialidad->getListaEspecialidades($filtro, $RegistrosAEmpezar, $RegistrosAMostrar);

echo "<table border='0px'>";
while ($MostrarFila = mysql_fetch_array($Resultado)) {
    echo "<tbody align = 'left'>";
    echo "<tr>";
    echo "<td width='30px'>" . $MostrarFila['id_especialidad'] . "</td>";
    echo "<td width='500px'>" . $MostrarFila['descripcion'] . "</td>";
    if ($edbaja == 'B' or $edbaja == 'T') {
        $borra = "Borrar(" . "'" . $MostrarFila['id_especialidad'] . "'" . ")";
        echo '<td><a href="javascript://" onclick="' . $borra . '">Borrar</a></td>';
    }
    if ($edbaja == 'E' or $edbaja == 'T') {
        $edita = "EditarRegistro(" . "'" . $MostrarFila['id_especialidad'] . "'" . ")";
        echo '<td><a href="javascript://" onclick="' . $edita . '">Editar</a></td>';
    }
    echo "</tr>";
    echo "</tbody>";
}
echo "</table>";
//******--------determinar las p�ginas---------******//
$NroRegistros = mysql_num_rows($especialidad->getListaEspecialidades($filtro, 0, 1000000));

$PagAnt = $PagAct - 1;
$PagSig = $PagAct + 1;
$PagUlt = $NroRegistros / $RegistrosAMostrar;
//verificamos residuo para ver si llevar� decimales
$Res = $NroRegistros % $RegistrosAMostrar;
// si hay residuo usamos funcion floor para que me
// devuelva la parte entera, SIN REDONDEAR, y le sumamos
// una unidad para obtener la ultima pagina
if ($Res > 0) {
    $PagUlt = floor($PagUlt) + 1;
}

echo "<FIELDSET>";

//desplazamiento
echo "<a onclick=\"Pagina('1','','$edbaja')\">Primero</a> ";
if ($PagAct > 1)
    echo "<a onclick=\"Pagina('$PagAnt','','$edbaja')\">Anterior</a> ";
echo "<strong>Pagina " . $PagAct . "/" . ($PagUlt - 1) . "</strong>";
if ($PagAct < $PagUlt)
    echo " <a onclick=\"Pagina('$PagSig','','$edbaja')\">Siguiente</a> ";
echo "<a onclick=\"Pagina('$PagUlt','','$edbaja')\">Ultimo</a>";
echo "<br>";
echo "<br>";
echo "</FIELDSET>";
?>