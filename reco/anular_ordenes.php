<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
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
    <head><title>Anulación de Ordenes de Auditoria</title>

        <script src="../jscript/funciones.js"></script>
        <script src="../jscript/funcionajax.js"></script>
        <script src="../jscript/showhidediv.js"></script>
        <script>

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
                ajax.open('GET', './operaciones/botones_consulta_ordenes_anular.php?aleatorio='+aleatorio);
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divContenido.innerHTML = ajax.responseText
                    }
                    ConsultarOrdenes(3);
                }

                ajax.send(null)
            }

            function ConsultarOrdenes(xtipo_de_orden){
                Pagina(1, '', '', xtipo_de_orden);
            }

            function CambiarOS() {
                ConsultarOrdenes(3);
            }

            function Pagina(nropagina, filtro, edbaja, xtipo_consulta){
                //donde se mostrar los registros
                var aleatorio = Math.random();
                var idprof    = document.getElementById('usuario').value;
                var desde     = document.getElementById('desde').value;
                var hasta     = document.getElementById('hasta').value;
                var codos     = document.getElementById('listObsocial').value;
                var divLista      = document.getElementById('Lista');
    

                var ajax1 = objetoAjax();
                ajax1.open('GET', './operaciones/paginador_anulacion_auditoria.php?pag='+nropagina+'&filtro='+filtro+'&edbaja='+edbaja+'&idprof='+idprof+'&desde='+desde+'&hasta='+hasta+'&tipo_consulta='+xtipo_consulta+'&aleatorio='+aleatorio+'&codos='+codos);
                divLista.innerHTML= '<img src="anim.gif">  :: Procesando Consulta ...';
                ajax1.onreadystatechange=function() {
                    if (ajax1.readyState==4) {
                        //mostrar resultados en esta capa
                        divLista.innerHTML = ajax1.responseText
                    }
                }
                ajax1.send(null)
            }

            function MostrarOrden(xnroauditoria){
                //donde se mostrar los registros
                var aleatorio = Math.random();
                divVerOrden = document.getElementById('Mensaje');

                var ajax3 = objetoAjax();
                divVerOrden.innerHTML= '<img src="anim.gif">';
                ajax3.open('GET', './operaciones/AnulaOrden.php?nrotrans='+xnroauditoria+'&aleatorio='+aleatorio);
                ajax3.onreadystatechange=function() {
                    if (ajax3.readyState==4) {
                        //mostrar resultados en esta capa
                        divVerOrden.innerHTML = ajax3.responseText
                    }
                    VerOrden(xnroauditoria);
                }

                ajax3.send(null)
            }

            function VerOrden(xnroauditoria){
                var aleatorio = Math.random();
                divList = document.getElementById('Verorden');
                hidediv('Lista');

                var ajax4 = objetoAjax();
                ajax4.open('GET', './operaciones/listar_hoja_orden.php?nrotrans='+xnroauditoria+'&aleatorio='+aleatorio);
                ajax4.onreadystatechange=function() {
                    if (ajax4.readyState==4) {
                        //mostrar resultados en esta capa
                        divList.innerHTML = ajax4.responseText
                    }
                }
                ajax4.send(null)
            }

            function ProcederAnulacion(xnroauditoria){
                var aleatorio = Math.random();
                var divVerOrden = document.getElementById('Mensaje');
                var divList = document.getElementById('Verorden');
                divList.innerHTML = '';
                showdiv('Lista');
        
                var ajax3 = objetoAjax();
                divVerOrden.innerHTML= '<img src="anim.gif"> :: Anulando Orden ...';
                ajax3.open('GET', './operaciones/ProcederAnulacionOrden.php?nrotrans='+xnroauditoria+'&aleatorio='+aleatorio);
                ajax3.onreadystatechange=function() {
                    if (ajax3.readyState==4) {
                        divVerOrden.innerHTML = '';
                        divVerOrden.innerHTML = ajax3.responseText;
                    }
                }
                ajax3.send(null)
            }

            function CancelarBaja()  {
                var aleatorio = Math.random();
                var divVerOrden = document.getElementById('Mensaje');
                divVerOrden.innerHTML = '';
                var divList = document.getElementById('Verorden');
                divList.innerHTML = '';
                showdiv('Lista');

                var ajax6=objetoAjax();
                ajax6.onreadystatechange=function() {
                    if (ajax6.readyState==4) {
                        //mostrar resultados en esta capa
                        divVerOrden.innerHTML = ajax6.responseText
                    }
                }
                ajax6.send(null)
            }

        </script>

        <link rel="stylesheet" type="text/css" href="/css1.css">

    </head>

    <body>

        <form name="frmConsulta" method="post" onsubmit="return false">

            <div style="margin:auto;width:550px;text-align:center;">

                <?
                include('menu_usuario.php');
                echo '<td width="54"><input name="usuario" type="hidden" id="usuario" value="' . $usuario . '" /></td>';
                ?>

            </div>

            <table width="550px" border="0" align="center">
                <tr>
                    <td width="130px" align="left">Anulación de Ordenes</td>
                    <td width="370px"><hr></td>
                </tr>
            </table>

            <table width="550px" border="0" align="center">
                <tr>
                    <td width="100px" align = "right">Desde:</td>
                    <td width="200px" align = "left">
                        <input name="desde" id="desde" type="text" style="font-size: 10px;" maxlength="10" width="2" size="10" onkeypress="javascript: if(ValidarFechaDesde(event, desde.value)) {hasta.focus()}; return true" />
                        (dd/mm/aaaa)
                    </td>

                    <td width="100" align="right">Hasta:
                    </td>
                    <td width="100" align="right">
                        <input name="hasta" id="hasta" type="text" style="font-size: 10px;" maxlength="10" width="2" size="10" onkeypress="javascript: if(ValidarFechaHasta(event, hasta.value)) {hasta.focus()}; return true" />
                    </td>

                </tr>

                <tr>
                    <td width="100px" align = "right">
                        Ob.Social:
                    </td>
                    <td width="200px" align = "left">
                        <?
                        include('operaciones/lista_os_auditarordenes.php');
                        ?>
                    </td>

                    <td width="100px">
                    </td>
                    <td  width="70px">
                    </td>

                </tr>

            </table>

            <table width="550px" border="0" align="center">
                <tr>
                    <td><hr></td>
                </tr>
            </table>

            <div id="Botones" align="center">
            </div>

            <div id="Mensaje">
            </div>

            <div id="Verorden" align="left">
            </div>

            <div id="Lista">
            </div>

        </div>
    </form>

    <script>
        document.getElementById('desde').focus();
    </script>

</body>
</html>