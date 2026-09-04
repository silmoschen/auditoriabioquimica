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
                document.getElementById("codigo").focus();                
                return true;
            }

            function CambiarOS() {
                ListarItems();
            }

            function ValidarCodigo(e, xcodigo){
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    if (VerificarCodigo(xcodigo)) {                        
                        return true;
                    }
                }
            }

            function VerificarCodigo(xcodigo) {
                if (xcodigo.length != 6) {
                    alert('Codigo Incorrecto ...!');
                    return false;
                }

                var divCod = document.getElementById("descrip");
                var aleatorio = Math.random();

                ajaxcod = objetoAjax();
                ajaxcod.open('GET', './operaciones/verificar_codigo_nbu.php?codigo='+xcodigo+'&aleatorio='+aleatorio);
                
                ajaxcod.onreadystatechange=function() {
                    if (ajaxcod.readyState==4) {
                        //mostrar resultados en esta capa                        
                        var contenido = ajaxcod.responseText;                
                        divCod.innerHTML = contenido;
                        
                        var cont  = contenido.substring(0, 10);
                        var cont1 = contenido.substring(0, 3);
                        
                        if (cont.length > 0 & cont1 != '***') {  // En base al contenido del TAG se si el objeto existe o no
                            AgregarCodigo(); 
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
                    var codigo = document.getElementById("codigo").value;

                    if(codos.length == 6 && codigo.length == 6) {
                        divLista = document.getElementById('Lista');
                        ajax=objetoAjax();
                        ajax.open('GET', './operaciones/alta_codigorestringido.php?codos='+codos+'&codigo='+codigo+'&aleatorio='+aleatorio);
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
                if (codos.length > 0) {
                    //donde se mostrar los registros
                    var aleatorio = Math.random();

                    document.getElementById('codigo').focus();
                                        
                    divLista = document.getElementById('Lista');
                    ajax=objetoAjax();
                    ajax.open('GET', './operaciones/lista_codigos_restringidos.php?codos='+codos+'&aleatorio='+aleatorio);
                    ajax.onreadystatechange=function() {
                        if (ajax.readyState==4) {
                            //mostrar resultados en esta capa
                            divLista.innerHTML = ajax.responseText
                        }
                        document.getElementById("codigo").value='';
                        document.getElementById("codigo").focus();
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
                ajax.open('GET', './operaciones/BorraCodigoRestringido.php?codos='+codos+'&codigo='+codigo+'&aleatorio='+aleatorio);
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

            function ProcederBaja(codos, codigo){
                //donde se mostrará los registros
                var aleatorio = Math.random();
                divBorra = document.getElementById('Borra');
                ajax1=objetoAjax();

                //uso del medoto GET
                //indicamos el archivo que realizar� el proceso de paginar
                //junto con un valor que representa el nro de pagina
                ajax1.open('GET', './operaciones/baja_codigorestringido.php?codos='+codos+'&codigo='+codigo+'&aleatorio='+aleatorio);
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

        </script>

        <link rel="stylesheet" type="text/css" href="/css1.css" />

    </head>

    <body onLoad="javascript: if (Init()) {codigo1.focus()}; return true;">

        <form name="frmCodigosRestringidos" method="post" onSubmit="return false">

            <div style="margin:auto;width:550px;text-align:center;">

                <?php
                echo '<td width="54"><input name="usuario" type="hidden" id="usuario" value="' . $usuario . '" /></td>';
                include('menu_admin.php');
                ?>

                <FIELDSET>
                    <LEGEND>Códigos Restringidos</LEGEND>

                    <table width="500px" border="0" align="center">

                        <tr>
                            <td align = "right" width="85px">Obra Social:</td>
                            <td colspan="3" align = "left" width="200px">
                                <?
                                include('operaciones/lista_os_auditarordenes.php');
                                ?>
                            </td>
                            <td width="90" align = "left">
                            </td>
                        </tr>

                    </table>

                    <table width="500px" border="0" align="center">

                        <tr>
                            <td width="87" align = "right">Código:</td>
                            <td width="87" align = "left">
                                <input name="codigo" id="codigo" type="text" style="font-size: 10px;" maxlength="6" width="2" size="7" onKeyPress="javascript: if(ValidarCodigo(event, codigo.value)) {codigo.focus()}; return true" />                            </td>
                            <td align = "left">
                                <div id="descrip">                                    
                                </div>
                            </td>
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
        ListarItems();
    </script>

</html>
