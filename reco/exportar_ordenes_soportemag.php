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

            function ProcesarRecalculo() {
                if (confirm('¿ Seguro para Procesar Exportación de Datos ?. Este Proceso Puede tardar Varios Minutos. NO LO INTERRUMPA !!!' )) {
                    Procesar();
                }
            }

            function Procesar(){
                var codos  = document.getElementById('listObsocial').value;
                if (codos.length > 0) {
                    var aleatorio = Math.random();
                    var divError1 = document.getElementById('Errores');
                    var divError3 = document.getElementById('BotonesImpresion');
                    var divProceso = document.getElementById('Proceso');
                    if (document.getElementById('ver_ocultar') != undefined) {
                        var divError2 = document.getElementById('ver_ocultar');
                        divError2.innerHTML = '';
                    }
                    divError1.innerHTML = '';
                    divError3.innerHTML = '';
                    divProceso.innerHTML = '';                    

                    var divMensaje = document.getElementById('Mensaje');                    
                    var desde  = document.getElementById('desde').value;
                    var hasta  = document.getElementById('hasta').value;
                    
                    if (ValidarFecha(desde) & ValidarFecha(hasta)) {                        
                        var ajax=objetoAjax();
                        divMensaje.innerHTML = '<img src="anim.gif"> :: Procesando Datos a Transferir, Espere por favor ...';
                        ajax.open('GET', './operaciones/procesar_export_soportemag.php?desde='+desde+'&hasta='+hasta+'&aleatorio='+aleatorio+'&codos='+codos);                      
                        ajax.onreadystatechange=function() {
                            if (ajax.readyState==4) {
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

            function GenerarArchivoExportacion(){
                var aleatorio = Math.random();
                var divMensaje = document.getElementById('Mensaje');

                var codos  = document.getElementById('listObsocial').value;
                var desde  = document.getElementById('desde').value;
                var hasta  = document.getElementById('hasta').value;

                var ajax=objetoAjax();
                divMensaje.innerHTML = '<img src="anim.gif"> :: Generando Fichero para Emulación, Espere por favor';
                ajax.open('GET', './operaciones/transferir_export_soportemag.php?desde='+desde+'&hasta='+hasta+'&aleatorio='+aleatorio+'&codos='+codos);
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        divMensaje.innerHTML = ajax.responseText
                    }                    
                }
                ajax.send(null);
            }
            
            function GenerarCantidadDeterminaciones(){
                var aleatorio = Math.random();
                var divMensaje = document.getElementById('Mensaje');

                var codos  = document.getElementById('listObsocial').value;
                var desde  = document.getElementById('desde').value;
                var hasta  = document.getElementById('hasta').value;

                var ajax=objetoAjax();
                divMensaje.innerHTML = '<img src="anim.gif"> :: Generando Fichero de Determinaciones, Espere por favor';
                ajax.open('GET', './operaciones/transferir_export_determinaciones.php?desde='+desde+'&hasta='+hasta+'&aleatorio='+aleatorio+'&codos='+codos);
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        divMensaje.innerHTML = ajax.responseText
                    }                    
                }
                ajax.send(null);
            }
            
            function GenerarCantidadDeterminacionesExcel(){
                var aleatorio = Math.random();
     
                var codos  = document.getElementById('listObsocial').value;
                var desde  = document.getElementById('desde').value;
                var hasta  = document.getElementById('hasta').value;
                var ruta  = document.getElementById('_ruta').value;
                
                if (confirm('¿ Seguro para Exportar las Ordenes Transferidas a Excel ?' )) {
                    var host = location.protocol + '//' + location.hostname + '/' + ruta + '/operaciones/transferir_export_determinaciones_excel.php?desde='+desde+'&hasta='+hasta+'&aleatorio='+aleatorio+'&codos='+codos;
                    window.location.href = host;
                }
            }

            function VerErrores(){
                var aleatorio = Math.random();
                divError = document.getElementById('Errores');
                var divError1 = document.getElementById('Errores');
                divError1.innerHTML = '';
                if (document.getElementById('ver_ocultar') != undefined) {
                    var divError2 = document.getElementById('ver_ocultar');
                    divError2.innerHTML = '';
                }
                ajax=objetoAjax();
                divError.innerHTML = '<img src="anim.gif"> :: Procesando, Espere por favor';
                ajax.open('GET', './operaciones/ver_errores_export_soportemag.php?aleatorio='+aleatorio);
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        divError.innerHTML = ajax.responseText
                    }
                    Ocultar_Errores();
                    Imprimir_Errores();
                }
                ajax.send(null)
            }

            function OcultarErrores(){                
                var divError1 = document.getElementById('Errores');
                var divError2 = document.getElementById('ver_ocultar');
                var divError3 = document.getElementById('BotonesImpresion');
                ajax=objetoAjax();
                divError1.innerHTML = '';
                divError2.innerHTML = '';
                divError3.innerHTML = '';
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        divError.innerHTML = ajax.responseText
                    }
                }
                ajax.send(null)
            }

            function Ocultar_Errores(){
                var aleatorio = Math.random();
                var divError = document.getElementById('ver_ocultar');

                ajax1=objetoAjax();
                divError.innerHTML = '';
                ajax1.open('GET', './operaciones/link_ocultar_errores_export_soportemag.php?aleatorio='+aleatorio);
                ajax1.onreadystatechange=function() {
                    if (ajax1.readyState==4) {
                        divError.innerHTML = ajax1.responseText
                    }
                }
                ajax1.send(null)
            }

            function Imprimir_Errores(){
                var aleatorio = Math.random();
                var divImprimir = document.getElementById('BotonesImpresion');

                ajax2=objetoAjax();
                ajax2.open('GET', './operaciones/imprimir_errores_exportacion.php?aleatorio='+aleatorio);
                ajax2.onreadystatechange=function() {
                    if (ajax2.readyState==4) {
                        divImprimir.innerHTML = ajax2.responseText
                    }
                }
                ajax2.send(null)
            }

            function ImprimirErrores()  {                
                var ficha = document.getElementById('Errores');
                var ventimp = window.open(' ', 'popimpr');
                ventimp.document.write( ficha.innerHTML );
                ventimp.document.close();
                ventimp.print();
                ventimp.close();
            }

            function CancelarImpresion(){
                OcultarErrores();
            }


        </script>

        <link rel="stylesheet" type="text/css" href="/css1.css">

    </head>

    <body onLoad="javascript: if (Init()) {desde.focus()}; return true;">

        <form name="frmExportarOrdenes" method="post" onsubmit="return false">

            <div style="margin:auto;width:550px;text-align:center;">
                <?php
                echo '<td width="54"><input name="usuario" type="hidden" id="usuario" value="' . $usuario . '" /></td>';
                include('menu_admin.php');
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
                        <td width="200px">
                            <?
                            include('operaciones/lista_os_exportar.php');
                            ?>
                        </td>
                        <td width="100px"></td>
                        <td width="100px"></td>
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
                            <a href="javascript://" onclick="ProcesarRecalculo()">Iniciar el Proceso de Exportación</a>
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
