<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

session_start();
?>

<html xmlns="http://www.w3.org/1999/xhtml">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <head><title>Calcular Coseguro</title>

        <script src="../jscript/funcionajax.js"></script>
        <script>

            var omitirValidacion = false;

            function Init() {
                document.frmConsulta.listObsocial.focus();
                return true;
            }

            function ValidarCodigo(e, xcodigo) {
                e = (window.event) ? event : e;
                intKey = (e.keyCode) ? e.keyCode : e.charCode;
                if (intKey == 13) {
                    VerificarPractica(xcodigo, true);
                    return true;
                }
            }

            function VerificarPractica(xcodigo, limpiar) {
                if (omitirValidacion) {
                    omitirValidacion = false;
                    return;
                }
                var divCod = document.getElementById('descrip');
                if (xcodigo.replace(/^\s+|\s+$/g, '') == '') {
                    if (divCod.innerHTML.replace(/^\s+/, '').substring(0, 3) == '***') {
                        divCod.innerHTML = '';
                    }
                    MostrarProcesando(false);
                    return;
                }
                var aleatorio = Math.random();

                MostrarProcesando(true);
                ajaxcod = objetoAjax();
                ajaxcod.open('GET', './operaciones/validar_practica.php?codigo=' + xcodigo + '&aleatorio=' + aleatorio);
                ajaxcod.onreadystatechange = function () {
                    if (ajaxcod.readyState == 4) {
                        var contenido = ajaxcod.responseText.replace(/^\s+/, '');
                        divCod.innerHTML = contenido;
                        AcumularPractica(xcodigo, contenido, limpiar);
                    }
                }
                ajaxcod.send(null);
            }

            function AcumularPractica(xcodigo, xpractica, limpiar) {
                if (xpractica.substring(0, 3) == '***') {
                    MostrarProcesando(false);
                    return;
                }

                var tabla = document.getElementById('tablaPracticas');
                var pie = document.getElementById('pieCoseguro');
                var fila = tabla.insertRow(pie.rowIndex);
                var colCodigo = fila.insertCell(0);
                var colPractica = fila.insertCell(1);
                var colCoseguro = fila.insertCell(2);
                var colBorrar = fila.insertCell(3);
                colCodigo.align = 'left';
                colPractica.align = 'left';
                colCoseguro.align = 'right';
                colBorrar.align = 'right';
                colCodigo.innerHTML = xcodigo;
                colPractica.innerHTML = xpractica;
                colCoseguro.innerHTML = '';
                colBorrar.innerHTML = '<input type="button" value="X" onmousedown="omitirValidacion=true" onclick="BorrarPractica(this)" />';
                MostrarTablaPracticas();
                if (limpiar) {
                    LimpiarCodigo();
                }
                CargarCoseguro(xcodigo, colCoseguro);
            }

            function LimpiarCodigo() {
                document.getElementById('codigo').value = '';
                document.getElementById('codigo').focus();
            }

            function MostrarProcesando(visible) {
                document.getElementById('procesando').style.display = visible ? '' : 'none';
            }

            function BorrarPractica(boton) {
                omitirValidacion = false;
                var fila = boton.parentNode.parentNode;
                fila.parentNode.removeChild(fila);
                SumarCoseguro();
                MostrarTablaPracticas();
                document.getElementById('codigo').focus();
            }

            function MostrarTablaPracticas() {
                var tabla = document.getElementById('tablaPracticas');
                var filas = tabla.getElementsByTagName('tr');
                var items = 0;
                for (var i = 1; i < filas.length; i++) {
                    if (filas[i].id != 'pieCoseguro') {
                        items++;
                    }
                }
                tabla.style.display = items > 0 ? '' : 'none';
            }

            function CargarCoseguro(xcodigo, colCoseguro) {
                var codos = document.getElementById('listObsocial').value;
                var aleatorio = Math.random();

                var ajaxcos = objetoAjax();
                ajaxcos.open('GET', './operaciones/get_monto_coseguro.php?codos=' + codos + '&codigo=' + xcodigo + '&aleatorio=' + aleatorio, false);
                ajaxcos.send(null);
                if (ajaxcos.readyState == 4 && ajaxcos.status == 200) {
                    var n = parseFloat(ajaxcos.responseText.replace(/^\s+|\s+$/g, '').replace(',', '.'));
                    if (isNaN(n)) {
                        n = 0;
                    }                    
                    colCoseguro.setAttribute('monto', n);
                    colCoseguro.innerHTML = FormatearNumero(n);
                    SumarCoseguro();
                    MostrarProcesando(false);
                }
            }

            function SumarCoseguro() {
                var tabla = document.getElementById('tablaPracticas');
                var filas = tabla.getElementsByTagName('tr');
                var total = 0;
                for (var i = 1; i < filas.length; i++) {
                    if (filas[i].id == 'pieCoseguro') {
                        continue;
                    }
                    var n = parseFloat(filas[i].cells[2].getAttribute('monto'));
                    if (!isNaN(n)) {
                        total = total + n;
                    }
                }
                total = Math.round(total * 100) / 100;
                document.getElementById('totalCoseguro').innerHTML = '<b>' + FormatearNumero(total) + '</b>';
            }

            function FormatearNumero(n) {
                var negativo = n < 0;
                n = Math.abs(Math.round(n * 100) / 100);
                var partes = n.toFixed(2).split('.');
                var entero = partes[0];
                var miles = '';
                while (entero.length > 3) {
                    miles = '.' + entero.substr(entero.length - 3) + miles;
                    entero = entero.substr(0, entero.length - 3);
                }
                return (negativo ? '-' : '') + entero + miles + ',' + partes[1];
            }

            function CambiarOS() {
                document.getElementById('codigo').value = '';
                document.getElementById('descrip').innerHTML = '';
                var tabla = document.getElementById('tablaPracticas');
                var i = tabla.rows.length - 1;
                while (i > 0) {
                    if (tabla.rows[i].id != 'pieCoseguro') {
                        tabla.deleteRow(i);
                    }
                    i--;
                }
                document.getElementById('totalCoseguro').innerHTML = '<b>0,00</b>';
                MostrarTablaPracticas();
                document.getElementById('codigo').focus();
                return false;
            }

        </script>

        <link rel="stylesheet" type="text/css" href="/css1.css">

    </head>

    <body>

        <form name="frmConsulta" method="post" onsubmit="return false">

            <div style="margin:auto;width:550px;text-align:center;">

                <?php include('menu_usuario.php'); ?>

                <table width="550px" border="0" align="center">
                    <tr>
                        <td width="100px" align="left">Calcular Coseguro</td>
                        <td width="400px"><hr></td>
                    </tr>
                </table>

                <table width="550px" border="0" align="left">
                    <tr>
                        <td width="50px" align = "right">
                            Obra Social:
                        </td>
                        <td width="200px" align = "left">
                            <?
                            include('operaciones/lista_os_auditarordenes.php');
                            ?>
                        </td>
                        <td width="100px" align="left">
                            <div id="procesando" style="display:none;">Procesando ...</div>
                        </td>

                    </tr>
                </table>

                <table width="550px" border="0" align="left">
                    <tr>
                        <td width="50px" align = "right">
                            Código:
                        </td>
                        <td width="20px" align = "left">
                            <input name="codigo" id="codigo" type="text" style="font-size: 10px;" maxlength="6" size="6" onkeypress="javascript: ValidarCodigo(event, this.value); return true" onblur="VerificarPractica(this.value)" />
                            
                        </td>

                        <td width="350px">
                            <span id="descrip"></span>
                        </td>
                       

                    </tr>
                </table>

                <table width="550px" border="0" align="center">
                    <tr>
                        <td><hr></td>
                    </tr>
                </table>

                <table width="550px" border="0" align="center" id="tablaPracticas" style="display:none;">
                    <tr>
                        <td width="80px" align="left"><b>Código</b></td>
                        <td width="270px" align="left"><b>Práctica</b></td>
                        <td width="120px" align="right"><b>Coseguro</b></td>
                        <td width="80px"></td>
                    </tr>
                    <tr id="pieCoseguro">
                        <td colspan="2" align="left" style="border-top:1px solid #000;"><b>Total Coseguro</b></td>
                        <td align="right" id="totalCoseguro" style="border-top:1px solid #000;"><b>0,00</b></td>
                        <td style="border-top:1px solid #000;"></td>
                    </tr>
                </table>

            </div>
        </form>

        <script>
            Init();
        </script>

    </body>
</html>
