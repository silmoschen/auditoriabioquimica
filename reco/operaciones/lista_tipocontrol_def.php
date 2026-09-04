<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cModelos.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cDiagnosticosOMS.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cObSocial.php');

$codos = $_REQUEST['codos'];
$idcontrol = $_REQUEST['idcontrol'];

$obj = new cModelos;
$dix = new cDiagnosticosOMS;
$obsocial = new cObsocial;

if ($obj->VerificarDefinicion($codos, $idcontrol)) {
    $obsocial->getObject($codos);
    echo '<p align = "left">Obra Social: <b>' . $obsocial->getNombre() . '</b><br>';

    if ($obj->VerificarEstado($codos, $idcontrol)) {
      $estado = 'checked="checked"';    
    }
    
    if ($obj->VerificarAutorizacionDiferida($codos, $idcontrol)) {
      $aut_dif = 'checked="checked"';    
    }

    echo '<table>';
    echo '<tr>';
    echo '<td width="350px">';
    $dix->getObject($idcontrol);
    echo 'Dx.: <b>' . $dix->getDescrip() . '</b>';
    echo '</td>';
    echo '<td width="150px">';
    echo '<input name="inhabilitado" id="inhabilitado" type="checkbox" style="font-size: 10px;"' . $estado . ' onclick="javascript: Inhabilitar(inhabilitado.checked); return true" />' . "Deshabilitar";
    echo '</td>';
    echo '</tr>';
    echo '<tr>';
    echo '<td>';
    echo '<input name="diferida" id="diferida" type="checkbox" style="font-size: 10px;"' . $aut_dif  . ' onclick="javascript: AutorizacionDiferida(diferida.checked); return true" />' . "Este Diagnóstico Requiere Autorización Diferida";

} else {
    echo '<p align = "left">';
    echo 'No hay Determinaciones Definidas en este Diagnóstico';
    echo '</p>';
}

echo '</td>';
echo '</tr>';
echo '</table>';

$resultado = $obj->getModelo($codos, $idcontrol);
?>

<div style="margin:auto;width:550px;text-align:center;">

    <?php
    $b = true;
    while ($fila = mysql_fetch_array($resultado)) {
        if ($b) {
            echo '<hr>';
            echo '<table>';
            echo '<tr>';
            echo '<td><b>Codigo</b></td>';
            echo '<td align="left"><b>Determinacion</b></td>';
            echo '<td><b>Frecuencia</b></td>';
            echo '<td><b>T.Frec.</b></td>';
            echo '<td><b>Baja</b></td>';
            echo '</tr>';
            echo '</table>';
            echo '<hr>';
            echo '<table>';
            $b = false;
        }

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
        echo '<td>' . $fila['codigo'] . '</td>';
        echo '<td align="left">' . $fila['descrip'] . '</td>';
        echo '<td>' . $fila['frecuencia'] . '</td>';
        echo '<td>' . $df . '</td>';
        $borra = "BajaModelo(" . "'" . $idcontrol . "'" . ", " . "'" . $fila['codigo'] . "'" . ")";
        $edita = "EditaModelo(" . "'" . $idcontrol . "'" . ", " . "'" . $fila['codigo'] . "'" . ")";
        echo '<td><a href="javascript://" onclick="' . $edita . '">Editar</a></td>';
        echo '<td><a href="javascript://" onclick="' . $borra . '">Borrar</a></td>';
        echo '</tr>';
    }

    if ($b == false) {
        echo '</table><hr>';
        $seleccion = "CerrarListaDefinicion()";
        echo '<td width="2px"><a href="javascript://" onclick="' . $seleccion . '">Finalizar Consulta</a></td>';
    }
    ?>

</div>