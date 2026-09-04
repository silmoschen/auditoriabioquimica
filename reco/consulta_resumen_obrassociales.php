<?php
session_start();

$usuario = $_SESSION['susuario'];
$pass = $_SESSION['spass'];
$nombre = $_SESSION['snombre'];
$regla = $_REQUEST['regla'];

if ($regla == '')
    $regla = '10';

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cUtiles.php");
$u = new cUtiles;

echo '<input type="hidden" id="_ruta" value="' . $ruta . '"/>';
echo '<input type="hidden" id="_regla" value="' . $regla . '"/>';
?>

<html xmlns="http://www.w3.org/1999/xhtml">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <head><title>Generar Cuadro de Obras Sociales</title>

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
                        GenerarCuadro();
                        return true;
                    }
                }
            }

            function GenerarCuadro() {

                var aleatorio = Math.random();
                var divMensaje = document.getElementById('Resultado');

                var regla = document.getElementById('_regla').value;
                var desde = document.getElementById('desde').value;
                var hasta = document.getElementById('hasta').value;

                if (ValidarFecha(desde) && ValidarFecha(hasta)) {

                    var ajax = objetoAjax();
                    divMensaje.innerHTML = '<img src="anim.gif"> :: Generando Cuadro de Resultados, Espere por favor';
                    ajax.open('GET', './operaciones/generar_cuadro_obrasocial.php?desde=' + desde + '&hasta=' + hasta + '&aleatorio=' + aleatorio + '&regla=' + regla);
                    ajax.onreadystatechange = function () {
                        if (ajax.readyState == 4) {
                            divMensaje.innerHTML = ajax.responseText
                        }
                    }
                    ajax.send(null);
                }
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
                include('menu_admin.php');
                ?>

                <table width="550px" border="0" align="center">
                    <tr>
                        <td width="140px" align="left">Cuadro de Consumos</td>
                        <td width="360px"><hr></td>
                    </tr>
                </table>

                <table width="426" border="0" align="center">                  

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

                        <td width="70">Hasta Fecha:</td>
                        <td width="100px">
                            <input name="hasta" id="hasta" type="text" style="font-size: 10px;" maxlength="10" width="2" size="10" onkeypress="javascript: if (ValidarFechaHasta(event, hasta.value)) {
                                        hasta.focus()
                                    }
                                    ;
                                    return true" />

                        </td>
                    </tr>

                    <tr>

                        <td width="50px"></td>
                        <td width="200px" align="left">                            
                            <a href="javascript://" onclick="GenerarCuadro()">Generar Cuadro de Resultados</a>
                        </td>
                        <td width="70px">
                        </td>
                        <td width="100px"></td>
                    </tr>
                </table>

                <table width="550px" border="0" align="center">
                    <tr>
                        <td><hr></td>
                    </tr>
                </table>

                <div id="Mensaje" align="left">
                </div>

                <div id="Resultado" align="center">
                </div>

            </div>
        </form>

    </body>

    <script>
        Init();
    </script>

</html>
