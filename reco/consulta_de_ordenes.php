<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

session_start();

$usuario = $_SESSION['susuario'];
$pass = $_SESSION['spass'];
$nombre = $_SESSION['snombre'];
$trans = $_REQUEST['trans'];
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cUtiles.php");
$u = new cUtiles;
?>

<html xmlns="http://www.w3.org/1999/xhtml">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <head><title>Consulta de Ordenes de Auditoría</title>

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
                        MenuConsultaOrdenes();
                        return true;
                    }
                }
            }

            function MenuConsultaOrdenes(){
                //donde se mostrar los registros
                var aleatorio = Math.random();
                divContenido = document.getElementById('Botones');

                ajax=objetoAjax();
                //uso del medoto GET
                //indicamos el archivo que realizar� el proceso de paginar
                //junto con un valor que representa el nro de pagina
                ajax.open('GET', './operaciones/botones_consulta_ordenes.php?aleatorio='+aleatorio);
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divContenido.innerHTML = ajax.responseText
                    }
                }

                ajax.send(null)
            }

            function ConsultarOrdenes(xtipo_de_orden){
                if (xtipo_de_orden < 4) {
                    Pagina(1, '', '', xtipo_de_orden, '');
                }
                if (xtipo_de_orden == 4) {
                    ListarOrdenes();
                }
                
                ConsultarPendientes();
            }

            function CambiarOS() {
                ConsultarOrdenes(3);
            }
            
             function ConsultarPendientes(){
                var aleatorio = Math.random();
                divPendientes = document.getElementById('Pendientes');
                
                var desde     = document.getElementById('desde').value;
                var hasta     = document.getElementById('hasta').value;
                var idprof    = document.getElementById('usuario').value;
                var codos     = document.getElementById('listObsocial').value;

                ajax1=objetoAjax();
                ajax1.open('GET', './operaciones/consultar_ordenes_pendientes.php?idprof='+idprof+'&desde='+desde+'&hasta='+hasta+'&aleatorio='+aleatorio+'&codos='+codos);
                ajax1.onreadystatechange=function() {
                    if (ajax1.readyState==4) {
                        //mostrar resultados en esta capa
                        divPendientes.innerHTML = ajax1.responseText
                    }
                }
                ajax1.send(null)
            }

            function ListarOrdenes() {
                //donde se mostrar los registros
                var aleatorio = Math.random();
                var desde     = document.getElementById('desde').value;
                var hasta     = document.getElementById('hasta').value;
                var idprof    = document.getElementById('usuario').value;
                var codos     = document.getElementById('listObsocial').value;

                if (ValidarFecha(desde)) {} else {return false;}
                if (ValidarFecha(hasta)) {} else {return false;}

                var divInforme = document.getElementById('Informe');
                divImpresion   = document.getElementById('BotonesImpresion');
                divLista       = document.getElementById('Lista');
                divLista.innerHTML = '';
                divLista1      = document.getElementById('ListarOrden');
                divLista1.innerHTML = '';
                divLista3      = document.getElementById('ObservacionAuditor');
                divLista3.innerHTML = '';

                var ajaxinf=objetoAjax();
                //uso del medoto GET
                //indicamos el archivo que realizar� el proceso de paginar
                //junto con un valor que representa el nro de pagina
                ajaxinf.open('GET', './operaciones/informe_ordenes_auditoria.php?idprof='+idprof+'&desde='+desde+'&hasta='+hasta+'&aleatorio='+aleatorio+'&codos='+codos);
                divInforme.innerHTML= '<img src="anim.gif"> :: Procesando, espere un momento ...';
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
                divBotones.innerHTML= '<img src="anim.gif"> :: Procesando, espere un momento ...';
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

            function Pagina(nropagina, filtro, edbaja, xtipo_consulta, nrodoc){                
                //donde se mostrar los registros
                showdiv("Lista");
                divBuscar = document.getElementById('Busqueda');
                divBuscar.innerHTML = '';
                divImprimir = document.getElementById('BotonesImpresion');
                divImprimir.innerHTML = '';
                var aleatorio = Math.random();
                var idprof    = document.getElementById('usuario').value;
                var desde     = document.getElementById('desde').value;
                var hasta     = document.getElementById('hasta').value;
                var codos     = document.getElementById('listObsocial').value;
                divLista      = document.getElementById('Lista');
                divVerOrden   = document.getElementById('ListarOrden');
                divVerOrden.innerHTML = '';

                var ajax=objetoAjax();
                //uso del medoto GET
                //indicamos el archivo que realizar� el proceso de paginar
                //junto con un valor que representa el nro de pagina
                divLista.innerHTML= '<img src="anim.gif">  :: Procesando, espere un momento ...';
                ajax.open('GET', './operaciones/paginador_consulta_auditoria.php?pag='+nropagina+'&filtro='+filtro+'&edbaja='+edbaja+'&idprof='+idprof+'&desde='+desde+'&hasta='+hasta+'&tipo_consulta='+xtipo_consulta+'&aleatorio='+aleatorio+'&codos='+codos+'&nrodoc='+nrodoc+'&profesional="S"');
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
                    document.frmConsulta.btnImprimir.focus();
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
                showdiv("Lista");
                divVerOrden = document.getElementById('ListarOrden');
                divVerOrden.innerHTML = '';
                divBt = document.getElementById('BotonesImpresion');
                divBt.innerHTML = '';
                divVerObs = document.getElementById('ObservacionAuditor');
                divVerObs.innerHTML = '';
                divIN = document.getElementById('Informe');
                divIN.innerHTML = '';

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

            function checkAll() {
                var field = document.frmConsulta.list;
                document.frmConsulta.btnImprime.disabled=false;
                for (i = 0; i < field.length; i++)
                    field[i].checked = true ;
            }

            function uncheckAll() {
                var field = document.frmConsulta.list;
                document.frmConsulta.btnImprime.disabled=true;
                for (i = 0; i < field.length; i++)
                    field[i].checked = false ;
            }

            function ImprimirSeleccion() {
                var field = document.frmConsulta.list;
                var lista = '';

                for (i = 0; i < field.length; i++) {
                    if (field[i].checked) {
                        if (field[i].value != undefined) {
                            var c = i+1;
                            lista = lista + '&orden' + c.toString() + '=' + field[i].value;
                        }
                    }
                }

                var aleatorio = Math.random();
                var theURL = './operaciones/listar_ordenes.php?aleatorio='+aleatorio+lista;

                divVerOrden = document.getElementById('ListarOrden');
                divBotones = document.getElementById('BotonesImpresion');
                hidediv("Lista");
                divBotones.innerHTML = '<img src="anim.gif"> :: Procesando, espere un momento ...';

                ajax3=objetoAjax();
                //uso del medoto GET
                //indicamos el archivo que realizar� el proceso de paginar
                //junto con un valor que representa el nro de pagina
                ajax3.open('GET', theURL);
                ajax3.onreadystatechange=function() {
                    if (ajax3.readyState==4) {
                        //mostrar resultados en esta capa
                        divVerOrden.innerHTML = ajax3.responseText
                    }
                    MostrarBotonesImpresion();
                    document.frmConsulta.btnImprimir.focus();
                }

                ajax3.send(null)
            }

            function chequear() {
                var field = document.frmConsulta.list;
                document.frmConsulta.btnImprime.disabled=true;
                for (i = 0; i < field.length; i++) {
                    if (field[i].checked) {
                        document.frmConsulta.btnImprime.disabled=false;
                    }
                }
            }

            function  BuscarNroDoc(tb) {
                divBuscar = document.getElementById('Busqueda');
                ajax=objetoAjax();
                ajax.open('GET', './operaciones/buscar_documento_afiliado.php');
                divBuscar.innerHTML= '<img src="anim.gif"> :: Procesando ...';
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divBuscar.innerHTML = ajax.responseText;
                    }
                    document.getElementById('buscarvalor').focus();
                }

                ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
                //enviando los valores
                ajax.send(null);
            }

            function ProcederBuscar(id){
                if (id.length > 0) {
                    var valor = document.getElementById('buscarvalor').value;
                    Pagina(1, '', 'T', '', valor);
                    divBuscar = document.getElementById('Busqueda');
                    divBuscar.innerHTML = '';
                } else {
                    alert('El Número de Documento Ingresado es Incorrecto ...!');
                    divBuscar = document.getElementById('Busqueda');
                    divBuscar.innerHTML = '';
                }
            }

            function BuscarValor(e, xbuscar) {
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    if (xbuscar.length >= 0) {
                        var codos = document.getElementById('listObsocial').value;
                        Pagina(codos, 1, xbuscar, 'T', valor);
                        divBuscar = document.getElementById('buscar');
                        divBuscar.innerHTML = '';
                    }
                }
            }

            function CancelarBusqueda(){
                var ajax = objetoAjax();
                divBuscar = document.getElementById('Busqueda');
                divBuscar.innerHTML = '';
                ajax.send(null);
            }

        </script>

        <link rel="stylesheet" type="text/css" href="/css1.css">

    </head>

    <body>

        <form name="frmConsulta" method="post" onsubmit="return false">

            <div style="margin:auto;width:550px;text-align:center;">

                <?php
                echo '<td width="54"><input name="usuario" type="hidden" id="usuario" value="' . $usuario . '" /></td>';
                ?>

                <?php include('menu_usuario.php'); ?>

                <table width="550px" border="0" align="center">
                    <tr>
                        <td width="120px" align="left">Consulta de Ordenes</td>
                        <td width="380px"><hr></td>
                    </tr>
                </table>

                <div align="left"></div>
                <table width="550px" border="0" align="center">
                    <tr>
                        <td width="100px" align = "right">Desde:</td>
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
                            O. Social:
                        </td>
                        <td width="200px" align = "left">
                            <?
                            include('operaciones/lista_os_auditarordenes.php');
                            ?>
                        </td>

                        <td width="100px"></td>
                        <td  width="70px"></td>

                    </tr>

                </table>

                <table width="550px" border="0" align="center">
                    <tr>
                        <td><hr></td>
                    </tr>
                </table>

                <div id="Botones" align="center">
                </div>

                <div id="Busqueda" align="center">
                </div>

                <div id="BotonesImpresion" align="center">
                </div>

                <div id="ObservacionAuditor" align="center">
                </div>

                <div id="ListarOrden" align="left">
                </div>

                <div id="Lista" align="left">
                </div>

                <div id="Pendientes" align="left">
                </div>

                <div id="Informe" align="left">
                </div>

            </div>
        </form>

        <script>
            Init();
        </script>

    </body>
</html>