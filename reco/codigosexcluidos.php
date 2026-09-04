<?php
session_start();
//session_register('susuario');
//session_register('spass');
//session_register('snombre');
//session_register('sdireccion');
//session_register('stelefono');
//session_register('semail');

$usuario = $_SESSION['susuario'];
$pass    = $_SESSION['spass'];
$nombre  = $_SESSION['snombre'];

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cUtiles.php");
$u = new cUtiles;
?>

<html xmlns="http://www.w3.org/1999/xhtml">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <head><title>Inclusión de Códigos NBU</title>

        <script src="../jscript/funcionajax.js"></script>
        <script>

            function Init() {
                document.frmCodigosExcluidos.codigo1.focus();
                return true;
            }

            function ValidarCodigo1(e, xcodigo){
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    if (VerificarCodigo(xcodigo, 1)) {
                        ListarItems(xcodigo)
                        return true;
                    }
                }
            }

            function ValidarCodigo2(e, xcodigo){
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    return VerificarCodigo(xcodigo, 2);
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
                ajaxcod.open('GET', './operaciones/verificar_codigo_nbu.php?codigo='+xcodigo+'&aleatorio='+aleatorio);
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
                                document.frmCodigosExcluidos.codigo2.disabled=false;
                                document.frmCodigosExcluidos.codigo2.focus();
                            }
                            return true;
                        }
                    }
                }

                ajaxcod.send(null);

                return true;
            }

            function AgregarCodigo() {
                var aleatorio = Math.random();
                var codigo1 = document.frmCodigosExcluidos.codigo1.value;
                var codigo2 = document.frmCodigosExcluidos.codigo2.value;

                if(codigo1.length == 6 & codigo2.length == 6) {
                    divLista = document.getElementById('Lista');
                    ajax=objetoAjax();
                    ajax.open('GET', './operaciones/insertar_codigo_excluido.php?codigo1='+codigo1+'&codigo2='+codigo2+'&aleatorio='+aleatorio);
                    divLista.innerHTML= '<img src="anim.gif"> :: Registrando ...';
                    ajax.onreadystatechange=function() {
                        if (ajax.readyState==4) {
                            //mostrar resultados en esta capa
                            divLista.innerHTML = ajax.responseText
                        }
                        ListarItems(codigo1);
                    }
                    ajax.send(null)
                }
            }

            function ListarItems(codigo)  {
                //donde se mostrar los registros
                var aleatorio = Math.random();
                divLista = document.getElementById('Lista');
                ajax=objetoAjax();
                ajax.open('GET', './operaciones/lista_codigos_excluidos.php?codigo='+codigo+'&aleatorio='+aleatorio);
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divLista.innerHTML = ajax.responseText
                    }
                    document.frmCodigosExcluidos.codigo2.value='';
                    document.frmCodigosExcluidos.codigo2.focus();
                }
                ajax.send(null)
            }

            function BajaCodigo(codigo1, codigo2){
                //donde se mostrará los registros
                var aleatorio = Math.random();
                divBorrar = document.getElementById('Borra');
                ajax=objetoAjax();

                //uso del medoto GET
                //indicamos el archivo que realizar� el proceso de paginar
                //junto con un valor que representa el nro de pagina
                ajax.open('GET', './operaciones/BorraCodigoExcluido.php?codigo1='+codigo1+'&codigo2='+codigo2+'&aleatorio='+aleatorio);
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

            function ProcederBaja(codigo1, codigo2){
                //donde se mostrará los registros
                var aleatorio = Math.random();
                divBorra = document.getElementById('Borra');
                ajax1=objetoAjax();

                //uso del medoto GET
                //indicamos el archivo que realizar� el proceso de paginar
                //junto con un valor que representa el nro de pagina
                ajax1.open('GET', './operaciones/baja_codigo_excluido.php?codigo1='+codigo1+'&codigo2='+codigo2+'&aleatorio='+aleatorio);
                divBorra.innerHTML= '<img src="anim.gif"> :: Eliminando ...';
                ajax1.onreadystatechange=function() {
                    if (ajax1.readyState==4) {
                        //mostrar resultados en esta capa
                        divBorra.innerHTML = '';
                    }
                    ListarItems(codigo1);
                }
                ajax1.send(null)
            }

            function CerrarListaDefinicion(){
                divLista = document.getElementById('Lista');
                ajax=objetoAjax();
                divLista.innerHTML = '';
            }

        </script>

        <link rel="stylesheet" type="text/css" href="/css1.css">

    </head>

    <body onLoad="javascript: if (Init()) {codigo1.focus()}; return true;">

        <form name="frmCodigosExcluidos" method="post" onsubmit="return false">

            <div style="margin:auto;width:550px;text-align:center;">

                <?php
                echo '<td width="54"><input name="usuario" type="hidden" id="usuario" value="' . $usuario . '" /></td>';
                include('menu_admin.php');
                ?>

                <FIELDSET>
                    <LEGEND>Inclusión de Códigos NBU</LEGEND>

                    <table width="426" border="0" align="left">
                        <tr>
                            <td width="141" align = "right">Código:
                                <input name="codigo1" type="text" style="font-size: 10px;" maxlength="6" width="2" size="6" onkeypress="javascript: if(ValidarCodigo1(event, codigo1.value)) {codigo2.focus()}; return true" />
                            </td>
                            <td width="295" align="left">
                                <div id="descrip1">
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td width="141" align="right">Cód.Incluído:
                                <input name="codigo2" type="text" style="font-size: 10px;" maxlength="6" disabled = "true" width="2" size="6" onkeypress="javascript: if(ValidarCodigo2(event, codigo2.value)) {codigo2.focus()}; return true" />
                            </td>
                            <td width="295" align="left">
                                <div id="descrip2">
                                </div>
                            </td>
                        </tr>
                    </table>

                </FIELDSET>

                <div id="Borra">
                </div>

                <div id="Lista">
                </div>

            </div>
        </form>

    </body>

    <script>
        Init();
    </script>

</html>