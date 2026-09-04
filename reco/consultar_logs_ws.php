<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

session_start();
//session_register('susuario');
//session_register('spass');
//session_register('snombre');
//session_register('sdireccion');
//session_register('stelefono');
//session_register('semail');

$usuario = $_SESSION['susuario'];
$pass = $_SESSION['spass'];
$nombre = $_SESSION['snombre'];

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cUtiles.php");
$u = new cUtiles;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <head><title>Consulta de Logs</title>
        <!--
            Toda la Secuencia JavaScript
            <td width="108" align="left"><input name="nrodoc" type="text" maxlength="15" width="4" onblur="javascript: if(ValidarAfiliado1(event, listObsocial.value, nrodoc.value)) {listMedicos.focus()}; return true" onkeypress="javascript: if(ValidarAfiliado(event, listObsocial.value, nrodoc.value)) {listMedicos.focus()}; return true">
        -->

        <script src="../jscript/funciones.js"></script>
        <script src="../jscript/funcionajax.js"></script>
        <script src="../jscript/showhidediv.js"></script>
        <script>

            function ConsultarLogs(){
                Pagina(1);
            }

            function Pagina(nropagina){
                var aleatorio = Math.random();
                var divLista  = document.getElementById('Lista');
                var ajax=objetoAjax();
                ajax.open('GET', './operaciones/paginador_logs_ws.php?pag='+nropagina+'&aleatorio='+aleatorio);
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divLista.innerHTML = ajax.responseText
                    }
                }
                ajax.send(null)
            }

        </script>

        <link rel="stylesheet" type="text/css" href="/css1.css">

    </head>

    <body onLoad="javascript: if (Init()) {desde.focus()}; return true;">

        <form name="frmConsultaLog" method="post" onsubmit="return false">

            <div style="margin:auto;width:550px;text-align:center;">

                <?php include('menu_admin.php'); ?>   

                <div id="Lista" align="center">
                </div>

                <script>
                    ConsultarLogs();
                </script>

            </div>
        </form>

    </body>
</html>
