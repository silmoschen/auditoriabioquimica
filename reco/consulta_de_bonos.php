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
    <head><title>Consulta Cantidad de Bonos</title>

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

            
             function Procesar(){
                var aleatorio = Math.random();
                divResult = document.getElementById('result');
                
                divResult.innerHTML = '<img src="anim.gif"> :: Procesando, espere un momento ...';
                
                var desde     = document.getElementById('desde').value;
                var hasta     = document.getElementById('hasta').value;                
                var codos     = document.getElementById('listObsocial').value;

                ajax1=objetoAjax();
                ajax1.open('GET', './operaciones/consultar_bonos.php?desde='+desde+'&hasta='+hasta+'&aleatorio='+aleatorio+'&codos='+codos);
                ajax1.onreadystatechange=function() {
                    if (ajax1.readyState==4) {
                        //mostrar resultados en esta capa
                        divResult.innerHTML = ajax1.responseText
                    }
                }
                ajax1.send(null)
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

                <?php include('menu_admin.php'); ?>

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
                            include('operaciones/lista_os_auditarordenes_bonos.php');
                            ?>
                        </td>

                        <td width="100px">
                            
                            
                        </td>
                        <td  width="70px">
                            <input type="button" class="button gray small" id="btnResult" value="Procesar" onclick="Procesar()" />                            
                        </td>

                    </tr>

                </table>

                <table width="550px" border="0" align="center">
                    <tr>
                        <td><hr></td>
                    </tr>
                </table>

                <div id="result" align="center">
                </div>

            </div>
        </form>

        <script>
            Init();
        </script>

    </body>
</html>