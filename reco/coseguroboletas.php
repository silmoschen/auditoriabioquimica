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
    <head><title>Coseguro Boletas</title>

        <script src="../jscript/funciones.js"></script>
        <script src="../jscript/funcionajax.js"></script>
        <script src="../jscript/showhidediv.js"></script>
        <script>

            function ValidarPeriodo(e, xdato) {
                e = (window.event) ? event : e;
                intKey = (e.keyCode) ? e.keyCode : e.charCode;
                if (intKey == 13) {
                    return VerificarPeriodo(xdato);
                }
            }

            function ValidarMonto(e, xdato) {
                e = (window.event) ? event : e;
                intKey = (e.keyCode) ? e.keyCode : e.charCode;
                if (intKey == 13) {
                    return ValidarNumero(xdato);
                }
            }

            function ValidarENTER(e) {
                e = (window.event) ? event : e;
                intKey = (e.keyCode) ? e.keyCode : e.charCode;
                if (intKey == 13) {
                    return true;
                }
            }

            function CambiarOS() {
                document.getElementById('periodo').disabled = true;
                document.getElementById('listModo').disabled = true;
                document.getElementById('concepto').disabled = true;
                document.getElementById('fecha').disabled = true;
                document.getElementById('monto').disabled = true;

                //donde se mostrar los registros
                var aleatorio = Math.random();
                divLista = document.getElementById('Lista');
                var codos = document.getElementById('listObsocial').value;

                var ajax = objetoAjax();
                divLista.innerHTML = '<img src="anim.gif"> :: Cargando ...';
                if (codos != '000000') {
                    ajax.open('GET', './operaciones/paginador_mfboletas.php?codos=' + codos + '&aleatorio=' + aleatorio);
                } else {
                    divLista.innerHTML = '';
                }
                ajax.onreadystatechange = function () {
                    if (ajax.readyState == 4) {
                        //mostrar resultados en esta capa
                        divLista.innerHTML = ajax.responseText
                    }
                    if (codos != '000000') {
                        document.getElementById('periodo').disabled = false;
                        document.getElementById('listModo').disabled = false;
                        document.getElementById('monto').disabled = false;
                        document.getElementById('concepto').disabled = false;
                        document.getElementById('fecha').disabled = false;
                        document.getElementById('periodo').focus();
                    }
                }

                ajax.send(null)
            }

            function Pagina() {
                //donde se mostrar los registros
                var aleatorio = Math.random();
                var codos = document.getElementById('listObsocial').value;

                ajax = objetoAjax();
                //uso del medoto GET
                //indicamos el archivo que realizar� el proceso de paginar
                //junto con un valor que representa el nro de pagina
                ajax.open('GET', './operaciones/paginador_mfboletas.php?codos=' + codos + '&aleatorio=' + aleatorio);
                var divLista = document.getElementById('Lista');
                divLista.innerHTML = '<img src="anim.gif">';
                ajax.onreadystatechange = function () {
                    if (ajax.readyState == 4) {
                        //mostrar resultados en esta capa
                        divLista.innerHTML = ajax.responseText
                    }
                }
                ajax.send(null)
            }

            function registrar(codos, periodo, tipo, monto, concepto, fecha) {
                //donde se mostrar los registros
                var aleatorio = Math.random();
                var codos = document.getElementById('listObsocial').value;

                if (codos == '000000') {
                    alert('Codigo Obra Social Incorrecto ...!');
                    return false;
                }

                if (VerificarPeriodo(periodo) == false) {
                    return false;
                }

                if (ValidarNumero(monto) == false) {
                    return false;
                }

                var ajax = objetoAjax();
                //uso del medoto GET
                //indicamos el archivo que realizar� el proceso de paginar
                //junto con un valor que representa el nro de pagina
                var divLista = document.getElementById('mensaje');
                ajax.open('GET', './operaciones/registro_mf.php?codos=' + codos + '&periodo=' + periodo + '&monto=' + monto + '&tipo=' + tipo + '&aleatorio=' + aleatorio + '&concepto=' + concepto + '&fecha=' + fecha);
                divLista.innerHTML = '<img src="anim.gif"> :: Procesando ...';
                ajax.onreadystatechange = function () {
                    if (ajax.readyState == 4) {
                        //mostrar resultados en esta capa
                        divLista.innerHTML = ajax.responseText
                    }
                    Pagina();
                    document.getElementById('periodo').value = '';
                    document.getElementById('monto').value = '';
                    document.getElementById('concepto').value = '';
                    document.getElementById('fecha').value = '';
                    document.getElementById('periodo').focus();
                }
                ajax.send(null)
            }

            function Borrar(ID) {
                var ajax = objetoAjax();
                var divBorrar = document.getElementById('borrar');
                ajax.open('GET', './operaciones/BorraMF.php?ID=' + ID, true);
                divBorrar.innerHTML = '<img src="anim.gif">';
                ajax.onreadystatechange = function () {
                    if (ajax.readyState == 4) {
                        //mostrar resultados en esta capa
                        divBorrar.innerHTML = ajax.responseText
                    }
                }

                ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                //enviando los valores
                ajax.send(null);
            }

            function ProcederBaja(ID) {
                var ajax = objetoAjax();
                var aleatorio = Math.random();
                divBorrar = document.getElementById('borrar');
                ajax.open('GET', './operaciones/baja_mf.php?ID=' + ID + '&aleatorio=' + aleatorio, true);
                divBorrar.innerHTML = '<img src="anim.gif"> :: Borrando ...';
                ajax.onreadystatechange = function () {
                    if (ajax.readyState == 4) {
                        //mostrar resultados en esta capa
                        divBorrar.innerHTML = ajax.responseText
                        if (divBorrar.innerHTML.length == 0) {
                            // Si la Baja fue OK, refrescamos la página
                            Pagina();
                        }
                    }
                }

                ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                //enviando los valores
                ajax.send(null);
            }

            function CancelarBaja() {
                divBorrar = document.getElementById('borrar');
                divBorrar.innerHTML = '';
                Pagina();
            }

        </script>

        <link rel="stylesheet" type="text/css" href="/css1.css" />

    </head>

    <body onLoad="javascript: if (Init()) {
                desde.focus()}; return true;">

        <div style="margin:auto;width:550px;text-align:center;">

            <form name="frmConsulta" method="post" onsubmit="return false">

                <?php
                echo '<input name="usuario" type="hidden" id="usuario" value="' . $usuario . '" />';
                include('menu_admin.php');
                ?>

                <table width="550px" border="0">
                    <tr>
                        <td width="190px" align = "left">
                            Determinar Coseguro Monto Fijo Boletas
                        </td>
                        <td width="300px" align = "left">
                            <hr />
                        </td>
                    </tr>

                </table>

                <table width="550px" border="0">

                    <tr>
                        <td width="65px" align = "right">
                            Obra Social:
                        </td>
                        <td width="250px" align = "left">
                            <?
                            include('operaciones/lista_os_perfiles_mf.php');
                            ?>
                        </td>
                        <td width="50px" align = "right">
                            <input type="button" name="btnregistrar" id="btnregistrar" value="Registrar" class="button gray small" onclick="registrar(listObsocial.value, periodo.value, listModo.value, monto.value, concepto.value, fecha.value); return false;" />
                        </td>
                        <td width="50px" align = "right">
                            <input type="button" name="btncancelar" id="btncancelar" value="Cancelar" class="button gray small" onclick="Cancelar();
                                    return false;" />
                        </td>
                    </tr>

                </table>

                <table width="550px" border="0">

                    <tr>
                        <td width="40px" align = "right">
                            Período:
                        </td>
                        <td width="50px" align = "left">
                            <input name="periodo" id="periodo" type="text" style="font-size: 10px;" maxlength="7" width="40" size="8" onkeypress="javascript: if (ValidarPeriodo(event, periodo.value)) {
                                        listModo.focus()}; return true" />
                            (mm/aaaa)
                        </td>               
                        
                        <td width="40px" align = "right">
                            Aplicar:
                        </td>
                        <td width="50px" align = "left">
                            <select name="listModo" id="listModo" width="20" style="width:100px" onkeypress="javascript: if (ValidarENTER(event)) {
                                        concepto.focus()
                                    }">
                                <option value = "1">a Boleta</option>;
                                <option value = "2">a Determinación</option>;
                            </select>
                        </td>
                    </tr>
                    
                </table>

                <table width="550px" border="0">
                    <tr>
                        <td width="20px" align = "right">
                            Concepto:
                        </td>
                        <td width="50px" align = "left">
                           <input name="concepto" id="concepto" type="text" style="font-size: 10px;" maxlength="150" width="40" size="15" onkeypress="javascript: if (ValidarENTER(event)) {
                                        fecha.focus()}; return true" />
                           
                        </td>    
                        
                        <td width="40px" align = "right">
                            Vigente desde:
                        </td>
                        <td width="50px" align = "left">
                           <input name="fecha" id="fecha" type="text" style="font-size: 10px;" maxlength="10" width="10" size="10" onkeypress="javascript: if (ValidarENTER(event)) {
                                        monto.focus()}; return true" />
                           (dd/mm/aaaa)
                           
                        </td>      


                        <td width="40px" align = "right">
                            Monto:
                        </td>
                        <td width="50px" align = "left">
                            <input name="monto" id="monto" type="text" style="font-size: 10px;" maxlength="30" width="100" size="8" onkeypress="javascript: if (ValidarMonto(event, monto.value)) {
                                        btnregistrar.focus()
                                    }
                                    ;
                                    return true" />
                        </td>

                    </tr>

                </table>

                <table width="550px" border="0">

                    <tr>
                        <td width="550px"><hr /></td>
                    </tr>

                </table>

                <div id="Proceso" align="left">
                </div>

                <div id="borrar" align="left">
                </div>

                <div id="Lista" align = 'left'>
                </div>

                <div id="mensaje" align = 'left'>
                </div>



            </form>

        </div>

        <script>
            document.getElementById('listObsocial').focus();
            document.getElementById('periodo').disabled = true;
            document.getElementById('monto').disabled = true;
            document.getElementById('concepto').disabled = true;
            document.getElementById('fecha').disabled = true;
            document.getElementById('listModo').disabled = true;
        </script>

    </body>
</html>