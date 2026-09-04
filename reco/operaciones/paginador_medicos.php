<?php

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cMedicos.php');
$medico = new cMedicos;

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

$codos = $_GET['codos'];
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

$Resultado = $medico->getListaMedicos($codos, $filtro, $RegistrosAEmpezar, $RegistrosAMostrar);

?>

<table border="0px">
    <tr>
        <td width="10px"><b>Id.</b></td>
        <td width="300px" align = "left"><b>Nombre del Profesional</b></td>
        <td width="50px" align = "left"><b>Matrícula</b></td>
        <td width="50px" align = "left"><b>Libro</b></td>
        <td width="50px" align = "left"><b>Folio</b></td>
        <td width="50px" align = "left"><b>In.</b></td>
        <?
        if ($edbaja == 'B' or $edbaja == 'E') {
            echo '<td width="50px" align = "left"><b>E/B</b></td>';
        }
        ?>
    </tr>
</table>

<?

echo "<table border='0px'>";
while ($MostrarFila = mysql_fetch_array($Resultado)) {
    echo "<tbody align = 'left'>";
    echo "<tr>";
    echo "<td width='10px'>" . $MostrarFila['idprof'] . "</td>";
    echo "<td width='300px'>" . $MostrarFila['nombre'] . "</td>";
    echo "<td width='50px'>" . $MostrarFila['matricula'] . "</td>";
    echo "<td width='50px'>" . $MostrarFila['libro'] . "</td>";
    echo "<td width='50px'>" . $MostrarFila['folio'] . "</td>";
    echo "<td width='50px'>" . $MostrarFila['estado'] . "</td>";

    $ccodos = $MostrarFila['codos'];
    $cidprof = $MostrarFila['idprof'];

    if ($edbaja == 'B' or $edbaja == 'T') {
        //$borra = "Borrar(".$MostrarFila['idcontrol'].")";
        $borra = "Borrar('$ccodos', '$cidprof')";
        echo '<td><a href="javascript://" onclick="' . $borra . '">Borrar</a></td>';
    }
    if ($edbaja == 'E' or $edbaja == 'T') {
        //$edita = "EditarRegistro(".$MostrarFila['idcontrol'].")";
        $edita = "EditarRegistro('$ccodos', '$cidprof')";
        echo '<td><a href="javascript://" onclick="' . $edita . '">Editar</a></td>';
    }
    echo "</tr>";
    echo "</tbody>";
}
echo "</table>";
//******--------determinar las p�ginas---------******//
$NroRegistros = mysql_num_rows($medico->getListaMedicos($codos, $filtro, $RegistrosAEmpezar, 10000000));

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

echo "<FIELDSET>";

//desplazamiento
echo "<a onclick=\"Pagina($codos, '1','','$edbaja')\">Primero</a> ";
if ($PagAct > 1)
    echo "<a onclick=\"Pagina($codos, '$PagAnt','','$edbaja')\">Anterior</a> ";
echo "<strong>Pagina " . $PagAct . "/" . $PagUlt . "</strong>";
if ($PagAct < $PagUlt)
    echo " <a onclick=\"Pagina($codos, '$PagSig','','$edbaja')\">Siguiente</a> ";
echo "<a onclick=\"Pagina($codos, '$PagUlt','','$edbaja')\">Ultimo</a>";
echo "<br>";
echo "<br>";
echo "</FIELDSET>";
?>
