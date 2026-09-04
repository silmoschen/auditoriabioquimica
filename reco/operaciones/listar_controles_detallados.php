<p align="center">
<h3>Listado de Definición de Modelos y Frecuencias</h3>
</p>
<?php

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cModelos.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cObSocial.php');

$codos = $_GET['codos'];

$modelo = new cModelos;
$os     = new cObsocial;
$os->getObject($codos);

echo '<b>Obra Social: ' . $os->getNombre() . '</b>';

$Resultado= $modelo->getModelosDef($codos, 1, 100000);

echo '<hr>';
echo "<table border='0px' width='550px'>";
echo '<tr>';
echo '<td><b>Codigo</b></td>';
echo '<td align="left"><b>Determinacion</b></td>';
echo '<td><b>Frecuencia</b></td>';
echo '<td><b>T.Frec.</b></td>';
echo '</tr>';
while($MostrarFila=mysql_fetch_array($Resultado)){    
	echo "<tr>";    
	echo "<td width='10px' align='left'>Dx.:<b>".$MostrarFila['oms_cod']."</b></td>";
    echo "<td width='200px' align='left'><b>". $MostrarFila['descrip']."</b></td>";
    //$seleccion = "ConsultarModelo1("."'".$MostrarFila['oms_cod']."'".")";
    echo "</tr>";  
    
    $res = $modelo->getModelo($codos, $MostrarFila['oms_cod']);
    while($fila=mysql_fetch_array($res)){
        $df = '';
        if ($fila['tipofrecuencia'] == 1) {
            $df = 'Días';
        }
        if ($fila['tipofrecuencia'] == 2) {
            $df = 'Meses';
        }
        if ($fila['tipofrecuencia'] == 3) {
            $df = 'Años';
        }
        if ($fila['tipofrecuencia'] == 4) {
            $df = 'Sin Limite';
        }

        echo '<tr>';
        echo '<td>'.$fila['codigo'].'</td>';
        echo '<td align="left">'.$fila['descrip'].'</td>';
        echo '<td>'.$fila['frecuencia'].'</td>';
        echo '<td>'.$df.'</td>';
        echo '</tr>';        
    }
    echo '</tr><td></td><td></td><td></td><td></td></tr>';
}
echo "</tbody>";
echo "</table>";

?>
