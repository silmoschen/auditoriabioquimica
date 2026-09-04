<?php
session_start();

$usuario = $_SESSION['susuario'];
$pass = $_SESSION['spass'];
$nombre = $_SESSION['snombre'];

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cUtiles.php");
$u = new cUtiles;
?>

<html xmlns="http://www.w3.org/1999/xhtml">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <head><title>Listar Coseguros</title>

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

            function ProcesarInforme(){                
                var codos  = document.getElementById('listObsocial').value;
                if (codos != '000000') {
                    var aleatorio = Math.random();
                    var divProceso = document.getElementById('Proceso');
                    divProceso.innerHTML = '';                    

                    var divMensaje = document.getElementById('Mensaje');                    
                    var desde  = document.getElementById('desde').value;
                    var hasta  = document.getElementById('hasta').value;
                    
                    if (ValidarFecha(desde) & ValidarFecha(hasta)) {  
                        if (confirm('¿ Seguro para Iniciar Proceso ?. Este Proceso Puede tardar Varios Minutos. NO LO INTERRUMPA !!!' )) {
                            var ajax=objetoAjax();
                            divMensaje.innerHTML = '<img src="anim.gif"> :: Procesando Datos a Transferir, Espere por favor ...';
                            ajax.open('GET', './operaciones/procesar_inf_coseguros.php?desde='+desde+'&hasta='+hasta+'&aleatorio='+aleatorio+'&codos='+codos);                      
                            ajax.onreadystatechange=function() {
                                if (ajax.readyState==4) {
                                    divProceso.innerHTML = ajax.responseText;
                                    divMensaje.innerHTML = '';
                                }   
                                MostrarBotonesImpresion();
                            }                        
                            ajax.send(null)
                        }
                    } else {
                        alert('Una de las Fechas Ingresadas es Incorrecta ...!');
                    }
                } else {
                    alert('No Selecciono o Existen Obras Sociales Definidas para este Proceso ...!');
                }
            }  
            
            function MostrarBotonesImpresion() {
                var aleatorio = Math.random();
                divImprimir = document.getElementById('BotonesImpresion');
                ajax4=objetoAjax();
                ajax4.open('GET', './operaciones/imprimir_lista_tipocontrol.php?aleatorio='+aleatorio);
                ajax4.onreadystatechange=function() {
                    if (ajax4.readyState==4) {
                        //mostrar resultados en esta capa
                        divImprimir.innerHTML = ajax4.responseText
                    }
                    document.frmConsulta.btnImprimir.focus();
                }
                ajax4.send(null);
            }

            function ImprimirLista()  {                
                var ficha = document.getElementById('Proceso');
                var ventimp = window.open(' ', 'popimpr');
                ventimp.document.write( ficha.innerHTML );
                ventimp.document.close();
                ventimp.print();
                ventimp.close();
            }
            
            function CancelarImpresion() {                
               document.getElementById('BotonesImpresion').innerHTML = '';
               document.getElementById('Proceso').innerHTML = '';                    
            }


        </script>

        <link rel="stylesheet" type="text/css" href="/css1.css">

    </head>

    <body onLoad="javascript: if (Init()) {desde.focus()}; return true;">

        <form name="frmInfCoseguros" method="post" onsubmit="return false">

            <div style="margin:auto;width:550px;text-align:center;">
                <?php
                echo '<td width="54"><input name="usuario" type="hidden" id="usuario" value="' . $usuario . '" /></td>';
                include('menu_admin.php');
                ?>

                <table width="550px" border="0" align="center">
                    <tr>
                        <td width="140px" align="left">Informe de Coseguros</td>
                        <td width="360px"><hr></td>
                    </tr>
                </table>

                <table width="426" border="0" align="center">
                    <tr>
                        <td width="50" align="right">O.Social:</td>
                        <td width="200px">
                            <?
                            include('operaciones/list_os_coseguros.php');
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
                            <a href="javascript://" onclick="ProcesarInforme()">Generar Informe de Coseguros</a>
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

                <div id="BotonesImpresion" align="center">
                </div>

                <div id="Proceso" align="left">
                </div>

                <div id="Mensaje" align="center">
                </div>

            </div>
        </form>

    </body>

    <script>
        Init();
    </script>

</html>
