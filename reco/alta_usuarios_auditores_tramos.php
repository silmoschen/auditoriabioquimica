<?php
session_start();

$usuario = $_SESSION['susuario'];
$pass = $_SESSION['spass'];
$nombre = $_SESSION['snombre'];
if (isset($_GET['trans'])) {
    $trans = $_REQUEST['trans'];
}
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cUtiles.php");
$u = new cUtiles;

echo '<input type="hidden" id="ruta" value="' . $ruta . '">';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <head><title>Nuevos Usuarios</title>

        <script src="../jscript/funciones.js"></script>
        <script src="../jscript/funcionajax.js"></script>
        <script>

            function ValidarDesde(e, xfecha) {
                e = (window.event) ? event : e;
                intKey = (e.keyCode) ? e.keyCode : e.charCode;
                if (intKey == 13) {
                    if (ValidarFecha(xfecha)) {
                        return true;
                    }
                    return false;
                }
            }

            function ValidarHasta(e, xfecha) {
                e = (window.event) ? event : e;
                intKey = (e.keyCode) ? e.keyCode : e.charCode;
                if (intKey == 13) {
                    if (ValidarFecha(xfecha))
                        return true;
                    return false;
                }
            }

            function Cerrar() {
                return false;
            }

            function registrar(xdesde, xhasta, xauditor1, xauditor2) {
                if (ValidarFecha(xdesde) == false)
                    return false;
                if (ValidarFecha(xhasta) == false)
                    return false;

                var aleatorio = Math.random();

                var divMensaje = document.getElementById('Mensaje');

                divMensaje.innerHTML = '<i>Registrando Usuario ...</i>';

                var f1 = xdesde.substr(6, 4) + xdesde.substr(3, 2) + xdesde.substr(0, 2);
                var f2 = xhasta.substr(6, 4) + xhasta.substr(3, 2) + xhasta.substr(0, 2);
                var a1 = document.getElementById('auditor1').value;
                var a2 = document.getElementById('auditor2').value;

                ajax = objetoAjax();
                ajax.open('GET', './operaciones/registro_usuario_auditor_tramo.php?desde=' + f1 + '&hasta=' + f2 + '&auditor1=' + a1 + '&auditor2=' + a2 + '&aleatorio=' + aleatorio);
                ajax.onreadystatechange = function () {
                    if (ajax.readyState == 4) {
                        //mostrar resultados en esta capa
                        divMensaje.innerHTML = ajax.responseText;
                    }
                    ListarUsuarios();
                }

                ajax.send(null);
            }

            function ListarUsuarios() {
                var aleatorio = Math.random();

                var divListar = document.getElementById('ListaUsuarios');

                ajax1 = objetoAjax();
                ajax1.open('GET', './operaciones/lista_usuarios_auditores_tramos.php?&aleatorio=' + aleatorio);
                ajax1.onreadystatechange = function () {
                    if (ajax1.readyState == 4) {
                        //mostrar resultados en esta capa
                        divListar.innerHTML = ajax1.responseText;
                    }
                }

                ajax1.send(null);
            }

            function Borrar(id) {
                if (confirm('¿ Seguro Eliminar Definicion ?')) {
                    var aleatorio = Math.random();

                    var divMensaje = document.getElementById('Mensaje');

                    divMensaje.innerHTML = '<i>Borrando ...</i>';

                    ajax = objetoAjax();
                    ajax.open('GET', './operaciones/borro_usuario_auditores_tramos.php?id=' + id + '&aleatorio=' + aleatorio);
                    ajax.onreadystatechange = function () {
                        if (ajax.readyState == 4) {
                            //mostrar resultados en esta capa
                            divMensaje.innerHTML = ajax.responseText;
                        }
                        ListarUsuarios();
                    }

                    ajax.send(null);
                }
            }
        </script>

        <link rel="stylesheet" type="text/css" href="css1.css">

    </head>

    <body onLoad="javascript: if (Init()) {
                us.focus();
            }
            ;
            return true;">

        <div style="margin:auto;width:500px;text-align:center;">

            <?php
            include('menu_admin.php');
            echo '<td width="54"><input name="usuario" type="hidden" id="usuario" value="' . $usuario . '" /></td>';
            ?>
            <form name="frm_nuevousuario" method="post" onsubmit="return false">

                <div style="margin:auto;width:350px;text-align:center;">

                    <table width="350" border="0">
                        <tr>        
                            <td width="320" align="left">
                            </td>
                            <td width="34">
                            </td>
                        </tr>
                    </table>

                    <FIELDSET>
                        <LEGEND>Definir Tramo Auditores</LEGEND>

                        <table width="350px" border="0" align="center">
                            <tr>
                                <td align="right" width="50px">Desde:</td>
                                <td align="left" width="350px">
                                    <input name="us" id="desde" type="text" style="font-size: 10px;" maxlength="10" width="2" size="10" onkeypress="javascript: if (ValidarDesde(event, desde.value)) {
                                                hasta.focus()
                                            }
                                            ;
                                            return true" />
                                </td>
                            </tr>
                            <tr>
                                <td align="right" width="50px">Hasta:</td>
                                <td align="left" width="350px">
                                    <input name="hasta" id="hasta" type="text" style="font-size: 10px;" maxlength="10" width="2" size="10" onkeypress="javascript: if (ValidarHasta(event, hasta.value)) {
                                                cmbauditor1.focus()
                                            }
                                            ;
                                            return true" />
                                </td>
                            </tr>
                            <tr>
                                <td align="right" width="50px">Auditor 1:</td>
                                <td align="left">
                                    <div id="_auditor1">
                                        <?php include('operaciones/lista_usuarios_1.php') ?>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td align="right" width="50px">Auditor 2:</td>
                                <td align="left">
                                    <div id="_auditor2">
                                        <?php include('operaciones/lista_usuarios_2.php') ?>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td width="110px" align="right">
                                    <input type="button" name="btnRegistrar" value="Registrar"class="button gray small" onclick="registrar(desde.value, hasta.value, auditor1.value, auditor2.value);
                                            return false" />
                                </td>
                                <td align = "left"><input type="button" name="btnCerrar" class="button gray small" value="Cerrar" onclick="Cerrar();
                                        return false" /></td>
                            </tr>

                        </table>

                        <script>
                            document.getElementById('desde').focus();
                        </script>

                    </FIELDSET>

                </div>
            </form>

            <div id="Mensaje" align="center"></div>

            <div style="overflow-y: scroll; height:250px;" id="ListaUsuarios" align="center">
                <script>ListarUsuarios();</script>
            </div>

        </div>

    </body>
</html>