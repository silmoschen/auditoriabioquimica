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
                document.getElementById("leyenda").focus();                
                return true;
            }

            function CambiarOS() {
                ListarItems();
            }

            function AgregarLeyenda(event) {  
                e = (window.event) ? event : e;
                intKey = (e.keyCode) ? e.keyCode : e.charCode;
                if (intKey == 13) {
                    var codos  = document.getElementById('listObsocial').value;
                    if (codos.length > 0) {
                        var aleatorio = Math.random();
                        var leyenda = document.getElementById("leyenda").value;

                        if(codos.length > 0) {
                            divLista = document.getElementById('Lista');
                            ajax=objetoAjax();
                            ajax.open('GET', './operaciones/alta_leyendaos.php?codos='+codos+'&descrip='+leyenda+'&aleatorio='+aleatorio);
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
            }

            function ListarItems()  {
                var codos  = document.getElementById('listObsocial').value;
                if (codos.length > 0) {
                    //donde se mostrar los registros
                    var aleatorio = Math.random();

                    document.getElementById('leyenda').focus();
                                        
                    divLista = document.getElementById('Lista');
                    ajax=objetoAjax();
                    ajax.open('GET', './operaciones/lista_leyenda_os.php?codos='+codos+'&aleatorio='+aleatorio);
                    ajax.onreadystatechange=function() {
                        if (ajax.readyState==4) {
                            //mostrar resultados en esta capa
                            divLista.innerHTML = ajax.responseText
                        }
                        document.getElementById("leyenda").value='';
                        document.getElementById("leyenda").focus();
                    }
                    ajax.send(null)
                }
            }

            function Borrar(codos){
                //donde se mostrará los registros
                var aleatorio = Math.random();
                divBorrar = document.getElementById('Borra');
                ajax=objetoAjax();

                //uso del medoto GET
                //indicamos el archivo que realizar� el proceso de paginar
                //junto con un valor que representa el nro de pagina
                ajax.open('GET', './operaciones/BorraLeyendaos.php?codigo='+codos+'&aleatorio='+aleatorio);
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

            function ProcederBaja(codos){
                //donde se mostrará los registros
                var aleatorio = Math.random();
                divBorra = document.getElementById('Borra');
                ajax1=objetoAjax();

                //uso del medoto GET
                //indicamos el archivo que realizar� el proceso de paginar
                //junto con un valor que representa el nro de pagina
                ajax1.open('GET', './operaciones/baja_leyendaos.php?codos='+codos+'&aleatorio='+aleatorio);
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
                    <LEGEND>Leyendas a Imprimir en Cupones</LEGEND>

                    <table width="500px" border="0" align="center">

                        <tr>
                            <td align = "right" width="85px">Obra Social:</td>
                            <td colspan="3" align = "left" width="200px">
                                <?
                                include('operaciones/lista_os_auditarordenes.php');
                                ?>
                            </td>
                        </tr>

                    </table>

                    <table width="500px" border="0" align="center">

                        <tr>
                            <td width="85" align = "right">Leyenda:</td>
                            <td align = "left">
                                <input name="leyenda" id="leyenda" type="text" style="font-size: 10px;" maxlength="150" width="2" size="70" onKeyPress="javascript: if (AgregarLeyenda(event)) {leyenda.focus()}; return true" />                            
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
