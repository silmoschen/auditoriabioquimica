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
    <head><title>Anulación de Ordenes de Auditoria</title>

        <script src="../jscript/funciones.js"></script>
        <script src="../jscript/funcionajax.js"></script>
        <script src="../jscript/showhidediv.js"></script>
        <script>

            function Init() {
                hidediv("documento");
                document.frmConsulta.desde.focus();
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
                        MenuConsultaOrdenes();
                        return true;
                    }
                }
            }

            function ValidarDocumento(e, xnrodoc) {
                e = (window.event) ? event : e;
                intKey = (e.keyCode) ? e.keyCode : e.charCode;
                if (intKey == 13) {
                    if (xnrodoc.length == 0)
                        alert('Numero de Documento Incorrecto');
                    else {
                        ConsultarOrdenes(3);
                    }
                }
            }

            function MenuConsultaOrdenes() {
                //donde se mostrar los registros
                var aleatorio = Math.random();
                divContenido = document.getElementById('Botones');

                ajax = objetoAjax();
                //uso del medoto GET
                //indicamos el archivo que realizar� el proceso de paginar
                //junto con un valor que representa el nro de pagina
                ajax.open('GET', './operaciones/botones_consulta_ordenes_anular.php?aleatorio=' + aleatorio);
                ajax.onreadystatechange = function () {
                    if (ajax.readyState == 4) {
                        //mostrar resultados en esta capa
                        divContenido.innerHTML = ajax.responseText
                    }
                    ConsultarOrdenes(3);
                }

                ajax.send(null)
            }

            function ConsultarOrdenes(xtipo_de_orden) {
                hidediv("documento");
                Pagina(1, '', '', xtipo_de_orden);
            }

            function CambiarOS() {
                ConsultarOrdenes(3);
            }

            function ConsultarDocumento() {
                showdiv("documento");
                document.getElementById("nrodoc").focus();
            }

            function Pagina(nropagina, filtro, edbaja, xtipo_consulta) {
                //donde se mostrar los registros
                var aleatorio = Math.random();
                var idprof = document.getElementById('usuario').value;
                var desde = document.getElementById('desde').value;
                var hasta = document.getElementById('hasta').value;
                var codos = document.getElementById('listObsocial').value;
                var idprof = document.getElementById('listEfectores').value;
                var nrodoc = document.getElementById('nrodoc').value;
                divLista = document.getElementById('Lista');
              
                // todos
                var efectorexcluir = '';
                var efectorincluir = '';                               

                if (document.getElementById('_auditor1') != null) {
                    // Filtros de acuerdo al auditor
                    if (document.getElementById('_auditor1').value != ' ' && document.getElementById('_efector').value != ' ') {
                        if (document.getElementById('_auditor1').value == document.getElementById('_efector').value)
                            efectorexcluir = document.getElementById('_auditor1').value;

                        if (document.getElementById('_auditor2').value == document.getElementById('_efector').value)
                            efectorincluir = document.getElementById('_auditor1').value;

                        if (document.getElementById('_efector').value == 'administ') {
                            efectorexcluir = '';
                            efectorincluir = '';
                        }
                    }
                }               
   

                ajax1 = objetoAjax();
                //uso del medoto GET
                //indicamos el archivo que realizar� el proceso de paginar
                //junto con un valor que representa el nro de pagina
                //alert('./operaciones/paginador_anulacion_auditor.php?pag=' + nropagina + '&filtro=' + filtro + '&edbaja=' + edbaja + '&idprof=' + idprof + '&desde=' + desde + '&hasta=' + hasta + '&tipo_consulta=' + xtipo_consulta + '&aleatorio=' + aleatorio + '&codos=' + codos + '&nrodoc=' + nrodoc + '&efector_excluir=' + efectorexcluir + '&efector_incluir=' + efectorincluir);
                ajax1.open('GET', './operaciones/paginador_anulacion_auditor.php?pag=' + nropagina + '&filtro=' + filtro + '&edbaja=' + edbaja + '&idprof=' + idprof + '&desde=' + desde + '&hasta=' + hasta + '&tipo_consulta=' + xtipo_consulta + '&aleatorio=' + aleatorio + '&codos=' + codos + '&nrodoc=' + nrodoc + '&efector_excluir=' + efectorexcluir + '&efector_incluir=' + efectorincluir);
                divLista.innerHTML = '<img src="anim.gif">  :: Procesando Consulta ...';
                ajax1.onreadystatechange = function () {
                    if (ajax1.readyState == 4) {
                        //mostrar resultados en esta capa
                        divLista.innerHTML = ajax1.responseText
                    }
                }
                ajax1.send(null)
            }

            function MostrarOrden(xnroauditoria) {
                //donde se mostrar los registros
                var aleatorio = Math.random();
                divVerOrden = document.getElementById('Mensaje');

                ajax3 = objetoAjax();
                //uso del medoto GET
                //indicamos el archivo que realizar� el proceso de paginar
                //junto con un valor que representa el nro de pagina
                divVerOrden.innerHTML = '<img src="anim.gif">';
                ajax3.open('GET', './operaciones/AnulaOrden.php?nrotrans=' + xnroauditoria + '&aleatorio=' + aleatorio);
                ajax3.onreadystatechange = function () {
                    if (ajax3.readyState == 4) {
                        //mostrar resultados en esta capa
                        divVerOrden.innerHTML = ajax3.responseText
                    }
                    VerOrden(xnroauditoria);
                }

                ajax3.send(null)
            }

            function VerOrden(xnroauditoria) {
                //donde se mostrar los registros
                var aleatorio = Math.random();
                divList = document.getElementById('Verorden');
                hidediv('Lista');

                ajax4 = objetoAjax();
                //uso del medoto GET
                //indicamos el archivo que realizar� el proceso de paginar
                //junto con un valor que representa el nro de pagina
                ajax4.open('GET', './operaciones/listar_hoja_orden.php?nrotrans=' + xnroauditoria + '&aleatorio=' + aleatorio);
                ajax4.onreadystatechange = function () {
                    if (ajax4.readyState == 4) {
                        //mostrar resultados en esta capa
                        divList.innerHTML = ajax4.responseText
                    }
                }

                ajax4.send(null)
            }

            function ProcederAnulacion(xnroauditoria) {
                //donde se mostrar los registros
                var aleatorio = Math.random();
                divVerOrden = document.getElementById('Mensaje');
                divList = document.getElementById('Verorden');
                divList.innerHTML = '';
                showdiv('Lista');

                ajax3 = objetoAjax();
                //uso del medoto GET
                //indicamos el archivo que realizar� el proceso de paginar
                //junto con un valor que representa el nro de pagina
                divVerOrden.innerHTML = '<img src="anim.gif"> :: Anulando Orden ...';
                ajax3.open('GET', './operaciones/ProcederAnulacionOrden.php?nrotrans=' + xnroauditoria + '&aleatorio=' + aleatorio);
                ajax3.onreadystatechange = function () {
                    if (ajax3.readyState == 4) {
                        divVerOrden.innerHTML = '';
                        divVerOrden.innerHTML = ajax3.responseText;
                    }
                }

                ajax3.send(null)
            }

            function CancelarBaja() {
                //donde se mostrar los registros
                var aleatorio = Math.random();
                divVerOrden = document.getElementById('Mensaje');
                divVerOrden.innerHTML = '';
                divList = document.getElementById('Verorden');
                divList.innerHTML = '';
                showdiv('Lista');

                ajax6 = objetoAjax();
                ajax6.onreadystatechange = function () {
                    if (ajax6.readyState == 4) {
                        //mostrar resultados en esta capa
                        divVerOrden.innerHTML = ajax6.responseText
                    }
                }
                ajax6.send(null)
            }


        </script>

        <link rel="stylesheet" type="text/css" href="/css1.css">

    </head>

    <body onLoad="javascript: if (Init()) {
                desde.focus()
            }
            ;
            return true;">

        <form name="frmConsulta" method="post" onsubmit="return false">

            <div style="margin:auto;width:550px;text-align:center;">

                <?php
                include('menu_admin.php');
                echo '<td width="54"><input name="usuario" type="hidden" id="usuario" value="' . $usuario . '" /></td>';
                ?>

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
                            <input name="desde" id="desde" type="text" style="font-size: 10px;" maxlength="10" width="2" size="10" onkeypress="javascript: if (ValidarFechaDesde(event, desde.value)) {
                                        hasta.focus()
                                    }
                                    ;
                                    return true" />
                            (dd/mm/aaaa)
                        </td>

                        <td width="70" align="right">Hasta:
                        </td>
                        <td width="100" align="left">
                            <input name="hasta" id="hasta" type="text" style="font-size: 10px;" maxlength="10" width="2" size="10" onkeypress="javascript: if (ValidarFechaHasta(event, hasta.value)) {
                                        hasta.focus()
                                    }
                                    ;
                                    return true" />
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

                    <tr>
                        <td width="100px" align = "right">
                            Efector:
                        </td>
                        <td width="200px" align = "left">
                            <?
                            include('operaciones/lista_efectores.php');
                            ?>
                        </td>

                        <td colspan="2">
                            <?
                            echo '<a href="javascript://" onclick="ConsultarDocumento()">Consultar Documento</a></td>';
                            ?>
                        </td>                       

                    </tr>

                </table>

                <div id="documento">
                    <hr/>
                    <table width="550px" border="0" align="center">                  
                        <tr>
                            <td>
                                Nro. Documento: <input name="nrodoc" type="text" id="nrodoc" onkeypress="javascript: if (ValidarDocumento(event, nrodoc.value)) {
                                            nrodoc.focus()
                                        }
                                        ;
                                        return true" /> (ENTER para Procesar Busqueda)
                            </td>
                        </tr>
                    </table>
                </div>

                <div id="Botones" align="center">
                </div>

                <div id="Mensaje">
                </div>

                <div id="Verorden" align="left">
                </div>

                <div id="Lista">
                </div>

                <div id="auditores">
                    <?
                    if ($auditor1 != '') {
                        echo 'Auditor 1 ' . $nauditor1 . ' / ' . 'Auditor 2 ' . $nauditor2;
                        echo '<input type="hidden" id="_auditor1" value="' . $auditor1 . '">';
                        echo '<input type="hidden" id="_auditor2" value="' . $auditor2 . '">';
                        echo '<input type="hidden" id="_efector" value="' . $efector . '">';
                    }
                    ?>
                </div>

            </div>
        </form>

    </body>

    <script>
        Init();
    </script>
</html>