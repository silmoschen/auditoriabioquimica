<?php
session_start();

$usuario = $_SESSION['susuario'];
$pass = $_SESSION['spass'];
$nombre = $_SESSION['snombre'];
$auditor1 = $_SESSION['auditor1'];
$auditor2 = $_SESSION['auditor2'];
$nauditor1 = $_SESSION['nauditor1'];
$nauditor2 = $_SESSION['nauditor2'];
$efector = $_SESSION['efector'];

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cUtiles.php");
$u = new cUtiles;
?>

<html xmlns="http://www.w3.org/1999/xhtml">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <head><title>Consulta de Ordenes de Auditoria</title>

        <script src="../jscript/funciones.js"></script>
        <script src="../jscript/funcionajax.js"></script>
        <script src="../jscript/showhidediv.js"></script>
        <script>

            function Init() {
                document.frmConsulta.desde.focus();
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
                        ConsultarOrdenes();
                        return true;
                    }
                }
            }

            function ListarOrdenes() {
                //donde se mostrar los registros
                var aleatorio = Math.random();
                var desde     = document.getElementById('desde').value;
                var hasta     = document.getElementById('hasta').value;
                var codos     = document.getElementById('listObsocial').value;
                var idprof    = '000000';
                if (document.getElementById('listEfectores').value != '000000') {
                    var idprof = document.getElementById('listEfectores').value;
                }
                if (ValidarFecha(desde)) {} else {return false;}
                if (ValidarFecha(hasta)) {} else {return false;}

                var divInforme    = document.getElementById('Informe');
                divImpresion  = document.getElementById('BotonesImpresion');
                divLista      = document.getElementById('Lista');
                divLista.innerHTML = '';
                divLista1     = document.getElementById('ListarOrden');
                divLista1.innerHTML = '';
                divLista3     = document.getElementById('ObservacionAuditor');
                divLista3.innerHTML = '';

                var ajaxinf=objetoAjax();
                //uso del medoto GET
                //indicamos el archivo que realizar� el proceso de paginar
                //junto con un valor que representa el nro de pagina
                ajaxinf.open('GET', './operaciones/informe_ordenes_auditoria.php?codos='+codos+'&idprof='+idprof+'&desde='+desde+'&hasta='+hasta+'&aleatorio='+aleatorio);
                divInforme.innerHTML= '<img src="anim.gif">  :: Procesando, espere un momento ...';
                ajaxinf.onreadystatechange=function() {
                    if (ajaxinf.readyState==4) {
                        //mostrar resultados en esta capa
                        divInforme.innerHTML = ajaxinf.responseText
                    }
                    ImprirInforme();
                }
                ajaxinf.send(null)
            }

            function ImprirInforme(){
                var aleatorio = Math.random();
                divBotones = document.getElementById('BotonesImpresion');

                ajax1=objetoAjax();
                ajax1.open('GET', './operaciones/imprimir_lista_tipocontrol.php?aleatorio='+aleatorio);
                ajax1.onreadystatechange=function() {
                    if (ajax1.readyState==4) {
                        //mostrar resultados en esta capa
                        divBotones.innerHTML = ajax1.responseText
                    }
                }
                ajax1.send(null)
            }

            function ImprimirLista()  {                
                var ficha = document.getElementById('Informe');
                var ventimp = window.open(' ', 'popimpr');
                ventimp.document.write( ficha.innerHTML );
                ventimp.document.close();
                ventimp.print();
                ventimp.close();
            }

            function CancelarImpresion(){
                divBotones = document.getElementById('BotonesImpresion');
                divLista   = document.getElementById('Informe');
                divBotones.innerHTML = '';
                divLista.innerHTML = '';

                ajax2=objetoAjax();
                ajax2.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                    }
                }
                ajax2.send(null)
            }

            function ConsultarOrdenes(){
                Pagina(1);
            }

            function CambiarOS(){
                ConsultarOrdenes();
            }

            function Pagina(nropagina){
                //donde se mostrar los registros
                var aleatorio = Math.random();
                showdiv("Lista");
                var desde     = document.getElementById('desde').value;
                var hasta     = document.getElementById('hasta').value;
                var codos     = document.getElementById('listObsocial').value;
                var idprof    = '000000';
                if (document.getElementById('listEfectores').value != '000000') {
                    var idprof = document.getElementById('listEfectores').value;
                }
                if (ValidarFecha(desde)) {} else {return false;}
                if (ValidarFecha(hasta)) {} else {return false;}

                divLista      = document.getElementById('Lista');
                divLista1     = document.getElementById('ListarOrden');
                divLista1.innerHTML = '';
                divLista2     = document.getElementById('BotonesImpresion');
                divLista2.innerHTML = '';
                divLista3     = document.getElementById('ObservacionAuditor');
                divLista3.innerHTML = '';
                divBotones = document.getElementById('BotonesImpresion');
                divInf = document.getElementById('Informe');
                divBotones.innerHTML = '';
                divInf.innerHTML = '';

                ajax=objetoAjax();
                //uso del medoto GET
                //indicamos el archivo que realizar� el proceso de paginar
                //junto con un valor que representa el nro de pagina
                ajax.open('GET', './operaciones/listar_ordenes_auditoria.php?codos='+codos+'&pag='+nropagina+'&idprof='+idprof+'&desde='+desde+'&hasta='+hasta+'&aleatorio='+aleatorio);                
                divLista.innerHTML= '<img src="anim.gif">  :: Procesando, espere un momento ...';
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divLista.innerHTML = ajax.responseText
                    }
                }
                ajax.send(null)
            }

            function MostrarOrden(xnroauditoria){
                //donde se mostrar los registros
                var aleatorio = Math.random();
                divVerOrden = document.getElementById('ListarOrden');
                hidediv("Lista");

                ajax3=objetoAjax();
                //uso del medoto GET
                //indicamos el archivo que realizar� el proceso de paginar
                //junto con un valor que representa el nro de pagina
                ajax3.open('GET', './operaciones/listar_hoja_orden.php?nrotrans='+xnroauditoria+'&aleatorio='+aleatorio);
                ajax3.onreadystatechange=function() {
                    if (ajax3.readyState==4) {
                        //mostrar resultados en esta capa
                        divVerOrden.innerHTML = ajax3.responseText
                    }
                    ListarObservacionAuditor(xnroauditoria);
                    MostrarBotonesImpresion();
                }

                ajax3.send(null)
            }

            function ListarObservacionAuditor(xnroauditoria){
                //donde se mostrar los registros
                var aleatorio = Math.random();
                divVerObs = document.getElementById('ObservacionAuditor');

                ajax9=objetoAjax();
                //uso del medoto GET
                //indicamos el archivo que realizar� el proceso de paginar
                //junto con un valor que representa el nro de pagina
                ajax9.open('GET', './operaciones/listar_hoja_orden_obsauditor.php?nrotrans='+xnroauditoria+'&aleatorio='+aleatorio);
                ajax9.onreadystatechange=function() {
                    if (ajax9.readyState==4) {
                        //mostrar resultados en esta capa
                        divVerObs.innerHTML = ajax9.responseText
                    }
                }
                ajax9.send(null)
            }

            function MostrarBotonesImpresion() {
                var aleatorio = Math.random();
                divImprimir = document.getElementById('BotonesImpresion');
                ajax4=objetoAjax();
                ajax4.open('GET', './operaciones/imprimir_orden.php?aleatorio='+aleatorio);
                ajax4.onreadystatechange=function() {
                    if (ajax4.readyState==4) {
                        //mostrar resultados en esta capa
                        divImprimir.innerHTML = ajax4.responseText
                    }
                }
                ajax4.send(null);
            }

            function ImprimirOrden()  {                
                var ficha = document.getElementById('ListarOrden');
                var ventimp = window.open(' ', 'popimpr');
                ventimp.document.write( ficha.innerHTML );
                ventimp.document.close();
                ventimp.print( );
                ventimp.close();

                CancelarImpresion();

            }

            function CancelarImpresion()  {
                //donde se mostrar los registros                
                divVerOrden = document.getElementById('ListarOrden');
                divVerOrden.innerHTML = '';
                divBt = document.getElementById('BotonesImpresion');
                divBt.innerHTML = '';
                divOA = document.getElementById('ObservacionAuditor');
                divOA.innerHTML = '';
                divIN = document.getElementById('Informe');
                divIN.innerHTML = '';

                showdiv("Lista");
                ajax5=objetoAjax();
                ajax5.onreadystatechange=function() {
                    if (ajax5.readyState==4) {
                        //mostrar resultados en esta capa
                        divVerOrden.innerHTML = ajax5.responseText
                    }
                }
                ajax5.send(null)
            }

            function CerrarListaOrdenes()  {
                //donde se mostrar los registros
                divVerOrden = document.getElementById('ListarOrden');
                divVerOrden.innerHTML = '';
                divBt = document.getElementById('BotonesImpresion');
                divBt.innerHTML = '';
                divList = document.getElementById('Lista');
                divList.innerHTML = '';

                ajax6=objetoAjax();
                ajax6.onreadystatechange=function() {
                    if (ajax6.readyState==4) {
                        //mostrar resultados en esta capa
                        divVerOrden.innerHTML = ajax6.responseText
                    }
                }
                ajax6.send(null)
            }

            function ConsultarOrdenesGeneradas(opcion) {
                var aleatorio = Math.random();
                var desde     = document.getElementById('desde').value;
                var hasta     = document.getElementById('hasta').value;
                var codos     = document.getElementById('listObsocial').value;
                var idprof    = '000000';
                if (document.getElementById('listEfectores').value != '000000') {
                    var idprof = document.getElementById('listEfectores').value;
                }
                if (ValidarFecha(desde)) {} else {return false;}
                if (ValidarFecha(hasta)) {} else {return false;}

                var divInforme    = document.getElementById('Informe');
                divImpresion  = document.getElementById('BotonesImpresion');
                divLista      = document.getElementById('Lista');
                divLista.innerHTML = '';
                divLista1     = document.getElementById('ListarOrden');
                divLista1.innerHTML = '';
                divLista3     = document.getElementById('ObservacionAuditor');
                divLista3.innerHTML = '';

                var ajaxinf=objetoAjax();
                ajaxinf.open('GET', './operaciones/informe_ordenes_auditoria_gen.php?codos='+codos+'&idprof='+idprof+'&desde='+desde+'&hasta='+hasta+'&aleatorio='+aleatorio+'&opcion='+opcion);
                divInforme.innerHTML= '<img src="anim.gif">  :: Procesando, espere un momento ...';
                ajaxinf.onreadystatechange=function() {
                    if (ajaxinf.readyState==4) {
                        //mostrar resultados en esta capa
                        divInforme.innerHTML = ajaxinf.responseText
                    }
                    ImprirInforme();
                }
                ajaxinf.send(null)
            }

        </script>

        <link rel="stylesheet" type="text/css" href="/css1.css" />

    </head>

    <body onLoad="javascript: if (Init()) {desde.focus()}; return true;">

        <form name="frmConsulta" method="post" onsubmit="return false">

            <div style="margin:auto;width:550px;text-align:center;">

                <?php include('menu_admin.php'); ?>
                <?php
                echo '<td width="54"><input name="usuario" type="hidden" id="usuario" value="' . $usuario . '" /></td>';
                ?>

                <table width="550px" border="0" align="center">
                    <tr>
                        <td width="120px" align="left">Consulta de Ordenes</td>
                        <td width="380px"><hr /></td>
                    </tr>
                </table>

                <table width="550" border="0" align="center">
                    <tr>
                        <td width="100" align = "right">Desde:</td>
                        <td width="300" align = "left">
                            <input name="desde" id="desde" type="text" style="font-size: 10px;" maxlength="10" width="2" size="10" onkeypress="javascript: if(ValidarFechaDesde(event, desde.value)) {hasta.focus()}; return true" />
                            (dd/mm/aaaa)</td>

                        <td colspan="2" align = "left"><div align="right">Hasta:</div></td>
                        <td width="104" align="right">
                            <div align="left">
                                <input name="hasta" id="hasta" type="text" style="font-size: 10px;" maxlength="10" width="2" size="10" onkeypress="javascript: if(ValidarFechaHasta(event, hasta.value)) {hasta.focus()}; return true" />
                            </div></td>
                    </tr>

                    <tr>
                        <td width="100" align = "right">
                            Ob.Social:</td>
                        <td colspan="3" align = "left">
                            <?
                            include('operaciones/lista_os_auditarordenes.php');
                            ?>
                        </td>

                        <td width="104"></td>
                    </tr>


                    <tr>
                        <td width="100" align = "right">
                            Efector:</td>
                        <td colspan="3" align = "left">
                            <?
                            include('operaciones/lista_efectores.php');
                            ?></td>

                        <td width="104"></td>
                    </tr>

                </table>

                <table width="550" border="0" align="center">
                    <tr>
                        <td width="100" align = "right">Tipo Cons.:</td>
                        <td width="80"><a href="javascript://" onclick="ConsultarOrdenesGeneradas(1)">Directas</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="javascript://" onclick="ConsultarOrdenesGeneradas(2)"></a></td>
                        <td width="80" align = "left"><a href="javascript://" onclick="ConsultarOrdenesGeneradas(2)">Diferidas</a></td>
                        <td width="80" align = "left"><a href="javascript://" onclick="ListarOrdenes()">Informe</a></td>
                        <td width="80" align = "left"><a href="javascript://" onclick="ListarOrdenes()"></a><a href="javascript://" onclick="ConsultarOrdenes()">Consulta</a></td>
                    </tr>
                </table>

                <table width="550px" border="0" align="center">
                    <tr>
                        <td><hr /></td>
                    </tr>
                </table>

                <div id="Botones" align="center">
                </div>

                <div id="Lista" align="left">
                </div>

                <div id="ObservacionAuditor" align="left">
                </div>

                <div id="ListarOrden" align="left">
                </div>

                <div id="BotonesImpresion" align="center">
                </div>

                <div id="Informe">
                </div>

            </div>
        </form>

    </body>

    <script>
        Init();
    </script>

</html>
