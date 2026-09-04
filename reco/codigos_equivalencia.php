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
    <head><title>Equivalencia de Códigos NBU</title>

        <script src="../jscript/funcionajax.js"></script>
        <script src="../jscript/showhidediv.js"></script>
        <script>

            function Init() {
                document.getElementById("codigo1").focus();
                return true;
            }

            function ValidarCodigo1(e, xcodigo){
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    if (VerificarCodigo(xcodigo, 1)) {
                        ListarItems();
                        return true;
                    }
                }
            }           
            
            function ValidarCodigo2(e, xcodigo){
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    if (VerificarCodigo(xcodigo, 2)) {
                        return true;
                    }
                }
            }

            function VerificarCodigo(xcodigo, modo) {
                if (xcodigo.length != 6) {
                    alert('Codigo Incorrecto ...!');
                    return false;
                }

                var aleatorio = Math.random();
                if (modo == 1) {
                    divCod  = document.getElementById('descrip1');
                } else {
                    divCod  = document.getElementById('descrip2');
                }

                ajaxcod = objetoAjax();
                if (modo == 1) {
                    ajaxcod.open('GET', './operaciones/verificar_codigo_nbu.php?codigo='+xcodigo+'&aleatorio='+aleatorio);
                }
                if (modo == 2) {
                    ajaxcod.open('GET', './operaciones/verificar_codigo_nbu_equivalente.php?codigo='+xcodigo+'&aleatorio='+aleatorio);
                }
                ajaxcod.onreadystatechange=function() {
                    if (ajaxcod.readyState==4) {
                        //mostrar resultados en esta capa
                        var contenido = ajaxcod.responseText;
                        divCod.innerHTML = contenido;

                        var cont  = contenido.substring(0, 10);
                        var cont1 = contenido.substring(0, 3);
                        if (cont.length > 0 & cont1 != '***') {  // En base al contenido del TAG se si el objeto existe o no

                            if (modo == 2) { AgregarCodigo(); }
                            if (modo == 1) {
                                document.getElementById("codigo2").disabled=false;
                                document.getElementById("btnBuscar").disabled=false;
                                document.getElementById("codigo2").focus();
                            }
                            return true;
                        }
                    }
                }

                ajaxcod.send(null);

                return true;
            }

            function AgregarCodigo() {
                var codos  = document.getElementById('listObsocial').value;
                if (codos.length > 0) {
                    var aleatorio = Math.random();
                    var codigo1 = document.getElementById("codigo1").value;
                    var codigo2 = document.getElementById("codigo2").value;                   

                    if(codos.length == 6 && codigo1.length == 6 & codigo2.length == 6) {
                        divLista = document.getElementById('Lista');
                        ajax=objetoAjax();
                        ajax.open('GET', './operaciones/insertar_codigo_equivalente.php?codos='+codos+'&codigo1='+codigo1+'&codigo2='+codigo2+'&aleatorio='+aleatorio);
                        divLista.innerHTML= '<img src="anim.gif"> :: Registrando ...';
                        ajax.onreadystatechange=function() {
                            if (ajax.readyState==4) {
                                //mostrar resultados en esta capa
                                divLista.innerHTML = ajax.responseText
                            }
                            ListarItems();
                        }
                        ajax.send(null);
                    }
                } else {
                    alert('No Existen Obras Sociales Definidas para este Proceso ...!');
                }
            }

            function ListarItems()  {
                var codos  = document.getElementById('listObsocial').value;
                var codigo = document.getElementById('codigo1').value;
                if (codos.length > 0) {
                    //donde se mostrar los registros
                    var aleatorio = Math.random();

                    document.getElementById('codigo2').value = '';
                    document.getElementById('codigo2').disabled = true;
                    document.getElementById('btnBuscar').disabled = true;
                    document.getElementById('codigo1').focus();
                    var div1  = document.getElementById('descrip1');
                    var div2  = document.getElementById('descrip2');
                    div1.innerHTML = '';
                    div2.innerHTML = '';

                    divLista = document.getElementById('Lista');
                    ajax=objetoAjax();
                    ajax.open('GET', './operaciones/lista_codigos_equivalentes.php?codos='+codos+'&codigo='+codigo+'&aleatorio='+aleatorio);
                    ajax.onreadystatechange=function() {
                        if (ajax.readyState==4) {
                            //mostrar resultados en esta capa
                            divLista.innerHTML = ajax.responseText
                        }
                        document.getElementById("codigo2").value='';
                        document.getElementById("codigo2").focus();
                    }
                    ajax.send(null)
                }
            }

            function ListarTodos()  {
                var codos  = document.getElementById('listObsocial').value;
                var codigo = '';
                if (codos.length > 0) {
                    //donde se mostrar los registros
                    var aleatorio = Math.random();

                    document.getElementById('codigo1').value = '';
                    document.getElementById('codigo2').value = '';
                    document.getElementById('codigo2').disabled = true;
                    document.getElementById('btnBuscar').disabled = true;
                    document.getElementById('codigo1').focus();
                    var div1  = document.getElementById('descrip1');
                    var div2  = document.getElementById('descrip2');
                    div1.innerHTML = '';
                    div2.innerHTML = '';

                    divLista = document.getElementById('Lista');
                    ajax=objetoAjax();
                    ajax.open('GET', './operaciones/lista_codigos_equivalentes.php?codos='+codos+'&codigo='+codigo+'&aleatorio='+aleatorio);
                    ajax.onreadystatechange=function() {
                        if (ajax.readyState==4) {
                            //mostrar resultados en esta capa
                            divLista.innerHTML = ajax.responseText
                        }
                        document.getElementById("codigo2").value='';
                        document.getElementById("codigo2").focus();
                    }
                    ajax.send(null)
                }
            }

            function BajaCodigo(codos, codigo){
                //donde se mostrará los registros
                var aleatorio = Math.random();
                divBorrar = document.getElementById('Borra');
                ajax=objetoAjax();

                //uso del medoto GET
                //indicamos el archivo que realizar� el proceso de paginar
                //junto con un valor que representa el nro de pagina
                ajax.open('GET', './operaciones/BorraCodigoEquivalente.php?codos='+codos+'&codigo='+codigo+'&aleatorio='+aleatorio);
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
                divBorrar = document.getElementById('Borra');
                ajax=objetoAjax();
                divBorrar.innerHTML = '';
            }

            function ProcederBaja(codos, codigo1){
                //donde se mostrará los registros
                var aleatorio = Math.random();
                divBorra = document.getElementById('Borra');
                ajax1=objetoAjax();

                //uso del medoto GET
                //indicamos el archivo que realizar� el proceso de paginar
                //junto con un valor que representa el nro de pagina
                ajax1.open('GET', './operaciones/baja_codigo_equivalente.php?codos='+codos+'&codigo1='+codigo1+'&aleatorio='+aleatorio);
                divBorra.innerHTML= '<img src="anim.gif"> :: Eliminando ...';
                ajax1.onreadystatechange=function() {
                    if (ajax1.readyState==4) {
                        //mostrar resultados en esta capa
                        divBorra.innerHTML = '';
                    }
                    ListarItems();
                }
                ajax1.send(null)
            }

            function Buscar() {
                hidediv("Lista");
                divBuscar = document.getElementById('Buscar');
                ajax=objetoAjax();
                var aleatorio = Math.random();
                var leyenda = 'Determinacióna Buscar:';
                ajax.open('GET', './operaciones/buscar.php?leyenda='+leyenda+'&aleatorio='+aleatorio);
                divBuscar.innerHTML= '<img src="anim.gif"> :: Buscando ...';
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divBuscar.innerHTML = ajax.responseText
                    }
                    document.getElementById('buscarvalor').focus();
                }
                ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
                //enviando los valores
                ajax.send(null);
            }

            function ProcederBuscar(id){
                valor=document.getElementById('buscarvalor').value;
                if (valor.length > 0) {
                    Pagina(1, valor, 'T');
                    divBuscar = document.getElementById('Buscar');
                    divBuscar.innerHTML = '';
                } else {
                    alert('Debe Introducir un Valor a Buscar ...!');
                }
            }

            function BuscarValor(e, xbuscar) {
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    ProcederBuscar(1);
                }
            }

            function CancelarBusqueda(){
                showdiv("Lista");
                var ajax=objetoAjax();
                var divBuscar = document.getElementById('Buscar');
                var divLista = document.getElementById('ListaBuscar');
                var divContenido = document.getElementById('ListaBuscar');
                divBuscar.innerHTML = '';
                divLista.innerHTML = '';
                divContenido.innerHTML = '';
                ajax.send(null);
            }

            function CerrarListaDefinicion(){
                divLista = document.getElementById('Lista');
                ajax=objetoAjax();
                divLista.innerHTML = '';
            }

            function Pagina(nropagina, filtro, edbaja){
                //donde se mostrar los registros
                divBaja = document.getElementById('Borra');
                divBaja.innerHTML = '';
                var aleatorio = Math.random();
                var divContenido = document.getElementById('ListaBuscar');
                ajax=objetoAjax();
                //uso del medoto GET
                //indicamos el archivo que realizar� el proceso de paginar
                //junto con un valor que representa el nro de pagina
                divContenido.innerHTML= '<img src="anim.gif"> :: Cargando ...';
                ajax.open('GET', './operaciones/paginador_practica_sel.php?pag='+nropagina+'&filtro='+filtro+'&aleatorio='+aleatorio);
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divContenido.innerHTML = ajax.responseText
                    }
                }
                ajax.send('pag='+nropagina+'&filtro='+filtro+'&edbaja='+edbaja)
            }

            function CodigoNBUSel(codigo) {
                if (confirm('¿ Seguro para Agregar Equivalencia con Código ' + codigo + ' ?' )) {
                    document.getElementById('codigo2').value = codigo;
                    AgregarCodigo();
                }
                CancelarBusqueda();
            }

        </script>

        <link rel="stylesheet" type="text/css" href="/css1.css">

    </head>

    <body onLoad="javascript: if (Init()) {codigo1.focus()}; return true;">

        <form name="frmCodigosEquivalencia" method="post" onSubmit="return false">

            <div style="margin:auto;width:550px;text-align:center;">

                <?php
                echo '<td width="54"><input name="usuario" type="hidden" id="usuario" value="' . $usuario . '" /></td>';
                include('menu_admin.php');
                ?>

                <FIELDSET>
                    <LEGEND>Equivalencia de Códigos NBU</LEGEND>

                    <table width="500px" border="0" align="center">

                        <tr>
                            <td align = "right" width="85px">Obra Social:</td>
                            <td colspan="3" align = "left" width="200px">
                                <?
                                include('operaciones/lista_os_exportar.php');
                                ?>
                            </td>
                            <td width="90" align = "left">
                                <?
                                echo '<a href="javascript://" onclick="ListarTodos()">Cons. Todos</a>';
                                ?>
                            </td>
                        </tr>

                    </table>

                    <table width="500px" border="0" align="center">

                        <tr>
                            <td width="87" align = "right">Código:</td>
                            <td align = "left">
                                <input name="codigo1" id="codigo1" type="text" style="font-size: 10px;" maxlength="6" width="2" size="6" onKeyPress="javascript: if(ValidarCodigo1(event, codigo1.value)) {codigo2.focus()}; return true" />                            </td>
                            <td colspan="3" align = "left">
                                <div id="descrip1">
                                    <div align="left"></div>
                                </div></td>
                        </tr>
                        <tr>
                            <td width="87" align="right">Equivalencia:</td>
                            <td width="50" align = "left">
                                <input name="codigo2" id="codigo2" type="text" style="font-size: 10px;" maxlength="6" disabled = "true" width="2" size="6" onKeyPress="javascript: if(ValidarCodigo2(event, codigo2.value)) {codigo2.focus()}; return true" /></td>
                            <td width="19" align = "left">
                                <?
                                echo '<input type="button" class="button gray small" value="?" name="btnBuscar" id="btnBuscar" disabled = "true" onclick="Buscar(); return false" />';
                                ?></td>
                            <td colspan="2" align="left" width="326" >
                                <div id="descrip2">
                                    <div align="left"></div>
                                </div></td>
                        </tr>
                    </table>

                </FIELDSET>

                <div id="Buscar">
                </div>

                <div id="Borra">
                </div>

                <div id="Lista">
                </div>

                <div id="ListaBuscar">
                </div>

            </div>
        </form>

    </body>

    <script>
        Init();
    </script>

</html>
