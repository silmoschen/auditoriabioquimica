<?php
session_start();
//session_register('susuario');
//session_register('spass');
//session_register('snombre');
//session_register('sdireccion');
//session_register('stelefono');
//session_register('semail');

$usuario = $_SESSION['susuario'];
$pass    = $_SESSION['spass'];
$nombre  = $_SESSION['snombre'];

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cUtiles.php");
$u = new cUtiles;
?>

<html xmlns="http://www.w3.org/1999/xhtml">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <head><title>Estadísticas</title>

        <script src="../jscript/funcionajax.js"></script>
        <script src="../jscript/funciones.js"></script>
        <script>

            function Init() {
                document.frmRecalcular.desde.focus();
                return true;
            }

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
                        ProcesarRecalculo();
                        return true;
                    }
                }
            }

            function Procesar(tipo){
                var aleatorio = Math.random();
                divInforme = document.getElementById('Informe');
                divBotones = document.getElementById('Botones');
                var codos  = document.getElementById('listObsocial').value;

                divInforme.innerHTML = '';

                var desde = document.getElementById('desde').value;
                var hasta = document.getElementById('hasta').value;
                var vn = document.getElementById('vn').checked;

                if (ValidarFecha(desde) & ValidarFecha(hasta)) {
                    ajax=objetoAjax();
                    divBotones.innerHTML = '<img src="anim.gif"> :: Procesando, Espere por favor';
                    ajax.open('GET', './operaciones/procesar_est_autorizado_rechazado.php?desde='+desde+'&hasta='+hasta+'&aleatorio='+aleatorio+'&codos='+codos+'&tipo='+tipo+'&vn='+vn);
                    ajax.onreadystatechange=function() {
                        if (ajax.readyState==4) {
                            divInforme.innerHTML = ajax.responseText
                        }
                        MostrarBotonesImpresion();
                    }
                    ajax.send(null)
                } else {
                    alert('Una de las Fechas Ingresadas es Incorrecta ...!');
                }
            }

            function MostrarBotonesImpresion() {
                var aleatorio = Math.random();
                divImprimir = document.getElementById('Botones');
                ajax4=objetoAjax();
                ajax4.open('GET', './operaciones/imprimir_estadisticas.php?aleatorio='+aleatorio);
                ajax4.onreadystatechange=function() {
                    if (ajax4.readyState==4) {
                        //mostrar resultados en esta capa
                        divImprimir.innerHTML = ajax4.responseText
                    }
                    document.frmConsulta.btnImprimir.focus();
                }
                ajax4.send(null);
            }

            function ImprimirEstadistica() {
                var aleatorio = Math.random();
                var ficha = document.getElementById('Informe');
                var ventimp = window.open(' ', 'popimpr');
                ventimp.document.write( ficha.innerHTML );
                ventimp.document.close();
                ventimp.print( );
                ventimp.close();

                CancelarImpresion();
            }

            function CancelarImpresion()  {
                var aleatorio = Math.random();
                divInf = document.getElementById('Informe');
                divInf.innerHTML = '';
                divBt = document.getElementById('Botones');
                divBt.innerHTML = '';
                ajax5=objetoAjax();
                ajax5.onreadystatechange=function() {
                    if (ajax5.readyState==4) {
                        //mostrar resultados en esta capa
                        divInf.innerHTML = ajax5.responseText
                    }
                }
                ajax5.send(null)
            }

        </script>

        <link rel="stylesheet" type="text/css" href="/css1.css">

    </head>

    <body onLoad="javascript: if (Init()) {desde.focus()}; return true;">

        <form name="frmRecalcular" method="post" onsubmit="return false">

            <div style="margin:auto;width:550px;text-align:center;">
                <?php
                echo '<td width="54"><input name="usuario" type="hidden" id="usuario" value="' . $usuario . '" /></td>';
                include('menu_admin.php');
                ?>

                <table width="550px" border="0" align="center">
                    <tr>
                        <td width="120px" align="left">Estadísticas Auditoría</td>
                        <td width="380px"><hr></td>
                    </tr>
                </table>

                <table width="426" border="0" align="center">
                    <tr>
                        <td width="50" align="right">O.Social:</td>
                        <td width="200px">
                            <?
                            include('operaciones/lista_os_auditarordenes.php');
                            ?>
                        </td>
                        <td width="200px" colspan="2" align="right">
                            <input type="checkbox" id="vn" checked="true">
                                Aplicar NBU Ext.
                            </input>
                        </td>                        
                    </tr>

                    <tr>
                        <td width="50px" align="right">Desde:</td>
                        <td width="200px" align = "left">
                            <input name="desde" id="desde" type="text" style="font-size: 10px;" maxlength="10" width="2" size="10" onkeypress="javascript: if(ValidarFechaDesde(event, desde.value)) {hasta.focus()}; return true" />
                            (dd/mm/aaaa)
                        </td>

                        <td width="70">Hasta:</td>
                        <td width="100px">
                            <input name="hasta" id="hasta" type="text" style="font-size: 10px;" maxlength="10" width="2" size="10" onkeypress="javascript: if(ValidarFechaHasta(event, hasta.value)) {hasta.focus()}; return true" />

                        </td>
                    </tr>

                    <tr>

                        <td width="50px"></td>
                        <td width="200px" align="left">
                            <a href="javascript://" onclick="Procesar('A')">Ordenes Autorizadas</a> /
                            <a href="javascript://" onclick="Procesar('R')">Rechazadas</a>
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

                <div id="Botones" align="center">
                </div>

                <div id="Informe" align="left">
                </div>

            </div>
        </form>

    </body>

    <script>
        Init();
    </script>

</html>
