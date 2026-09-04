<?php
session_start();

$codigo = '660001';

$usuario = $_SESSION['susuario'];
$pass    = $_SESSION['spass'];
$nombre  = $_SESSION['snombre'];
$trans   = $_REQUEST['trans'];

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cUtiles.php");
$u = new cUtiles;
?>

<html xmlns="http://www.w3.org/1999/xhtml">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <head><title>Estadísticas Pedidos por Médico</title>

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
                        if (confirm('¿ Seguro para Procesar Estadística en el Período ' + document.getElementById('desde').value + ' - ' + document.getElementById('hasta').value +  ' ?' )) {
                            Procesar();
                        }
                        return true;
                    }
                }
            }

            function Procesar(){
                var aleatorio = Math.random();
                divInforme = document.getElementById('Informe');
                divBotones = document.getElementById('Botones');
                
                divInforme.innerHTML = '';

                var desde = document.getElementById('desde').value;
                var hasta = document.getElementById('hasta').value;
                var codos = document.getElementById('listObsocial').value;
                var codigo = document.getElementById('codigo').value;

                if (ValidarFecha(desde) & ValidarFecha(hasta) & codigo != '') {
                    ajax=objetoAjax();
                    divInforme.innerHTML = '<img src="anim.gif"> :: Procesando, Espere por favor ...';
                    ajax.open('GET', './operaciones/procesar_est_determinaciones_medico.php?desde='+desde+'&hasta='+hasta+'&codos='+codos+'&codigo='+codigo+'&aleatorio='+aleatorio);
                    ajax.onreadystatechange=function() {
                        if (ajax.readyState==4) {
                            divInforme.innerHTML = ajax.responseText
                        }
                        MostrarBotonesImpresion();
                    }
                    ajax.send(null)
                } else {
                    alert('Una de las Fechas Ingresadas ó el Parametroson Incorrectos ...!');
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
                var ficha = document.getElementById('Informe');
                var ventimp = window.open(' ', 'popimpr');
                ventimp.document.write( ficha.innerHTML );
                ventimp.document.close();
                ventimp.print( );
                ventimp.close();

                CancelarImpresion();
            }

            function CancelarImpresion()  {                
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

        <link rel="stylesheet" type="text/css" href="/css1.css" />

    </head>

    <body onLoad="javascript: if (Init()) {desde.focus()}; return true;">

        <form name="frmEstadisticasPedidosMedicos" method="post" onsubmit="return false">

            <div style="margin:auto;width:550px;text-align:center;">
                <?php
                echo '<td width="54"><input name="usuario" type="hidden" id="usuario" value="' . $usuario . '" /></td>';
                include('menu_admin.php');
                ?>

                <table width="550px" border="0" align="center">
                    <tr>
                        <td width="180px" align="left">Estadísticas Pedidos por Médico</td>
                        <td width="320px"><hr /></td>
                    </tr>
                </table>

                <table width="550px" border="0" align="center">
                    <tr>
                        <td width="50px" align="right">O.Social:</td>
                        <td width="200px">
                            <?
                            include('operaciones/lista_os_auditarordenes.php');
                            ?>
                        </td>
                        <td width="70px"></td>
                        <td width="100px"></td>
                    </tr>

                    <tr>
                        <td width="50px" align="right">Desde:</td>
                        <td width="200px" align = "left">
                            <input name="desde" id="desde" type="text" style="font-size: 10px;" maxlength="10" width="2" size="10" onkeypress="javascript: if(ValidarFechaDesde(event, desde.value)) {hasta.focus()}; return true" />
                            (dd/mm/aaaa)
                        </td>
                        <td width="70px" align="right">Hasta:</td>
                        <td width="100px">
                            <input name="hasta" id="hasta" type="text" style="font-size: 10px;" maxlength="10" width="2" size="10" onkeypress="javascript: if(ValidarFechaHasta(event, hasta.value)) {hasta.focus()}; return true" />
                        </td>
                    </tr>
                </table>

                <table width="550px" border="0" align="center">
                    <tr>
                        <td width="70px" align="right">Código:</td>
                        <td width="60px" align = "left">
                            <?
                            echo '<input name="codigo" id="codigo" type="text" style="font-size: 10px;" maxlength="6" width="2" size="10" value=' . $codigo . '>';
                            ?>
                        </td>
                        <td width="300px" style="font-size:9px;">Parámetro de Referencia</style>
                        </td>
                        <td width="150px" align="left">
                            <a href="javascript://" onclick="Procesar()">Iniciar Proceso</a>
                        </td>
                    </tr>
                </table>

                <table width="550px" border="0" align="center">
                    <tr>
                        <td><hr /></td>
                    </tr>
                </table>

                <div id="Botones" align="center">
                </div>

                <div id="Informe" align="center">
                </div>

            </div>
        </form>

    </body>

    <script>
        Init();
    </script>

</html>

