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
    <head><title>Autorización de Ordenes de Auditoria</title>

        <script src="../jscript/funciones.js"></script>
        <script src="../jscript/funcionajax.js"></script>
        <script src="../jscript/showhidediv.js"></script>
        <script>

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

            function ImprimirLista() {
                var ficha = document.getElementById('Verorden');
                var ventimp = window.open(' ', 'popimpr');
                ventimp.document.write(ficha.innerHTML);
                ventimp.document.close();
                ventimp.print();
                ventimp.close();
            }

            function MenuConsultaOrdenes() {
                //donde se mostrar los registros
                var aleatorio = Math.random();
                divContenido = document.getElementById('Botones');

                ajax = objetoAjax();
                //uso del medoto GET
                //indicamos el archivo que realizar� el proceso de paginar
                //junto con un valor que representa el nro de pagina
                ajax.open('GET', './operaciones/botones_consulta_ordenes_revisar.php?aleatorio=' + aleatorio);
                ajax.onreadystatechange = function () {
                    if (ajax.readyState == 4) {
                        //mostrar resultados en esta capa
                        divContenido.innerHTML = ajax.responseText
                    }                    
                }

                ajax.send(null)
            }

            function ConsultarOrdenes(xtipo_de_orden) {                  
                Pagina(1, '', '', 9);                
            }

            function CambiarOS() {
                ConsultarOrdenes(1);
            }
            
            function CambiarEfector() {
                ConsultarOrdenes(1);
            }

            function Pagina(nropagina, filtro, edbaja, xtipo_consulta) {
                //donde se mostrar los registros
                var aleatorio = Math.random();
                var idprof = document.getElementById('listEfectores').value;
                var desde = document.getElementById('desde').value;
                var hasta = document.getElementById('hasta').value;
                var codos = document.getElementById('listObsocial').value;
                var divLista = document.getElementById('Lista');

                hidediv('Verorden');
                hidediv('MostrarDeterminaciones');
                showdiv('Lista');

                var ajax1 = objetoAjax();                       
                ajax1.open('GET', './operaciones/paginador_revision_auditoria.php?pag=' + nropagina + '&filtro=' + filtro + '&edbaja=' + edbaja + '&idprof=' + idprof + '&desde=' + desde + '&hasta=' + hasta + '&tipo_consulta=' + xtipo_consulta + '&aleatorio=' + aleatorio + '&codos=' + codos);
                
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
                showdiv('Verorden');
                showdiv('MostrarDeterminaciones');
                divVerOrden = document.getElementById('MostrarDeterminaciones');

                var ajax3 = objetoAjax();
                divVerOrden.innerHTML = '<img src="anim.gif"> :: Consultando Orden ...';
                ajax3.open('GET', './operaciones/AutorizarOrdenAdmin.php?nrotrans=' + xnroauditoria + '&aleatorio=' + aleatorio);
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
                var aleatorio = Math.random();
                divList = document.getElementById('Verorden');
                hidediv('Lista');

                var ajax4 = objetoAjax();
                ajax4.open('GET', './operaciones/listar_hoja_orden.php?nrotrans=' + xnroauditoria + '&aleatorio=' + aleatorio);
                ajax4.onreadystatechange = function () {
                    if (ajax4.readyState == 4) {
                        //mostrar resultados en esta capa
                        divList.innerHTML = ajax4.responseText
                    }
                }
                ajax4.send(null)
            }

            function btnadd1(xnroauditoria) {
                
                var xcodigo = document.getElementById('txtcodigo1').value;
               
                var aleatorio = Math.random();
                divCod = document.getElementById('practica1');
                ajaxcod = objetoAjax();
                ajaxcod.open('GET', './operaciones/verificar_codigo_nbu.php?codigo=' + xcodigo + '&aleatorio=' + aleatorio);
                ajaxcod.onreadystatechange = function () {
                    if (ajaxcod.readyState == 4) {
                        contenido = ajaxcod.responseText;
                        divCod.innerHTML = contenido

                        var cont = contenido.substring(0, 10);
                        var cont1 = contenido.substring(0, 3);
                        if (cont.length > 0 & cont1 != '***') {  // En base al contenido del TAG se si el objeto existe o no                           

                        }

                        if (confirm('¿ Seguro para Agregar Práctica ' + xcodigo + ' - ' + contenido + ' en Orden: ' + xnroauditoria + ' ?')) {
                            addPractica(xnroauditoria, xcodigo);
                        }
                    }

                    document.getElementById('txtcodigo1').value = '';

                }

                ajaxcod.send(null);

            }

            function addPractica(xnroauditoria, xcodigo) {
                var aleatorio = Math.random();
                divError = document.getElementById('err');
                ajaxcod1 = objetoAjax();
                ajaxcod1.open('GET', './operaciones/auditoria_add_practica.php?codigo=' + xcodigo + '&nroauditoria=' + xnroauditoria + '&nroautorizacion=----&aleatorio=' + aleatorio);

                ajaxcod1.onreadystatechange = function () {
                    if (ajaxcod1.readyState == 4) {
                        contenido = ajaxcod1.responseText;
                        divError.innerHTML = contenido;
                    }

                    CancelarDeterminaciones();
                    MostrarOrden(xnroauditoria);
                }

                ajaxcod1.send(null);

            }

            function AutorizarDeterminaciones(xnroauditoria, xtt) {
                
                var expediente = '';
                if (xtt == 2) {
                    expediente = document.getElementById('expediente').value;
                    if (expediente.length < 1) {
                        alert('El Numero de Expediente es un Dato Obligatorio');
                        return false;
                    }
                }

                if (confirm('¿ Seguro para Finalizar los Cambios en Determinaciones ?')) {
                    var field = document.frmConsulta.list;
                    var autorizadas = '';
                    var rechazadas = '';

                    for (i = 0; i < field.length; i++) {
                        if (field[i].checked) {
                            if (field[i].value != undefined) {
                                autorizadas = autorizadas + field[i].value;
                            }
                        } else {
                            if (field[i].value != undefined) {
                                rechazadas = rechazadas + field[i].value;
                            }
                        }
                    }

                    var aleatorio = Math.random();
                    var theURL = './operaciones/actualizar_orden_usuario.php?nrotrans=' + xnroauditoria + '&autorizadas=' + autorizadas + '&rechazadas=' + rechazadas + '&capitadas=&observaciones=&aleatorio=' + aleatorio + '&usuario=__ussdif&expediente=' + expediente;

                    var divMensaje = document.getElementById('Mensaje');
                    var ajax = objetoAjax();
                    divMensaje.innerHTML = '<img src="anim.gif"> Registrando Orden ...';

                    ajax.open('GET', theURL);
                    ajax.onreadystatechange = function () {
                        if (ajax.readyState == 4) {
                            divMensaje.innerHTML = ajax.responseText
                        }
                        VerOrden(xnroauditoria);
                    }

                    ajax.send(null);
                }
            }

            function AutorizarDeterminacionesF4(xnroauditoria, xtt) {

                var expediente = '';
                if (xtt == 2) {

                    expediente = document.getElementById('formulario4').value;
                    if (expediente.length < 1) {
                        alert('El Numero de Formulario es un Dato Obligatorio');
                        return false;
                    }
                    if (expediente == 'MANUAL') {
                        alert('El Numero de Formulario es Incorrecto');
                        return false;
                    }
                }

                if (confirm('¿ Seguro para Finalizar los Cambios en Determinaciones ?')) {
                    var field = document.frmConsulta.list;
                    var autorizadas = '';
                    var rechazadas = '';

                    for (i = 0; i < field.length; i++) {
                        if (field[i].checked) {
                            if (field[i].value != undefined) {
                                autorizadas = autorizadas + field[i].value;
                            }
                        } else {
                            if (field[i].value != undefined) {
                                rechazadas = rechazadas + field[i].value;
                            }
                        }
                    }

                    var aleatorio = Math.random();
                    var theURL = './operaciones/actualizar_orden_usuario.php?nrotrans=' + xnroauditoria + '&autorizadas=' + autorizadas + '&rechazadas=' + rechazadas + '&capitadas=&observaciones=&aleatorio=' + aleatorio + '&usuario=__ussdif&expediente=' + expediente;

                    var divMensaje = document.getElementById('Mensaje');
                    var ajax = objetoAjax();
                    divMensaje.innerHTML = '<img src="anim.gif"> Registrando Orden ...';

                    ajax.open('GET', theURL);
                    ajax.onreadystatechange = function () {
                        if (ajax.readyState == 4) {
                            divMensaje.innerHTML = ajax.responseText
                        }
                        VerOrden(xnroauditoria);
                    }

                    ajax.send(null);
                }
            }

            function CancelarDeterminaciones() {
                ConsultarOrdenes(1);
            }

            function ValidarExpediente(e, expediente, xnroauditoria) {
                e = (window.event) ? event : e;
                intKey = (e.keyCode) ? e.keyCode : e.charCode;
                if (intKey == 13) {
                    if (expediente.length > 0) {
                        //donde se mostrar los registros
                        var aleatorio = Math.random();
                        showdiv('Verorden');
                        showdiv('MostrarDeterminaciones');
                        divVerOrden = document.getElementById('MostrarDeterminaciones');

                        var ajax3 = objetoAjax();
                        divVerOrden.innerHTML = '<img src="anim.gif"> :: Validando Orden ...';
                        ajax3.open('GET', './operaciones/AutorizarOrdenRegla3.php?nrotrans=' + xnroauditoria + '&expediente=' + expediente + '&aleatorio=' + aleatorio);
                        ajax3.onreadystatechange = function () {
                            if (ajax3.readyState == 4) {
                                //mostrar resultados en esta capa
                                divVerOrden.innerHTML = ajax3.responseText
                            }
                            VerOrden(xnroauditoria);
                        }

                        ajax3.send(null)

                    } else
                        alert('Debe Introducir el Numero de Expediente ...!');
                }
            }

            function MostrarResultado(opt, res) {
                if (opt) {
                    alert(res);
                }
            }


        </script>

        <link rel="stylesheet" type="text/css" href="/css1.css">

    </head>

    <body>

        <form name="frmConsulta" method="post" onsubmit="return false">

            <div style="margin:auto;width:550px;text-align:center;">

                <?
                include('menu_admin.php');
                echo '<td width="54"><input name="usuario" type="hidden" id="usuario" value="' . $usuario . '" /></td>';
                echo '<input name="resws" type="hidden" id="resws" />';
                ?>

            </div>

            <table width="550px" border="0" align="center">
                <tr>
                    <td width="130px" align="left">Autorización de Ordenes</td>
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

                    <td width="100" align="right">Hasta:
                    </td>
                    <td width="100" align="right">
                        <input name="hasta" id="hasta" type="text" style="font-size: 10px;" maxlength="10" width="2" size="10" onkeypress="javascript: if (ValidarFechaHasta(event, hasta.value)) {
                                    hasta.focus()
                                }
                                ;
                                return true" />
                    </td>

                </tr>
                
            </table>
            
            <table width="550px" border="0" align="left">

                <tr>
                    <td width="72px" align = "right">
                        Ob.Social:
                    </td>
                    <td width="300px" align = "left">
                        <?
                        include('operaciones/lista_os_auditarordenes_sin_excluir.php');
                        ?>
                    </td>              

                </tr>

                <tr>
                    <td width="67px" align="right">Efector:</td>
                    <td align="left">
                        <?php include('operaciones/lista_efectores_ingordenes.php') ?>
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

            <div id="MostrarDeterminaciones">
            </div>

            <div id="Mensaje">
            </div>

            <div id="Verorden" align="left">
            </div>

            <div id="Lista">
            </div>

            <div id="resultadows">
            </div>

            </div>
        </form>

        <script>
            document.getElementById('desde').focus();
        </script>

    </body>
</html>