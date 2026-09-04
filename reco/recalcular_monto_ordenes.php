<?php
session_start();

$usuario = $_SESSION['susuario'];
$pass    = $_SESSION['spass'];
$nombre  = $_SESSION['snombre'];

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cUtiles.php");
$u = new cUtiles;
?>

<html xmlns="http://www.w3.org/1999/xhtml">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <head><title>Recalcular Montos de Auditoría</title>

        <script src="../jscript/funcionajax.js"></script>
        <script src="../jscript/funciones.js"></script>
        <script>

            function Init() {
                document.frmRecalcular.desde.focus();
                return true;
            }

            function CambiarOSS(){
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
                        ProcesarRecalculo();
                        return true;
                    }
                }
            }

            function ProcesarRecalculo(){
                //donde se mostrar los registros
                var aleatorio = Math.random();
                divResultado  = document.getElementById('Resultado');
                var codos = document.getElementById('listObsocial').value;

                var desde = document.getElementById('desde').value;
                var hasta = document.getElementById('hasta').value;

                if (ValidarFecha(desde) & ValidarFecha(hasta)) {
                    if (confirm('¿ Seguro para Recalcular Monto en Determinaciones en el Período ' + desde + ' - ' + hasta +  ' ?' )) {
                        ajax=objetoAjax();
                        //uso del medoto GET
                        //indicamos el archivo que realizar� el proceso de paginar
                        //junto con un valor que representa el nro de pagina
                        divResultado.innerHTML = '<img src="anim.gif"> :: Procesando, Espere por favor';
                        ajax.open('GET', './operaciones/recalcular_montos.php?desde='+desde+'&hasta='+hasta+'&aleatorio='+aleatorio+'&codos='+codos);
                        ajax.onreadystatechange=function() {
                            if (ajax.readyState==4) {
                                //mostrar resultados en esta capa
                                divResultado.innerHTML = ajax.responseText
                            }
                        }

                        ajax.send(null)
                    }
                } else {
                    alert('Una de las Fechas Ingresadas es Incorrecta ...!');
                }
            }

        </script>

        <link rel="stylesheet" type="text/css" href="/css1.css">

    </head>

    <body onLoad="javascript: if (Init()) {desde.focus()}; return true;">

        <form name="frmRecalcular" method="post" onsubmit="return false">

            <div style="margin:auto;width:550px;text-align:center;">
                <?php
                echo '<td width="54"><input name="usuario" type="hidden" id="usuario" value="' . $usuario . '" /></td>';
                include('menu_admin.php');
                ?>

                <table width="550px" border="0" align="center">
                    <tr>
                        <td width="110px" align="left">Recalcular Montos</td>
                        <td width="390px"><hr /></td>
                    </tr>
                </table>

                <table width="426" border="0" align="center">
                    <tr>
                        <td width="50" align="right">O.Social:</td>
                        <td width="200px">
                            <?
                            include('operaciones/lista_os_recalcular.php');
                            ?>
                        </td>
                        <td width="100px"></td>
                        <td width="100px"></td>
                    </tr>

                    <tr>
                        <td width="50px" align="right">Desde:</td>
                        <td width="200px" align = "left">
                            <input name="desde" id="desde" type="text" style="font-size: 10px;" maxlength="10" width="2" size="10" onkeypress="javascript: if(ValidarFechaDesde(event, desde.value)) {hasta.focus()}; return true" />
                            (dd/mm/aaaa)
                        </td>

                        <td width="70">Hasta:</td>
                        <td width="100px">
                            <input name="hasta" id="hasta" type="text" style="font-size: 10px;" maxlength="10" width="2" size="10" onkeypress="javascript: if(ValidarFechaHasta(event, hasta.value)) {hasta.focus()}; return true" />
                        </td>
                    </tr>

                    <tr>
                        <td width="50px"></td>
                        <td width="200px" align="left">
                            <a href="javascript://" onclick="ProcesarRecalculo()">Iniciar Proceso</a>
                        </td>
                        <td width="70px"></td>
                        <td width="100px"></td>
                    </tr>
                </table>

                <table width="500px" border="0" align="center">
                    <tr>
                        <td><hr></td>
                    </tr>
                </table>

                <div id="Botones" align="center">
                </div>

                <div id="Resultado">
                </div>

            </div>
        </form>

    </body>

    <script>
        Init();
    </script>

</html>
