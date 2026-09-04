<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cObSocial.php");

$obj = new cObsocial;

$resultado = $obj->getObrasSociales();
?>

<select name="listObsocial" id="listObsocial" width="80" style="width:300px" onchange="javascript: if(CambiarOS()) {desde.focus()}" onclick="javascript: if(CambiarOSS()) {desde.focus()}; return true;">
    <?php
    while ($fila = mysql_fetch_array($resultado)) {
        echo '<option value =' . '"' . $fila['codos'] . '"' . '>' . substr($fila['nombre'], 0, 40) . '</option>';
    }
    ?>
</select>  