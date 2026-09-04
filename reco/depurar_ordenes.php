<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

session_start();

$usuario = $_SESSION['susuario'];
$pass    = $_SESSION['spass'];
$nombre  = $_SESSION['snombre'];

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cUtiles.php");
$u = new cUtiles;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <head><title>Depuracion de Ordenes</title>
        <!--
            Toda la Secuencia JavaScript
            <td width="108" align="left"><input name="nrodoc" type="text" maxlength="15" width="4" onblur="javascript: if(ValidarAfiliado1(event, listObsocial.value, nrodoc.value)) {listMedicos.focus()}; return true" onkeypress="javascript: if(ValidarAfiliado(event, listObsocial.value, nrodoc.value)) {listMedicos.focus()}; return true">
        -->

        <script src="../jscript/funciones.js"></script>
        <script src="../jscript/funcionajax.js"></script>
        <script src="../jscript/showhidediv.js"></script>
        <script>

            function Init() {
                document.getElementById('desde').focus();
                CambiarOSS();
                return true;
            }
            
            function CambiarOSS() {
                var codos = document.getElementById('listObsocial').value;
                
                divResult = document.getElementById('Result');
                
                ajax1=objetoAjax();
                ajax1.open('GET', './operaciones/depurar_ordenes_list.php?codos='+codos);
                ajax1.onreadystatechange=function() {
                    if (ajax1.readyState==4) {
                        //mostrar resultados en esta capa
                        divResult.innerHTML = ajax1.responseText
                    }
                }
                ajax1.send(null)
            }

            function ValidarFechaDesde(e, xfenac){
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    return ValidarFecha(xfenac);
                }
            }

            function Depurar(){
                var desde = document.getElementById('desde').value;
                var codos = document.getElementById('listObsocial').value;
                if (ValidarFecha(desde) && codos.length > 0) {
                    if (confirm('¿ Seguro para Depurar Operaciones Anteriores a ' + desde + ' Pertenecientes a la Obra Social ' + codos + ' ?' )) {
                        var aleatorio   = Math.random();
                        var divMensaje  = document.getElementById('Mensaje');
                        var divProceso  = document.getElementById('Proceso');
                        var ajax=objetoAjax();
                        divProceso.innerHTML = '';
                        divMensaje.innerHTML = '<img src="anim.gif"> :: Procesando, espere un momento ...';
                        ajax.open('GET', './operaciones/depurar_ordenes.php?desde='+desde+'&codos='+codos+'&aleatorio='+aleatorio);
                        ajax.onreadystatechange=function() {
                            if (ajax.readyState==4) {
                                //mostrar resultados en esta capa
                                divProceso.innerHTML = ajax.responseText;
                                divMensaje.innerHTML = '';
                                CambiarOSS();
                            }
                        }
                        ajax.send(null)
                    }
                }
            }


        </script>

        <link rel="stylesheet" type="text/css" href="/css1.css">

    </head>

    <body onLoad="javascript: if (Init()) {desde.focus()}; return true;">

        <form name="frmDepurar" method="post" onsubmit="return false">

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

                <table width="550px" border="0" align="center">
                    <tr>
                        <td width="150px" align="left">Depuración de Ordenes</td>
                        <td width="350px"><hr></td>
                    </tr>
                </table>

                <div align="left"></div>
                <table width="550px" border="0" align="center">

                    <tr>
                        <td width="150px" align = "right">Seleccione la Obra Social:</td>
                        <td width="200px" align = "left">
                            <?
                            include('operaciones/lista_os_auditarordenes.php');
                            ?>
                        </td>
                        <td  width="50px">
                            <a href="javascript://" onclick="Depurar()">Depurar</a>
                        </td>
                    </tr>                  <tr>
                        <td width="150px" align = "right">Depurar Ordenes Anteriores a la Fecha:</td>
                        <td width="200px" align = "left">
                            <input name="desde" id="desde" type="text" style="font-size: 10px;" maxlength="10" width="2" size="10" onkeypress="javascript: if(ValidarFechaDesde(event, desde.value)) {hasta.focus()}; return true" />
                            (dd/mm/aaaa)
                        </td>
                        <td  width="50px"></td>
                    </tr>

                </table>

                <table width="550px" border="0" align="center">
                    <tr>
                        <td><hr></td>
                    </tr>
                </table>

                <font color="#FF0000">
                Advertencia: este Proceso transfiere las operaciones anteriores a la fecha
                que se ingrese a un Registro Historico. El Proceso es Irreversible. Cerciorece
                antes de Proceder.
                </font>

                <div id="Mensaje" align="center">
                </div>
                <div id="Proceso" align="left">
                </div>
                <div id="Result" align="left">
                </div>

                <script>
                    Init();
                </script>

            </div>
        </form>

    </body>
</html>
