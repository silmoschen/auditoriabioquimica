<?
session_start();

$usuario = $_SESSION['susuario'];
$susuario = $_SESSION['susuario'];
$pass = $_SESSION['spass'];
$nombre = $_SESSION['snombre'];
$trans = $_REQUEST['trans'];
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cUtiles.php");
$u = new cUtiles;
?>

<html xmlns="http://www.w3.org/1999/xhtml"> 
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <head><title>Ingreso Ordenes de Auditoria</title>

        <script src="../jscript/funciones.js"></script>
        <script src="../jscript/funcionajax.js"></script>
        <script src="../jscript/showhidediv.js"></script>
        <script>

            function Init() {
                document.getElementById('nrodoc').focus();
                return true;
            }

            function loadObrasSociales() {
                document.getElementById('divtrack').style.display = 'none';
                var aleatorio = Math.random();
                var divOS = document.getElementById('obssociales');
                ajax = objetoAjax();
                var uss = document.getElementById('usuario').value;
                ajax.open('GET', './operaciones/lista_os_ingordenes_efector.php?idprof=' + uss + '&aleatorio=' + aleatorio, false);
                ajax.onreadystatechange = function () {
                    if (ajax.readyState == 4) {
                        //mostrar resultados en esta capa
                        divOS.innerHTML = ajax.responseText
                    }
                    CargarDiagnosticos();
                    loadRPC();
                }

                ajax.send(null);
            }

            function ControlOS(e) {
                e = (window.event) ? event : e;
                intKey = (e.keyCode) ? e.keyCode : e.charCode;
                if (intKey == 13) {
                    return true;
                }
            }

            function ControlOSS() {
                loadRPC();
                CargarDiagnosticos();
                return true;
            }

            function ValidarEnter(e) {
                e = (window.event) ? event : e;
                intKey = (e.keyCode) ? e.keyCode : e.charCode;
                if (intKey == 13) {
                    return true;
                }
            }

            function loadTopeRPC() {
                var aleatorio = Math.random();
                var divproc = document.getElementById('proceso');
                document.getElementById('ListarOrden').style.display = 'block';
                document.getElementById('__nroauditoria').value == ''
                divproc.innerHTML = '<i><img src="anim.gif"> :: Procesando ...</i>';
                var divrpc = document.getElementById('a_tope');
                var ajax = objetoAjax();
                var codos = document.getElementById('listObsocial').value;
                ajax.open('GET', './operaciones/verificar_tope_practicas_rpc.php?codos=' + codos + '&aleatorio=' + aleatorio, true);

                ajax.onreadystatechange = function () {
                    if (ajax.readyState == 4) {
                        //mostrar resultados en esta capa
                        divproc.innerHTML = '';
                        var js = ajax.responseText;
                        divrpc.value = js.substr(0, 2).trim(); //ajax.responseText;
                        var n = js.search('track');
                        if (n > 0) {
                            document.getElementById('divtrack').style.display = 'block';
                            document.getElementById('a_track').value = '1';
                        } else
                        {
                            document.getElementById('divtrack').style.display = 'none';
                            document.getElementById('a_track').value = '0';
                        }
                        //alert(document.getElementById('a_tope').value);
                    }
                }
                ajax.send(null);

                document.getElementById('__nroauditoria').value = '';
                document.getElementById('divtoken').style.display = 'none';
                document.getElementById('MensajeReg').innerHTML = '';

            }

            function loadRPC() {
                var aleatorio = Math.random();
                var divproc = document.getElementById('proceso');
                divproc.innerHTML = '<i><img src="anim.gif"> :: Procesando ...</i>';
                var divrpc = document.getElementById('a_rpc');
                var ajax = objetoAjax();
                var codos = document.getElementById('listObsocial').value;
                ajax.open('GET', './operaciones/verificar_rpc.php?codos=' + codos + '&aleatorio=' + aleatorio, true);
                ajax.onreadystatechange = function () {
                    if (ajax.readyState == 4) {
                        //mostrar resultados en esta capa
                        divproc.innerHTML = '';
                        divrpc.value = ajax.responseText;
                        if (divrpc.value == 'S' || divrpc.value == 'M') {
                            loadTopeRPC();
                            hidediv("divdoc");
                        } else {
                            showdiv("divdoc");
                            document.getElementById('a_tope').value = '0';
                        }
                    }
                }
                ajax.send(null);
            }


            function ValidarAfiliado(e, codos, nrodoc) {
                e = (window.event) ? event : e;
                intKey = (e.keyCode) ? e.keyCode : e.charCode;
                if (intKey == 13) {

                    if (document.getElementById('a_track').value == 1) {
                        // Require track
                        document.getElementById('track').disabled = false;
                        document.getElementById('track').focus();
                        return;
                    }

                    if (nrodoc.length > 0) {
                        var nr = nrodoc.replace('-', '');
                        nr = nr.replace('/', '');
                        //nr = nrodoc;
                        // Nos adelantamos porque sino el evento no lo toma
                        document.getElementById('nrodoc').value = nr;
                        document.frmOrden.nombremedico.disabled = false;
                        document.getElementById('fepedido').disabled = false;
                        CargarDocumento(codos, nr);
                    } else {
                        alert('El Número de Documento es Incorrecto ...!')
                    }
                    return true;
                }
            }

            function ValidarTrack(e, codos, nrodoc, track) {
                e = (window.event) ? event : e;
                intKey = (e.keyCode) ? e.keyCode : e.charCode;
                if (intKey == 13) {

                    if (track.length > 0) {
                        var nr = nrodoc.replace('-', '');
                        nr = nr.replace('/', '');
                        //nr = nrodoc;
                        // Nos adelantamos porque sino el evento no lo toma
                        document.getElementById('nrodoc').value = nr;
                        document.frmOrden.nombremedico.disabled = false;
                        document.getElementById('fepedido').disabled = false;
                        CargarDocumento(codos, nr);
                    } else {
                        alert('El Número de Documento es Incorrecto ...!')
                    }
                    return true;
                }
            }

            function CargarDocumento(xcodos, xnrodoc) {

                // Recuperamos track
                var track1 = '';
                if (document.getElementById('a_track').value == 1) {
                    track1 = document.getElementById('track').value;
                }

                var aleatorio = Math.random();
                var cont = '';
                var found = false;

                var ajaxp0 = objetoAjax();

                var divproc = document.getElementById('proceso');
                divproc.innerHTML = '<i><img src="anim.gif"> :: Procesando</i>';
                var divCDP = document.getElementById('ErrorAltaPaciente');
                divCDP.innerHTML = '';
                var divCD = document.getElementById('CargarDocumentoPaciente');
                var usuario = document.frmOrden.usuario.value;

                // verificar si la validación es remota                
                if (document.getElementById('a_rpc').value == 'S' || document.getElementById('a_rpc').value == 'M') {
                    ajaxp0.open('GET', './operaciones/alta_paciente_rpc.php?codos=' + xcodos + '&nrodoc=' + xnrodoc + '&aleatorio=' + aleatorio + '&idprof=' + usuario + '&track=' + track1, true);
                    ajaxp0.onreadystatechange = function () {
                        if (ajaxp0.readyState == 4) {
                            //mostrar resultados en esta capa
                            divproc.innerHTML = '';
                            var contenido = ajaxp0.responseText;
                            divCDP.innerHTML = contenido;
                            var c = contenido;
                            cont = contenido.substring(0, 10);
                            if (cont.indexOf('Paciente') > -1) {  // En base al contenido del TAG se si el objeto existe o no
                                found = true;
                                var p = c.indexOf('-', 0);
                                divCD.innerHTML = c.substring(p + 2, 145);
                                divCDP.innerHTML = '';
                                document.getElementById('nombremedico').focus();
                            } else {
                                document.getElementById('nombremedico').disabled = true;
                                document.getElementById('fepedido').disabled = true;
                                document.getElementById('ok').focus();
                            }
                        }

                        if (found) {  // Si el Objeto Existe habilitamos los controles
                            document.frmOrden.diferida.value = 'N';
                            document.frmOrden.fepedido.disabled = false;
                        } else {      // Si el Objeto No Existe inhabilitamos los controles
                            //document.getElementById('listPerfil').disabled = true;
                            document.frmOrden.diferida.value = 'S';
                            document.frmOrden.fepedido.disabled = true;
                            document.getElementById('nrodoc').focus();
                        }

                    }

                    ajaxp0.send(null);

                    return false;
                }

                // validadción en el padrón propio                
                ajaxp0.open('GET', './operaciones/alta_paciente.php?codos=' + xcodos + '&nrodoc=' + xnrodoc + '&aleatorio=' + aleatorio, true);
                ajaxp0.onreadystatechange = function () {
                    if (ajaxp0.readyState == 4) {
                        //mostrar resultados en esta capa
                        divproc.innerHTML = '';
                        var contenido = ajaxp0.responseText;
                        divCDP.innerHTML = contenido;
                        var c = contenido;
                        cont = contenido.substring(0, 10);
                        if (cont.indexOf('Paciente') > -1) {  // En base al contenido del TAG se si el objeto existe o no
                            found = true;
                            var p = c.indexOf('-', 0);
                            divCD.innerHTML = c.substring(p + 2, 45);
                            divCDP.innerHTML = '';
                            document.getElementById('nombremedico').focus();
                        } else {
                            document.getElementById('nombremedico').disabled = true;
                            document.getElementById('ok').focus();
                        }
                    }

                    if (found) {  // Si el Objeto Existe habilitamos los controles
                        document.frmOrden.diferida.value = 'N';
                        document.frmOrden.fepedido.disabled = false;
                    } else {      // Si el Objeto No Existe inhabilitamos los controles
                        //document.getElementById('listPerfil').disabled = true;
                        document.frmOrden.diferida.value = 'S';
                        document.frmOrden.fepedido.disabled = true;
                        document.getElementById('nrodoc').focus();
                    }
                }

                ajaxp0.send(null);
            }

            function DarAltaPaciente(xcodos, xnrodoc) {
                var aleatorio = Math.random();
                var divCDA;
                divCDA = document.getElementById('FormAltaPaciente');
                divCDP = document.getElementById('ErrorAltaPaciente');
                divCDP.innerHTML = '';
                var ajaxp1 = objetoAjax();
                ajaxp1.open('GET', './operaciones/alta_afiliado.php?codos=' + xcodos + '&nrodoc=' + xnrodoc + '&aleatorio=' + aleatorio, true);
                divCDA.innerHTML = '<img src="anim.gif"> :: Nuevo Paciente ...';
                ajaxp1.onreadystatechange = function () {
                    if (ajaxp1.readyState == 4) {
                        //mostrar resultados en esta capa
                        divCDA.innerHTML = ajaxp1.responseText;
                    }

                    document.getElementById('listMedicos').disabled = true;
                    document.frmOrden.nombre.focus();
                    document.frmOrden.nrodoc.disabled = true;
                    document.frmOrden.nombre.focus();
                }
                ajaxp1.send(null);
            }

            function CancelarAltaPaciente() {
                divCDP = document.getElementById('ErrorAltaPaciente');
                divCDP.innerHTML = '';
                divCDN = document.getElementById('NuevoPaciente');
                divCDN.innerHTML = '';
                divCDA = document.getElementById('FormAltaPaciente');
                divCDA.innerHTML = '';
                ajaxp2 = objetoAjax();
                ajaxp2.onreadystatechange = function () {
                    if (ajaxp2.readyState == 4) {
                        //mostrar resultados en esta capa
                        divCDP.innerHTML = ajaxp2.responseText
                    }
                }
                //document.frmOrden.nrodoc.disabled = false;
                //document.getElementById('listMedicos').disabled = true;
                //document.getElementById('listPerfil').disabled = true;
                //document.getElementById('nombremedico').disabled = true;
                document.frmOrden.nrodoc.focus();
                ajaxp2.send(null);
            }

            function Registrar_Afiliado(xcodos, xnrodoc, xnombre, xobservac, xfechanac, xdepto, xretiva) {
                if (xnombre.length == 0) {
                    alert('Nombre Incorrecto ...!');
                    return false;
                }
                var d = xdepto.substring(0, 1).toUpperCase();
                if (d == 'S' || d == 'N') {
                } else {
                    alert('Las Opciones son S ó N ...!');
                    return false;
                }

                var aleatorio = Math.random();
                divCDP1 = document.getElementById('CargarDocumentoPaciente');
                divCDP1.innerHTML = '';
                divCDA = document.getElementById('FormAltaPaciente');
                divCDA.innerHTML = '';
                divCDN1 = document.getElementById('NuevoPaciente');
                divCDN1.innerHTML = '';
                divCDP = document.getElementById('ErrorAltaPaciente');
                divCDP.innerHTML = '';
                ajaxp1 = objetoAjax();
                ajaxp1.open("GET", "./operaciones/registro_afiliado.php?codos=" + xcodos + "&nrodoc=" + xnrodoc + "&nombre=" + xnombre + "&observac=" + xobservac + "&fechanac=" + xfechanac + "&depto=" + d + '&retiva=' + xretiva + '&aleatorio=' + aleatorio, true);
                ajaxp1.onreadystatechange = function () {
                    if (ajaxp1.readyState == 4) {
                        //mostrar resultados en esta capa
                        divCDN1.innerHTML = ajaxp.responseText
                    }
                    CargarDocumento(xcodos, xnrodoc);
                    document.getElementById('nrodoc').disabled = false;
                    document.getElementById('nombremedico').disabled = false;
                    document.getElementById('nombremedico').focus();

                }
                ajaxp1.send(null);
            }

            function ValidarNombreMedico(e, nombre) {
                e = (window.event) ? event : e;
                intKey = (e.keyCode) ? e.keyCode : e.charCode;
                if (intKey == 13) {
                    if (nombre.length > 0) {
                        // Nos adelantamos porque sino el evento no lo toma
                        document.getElementById('listMedicos').disabled = false;
                        CargarMedicos(nombre);
                        return true;
                    } else {
                        alert('El Nombre debe Incluir al Menos un caracter  ...!');
                        return false;
                    }
                    return true;
                }
            }

            function CargarMedicos(xnombre) {
                var aleatorio = Math.random();
                var codos = document.getElementById('listObsocial').value;
                divme = document.getElementById('ListadoMedicos');
                divproc = document.getElementById('proceso');
                divproc.innerHTML = '<i>Procesando</i>';
                ajaxme = objetoAjax();
                ajaxme.open("GET", "./operaciones/lista_medicos_ingordenes.php?codos=" + codos + "&nombre=" + xnombre + "&aleatorio=" + aleatorio);
                ajaxme.onreadystatechange = function () {
                    if (ajaxme.readyState == 4) {
                        //mostrar resultados en esta capa
                        divproc.innerHTML = '';
                        divme.innerHTML = ajaxme.responseText
                        if (document.getElementById('listMedicos').value == '-1') {
                            document.getElementById('listMedicos').disabled = true;
                            document.frmOrden.nombremedico.focus();
                        } else {
                            document.getElementById('listMedicos').disabled = false;
                            document.getElementById('listMedicos').focus();
                        }
                    }
                }
                ajaxme.send(null);
            }

            function ValidarNombreMedicoCabecera(e, nombre) {
                e = (window.event) ? event : e;
                intKey = (e.keyCode) ? e.keyCode : e.charCode;
                if (intKey == 13) {
                    if (nombre.length > 0) {
                        // Nos adelantamos porque sino el evento no lo toma
                        document.getElementById('listMedicosCabecera').disabled = false;
                        CargarMedicosCabecera(nombre);
                        return true;
                    } else {
                        alert('El Nombre debe Incluir al Menos un caracter  ...!');
                        return false;
                    }
                    return true;
                }
            }

            function CargarMedicosCabecera(xnombre) {
                var aleatorio = Math.random();
                var codos = document.getElementById('listObsocial').value;
                divme = document.getElementById('ListadoMedicosCabecera');
                divproc = document.getElementById('proceso');
                divproc.innerHTML = '<i>Procesando</i>';
                ajaxme = objetoAjax();
                ajaxme.open("GET", "./operaciones/lista_medicoscabecera_ingordenes.php?codos=" + codos + "&nombre=" + xnombre + "&aleatorio=" + aleatorio);
                ajaxme.onreadystatechange = function () {
                    if (ajaxme.readyState == 4) {
                        //mostrar resultados en esta capa
                        divproc.innerHTML = '';
                        divme.innerHTML = ajaxme.responseText;
                        if (document.getElementById('listMedicosCabecera').value == '-1') {
                            document.getElementById('listMedicosCabecera').disabled = true;
                            //document.getElementById('RegistrarOrdenAuditoria').disabled = true;
                            document.getElementById('medicocabecera').focus();
                        } else {
                            document.getElementById('listMedicosCabecera').disabled = false;
                            document.getElementById('listMedicosCabecera').focus();
                        }
                    }
                }
                ajaxme.send(null);
            }

            function MedicoCabeceraChange() {
                document.getElementById('RegistrarOrdenAuditoria').disabled = false;
                document.getElementById('RegistrarOrdenAuditoria').focus();
            }

            function ControlMedicoCabecera(e, nombre) {
                e = (window.event) ? event : e;
                intKey = (e.keyCode) ? e.keyCode : e.charCode;
                if (intKey == 13) {
                    if (nombre.length > 0) {
                        // Nos adelantamos porque sino el evento no lo toma
                        //document.getElementById('listMedicos').disabled=false;
                        MedicoCabeceraChange();
                        return true;
                    } else {
                        alert('El Médico Seleccionado es Incorrecto  ...!');
                        return false;
                    }
                    return true;
                }
            }

            function verificarPerfil(e) {
                e = (window.event) ? event : e;
                intKey = (e.keyCode) ? e.keyCode : e.charCode;
                if (intKey == 13) {
                    document.getElementById('fepedido').focus();
                    //document.frmOrden.fepedido.disabled = false;
                    //document.frmOrden.fepedido.focus();
                    return true;
                }
            }

            function Control1() {
                var nrodoc = document.getElementById('nrodoc').value;
                if (nrodoc == '') {
                    alert('Paciente Incorrecto ...!');
                    return false;
                }

                var idprof = document.getElementById('listMedicos').value;
                if (idprof.length == 0) {
                    alert('No hay Médico Seleccionado ...!');
                    return false;
                }

                document.getElementById('listObsocial').disabled = true;
                document.getElementById('listMedicos').disabled = true;
                document.getElementById('listPerfil').disabled = true;
                document.getElementById('listPerfil').disabled = true;
                document.frmOrden.nrodoc.disabled = true;
                document.frmOrden.nombremedico.disabled = true;
                document.frmOrden.fepedido.disabled = true;
                document.getElementById('track').disabled = true;

                return true;
            }

            function ValidarFechaPedido(e, xfecha) {
                e = (window.event) ? event : e;
                intKey = (e.keyCode) ? e.keyCode : e.charCode;
                if (intKey == 13) {
                    if (ValidarFecha(xfecha)) {
                        if (Control1()) {
                            CargarDeterminaciones();
                        }
                    }
                }
            }

            function CargarDeterminaciones() {
                var aleatorio = Math.random();
                divproc = document.getElementById('proceso');
                divproc.innerHTML = '<i>Procesando</i>';
                divDet = document.getElementById('Determinaciones');
                ajaxdet = objetoAjax();
                ajaxdet.open('GET', './operaciones/alta_determinaciones.php?aleatorio=' + aleatorio);
                ajaxdet.onreadystatechange = function () {
                    if (ajaxdet.readyState == 4) {
                        //mostrar resultados en esta capa
                        divproc.innerHTML = '';
                        divDet.innerHTML = ajaxdet.responseText
                    }
                    document.frmOrden.codigo.focus();
                }
                ajaxdet.send(null);
            }

            function ValidarCodigo(e, xcodigo, xcodigo1) {
                e = (window.event) ? event : e;
                intKey = (e.keyCode) ? e.keyCode : e.charCode;
                if (intKey == 13) {
                    if (xcodigo != '*') {
                        VerificarCodigo(xcodigo, xcodigo1);
                    } else {
                        FinalizarCodigos(xcodigo);
                    }
                    return true;
                }
            }

            function VerificarCodigo(xcodigo, xcodigo1) {
                if (xcodigo1 > '') {
                    if (xcodigo == xcodigo1) {
                        alert('El Código ' + xcodigo1 + ' se Registra Automaticámente ...!');
                        document.getElementById('codigo').value = '';
                        return false;
                    }
                }

                var cont = '';
                var aleatorio = Math.random();

                var oscod = document.getElementById('listObsocial').value;
                var nrodoc = document.getElementById('nrodoc').value;
                var usuario = document.frmOrden.usuario.value;

                divCod = document.getElementById('DescripNBU');
                divproc = document.getElementById('proceso');
                divproc.innerHTML = '<i>Procesando</i>';
                ajaxcod = objetoAjax();
                var codigos = ExtraerCodigos();
                ajaxcod.open('GET', './operaciones/verificar_codigo_nbu_io.php?codigo=' + xcodigo + '&codigos=' + codigos + '&aleatorio=' + aleatorio + '&codos=' + oscod + '&nrodoc=' + nrodoc + '&efector=' + usuario);
                ajaxcod.onreadystatechange = function () {
                    if (ajaxcod.readyState == 4) {
                        //mostrar resultados en esta capa
                        divproc.innerHTML = '';
                        contenido = ajaxcod.responseText;
                        divCod.innerHTML = contenido

                        var cont = contenido.substring(0, 10);
                        var cont1 = contenido.substring(0, 3);
                        if (cont.length > 0 & cont1 != '***') {  // En base al contenido del TAG se si el objeto existe o no
                            AddCodigo(xcodigo, contenido);
                        }
                    }

                    document.frmOrden.codigo.focus();
                }

                ajaxcod.send(null);
            }

            function AddCodigo(xcodigo, xdescrip) {
                var descrip = xdescrip; //.substr(0, 100);
                var lineas = 0; //contador de líneas
                var i = 0;
                var texto = document.frmOrden.salida.value;
                var largo = texto.length;

                for (i = 0; i < largo; i++) {
                    if (texto.charAt(i) == '\n') {
                        lineas = lineas + 1;
                    }
                }

                lineas = lineas + 1;

                var _tope = document.getElementById('a_tope').value;
                if (_tope != '0') {
                    if (lineas + 1 > _tope) {
                        alert('El Máximo de Prácticas permitidas es de ' + _tope + '.' + '\n' +
                                'Ingrese esta Orden en dos Tramos');
                        return;
                    }
                }

                var lin = '';
                if (lineas < 10) {
                    lin = '0' + lineas;
                } else {
                    lin = lineas;
                }

                var items = ' [' + lin + '] ';

                document.frmOrden.salida.value += xcodigo + items + descrip + '\r';
                document.frmOrden.codigo.value = '';
            }

            function ExtraerCodigos() {
                var lineas = 1; //contador de líneas
                var i = 0;
                var texto = document.frmOrden.salida.value;
                var linea = '';
                var j = 0;
                var largo = texto.length;

                var miArray = new Array();

                for (i = 0; i < largo; i++) {
                    if (texto.charAt(i) == '\n') {
                        lineas = lineas + 1;
                        if (linea != undefined) {
                            miArray[j] = linea;
                            linea = '';
                            j = j + 1;
                        }
                    } else {
                        linea = linea + texto.charAt(i);
                    }
                }

                var miCodigo = new Array();
                var c = '';

                // Aislamos los códigos y los unificamos
                var codigos = '';
                for (i = 0; i < lineas; i++) {
                    if (miArray[i] != undefined) {
                        c = miArray[i];
                        var s = c.substring(0, 6);
                        miCodigo[i] = s;
                        codigos = codigos + s;
                    }
                }

                return codigos;
            }

            function FinalizarCodigos(xsalida) {
                if (confirm('¿ Seguro para Finalizar el Ingreso de Determinaciones ?')) {

                    var codigos = ExtraerCodigos();

                    if (codigos.length > 0) {
                        var aleatorio = Math.random();
                        divproc = document.getElementById('proceso');
                        divproc.innerHTML = '<i>Procesando</i>';
                        divObs = document.getElementById('Observaciones');
                        divDet1 = document.getElementById('Determinaciones');
                        var codos = document.getElementById('listObsocial').value;
                        divDet1.innerHTML = '';
                        ajaxobs = objetoAjax();
                        ajaxobs.open('GET', './operaciones/alta_observaciones.php?codigos=' + codigos + '&codos=' + codos + '&aleatorio=' + aleatorio);
                        ajaxobs.onreadystatechange = function () {
                            if (ajaxobs.readyState == 4) {
                                //mostrar resultados en esta capa
                                divproc.innerHTML = '';
                                divObs.innerHTML = ajaxobs.responseText;
                            }
                            document.getElementById('RegistrarOrdenAuditoria').disabled = false;
                            document.getElementById('observacion').focus();
                        }

                        ajaxobs.send(null);

                    } else {
                        alert('No hay Determinaciones Ingresadas  ...!');
                        document.frmOrden.codigo.focus();
                    }
                }
            }

            function CancelarIngresoDeterminaciones() {
                if (confirm('Si Acepta esta Opción Perderá los Datos Ingresados en la Sección Actual.' + '\n' + '¿ Seguro para Proceder ?')) {
                    divDet = document.getElementById('Determinaciones');
                    divDet.innerHTML = '';
                    ajaxdet = objetoAjax();
                    ajaxdet.onreadystatechange = function () {
                        if (ajaxdet.readyState == 4) {
                            //mostrar resultados en esta capa
                            divdet.innerHTML = ajaxdet.responseText
                        }
                    }
                    document.getElementById('listObsocial').disabled = false;
                    document.getElementById('listMedicos').disabled = false;
                    document.getElementById('listPerfil').disabled = false;
                    document.frmOrden.nrodoc.disabled = false;
                    document.frmOrden.fepedido.disabled = false;
                    document.frmOrden.nombremedico.disabled = false;
                    document.frmOrden.nrodoc.focus();
                    document.getElementById('track').disabled = false;
                    ajaxdet.send(null);
                }
            }

            function FinalizarObservacion(e, codigos) {
                e = (window.event) ? event : e;
                intKey = (e.keyCode) ? e.keyCode : e.charCode;
                if (intKey == 13) {
                    RegistrarOrden(codigos);
                    return true;
                }
            }

            function FinalizarObservacionMC(e, codigos) {
                e = (window.event) ? event : e;
                intKey = (e.keyCode) ? e.keyCode : e.charCode;
                if (intKey == 13) {
                    document.getElementById('medicocabecera').disabled = false;
                    document.getElementById('medicocabecera').focus();
                    return true;
                }
            }

            function RegistrarOrden(xcodigos) {
                ArchivarOrden(xcodigos, '');
            }

            function RegistrarOrdenMC(xcodigos) {
                var mc = document.getElementById('listMedicosCabecera').value;
                ArchivarOrden(xcodigos, mc);
            }

            function ArchivarOrden(xcodigos, medicocab) {
                if (xcodigos != null) {
                    codigos = xcodigos;
                } else {
                    codigos = ExtraerCodigos();
                }
                if (document.getElementById('listMedicos').value == '-1') {
                    alert('El Médico es Incorrecto ...!');
                    return false;
                }

                var idprof = document.getElementById('listMedicos').value;
                if (idprof.length == 0) {
                    alert('No hay Médico Seleccionado ...!');
                    return false;
                }

                var aleatorio = Math.random();
                var codos = document.getElementById('listObsocial').value;
                var fecha = document.frmOrden.fecha.value;
                var nrodoc = document.frmOrden.nrodoc.value;
                var fecha1 = fecha;
                var f = fecha1.substr(6, 4) + fecha1.substr(3, 2) + fecha1.substr(0, 2);
                var nrotrans = document.frmOrden.usuario.value + f + LlenarIzquierda(allTrim(nrodoc), 9, '0') + document.frmOrden.nrotrans.value;

                // 26/08/2019 --------------------------------------------------       
                var _n = nrodoc;
                var _l = _n.length;

                if (_l > 9) {
                    var _t = _n.substr(_l - 9, _l);

                    nrotrans = document.frmOrden.usuario.value + f + _t + document.frmOrden.nrotrans.value;
                }
                //alert(nrotrans + ' ' + nrotrans.length); // + ' ' + _l);     
                //--------------------------------------------------------------        

                nrotrans = nrotrans.substring(0, 30);
                var usuario = document.frmOrden.usuario.value;
                var iddiag = document.getElementById('listPerfil').value;
                var observac = document.frmOrden.observacion.value;
                var idperfil = iddiag;
                var fepedido = document.frmOrden.fepedido.value;
                var diferida = 'N';

                if (confirm('¿ Seguro para Registrar Orden ' + document.frmOrden.nrotrans.value + ' en Paciente Documento Nro. ' + document.frmOrden.nrodoc.value + ' ?')) {
                    //document.getElementById('RegistrarOrdenAuditoria').disabled=true;
                    divMensajes = document.getElementById('MensajeReg');
                    ajax = objetoAjax();
                    //alert('./operaciones/insertar_orden_usuario.php?nrotrans='+nrotrans+'&efector='+usuario+'&fecha='+fecha+'&codos='+codos+'&idzona='+'00'+'&nrodoc='+nrodoc+'&idprof='+idprof+'&observacion='+observac+'&iddiag='+iddiag+'&codigos='+codigos+'&idperfil='+idperfil+'&fepedido='+fepedido+'&diferida='+diferida+'&medicocab='+medicocab+'&aleatorio='+aleatorio);
                    //if (document.getElementById('a_rpc').value == 'S') alert('./operaciones/insertar_orden_usuario.php?nrotrans='+nrotrans+'&efector='+usuario+'&fecha='+fecha+'&codos='+codos+'&idzona='+'00'+'&nrodoc='+nrodoc+'&idprof='+idprof+'&observacion='+observac+'&iddiag='+iddiag+'&codigos='+codigos+'&idperfil='+idperfil+'&fepedido='+fepedido+'&diferida='+diferida+'&medicocab='+medicocab+'&aleatorio='+aleatorio);
                    if (document.getElementById('a_rpc').value == 'S')
                        ajax.open('GET', './operaciones/insertar_orden_usuario_rpc.php?nrotrans=' + nrotrans + '&efector=' + usuario + '&fecha=' + fecha + '&codos=' + codos + '&idzona=' + '00' + '&nrodoc=' + nrodoc + '&idprof=' + idprof + '&observacion=' + observac + '&iddiag=' + iddiag + '&codigos=' + codigos + '&idperfil=' + idperfil + '&fepedido=' + fepedido + '&diferida=' + diferida + '&medicocab=' + medicocab + '&aleatorio=' + aleatorio);

                    if (document.getElementById('a_rpc').value != 'S')
                        ajax.open('GET', './operaciones/insertar_orden_usuario.php?nrotrans=' + nrotrans + '&efector=' + usuario + '&fecha=' + fecha + '&codos=' + codos + '&idzona=' + '00' + '&nrodoc=' + nrodoc + '&idprof=' + idprof + '&observacion=' + observac + '&iddiag=' + iddiag + '&codigos=' + codigos + '&idperfil=' + idperfil + '&fepedido=' + fepedido + '&diferida=' + diferida + '&medicocab=' + medicocab + '&aleatorio=' + aleatorio);


                    divMensajes.innerHTML = '<img src="anim.gif"> :: Espere un Momento, Registrando Orden ...';
                    ajax.onreadystatechange = function () {
                        if (ajax.readyState == 4) {
                            //mostrar resultados en esta capa
                            divproc.innerHTML = '';
                            divMensajes.innerHTML = ajax.responseText
                        }

                        //alert(divMensajes.innerHTML);

                        // 27/07/2020 -> TOKEN SWISS MEDICAL
                        var __tk = divMensajes.innerHTML.indexOf("REQUIERE TOKEN");
                        if (__tk > 0) {
                            token(nrotrans);
                            return;
                        } else {
                            ListarOrden(nrotrans);
                        }

                        //ListarOrden(nrotrans); // anulada 27/07/2020
                        //MostrarBotonesImpresion();

                        document.frmOrden.nrodoc.disabled = false;
                        document.getElementById('listObsocial').disabled = false;
                        document.getElementById('listPerfil').disabled = false;
                        document.getElementById('listMedicos').disabled = false;
                        document.frmOrden.fepedido.disabled = false;
                        document.frmOrden.observacion.disabled = false;
                        document.frmOrden.nombremedico.disabled = false;
                        document.getElementById('listPerfil').disabled = false;

                    }
                    ajax.send(null);
                }
            }

            function ListarOrden(nrotrans) {
                var aleatorio = Math.random();
                divListarOrden = document.getElementById('ListarOrden');
                divObs = document.getElementById('Observaciones');
                divNBU = document.getElementById('Determinaciones');
                divObs.innerHTML = '';
                divNBU.innerHTML = '';
                ajax3 = objetoAjax();
                ajax3.open('GET', './operaciones/listar_hoja_orden.php?nrotrans=' + nrotrans + '&aleatorio=' + aleatorio);
                ajax3.onreadystatechange = function () {
                    if (ajax3.readyState == 4) {
                        //mostrar resultados en esta capa
                        divListarOrden.innerHTML = ajax3.responseText
                    }
                    if (document.getElementById('__nroauditoria').value == '')
                        MostrarBotonesImpresion();
                }
                ajax3.send(null);
            }

            function MostrarBotonesImpresion() {
                var aleatorio = Math.random();
                divImprimir = document.getElementById('BotonesImpresion');
                ajax4 = objetoAjax();
                ajax4.open('GET', './operaciones/imprimir_orden.php?aleatorio=' + aleatorio);
                ajax4.onreadystatechange = function () {
                    if (ajax4.readyState == 4) {
                        //mostrar resultados en esta capa
                        divImprimir.innerHTML = ajax4.responseText
                    }
                    document.frmOrden.btnImpirmir.focus();
                }
                ajax4.send(null);
            }

            function ImprimirOrden() {
                var ficha = document.getElementById('ListarOrden');
                var ventimp = window.open(' ', 'popimpr');
                ventimp.document.write(ficha.innerHTML);
                ventimp.document.close();
                ventimp.print( );
                ventimp.close();

                CancelarImpresion();
            }

            function CancelarImpresion() {
                var aleatorio = Math.random();
                var divControl = document.getElementById('controlcont');
                var codos = document.getElementById('listObsocial').value;
                var ajaxc = objetoAjax();
                ajaxc.open('GET', './operaciones/determinar_tipo_ingreso_orden.php?codos=' + codos + '&aleatorio=' + aleatorio);
                ajaxc.onreadystatechange = function () {
                    if (ajaxc.readyState == 4) {
                        //mostrar resultados en esta capa
                        divControl.innerHTML = ajaxc.responseText
                    }
                    if (divControl.innerHTML > '') {
                        document.getElementById("r_carga").value = divControl.innerHTML;
                        Reiniciar(divControl.innerHTML);
                    }

                }

                ajaxc.send(null);

            }

            function Reiniciar(tipo_reinicio) {
                if (tipo_reinicio.substr(0, 1) == 'R') {
                    // Vuelve al menu
                    var aleatorio = Math.random();
                    link = '?p111989=' + document.getElementById('usuario').value + '&susuaurio=' + document.getElementById('usuario').value + '&reinit=' + aleatorio;
                    var host = location.protocol + '//' + location.hostname + '/menu_usuario.php' + link;
                    window.location.href = host;
                }
                if (tipo_reinicio.substr(0, 1) == 'I') {
                    // Reinicia el Form
                    var aleatorio = '"' + Math.random() + '"';
                    var tr = aleatorio.substr(5, 6);
                    document.getElementById('nrotrans').value = tr;
                    var ajaxini = objetoAjax();
                    document.getElementById('nrodoc').value = '';
                    var divn = document.getElementById('CargarDocumentoPaciente');
                    var lis = document.getElementById('ListarOrden');
                    var bt = document.getElementById('BotonesImpresion');
                    var ct = document.getElementById('controlcont');
                    var mr = document.getElementById('MensajeReg');
                    var re = document.getElementById('Registrar');
                    var ob = document.getElementById('Observaciones');

                    divn.innerHTML = '';
                    lis.innerHTML = '';
                    bt.innerHTML = '';
                    ct.innerHTML = '';
                    mr.innerHTML = '';
                    re.innerHTML = '';
                    ob.innerHTML = '';

                    document.getElementById("r_carga").value = '';
                    document.getElementById('nombremedico').value = '';
                    document.getElementById('fepedido').value = '';

                    ajaxini.onreadystatechange = function () {
                        if (ajaxini.readyState == 4) {
                        }
                    }

                    //document.getElementById('listObsocial').disabled = false;
                    //document.getElementById('nrodoc').disabled = false;
                    //document.getElementById('nrodoc').focus();

                    document.frmOrden.nrodoc.disabled = false;
                    document.getElementById('listObsocial').disabled = false;
                    document.getElementById('listPerfil').disabled = false;
                    document.getElementById('listMedicos').disabled = false;
                    document.getElementById('fepedido').disabled = false;
                    document.getElementById('nombremedico').disabled = false;
                    document.getElementById('nrodoc').focus();

                    //document.frmOrden.fepedido.disabled = false;
                    //document.frmOrden.observacion.disabled = false;
                    //document.frmOrden.nombremedico.disabled = false;
                    //document.getElementById('listPerfil').disabled = false;
                    //document.getElementById('nrodoc').focus();

                    ajaxini.send(null);
                }

            }

            function DarDeBajaCodigo() {
                //donde se mostrar los registros
                var aleatorio = Math.random();
                divContenido = document.getElementById('BajaCodigo');

                ajax = objetoAjax();
                //uso del medoto GET
                //indicamos el archivo que realizar� el proceso de paginar
                //junto con un valor que representa el nro de pagina
                ajax.open('GET', './operaciones/BajaItemsNBU.php?aleatorio=' + aleatorio);
                ajax.onreadystatechange = function () {
                    if (ajax.readyState == 4) {
                        //mostrar resultados en esta capa
                        divContenido.innerHTML = ajax.responseText
                    }
                    document.getElementById('itemsbaja').focus();
                }

                ajax.send(null)
            }

            function BajaItems(e, xitems) {
                e = (window.event) ? event : e;
                intKey = (e.keyCode) ? e.keyCode : e.charCode;
                if (intKey == 13) {
                    if (xitems.length < 2) {
                        alert('El Items tiene Mínimo dos Dígitos  ...!');
                        return false;
                    } else {
                        if (confirm('¿ Seguro para dar de Baja Items ' + xitems + ' ?')) {
                            DarBajaItems(xitems);
                        } else {
                            AnularBajaItems();
                        }
                    }
                }
            }

            function AnularBajaItems() {
                //donde se mostrar los registros
                var aleatorio = Math.random();
                divContenido = document.getElementById('BajaCodigo');
                divContenido.innerHTML = '';
                ajax = objetoAjax();
                ajax.onreadystatechange = function () {
                    if (ajax.readyState == 4) {
                        //mostrar resultados en esta capa
                        divContenido.innerHTML = ajax.responseText
                    }
                    document.frmOrden.codigo.focus();
                }

                ajax.send(null)
            }

            function DarBajaItems(xitems) {
                var lineas = 1; //contador de líneas
                var i = 0;
                var texto = document.frmOrden.salida.value;
                var linea = '';
                var j = 0;
                var largo = texto.length;

                var miArray = new Array();

                for (i = 0; i < largo; i++) {
                    if (texto.charAt(i) == '\n') {
                        lineas = lineas + 1;
                        if (linea != undefined) {
                            miArray[j] = linea;
                            linea = '';
                            j = j + 1;
                        }
                    } else {
                        linea = linea + texto.charAt(i);
                    }
                }

                var miCodigo = new Array();
                var miLinea = new Array();
                var c = '';

                // Aislamos los códigos y los unificamos
                var codigos = '';
                for (i = 0; i < lineas; i++) {
                    if (miArray[i] != undefined) {
                        c = miArray[i];
                        var s = c.substring(0, 6);
                        miCodigo[i] = s;
                        codigos = codigos + s;
                    }
                }

                // Damos de Baja el Items
                var x = 0;
                var ind = 0;
                for (i = 0; i < lineas; i++) {
                    if (miArray[i] != undefined) {
                        c = miArray[i];
                        var s = c.substring(7, 11);

                        if (xitems.length < 2) {
                            var s1 = '[0' + xitems + ']';
                        } else {
                            var s1 = '[' + xitems + ']';
                        }

                        if (s != s1) {
                            ind = ind + 1;
                            var s2 = '[' + ind + ']';
                            if (ind < 10) {
                                var s2 = '[0' + ind + ']';
                            }
                        }

                        if (s != s1) {
                            x = x + 1;
                            var s = c.substring(0, 6);
                            var t = c.substring(12, 30);
                            miLinea[x] = s + ' ' + s2 + ' ' + t;
                        }

                    }

                }

                document.frmOrden.salida.value = '';

                for (i = 0; i <= x; i++) {
                    if (miLinea[i] != undefined) {
                        document.frmOrden.salida.value += miLinea[i] + '\n';
                    }
                }

                AnularBajaItems();
            }

            function ControlMedico(e) {
                e = (window.event) ? event : e;
                intKey = (e.keyCode) ? e.keyCode : e.charCode;

                if (intKey == 13) {
                    document.getElementById('listPerfil').focus();
                    return true;
                }
            }

            function ValidarNombre(e, xnombre) {
                e = (window.event) ? event : e;
                intKey = (e.keyCode) ? e.keyCode : e.charCode;
                if (intKey == 13) {
                    if (xnombre.length == 0) {
                        alert('Nombre Incorrecto ...!');
                        return false;
                    } else {
                        return true;
                    }
                }
            }

            function ValidarObservac(e, xobservac) {
                e = (window.event) ? event : e;
                intKey = (e.keyCode) ? e.keyCode : e.charCode;
                if (intKey == 13) {
                    if (xobservac.length == 0) {
                        return true;
                    } else {
                        return true;
                    }
                }
            }

            function ValidarFechanac(e, xfenac) {
                e = (window.event) ? event : e;
                intKey = (e.keyCode) ? e.keyCode : e.charCode;
                if (intKey == 13) {
                    if (xfenac.length > 0) {
                        return ValidarFecha(xfenac);
                    } else {
                        return true;
                    }
                }
            }

            function ValidarDepto(e, xdepto) {
                e = (window.event) ? event : e;
                intKey = (e.keyCode) ? e.keyCode : e.charCode;
                if (intKey == 13) {
                    if (xdepto.length > 0) {
                        var d = xdepto.substring(0, 1).toUpperCase();
                        document.frmOrden.depto.value = d;
                        if (d == 'S' || d == 'N') {
                            return true;
                        } else {
                            alert('Las Opciones son S ó N ...!');
                            return false;
                        }
                    } else {
                        alert('Las Opciones son S ó N ...!');
                        return false;
                    }
                }
            }

            function BuscarContextualmenteNBU() {
                divBuscar = document.getElementById('buscar');
                ajax = objetoAjax();
                ajax.open('GET', './operaciones/buscar.php');
                divBuscar.innerHTML = '<img src="anim.gif">';
                ajax.onreadystatechange = function () {
                    if (ajax.readyState == 4) {
                        //mostrar resultados en esta capa
                        divBuscar.innerHTML = ajax.responseText
                    }
                    document.getElementById("buscarvalor").focus();
                }

                ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                //enviando los valores
                ajax.send(null);
            }

            function ProcederBuscar(valor) {
                Pagina(1, valor, 'T');
                divBuscar = document.getElementById('buscar');
                divBuscar.innerHTML = '';
            }

            function BuscarValor(e, valor) {
                e = (window.event) ? event : e;
                intKey = (e.keyCode) ? e.keyCode : e.charCode;
                if (intKey == 13) {
                    ProcederBuscar(valor);
                    return true;
                }
            }

            function QuitarFiltroNBU() {
                Pagina(1, '', 'T');
                divBuscar = document.getElementById('buscar');
                divBuscar.innerHTML = '';
            }

            function CerrarBusquedaNBU() {
                divBuscar = document.getElementById('BuscarDeterminacion');
                ajax = objetoAjax();
                divBuscar.innerHTML = '';
                ajax.onreadystatechange = function () {
                    if (ajax.readyState == 4) {
                        //mostrar resultados en esta capa
                        divBuscar.innerHTML = ajax.responseText
                    }
                }

                ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                //enviando los valores
                ajax.send(null);
            }

            function CargarDiagnosticos() {
                var aleatorio = Math.random();
                var codos = document.getElementById('listObsocial').value;
                divCargarDiagnostico = document.getElementById('dx');
                ajax = objetoAjax();
                ajax.open('GET', './operaciones/lista_perfiles_ingordenes.php?codos=' + codos + '&aleatorio' + aleatorio);
                ajax.onreadystatechange = function () {
                    if (ajax.readyState == 4) {
                        //mostrar resultados en esta capa
                        divCargarDiagnostico.innerHTML = ajax.responseText
                    }
                }

                ajax.send(null);
            }

            function CargarDx() {
                CargarDiagnosticos();
                document.getElementById('nrodoc').focus();
            }

            // Consultar Paciente por Nombre

            function Consultar_Documento() {
                var aleatorio = Math.random();
                if (document.getElementById('nrodoc').disabled == false) {
                    hidediv("carga");
                    divBuscar = document.getElementById('buscar');
                    ajaxb = objetoAjax();
                    var leyenda = "Apellido/Nombre Af.:";
                    ajaxb.open('GET', './operaciones/buscar.php?leyenda=' + leyenda + '&aleatorio=' + aleatorio);
                    divBuscar.innerHTML = '<img src="anim.gif"> :: Procesando ...';
                    ajaxb.onreadystatechange = function () {
                        if (ajaxb.readyState == 4) {
                            //mostrar resultados en esta capa
                            divBuscar.innerHTML = ajaxb.responseText;
                        }
                        document.getElementById('buscarvalor').focus();
                    }

                    ajaxb.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    //enviando los valores
                    ajaxb.send(null);
                } else {
                    alert('Esta Opción de Consulta está Disponible solo durante el Ingreso de los Datos de Cabecera ...!');
                }
            }

            function BuscarValor(e, xbuscar) {
                e = (window.event) ? event : e;
                intKey = (e.keyCode) ? e.keyCode : e.charCode;
                if (intKey == 13) {
                    if (xbuscar.length >= 0) {
                        var codos = document.getElementById('listObsocial').value;
                        PagAfiliado(1, xbuscar, codos, 'S');
                    }
                }
            }

            function ProcederBuscar(xbuscar) {
                if (xbuscar.length > 0) {
                    var codos = document.getElementById('listObsocial').value;
                    PagAfiliado(1, xbuscar, codos, '');
                } else {
                    alert('Debe Introducir un Valor a Buscar ...!');
                    document.getElementById('buscarvalor').focus();
                }
            }

            function PagAfiliado(nropagina, filtro, codos, edbaja, falter) {
                var aleatorio = Math.random();
                var divBuscar = document.getElementById('listar');
                var tipofiltro = 1;
                var ajax = objetoAjax();
                ajax.open('GET', './operaciones/lista_afiliados.php?pag=' + nropagina + '&filtro=' + filtro + '&edbaja=' + edbaja + '&codos=' + codos + '&aleatorio=' + aleatorio + '&tipofiltro=' + tipofiltro);
                divBuscar.innerHTML = '<img src="anim.gif"> :: Procesando ...';
                ajax.onreadystatechange = function () {
                    if (ajax.readyState == 4) {
                        //mostrar resultados en esta capa
                        divBuscar.innerHTML = ajax.responseText;
                    }
                }
                ajax.send(null)
            }

            function CancelarBusqueda() {
                ajax = objetoAjax();
                divBuscar = document.getElementById('buscar');
                divBuscar.innerHTML = '';
                divListar = document.getElementById('listar');
                divListar.innerHTML = '';
                showdiv("carga");
                document.getElementById('nrodoc').focus();
                ajax.send(null);
            }

            function SelectRegistro(nrodoc) {
                showdiv("carga");
                var codos = document.getElementById('listObsocial').value;
                document.getElementById('nrodoc').value = nrodoc;
                CargarDocumento(codos, nrodoc);
                divBuscar = document.getElementById('buscar');
                divBuscar.innerHTML = '';
                divListar = document.getElementById('listar');
                divListar.innerHTML = '';
                document.getElementById('nombremedico').disabled = false;
                document.getElementById('nomnremedico').focus();
            }

            function BuscarCodigoNBU() {
                var aleatorio = Math.random();
                hidediv("Determinaciones");
                var divBuscar = document.getElementById('BuscarDeterminacion');
                var ajaxp = objetoAjax();
                var leyenda = "Ing. Descrip. Practica:";
                ajaxp.open('GET', './operaciones/buscar1.php?leyenda=' + leyenda + '&aleatorio=' + aleatorio);
                ajaxp.onreadystatechange = function () {
                    if (ajaxp.readyState == 4) {
                        divBuscar.innerHTML = ajaxp.responseText;
                    }
                    document.getElementById('buscarvalor').focus();
                }
                ajaxp.send(null)
            }

            function CancelarBusqueda1() {
                ajax = objetoAjax();
                divBuscar = document.getElementById('BuscarDeterminacion');
                divBuscar.innerHTML = '';
                divListar = document.getElementById('listar');
                divListar.innerHTML = '';
                showdiv("Determinaciones");
                document.getElementById('codigo').focus();
                ajax.send(null);
            }

            function BuscarValor1(e) {
                e = (window.event) ? event : e;
                intKey = (e.keyCode) ? e.keyCode : e.charCode;
                if (intKey == 13) {
                    ProcederBuscar1();
                }
            }

            function ProcederBuscar1() {
                var cod = document.getElementById('buscarvalor').value;
                if (cod > '') {
                    PaginaNBU(1, cod, 'N');
                } else {
                    alert('Debe Introducir un Valor a Buscar ...!');
                }
            }

            function CodigoNBUSel(xcodigo) {
                showdiv("Determinaciones");
                document.frmOrden.codigo.value = xcodigo;
                VerificarCodigo(xcodigo);
                var divBuscarDeterminacion = document.getElementById('BuscarDeterminacion');
                var divLista = document.getElementById('lista');
                divLista.innerHTML = '';
                ajax7 = objetoAjax();
                divBuscarDeterminacion.innerHTML = '';
                ajax7.onreadystatechange = function () {
                    if (ajax7.readyState == 4) {
                        //mostrar resultados en esta capa
                        divBuscarDeterminacion.innerHTML = ajax7.responseText
                    }
                    VerificarCodigo(xcodigo);
                }
                ajax7.send(null);
            }

            function PaginaNBU(nropagina, filtro, edbaja) {
                //donde se mostrar los registros
                var aleatorio = Math.random();
                divContenido = document.getElementById('lista');

                ajax = objetoAjax();
                //uso del medoto GET
                //indicamos el archivo que realizar� el proceso de paginar
                //junto con un valor que representa el nro de pagina
                ajax.open('GET', './operaciones/paginador_nbu_sel.php?pag=' + nropagina + '&filtro=' + filtro + '&edbaja=' + edbaja + '&aleatorio=' + aleatorio);
                divContenido.innerHTML = '<img src="anim.gif">';
                ajax.onreadystatechange = function () {
                    if (ajax.readyState == 4) {
                        //mostrar resultados en esta capa
                        divContenido.innerHTML = ajax.responseText
                    }
                }
                ajax.send(null)
            }

            //----------------------------------------------------------------

            function token(nroauditoria) {
                document.getElementById('ListarOrden').style.display = 'hide';
                document.getElementById('__nroauditoria').value = nroauditoria;
                document.getElementById('divtoken').style.display = 'block';
                document.getElementById('__token').focus();
            }

            function validarToken() {
                var _n = document.getElementById('__nroauditoria').value
                var _t = document.getElementById('__token').value;

                if (_t == '') {
                    alert('TOKEN INCORRECTO ...!');
                    return;
                }
                
                var divproc = document.getElementById('divproccesstoken');
                divproc.innerHTML = '<i><img src="anim.gif"> :: Validando TOKEN ...</i>';

                // Invocamos RPC
                var aleatorio = Math.random();
                var ajax = objetoAjax();
                var res = '';
                ajax.open('GET', './operaciones/validar_orden_token_rpc.php?nroauditoria=' + _n + '&token=' + _t + '&aleatorio=' + aleatorio, false);
                ajax.onreadystatechange = function () {
                    if (ajax.readyState == 4) {
                        divproc.innerHTML = '';
                        //mostrar resultados en esta capa
                        res = ajax.responseText.trim();
                        
                        var n = res.search("Ok");
                                                
                        if (n < 0)
                            alert(res);
                        if (n === 0) {
                            ListarOrden(_n);
                            document.getElementById('divtoken').style.display = 'hide';
                            document.getElementById('divtoken').innerHTML = '';
                            MostrarBotonesImpresion();
                        }
                    }


                }

                ajax.send(null);
            }


        </script>

        <link rel="stylesheet" type="text/css" href="/css1.css" />
    </head>

    <!--
        Fin de la la Secuencia JavaScript
    --------------------------------------------------------------------
    -->

    <body>

        <form name="frmOrden" method="post" onsubmit="return false">

            <div style="margin:auto;width:550px;text-align:center;">

                <?php include('menu_usuario.php'); ?>

                <div id="Menu">
                    <?php
                    echo '<td width="54"><input name="usuario" type="hidden" id="usuario" value="' . $usuario . '" /></td>';
                    ?>
                </div>

                <div id="Mensajes">
                </div>

                <table width="550px" border="0" align="center">
                    <tr>
                    </tr>
                    <td width="120px" align="left">Ingreso de Ordenes</td>
                    <td width="430px"><hr></td>
                    <tr>
                </table>

                <div id="buscar"></div>
                <div id="listar"></div>

                <div id="carga">
                    <table width="500px" border="0" align="center">
                        <tr>
                            <td width="67px" align="right">Obra Social:</td>
                            <td align="left">
                                <div id="obssociales">                                   
                                </div>                    
                            </td>

                            <td width="85px" align="left">
                                <div id="divdoc">
                                    <a href="javascript://" onclick="Consultar_Documento()">Cons.Documento</a>
                                </div>
                                <td/>
                        </tr>
                    </table>
                    <table width="500px" border="0" align="center">
                        <tr>
                            <td width="67px" align="right">Afiliado:</td>
                            <td width="80px" align="left"><input name="nrodoc" id="nrodoc" type="text" style="font-size: 10px;" maxlength="25" width="4" size="18" onkeypress="javascript: if (ValidarAfiliado(event, listObsocial.value, nrodoc.value)) {
                                        listMedicos.focus();
                                        return true;
                                    }" />
                                <td align="left">
                                    <div id="CargarDocumentoPaciente" align="left">
                                    </div>
                                </td>
                        </tr>
                    </table>
                    <div id='divtrack'>
                        <table width="500px" border="0" align="center">
                            <tr>
                                <td width="67px" align="right">Track:</td>
                                <td width="80px" align="left"><input name="track" id="track" type="text" style="font-size: 10px;" maxlength="25" width="4" size="18" onkeypress="javascript: if (ValidarTrack(event, listObsocial.value, nrodoc.value, track.value)) {
                                            listMedicos.focus();
                                            return true;
                                        }" />
                                    <td align="left">                            
                                    </td>
                            </tr>
                        </table>
                    </div>
                    </table>
                    <table width="500px" border="0" align="center">
                        <tr>
                            <td width="67px" align="right">Médico:</td>
                            <td align="left">
                                <input name="nombremedico" id="nombremedico" type="text" style="font-size: 10px;" maxlength="40" width="15" size="44" onkeypress="javascript: if (ValidarNombreMedico(event, nombremedico.value)) {
                                            listMedicos.focus();
                                            return true;
                                        }" />
                            </td>
                            <td align="right">
                                Nro. Trans: 
                            </td>
                            <td width="70px" align="left">
                                <?
                                echo '<input name="nrotrans" type="text" id="nrotrans" style="font-size: 10px;" tabindex="2" size="10" maxlength="10" disabled="true" readonly="true" value = "' . $trans . '"/>';
                                ?>
                            </td>
                        </tr>
                    </table>
                    <table width="500px" border="0" align="center">
                        <tr>
                            <td width="66px" align="right">Sel. Médico:</td>
                            <td align="left">
                                <div id="ListadoMedicos">
                                    <?php include('operaciones/lista_medicos_ingordenes1.php') ?>
                                </div>
                                <td align="right">Fecha:
                                    <td width="70px" align="left">
                                        <?
                                        echo '<input name="fecha" type="text" id="fecha" disabled="true" style="font-size: 10px;" tabindex="2" size="10" maxlength="10" readonly="true" value="' . $u->getFechaActual() . '"/>';
                                        echo '<input name="r_carga" type="hidden" disabled="disabled" id="r_carga" style="font-size: 10px;" size="1" maxlength="1" readonly="true" /></td>';
                                        ?>  
                                    </td>
                                    </tr>
                                    </table>
                                    <table width="500px" border="0" align="center">
                                        <tr>
                                            <td width="66x" align="right">Diagnóstico:</td>
                                            <td align="left">
                                                <div id="dx">
                                                </div>
                                            </td>

                                            <td align="right">Fecha Pedido: </td>
                                            <td width="70px" align="left">
                                                <input name="fepedido" id="fepedido" type="text" style="font-size: 10px;" maxlength="12" size="10" 
                                                       onkeypress="javascript: if (ValidarFechaPedido(event, fepedido.value)) {
                                                                   fepedido.focus();
                                                                   return true;
                                                               }"  />
                                            </td>
                                        </tr>
                                    </table>

                                    </div>

                                    <hr />

                                    <div id="NuevoPaciente" align="center">
                                    </div>

                                    <div id="Observaciones" align="left">
                                    </div>

                                    <div id="BuscarDeterminacion" align="left">
                                    </div>

                                    <div id="lista" align="left">
                                    </div>

                                    <div id="Determinaciones" align="left">
                                    </div>

                                    <div id="Registrar">
                                    </div>

                                    <div id="MensajeReg">
                                    </div>

                                    <div id="ListarOrden" align="left">
                                    </div>

                                    <div id="ErrorAltaPaciente">
                                    </div>

                                    <div id="FormAltaPaciente">
                                    </div>

                                    <div id="BotonesImpresion" align="center">
                                    </div>

                                    <div id="controlcont" align="center">
                                    </div>

                                    <div id="proceso"></div>

                                    <div id="obssocialesrpc">    
                                    </div>

                                    <input type="hidden" name="a_rpc" id="a_rpc" />
                                    <input type="hidden" name="a_tope" id="a_tope" />
                                    <input type="hidden" name="a_track" id="a_track" />

                                    <div id="divtoken">
                                        <br/>
                                        <input type="hidden" id="__nroauditoria" />
                                        Token:
                                        <input type="text" id="__token" /> 
                                        &nbsp;&nbsp;
                                        <input type="button" value="Validar" onclick="validarToken()" />
                                    </div>
                                    <div id="divproccesstoken"></div>

                                    <script>
                                        loadObrasSociales();
                                        Init();
                                        document.getElementById('divtoken').style.display = 'none';
                                    </script>

                                    </form>

                                    </body>

                                    </html>