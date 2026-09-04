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
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Modelos de Tipos de Auditoria</title>

        <script src="../jscript/funcionajax.js"></script>
        <script src="../jscript/showhidediv.js"></script>
        <script>

            function Init() {
                document.frm_modelo_nbu.codigo.focus();
                return true;
            }

            function listaControles(){
                var aleatorio = Math.random();
                //donde se mostrará los registros
                divLista = document.getElementById('contenido');
                ajax=objetoAjax();
                //uso del medoto GET
                //indicamos el archivo que realizar� el proceso de paginar
                //junto con un valor que representa el nro de pagina
                ajax.open('GET', './operaciones/lista_tipocontrol.php?aleatorio'+aleatorio);
                divLista.innerHTML= '<img src="anim.gif"> :: Procesando ...';
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divLista.innerHTML = ajax.responseText
                    }
                }
                ajax.send(null)
            }

            function operaciones(){
                // div donde se mostrará el contenido
                divOperaciones = document.getElementById('operaciones');
                // Creamos el objeto Ajax
                ajax = objetoAjax();
                //uso del medoto GET
                //indicamos el archivo que generará el contenido
                ajax.open('GET', './operaciones/botones_modelo.php');
                // GIF animado ó comentario personalizado mientras se carga
                divOperaciones.innerHTML= '<img src="anim.gif">';
                // Interceptando los estados de Ajax
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {  // valor 4... completado
                        //mostrar resultados en esta capa
                        divOperaciones.innerHTML = ajax.responseText;
                    }
                }
                ajax.send(null)
            }

            function NuevoModelo(){
                var aleatorio = Math.random();
                var idcontrol = document.getElementById('iddef').value;
                if (idcontrol.length == 0) {
                    idcontrol=document.getElementById('listControl').value;
                }
                //donde se mostrará los registros
                divBotones = document.getElementById('borrar');
                divBotones.innerHTML = '';
                divLista = document.getElementById('listadef');
                divLista.innerHTML= '';
                divUICargas = document.getElementById('UICarga');
                hidediv("contenido");
                ajaxm=objetoAjax();
                //uso del medoto GET
                //indicamos el archivo que realizar� el proceso de paginar
                //junto con un valor que representa el nro de pagina
                ajaxm.open('GET', './operaciones/nueva_practica_modelo.php?idcontrol='+idcontrol+'&aleatorio='+aleatorio+'&modo=1');
                divUICargas.innerHTML= '<img src="anim.gif"> :: Nuevo ...';
                ajaxm.onreadystatechange=function() {
                    if (ajaxm.readyState==4) {
                        //mostrar resultados en esta capa
                        divUICargas.innerHTML = ajaxm.responseText;
                    }
                    document.getElementById('codigo').focus();
                }
                ajaxm.send(null)
            }

            function EditaModelo(id, codigo){
                var aleatorio = Math.random();
                var idcontrol = document.getElementById('iddef').value;
                var codos = document.getElementById('listObsocial').value;
                if (idcontrol.length == 0) {
                    idcontrol=document.getElementById('listControl').value;
                }
                //donde se mostrará los registros
                divBotones = document.getElementById('borrar');
                divBotones.innerHTML = '';
                divLista = document.getElementById('listadef');
                divLista.innerHTML= '';
                divUICargas = document.getElementById('UICarga');
                hidediv("contenido");
                ajaxm=objetoAjax();
                //uso del medoto GET
                //indicamos el archivo que realizar� el proceso de paginar
                //junto con un valor que representa el nro de pagina
                ajaxm.open('GET', './operaciones/nueva_practica_modelo.php?idcontrol='+id+'&aleatorio='+aleatorio+'&codigo='+codigo+'&codos='+codos+'&modo=2');
                divUICargas.innerHTML= '<img src="anim.gif"> :: Editando ...';
                ajaxm.onreadystatechange=function() {
                    if (ajaxm.readyState==4) {
                        //mostrar resultados en esta capa
                        divUICargas.innerHTML = ajaxm.responseText;
                    }
                    document.getElementById('frecuencia').focus();
                }
                ajaxm.send(null)
            }


            function ValidarCodigo(e, xcodigo){
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    VerificarCodigo(xcodigo);
                    return true;
                }
            }

            function VerificarCodigo(xcodigo) {
                var aleatorio = Math.random();
                divCod  = document.getElementById('Determinacion');
                ajaxcod = objetoAjax();
                ajaxcod.open('GET', './operaciones/verificar_codigo_nbu.php?codigo='+xcodigo+'&aleatorio='+aleatorio);
                ajaxcod.onreadystatechange=function() {
                    if (ajaxcod.readyState==4) {
                        //mostrar resultados en esta capa
                        var contenido = ajaxcod.responseText;
                        divCod.innerHTML = contenido;

                        if (contenido == '*** Código de Determinación Inexistente ***') {
                            document.frm_modelo_nbu.frecuencia.disabled=true;
                            document.frm_modelo_nbu.tipofrecuencia.disabled=false;
                            document.frm_modelo_nbu.codigo.focus();
                        } else {
                            document.frm_modelo_nbu.frecuencia.disabled=false;
                            document.frm_modelo_nbu.tipofrecuencia.disabled=false;
                            document.frm_modelo_nbu.frecuencia.focus();
                        }
                    }
                }

                ajaxcod.send(null);
            }

            function ValidarFrecuencia(e, xfrecuencia){
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    if(xfrecuencia.length == 0) {
                        alert('La Frecuencia es Incorrecta ...!');
                        return false;
                    }
                    return true;
                }

            }

            function ValidarTipoFrecuencia(e, xtipofrecuencia){
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    if(xtipofrecuencia.length == 0) {
                        alert('La Frecuencia es Incorrecta ...!');
                        return false;
                    } else {
                        if (xtipofrecuencia < 1 || xtipofrecuencia > 4) {
                            alert('Las Opciones son 1, 2, 3 ó 4 ...!');
                            return false;
                        }
                    }
                    return true;
                }
            }

            function RegistrarModelo(idcontrol, codigo, frecuencia, tipofrecuencia, modo){
                var codos = document.getElementById('listObsocial').value;
                if (codos.length == 0) {
                    alert('La Obra Social es Incorrecta ...!');
                    return false;
                }
                if (idcontrol.length <= 0) {
                    alert('El Id. de Control es Incorrecto ...!');
                    return false;
                }
                if (codigo.length <= 0) {
                    alert('El Código de Determinación es Incorrecto ...!');
                    return false;
                } else {
                    divCod  = document.getElementById('Determinacion');
                    if (divCod.innerHTML == '*** Código de Determinación Inexistente ***') {
                        alert('El Código de Determinación es Incorrecto ...!');
                        return false;
                    }
                }
                if(frecuencia.length == 0) {
                    alert('La Frecuencia es Incorrecta ...!');
                    return false;
                }
                if(tipofrecuencia.length == 0) {
                    alert('La Frecuencia es Incorrecta ...!');
                    return false;
                } else {
                    if (tipofrecuencia < 1 || tipofrecuencia > 4) {
                        alert('Las Opciones son 1, 2, 3 ó 4 ...!');
                        return false;
                    }
                }

                var aleatorio = Math.random();
                divMensaje = document.getElementById('mensaje');
                divMensaje.innerHTML= '<img src="anim.gif">  :: Registrando Determinación ...';

                ajax=objetoAjax();
                ajax.open("GET", "./operaciones/registro_modelo.php?codos="+codos+"&idcontrol="+idcontrol+"&codigo="+codigo+"&frecuencia="+frecuencia+"&tipofrecuencia="+tipofrecuencia+"&aleatorio="+aleatorio+'&modo='+modo);

                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        divMensaje.innerHTML = ajax.responseText;
                    }
                    if (modo == 2) {
                        OcultarModelo();
                    } else {
                        document.getElementById('codigo').value = '';
                        document.getElementById('frecuencia').value = '';
                        document.getElementById('tipofrecuencia').value = '';
                        document.getElementById('codigo').focus();
                    }
                    ConsultarModelo();                    
                }

                ajax.send(null);

                showdiv("contenido");
            }

            function OcultarModelo(){
                divUICargas = document.getElementById('UICarga');
                showdiv("contenido");
                divUICargas.innerHTML = '';
            }

            function CambiarOS(){
                ConsultarModelo();
            }

            function PaginaTC(idcontrol){
                var aleatorio = Math.random();
                divMensaje = document.getElementById('mensaje');
                divMensaje.innerHTML= '';

                var idcontrol = document.getElementById('listControl').value;
                var codos = document.getElementById('listObsocial').value;
                //donde se mostrar� los registros
                divLista = document.getElementById('listadef');
                divLista.innerHTML= '';
                divBotones = document.getElementById('borrar');
                divBotones.innerHTML = '';
                divContenido = document.getElementById('contenido');
                ajax=objetoAjax();
                //uso del medoto GET
                //indicamos el archivo que realizar� el proceso de paginar
                //junto con un valor que representa el nro de pagina
                ajax.open('GET', './operaciones/lista_tipocontrol_def.php?codos='+codos+'&idcontrol='+idcontrol+'&aleatorio='+aleatorio);
                divContenido.innerHTML= '<img src="anim.gif"> :: Procesando ...';
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divContenido.innerHTML = ajax.responseText
                    }
                }
                ajax.send(null)
            }

            function ConsultarModelo(){
                var id = document.getElementById('listControl').value;
                showdiv("contenido");
                PaginaTC(id);
            }

            function ConsultarModelo1(id){
                var aleatorio = Math.random();
                //donde se mostrará los registros
                document.getElementById('iddef').value = id;
                var codos = document.getElementById('listObsocial').value;
                divContenido = document.getElementById('contenido');
                divBotones = document.getElementById('borrar');
                divLista   = document.getElementById('listadef');
                divBotones.innerHTML = '';
                divLista.innerHTML = '';
                ajax=objetoAjax();
                //uso del medoto GET
                //indicamos el archivo que realizar� el proceso de paginar
                //junto con un valor que representa el nro de pagina
                ajax.open('GET', './operaciones/lista_tipocontrol_def.php?codos='+codos+'&idcontrol='+id+'&aleatorio='+aleatorio);
                divContenido.innerHTML= '<img src="anim.gif">';
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divContenido.innerHTML = '';
                        PaginaTC(id);
                    }
                }
                ajax.send(null)
            }

            function BajaModelo(id, codigo){
                //donde se mostrará los registros
                var codos = document.getElementById('listObsocial').value;
                var aleatorio = Math.random();
                divBorrar = document.getElementById('borrar');
                ajax=objetoAjax();

                //uso del medoto GET
                //indicamos el archivo que realizar� el proceso de paginar
                //junto con un valor que representa el nro de pagina
                ajax.open('GET', './operaciones/BorraModelo.php?codos='+codos+'&idcontrol='+id+'&codigo='+codigo+'&aleatorio='+aleatorio);
                divBorrar.innerHTML= '<img src="anim.gif">';
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divBorrar.innerHTML = ajax.responseText
                    }
                }
                ajax.send(null)
            }

            function CancelarBaja(){
                divBorrar = document.getElementById('borrar');
                ajax=objetoAjax();
                divBorrar.innerHTML = '';
            }

            function ProcederBaja(id, codigo){
                //donde se mostrará los registros
                var aleatorio = Math.random();
                var codos = document.getElementById('listObsocial').value;
                divBorrar = document.getElementById('borrar');
                ajax=objetoAjax();

                //uso del medoto GET
                //indicamos el archivo que realizar� el proceso de paginar
                //junto con un valor que representa el nro de pagina
                ajax.open('GET', './operaciones/baja_modelo.php?codos='+codos+'&idcontrol='+id+'&codigo='+codigo+'&aleatorio='+aleatorio);
                divBorrar.innerHTML= '<img src="anim.gif"> :: Borrando Determinación ...';
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divBorrar.innerHTML = '';
                        PaginaTC(id);
                    }
                }
                ajax.send(null)
            }

            function Salir()  {
                var aleatorio = Math.random();
                var host = location.protocol + '//' + location.hostname + '/usuarios.php?aleatorio=' + aleatorio;
                window.location.href = host;
            }

            function BuscarCodigoNBU(){
                var aleatorio = Math.random();
                var divBuscarDeterminacion = document.getElementById('BuscarDeterminacion');
                ajax7=objetoAjax();
                ajax7.open('GET', '/NBUSel.php?aleatorio='+aleatorio);
                ajax7.onreadystatechange=function() {
                    if (ajax7.readyState==4) {
                        //mostrar resultados en esta capa
                        divBuscarDeterminacion.innerHTML = ajax7.responseText;
                    }
                }
                ajax7.send(null);
            }

            function CodigoNBUSel(xcodigo) {
                document.frm_modelo_nbu.codigo.value = xcodigo;
                VerificarCodigo(xcodigo);
                document.frm_modelo_nbu.frecuencia.disabled=false;
                document.frm_modelo_nbu.frecuencia.focus();
                var divBuscarDeterminacion = document.getElementById('BuscarDeterminacion');
                ajax7=objetoAjax();
                divBuscarDeterminacion.innerHTML = '';
                ajax7.onreadystatechange=function() {
                    if (ajax7.readyState==4) {
                        //mostrar resultados en esta capa
                        divBuscarDeterminacion.innerHTML = ajax7.responseText
                    }
                }
                ajax7.send(null);
            }

            function Pagina(nropagina, filtro, edbaja){
                //donde se mostrar los registros
                var aleatorio = Math.random();
                divContenido = document.getElementById('contenido');

                ajax=objetoAjax();
                //uso del medoto GET
                //indicamos el archivo que realizar� el proceso de paginar
                //junto con un valor que representa el nro de pagina
                ajax.open('GET', './operaciones/paginador_nbu_sel.php?pag='+nropagina+'&filtro='+filtro+'&edbaja='+edbaja+'&aleatorio='+aleatorio);
                divContenido.innerHTML= '<img src="anim.gif"> :: Procesando ...';
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divContenido.innerHTML = ajax.responseText
                    }
                }
                ajax.send(null)
            }

            function BuscarContextualmenteNBU() {
                divBuscar = document.getElementById('buscar');
                ajax=objetoAjax();
                ajax.open('GET', './operaciones/buscar.php');
                divBuscar.innerHTML= '<img src="anim.gif">';
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divBuscar.innerHTML = ajax.responseText
                    }
                    document.frmbuscar.buscarvalor.focus();
                }

                ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
                //enviando los valores
                ajax.send(null);
            }

            function ProcederBuscar(valor){
                Pagina(1, '%'+valor, 'T');
                divBuscar = document.getElementById('buscar');
                divBuscar.innerHTML = '';
            }

            function BuscarValor(e, valor){
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    ProcederBuscar(valor);
                    return true;
                }
            }

            function QuitarFiltroNBU(){
                Pagina(1, '', 'T');
                divBuscar = document.getElementById('buscar');
                divBuscar.innerHTML = '';
            }

            function CerrarBusquedaNBU(){
                divBuscar = document.getElementById('BuscarDeterminacion');
                ajax=objetoAjax();
                divBuscar.innerHTML= '';
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divBuscar.innerHTML = ajax.responseText
                    }
                }

                ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
                //enviando los valores
                ajax.send(null);
            }

            function ListaControlesDefinidos(){
                PaginaMod(1, '', '');
            }

            function PaginaMod(nropagina, filtro, edbaja){
                //donde se mostrar los registros
                var aleatorio = Math.random();
                var codos = document.getElementById('listObsocial').value;
                divContenido = document.getElementById('contenido');
                divBotones = document.getElementById('borrar');
                divLista   = document.getElementById('listadef');

                divBotones.innerHTML = '';
                divLista.innerHTML = '';

                ajax=objetoAjax();
                //uso del medoto GET
                //indicamos el archivo que realizar� el proceso de paginar
                //junto con un valor que representa el nro de pagina
                //alert('./operaciones/listar_controles_definidos.php?pag='+nropagina+'&aleatorio='+aleatorio);
                ajax.open('GET', './operaciones/listar_controles_definidos.php?codos='+codos+'&pag='+nropagina+'&aleatorio='+aleatorio);
                divContenido.innerHTML= '<img src="anim.gif"> :: Procesando ...';
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divContenido.innerHTML = ajax.responseText
                    }
                }
                ajax.send(null)
            }

            function ListaDetallada(){
                var aleatorio = Math.random();
                var codos = document.getElementById('listObsocial').value;
                divContenido = document.getElementById('listadef');
                divCarga = document.getElementById('UICarga');
                divCarga.innerHTML = '';
                divCont = document.getElementById('contenido');
                divCont.innerHTML = '';

                ajax=objetoAjax();
                ajax.open('GET', './operaciones/listar_controles_detallados.php?codos='+codos+'&aleatorio='+aleatorio);
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divContenido.innerHTML = ajax.responseText
                    }
                    ImprimeListaDetallada();
                }
                ajax.send(null)
            }

            function ImprimeListaDetallada(){
                var aleatorio = Math.random();
                divBotones   = document.getElementById('borrar');

                ajax1=objetoAjax();
                ajax1.open('GET', './operaciones/imprimir_lista_tipocontrol.php?aleatorio='+aleatorio);
                divBotones.innerHTML= '<img src="anim.gif">   :: Procesando ...';
                ajax1.onreadystatechange=function() {
                    if (ajax1.readyState==4) {
                        //mostrar resultados en esta capa
                        divBotones.innerHTML = ajax1.responseText
                    }
                }
                ajax1.send(null)
            }

            function ImprimirLista()  {                
                var ficha = document.getElementById('listadef');
                var ventimp = window.open(' ', 'popimpr');
                ventimp.document.write( ficha.innerHTML );
                ventimp.document.close();
                ventimp.print();
                ventimp.close();
            }

            function CancelarImpresion(){
                divBotones = document.getElementById('borrar');
                divLista   = document.getElementById('listadef');
                divBotones.innerHTML = '';
                divLista.innerHTML = '';

                ajax2=objetoAjax();
                ajax2.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                    }
                }
                ajax2.send(null)
            }

            function CerrarListaOrdenes(){
                //donde se mostrar los registros                
                divContenido = document.getElementById('listadef');
                ajax=objetoAjax();
                divContenido.innerHTML= '';
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divContenido.innerHTML = ajax.responseText
                    }
                    CerrarListaDefinicion();
                }
                ajax.send(null)
            }

            function CerrarListaDefinicion(){
                //donde se mostrar los registros                
                divContenido = document.getElementById('contenido');
                ajax=objetoAjax();
                divContenido.innerHTML= '';
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divContenido.innerHTML = ajax.responseText
                    }
                }
                ajax.send(null)
            }

            function Inhabilitar(estado) {
                
                var est = '';
                var ley = 'Habilitar';
                if (estado) {
                    est = 'S';
                    ley = 'Inhabilitar';
                }

                if (confirm('¿ Seguro para ' + ley + ' Perfil de Diagnóstico ?' )) {
                    var aleatorio = Math.random();
                    divProcesa   = document.getElementById('UICarga');

                    var codos = document.getElementById('listObsocial').value;
                    var idcontrol = document.getElementById('listControl').value;

                    var ajax = objetoAjax();
                    ajax.open('GET', './operaciones/inhabilitar_modelo.php?codos='+codos+'&idcontrol='+idcontrol+'&estado='+est+'&aleatorio='+aleatorio);
                    divProcesa.innerHTML= '<img src="anim.gif">   :: Procesando ...';
                    ajax.onreadystatechange=function() {
                        if (ajax.readyState==4) {
                            //mostrar resultados en esta capa
                            divProcesa.innerHTML = ajax.responseText
                        }
                    }
                    ajax.send(null)
                    
                }

            }
            
            function AutorizacionDiferida(estado) {
                
                var est = '';
                var ley = 'Quitar Autorización Diferida';
                if (estado) {
                    est = 'S';
                    ley = 'Aplicar Autorización Diferida';
                }

                if (confirm('¿ Seguro para ' + ley + ' en este Perfil de Diagnóstico ?' )) {
                    var aleatorio = Math.random();
                    divProcesa   = document.getElementById('UICarga');

                    var codos = document.getElementById('listObsocial').value;
                    var idcontrol = document.getElementById('listControl').value;

                    var ajax = objetoAjax();
                    ajax.open('GET', './operaciones/autorizacion_diferida_modelo.php?codos='+codos+'&idcontrol='+idcontrol+'&estado='+est+'&aleatorio='+aleatorio);
                    divProcesa.innerHTML= '<img src="anim.gif">   :: Procesando ...';
                    ajax.onreadystatechange=function() {
                        if (ajax.readyState==4) {
                            //mostrar resultados en esta capa
                            divProcesa.innerHTML = ajax.responseText
                        }
                    }
                    ajax.send(null)
                    
                }

            }

        </script>
        <link rel="stylesheet" type="text/css" href="css1.css" />
    </head>

    <body onLoad="javascript: if (Init()) {codigo.focus()}; return true;">

        <div style="margin:auto;width:550px;text-align:center;">

            <? include('menu_admin.php'); ?>

            <FIELDSET>
                <LEGEND>Definición de Modelos y Frecuencias</LEGEND>

                <table border="0px" width="500px">
                    <tr>
                        <td width="5%" align = "right">Obra Social:</td>
                        <td width="50%"><?php include('operaciones/lista_os_auditarordenes.php') ?></td>
                        <td></td>
                        <td colspan="3" align = "right"></td>
                    </tr>

                    <tr>
                        <td width="5%" align = "right">Dx.:</td>
                        <td width="50%">
                            <?php include('operaciones/lista_tipocontrol.php') ?></td>
                        <td>
                            <?php
                            echo '<td width="18%"><a href="javascript://" onclick="NuevoModelo()">Agregar</a></td>';
                            echo '<td width="5%"><a href="javascript://" onclick="ConsultarModelo()">Consultar</a></td>';
                            ?>                        </td>
                    </tr>
                </table>
                <table>
                    <tr>

                    <input type="hidden" name="iddef" id="iddef" size="5" />
                    <?php include('operaciones/botones_tipocontrol_definidos.php') ?>

                    </tr>        
                </table>

            </FIELDSET>

            <div id="buscar">
            </div>

            <div id="BuscarDeterminacion">
            </div>

            <div id="UICarga" align="center">
            </div>

            <div id="mensaje">
            </div>

            <div id="borrar">
            </div>

            <div id="listadef">
            </div>

            <div id="contenido">
            </div>

        </div>

    </body>

</html>