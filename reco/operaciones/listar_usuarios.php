<?php

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/CUsuarios.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cUtiles.php');

$usuario = new CUsuarios;
$utiles = new cUtiles;

$Resultado = $usuario->getUsuarios();

echo '<hr>';

echo "<table border='0px' width='400px' align='center'>";
echo "<tr>";
echo "<td width='50px' align='left'><b>Usuario</b></td>";
echo "<td width='50px' align='left'><b>Password</b></td>";
echo "<td width='150px' align='left'><b>Observaciones</b></td>";
echo "<td width='40px' align='left'><b>Baja</b></td>";

echo "</tr>";

while($MostrarFila=mysql_fetch_array($Resultado)){
    echo "<tbody align = 'left'>";
	echo "<tr>";    
    echo "<td width='50px'>". $MostrarFila['usuario']."</td>";
	echo "<td width='50px'>*******</td>";
    echo "<td width='150px'>".$MostrarFila['observacion']."</td>";
    $borra = "Borrar(".$MostrarFila['ID'].")";
    echo '<td width="40px" align="left"><a href="javascript://" onclick="' . $borra . '">Borrar</a></td>';
    echo "</tr>";
    echo "</tbody>";
}
echo "</table>";

?>
