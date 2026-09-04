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

            function ValidarFechaDesde(e, xfenac){
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    return ValidarFecha(xfenac);
                }
            }

            function ValidarFechaHasta(e, xfenac){
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    if (ValidarFecha(xfenac)) {
                        ConsultarLogs();
                        return true;
                    }
                }
            }

            function ConsultarLogs(){
                Pagina(1);
            }

            function Pagina(nropagina){
                var aleatorio = Math.random();
                var divLista  = document.getElementById('Lista');
                var usuario   = document.getElementById('listUsuarios').value;
                var desde     = document.getElementById('desde').value;
                var hasta     = document.getElementById('hasta').value;
                var ajax=objetoAjax();
                ajax.open('GET', './operaciones/paginador_logs.php?pag='+nropagina+'&usuario='+usuario+'&desde='+desde+'&hasta='+hasta+'&aleatorio='+aleatorio);
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

                <div id="MenuConsulta" align="left">
                    <table width="450" border="0" align="center">
                        <tr>
                            <?php
                            echo '<td width="50"><input name="usuario" type="hidden" id="usuario" value="' . $usuario . '" /></td>';
                            ?>
                            <td width="350" align="left">
                            </td>
                            <td width="50">
                            </td>
                        </tr>
                    </table>
                </div>

                <table width="500px" border="0" align="center">
                    <tr>
                        <td width="110px" align="left">Consulta de Logs</td>
                        <td width="390px"><hr></td>
                    </tr>
                </table>

                <div align="left"></div>
                <table width="500px" border="0" align="center">
                    <tr>
                        <td width="70px" align = "right">Desde:</td>
                        <td width="200px" align = "left">
                            <input name="desde" id="desde" type="text" style="font-size: 10px;" maxlength="10" width="2" size="10" onkeypress="javascript: if(ValidarFechaDesde(event, desde.value)) {hasta.focus()}; return true" />
                            (dd/mm/aaaa)
                        </td>

                        <td width="100" align="right">Hasta:</td>
                        <td width="70" align="right">
                            <input name="hasta" id="hasta" type="text" style="font-size: 10px;" maxlength="10" width="2" size="10" onkeypress="javascript: if(ValidarFechaHasta(event, hasta.value)) {hasta.focus()}; return true" />
                        </td>

                    </tr>

                    <tr>
                        <td width="100px" align = "right">
                            Usuario:
                        </td>
                        <td width="200px" align = "left">
                            <?
                            include('operaciones/lista_usuarios.php');
                            ?>
                        </td>

                        <td width="100px" align="right"></td>
                        <td  width="70px">
                            <a href="javascript://" onclick="ConsultarLogs()">Consultar</a>
                        </td>

                    </tr>

                </table>

                <table width="500px" border="0" align="center">
                    <tr>
                        <td><hr></td>
                    </tr>
                </table>

                <div id="Lista" align="center">
                </div>

                <script>
                    document.getElementById('desde').focus();
                </script>

            </div>
        </form>

    </body>
</html>
