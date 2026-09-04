<?php

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cEquivalenciaPadrones.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cObSocial.php');
$td = new cEquivalenciaPadrones();
$os = new cObsocial();

$Resultado = $td->getCodigos();

echo "<hr>";

echo "<table border='0px' width='550px'>";
echo "<tr>";
echo "<td width='50px' align='left'><b>Código</b></td>";
echo "<td width='150px' align='left'><b>Obra Social</b></td>";
echo "<td width='50px' align='left'><b>Código</b></td>";
echo "<td width='150px' align='left'><b>Utiliza Padrón de ...</b></td>";
echo "<td width='30px'><b></b></td>";
echo "</tr></table>";

echo "<hr>";

echo "<table border='0px' width='550px'>";

while($MostrarFila=mysql_fetch_array($Resultado)){
    $os->getObject($MostrarFila['codigo1']);
    $obsocial1 = $os->getNombre();
    $os->getObject($MostrarFila['codigo2']);
    $obsocial2 = $os->getNombre();
    echo "<tr>";
    echo "<td width='50px' align='left'>".$MostrarFila['codigo1']."</td>";
    echo "<td width='150px' align='left'>".$obsocial1."</td>";
    echo "<td width='50px' align='left'>".$MostrarFila['codigo2']."</td>";
    echo "<td width='150px' align='left'>".$obsocial2."</td>";
    $borra = "Borrar("."'".$MostrarFila['codigo1']."'".")";
    echo '<td width="30px"><a href="javascript://" onclick="' . $borra . '">Borrar</a></td>';
    echo "</tr>";
}
echo "</table>";

?>