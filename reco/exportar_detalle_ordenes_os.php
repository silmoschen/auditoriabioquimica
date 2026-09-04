<?php
session_start();

$usuario = $_SESSION['susuario'];
$pass = $_SESSION['spass'];
$nombre = $_SESSION['snombre'];

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cUtiles.php");
$u = new cUtiles;

echo '<input type="hidden" id="_ruta" value="' . $ruta . '"/>';
?>

<html xmlns="http://www.w3.org/1999/xhtml">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <head><title>Exportar Ordenes</title>

        <script src="../jscript/funcionajax.js"></script>
        <script src="../jscript/funciones.js"></script>
        <script>

            function Init() {
                document.getElementById('desde').focus();
                return true;
            }

            function ValidarFechaDesde(e, xfenac) {
                e = (window.event) ? event : e;
                intKey = (e.keyCode) ? e.keyCode : e.charCode;
                if (intKey == 13) {
                    return ValidarFecha(xfenac);
                }
            }

            function ValidarFechaHasta(e, xfenac) {
                e = (window.event) ? event : e;
                intKey = (e.keyCode) ? e.keyCode : e.charCode;
                if (intKey == 13) {
                    if (ValidarFecha(xfenac)) {
                        //ProcesarRecalculo();
                        return true;
                    }
                }
            }

            function ProcesarRecalculo(tipo) {
                if (confirm('¿ Seguro para Procesar Datos ?. Este Proceso Puede tardar Varios Minutos. NO LO INTERRUMPA !!!')) {
                    if (tipo == 1)
                        Procesar1();
                    if (tipo == 2)
                        Procesar2();
                }
            }

            function Procesar1() {
                var codos = document.getElementById('listObsocial').value;
                var aleatorio = Math.random();
                if (codos.length > 0) {
                    var divMensaje = document.getElementById('Mensaje');
                    var desde = document.getElementById('desde').value;
                    var hasta = document.getElementById('hasta').value;
                    var divProceso = document.getElementById('Proceso');
                    var ruta = document.getElementById('_ruta').value;

                    if (ValidarFecha(desde) & ValidarFecha(hasta)) {
                        var host = location.protocol + '//' + location.hostname + '/' + ruta + '/operaciones/export_detalle_ordenes.php?desde=' + desde + '&hasta=' + hasta + '&aleatorio=' + aleatorio + '&codos=' + codos;
                        window.location.href = host;
                        return;
                    }
                }
            }

            function Procesar2() {
                var codos = document.getElementById('listObsocial').value;
                var aleatorio = Math.random();
                if (codos.length > 0) {
                    var divMensaje = document.getElementById('Mensaje');
                    var desde = document.getElementById('desde').value;
                    var hasta = document.getElementById('hasta').value;
                    var divProceso = document.getElementById('Proceso');
                    var ruta = document.getElementById('_ruta').value;

                    /*
                     
                     if (ValidarFecha(desde) & ValidarFecha(hasta)) {
                     var host = location.protocol + '//' + location.hostname + '/' + ruta + '/operaciones/export_detalle_ordenes.php?desde=' + desde + '&hasta=' + hasta + '&aleatorio=' + aleatorio + '&codos=' + codos;
                     window.location.href = host;
                     return;
                     }
                     
                     */

                    if (ValidarFecha(desde) & ValidarFecha(hasta)) {
                        var ajax = objetoAjax();
                        divMensaje.innerHTML = '<img src="anim.gif"> :: Procesando Datos, Espere por favor ...';
                        ajax.open('GET', './operaciones/export_detalle_ordenes.php?desde=' + desde + '&hasta=' + hasta + '&aleatorio=' + aleatorio + '&codos=' + codos);
                        ajax.onreadystatechange = function () {
                            if (ajax.readyState == 4) {
                                divProceso.innerHTML = ajax.responseText;
                                divMensaje.innerHTML = '';
                            }
                        }
                        ajax.send(null)

                    } else {
                        alert('Una de las Fechas Ingresadas es Incorrecta ...!');
                    }
                } else {
                    alert('No Existen Obras Sociales Definidas para este Proceso ...!');
                }
            }


            function GenerarArchivoExportacion() {
                var aleatorio = Math.random();
                var divMensaje = document.getElementById('Mensaje');

                var codos = document.getElementById('listObsocial').value;
                var desde = document.getElementById('desde').value;
                var hasta = document.getElementById('hasta').value;

                var ajax = objetoAjax();
                divMensaje.innerHTML = '<img src="anim.gif"> :: Generando Fichero para Emulación, Espere por favor';
                ajax.open('GET', './operaciones/transferir_export_soportemag.php?desde=' + desde + '&hasta=' + hasta + '&aleatorio=' + aleatorio + '&codos=' + codos);
                ajax.onreadystatechange = function () {
                    if (ajax.readyState == 4) {
                        divMensaje.innerHTML = ajax.responseText
                    }
                }
                ajax.send(null);
            }

        </script>

        <link rel="stylesheet" type="text/css" href="/css1.css">

    </head>

    <body onLoad="javascript: if (Init()) {
                desde.focus()
            }
            ;
            return true;">

        <form name="frmExportarOrdenes" method="post" onsubmit="return false">

            <div style="margin:auto;width:550px;text-align:center;">
                <?php
                echo '<td width="54"><input name="usuario" type="hidden" id="usuario" value="' . $usuario . '" /></td>';
                include('menu_iapos.php');
                ?>

                <table width="550px" border="0" align="center">
                    <tr>
                        <td width="140px" align="left">Exportación de Ordenes</td>
                        <td width="360px"><hr></td>
                    </tr>
                </table>

                <table width="426" border="0" align="center">
                    <tr>
                        <td width="50" align="right">O.Social:</td>
                        <td width="400" colspan="3">
                            <?
                            include('operaciones/lista_obrassociales_session.php');
                            ?>
                        </td>
                    </tr>

                    <tr>
                        <td width="50px" align="right">Desde:</td>
                        <td width="200px" align = "left">
                            <input name="desde" id="desde" type="text" style="font-size: 10px;" maxlength="10" width="2" size="10" onkeypress="javascript: if (ValidarFechaDesde(event, desde.value)) {
                                        hasta.focus()
                                    }
                                    ;
                                    return true" />
                            (dd/mm/aaaa)
                        </td>

                        <td width="70">Hasta:</td>
                        <td width="100px">
                            <input name="hasta" id="hasta" type="text" style="font-size: 10px;" maxlength="10" width="2" size="10" onkeypress="javascript: if (ValidarFechaHasta(event, hasta.value)) {
                                        hasta.focus()
                                    }
                                    ;
                                    return true" />

                        </td>
                    </tr>

                </table>
                <br/>
                <table width="426" border="0" align="center">
                    <tr>                       
                        <td width="200px" align="left" colspan="2">
                            <a href="javascript://" onclick="ProcesarRecalculo(1)">Exportar (a Excel)</a>
                        </td>
                        <td width="200px" colspan="2">
                            <a href="javascript://" onclick="ProcesarRecalculo(2)">Exportar (a Archivo csv)</a>
                        </td>
                    </tr>
                </table>

                <table width="550px" border="0" align="center">
                    <tr>
                        <td><hr></td>
                    </tr>
                </table>

                <div id="Proceso" align="left">
                </div>

                <div id="Mensaje" align="center">
                </div>

                <div id="BotonesImpresion" align="center">
                </div>

                <div id="Errores" align="center">
                </div>

            </div>
        </form>

    </body>

    <script>
        Init();
    </script>

</html>
