<?
session_start();

$usuario = $_SESSION['susuario'];
$pass = $_SESSION['spass'];
$nombre = $_SESSION['snombre'];
$auditor1 = $_SESSION['auditor1'];
$auditor2 = $_SESSION['auditor2'];
$nauditor1 = $_SESSION['nauditor1'];
$nauditor2 = $_SESSION['nauditor2'];
$efector = $_SESSION['efector'];

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cUtiles.php");
$u = new cUtiles;
?>

<html xmlns="http://www.w3.org/1999/xhtml">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <head><title>Auditoría de Ordenes</title>

        <script src="../jscript/funciones.js"></script>
        <script src="../jscript/funcionajax.js"></script>
        <script src="../jscript/showhidediv.js"></script>
        <script>

            function init() {
                document.getElementById('desde').focus();                
            }

            function ValidarFechaDesde(e, xfenac) {
                e = (window.event) ? event : e;
                intKey = (e.keyCode) ? e.keyCode : e.charCode;
                if (intKey == 13) {
                    return ValidarFecha(xfenac);
                }
            }

            function ValidarFechaHasta(e, xfenac) {
                e = (window.event) ? event : e;
                intKey = (e.keyCode) ? e.keyCode : e.charCode;
                if (intKey == 13) {
                    if (ValidarFecha(xfenac)) {
                        ConsultarOrdenesPendientes();
                        return true;
                    }
                }
            }
            function ConsultarOrdenes() {
                var m = document.getElementById("modo");
                m.value = 2;
                Pagina(1);
            }

            function ConsultarOrdenesPendientes() {
                var m = document.getElementById("modo");
                m.value = 1;
                Pagina(1);
            }

            function ConsultarOrdenesStandBy() {
                var m = document.getElementById("modo");
                m.value = 5;
                Pagina(1);
            }

            function Pagina(nropagina) {
                var modo = document.getElementById("modo").value;
                var codos = document.getElementById("listObsocial").value;
                var nrodoc = document.getElementById("nrodoc").value;
                divBuscar = document.getElementById('Buscar');
                divBuscar.innerHTML = '';

                //donde se mostrar los registros
                var aleatorio = Math.random();
                var desde = document.getElementById('desde').value;
                var hasta = document.getElementById('hasta').value;

                if (ValidarFecha(desde)) {
                } else {
                    document.getElementById('desde').focus();
                    return false;
                }

                if (ValidarFecha(hasta)) {
                } else {
                    document.getElementById('desde').focus();
                    return false;
                }

                var idprof = '000000';
                if (document.getElementById('listEfectores').value != '000000') {
                    var idprof = document.getElementById('listEfectores').value;
                }
                if (ValidarFecha(desde)) {
                } else {
                    return false;
                }
                if (ValidarFecha(hasta)) {
                } else {
                    return false;
                }

                // todos
                var efectorexcluir = '';
                var efectorincluir = '';

                if (document.getElementById('_auditor1') != null) {
                    // Filtros de acuerdo al auditor
                    if (document.getElementById('_auditor1').value != ' ' && document.getElementById('_efector').value != ' ') {
                        if (document.getElementById('_auditor1').value == document.getElementById('_efector').value)
                            efectorexcluir = document.getElementById('_auditor1').value;

                        if (document.getElementById('_auditor2').value == document.getElementById('_efector').value)
                            efectorincluir = document.getElementById('_auditor1').value;

                        if (document.getElementById('_efector').value == 'administ') {
                            efectorexcluir = '';
                            efectorincluir = '';
                        }
                    }
                }

                divLista = document.getElementById('Lista');
                divLista1 = document.getElementById('ListarOrden');
                divLista1.innerHTML = '';
                divLista3 = document.getElementById('ObservacionAuditor');
                divLista3.innerHTML = '';
                
                comboObrasSociales(desde, hasta, codos);

                var ajax = objetoAjax();
                ajax.open('GET', './operaciones/listar_ordenes_para_auditar.php?pag=' + nropagina + '&idprof=' + idprof + '&desde=' + desde + '&hasta=' + hasta + '&aleatorio=' + aleatorio + '&modo=' + modo + '&codos=' + codos + '&nrodoc=' + nrodoc + '&efector_excluir=' + efectorexcluir + '&efector_incluir=' + efectorincluir);
                divLista.innerHTML = '<img src="anim.gif"> :: Procesando, espere un momento ...';
                ajax.onreadystatechange = function () {
                    if (ajax.readyState == 4) {
                        //mostrar resultados en esta capa
                        divLista.innerHTML = ajax.responseText
                    }                    
                    
                    MostrarTotales();
                }
                ajax.send(null)
            }
            
            function comboObrasSociales(desde, hasta, codos) {
               
                var aleatorio = Math.random();
                var divListaOs = document.getElementById('listaobrassociales');
               
                var ajaxc = objetoAjax();
                ajaxc.open('GET', './operaciones/lista_os_auditarordenes_srn_ajax.php?desde=' + desde + '&hasta=' + hasta + '&codos=' + codos +  '&aleatorio=' + aleatorio);
                //alert('./operaciones/lista_os_auditarordenes_srn_ajax.php?desde=' + desde + '&hasta=' + hasta + '&codos=' + codos +  '&aleatorio=' + aleatorio);
                ajaxc.onreadystatechange = function () {
                    if (ajaxc.readyState == 4) {
                        //mostrar resultados en esta capa                        
                        divListaOs.innerHTML = ajaxc.responseText
                    }
                    
                }
                ajaxc.send(null) 
            }

            function MostrarOrden(xnroauditoria, xcodos, xnrodoc) {
                //donde se mostrar los registros
                var aleatorio = Math.random();
                divVerOrden = document.getElementById('ListarOrden');
                divEstado = document.getElementById('Estado');
                divEstado.innerHTML = '<img src="anim.gif">' + '  :: Procesando Petición en el Servidor. Espere un momento ...';

                hidediv("Lista");
                hidediv("auditores");
                hidediv("ListarTotales");
                hidediv("parametros");

                divBuscar = document.getElementById('Buscar');
                divBuscar.innerHTML = '';

                var navegador = 'otros';
                if (verificarIE()) {
                    navegador = 'ie';
                }

                ajax3 = objetoAjax();
                ajax3.open('GET', './operaciones/historiaclinica_paciente.php?nrotrans=' + xnroauditoria + '&codos=' + xcodos + '&nrodoc=' + xnrodoc + '&aleatorio=' + aleatorio + '&navegador=' + navegador);
                ajax3.onreadystatechange = function () {
                    if (ajax3.readyState == 4) {
                        //mostrar resultados en esta capa
                        divVerOrden.innerHTML = ajax3.responseText;
                        divEstado.innerHTML = '';
                        UnidadesNbu();
                    }                          
                }

                ajax3.send(null)
            }

            function ListarObservacionAuditor(xnroauditoria) {
                //donde se mostrar los registros
                var aleatorio = Math.random();
                divVerObs = document.getElementById('ObservacionAuditor');

                ajax9 = objetoAjax();
                //uso del medoto GET
                //indicamos el archivo que realizar� el proceso de paginar
                //junto con un valor que representa el nro de pagina
                ajax9.open('GET', './operaciones/listar_hoja_orden_obsauditor.php?nrotrans=' + xnroauditoria + '&aleatorio=' + aleatorio);
                ajax9.onreadystatechange = function () {
                    if (ajax9.readyState == 4) {
                        //mostrar resultados en esta capa
                        divVerObs.innerHTML = ajax9.responseText
                    }
                }
                ajax9.send(null)
            }

            function CerrarListaOrdenes() {
                //donde se mostrar los registros               
                divVerOrden = document.getElementById('ListarOrden');
                divVerOrden.innerHTML = '';
                divBt = document.getElementById('BotonesImpresion');
                divBt.innerHTML = '';
                divList = document.getElementById('Lista');
                divList.innerHTML = '';

                showdiv("parametros");
                showdiv("ListarTotales");
                showdiv("Lista");
                showdiv("auditores");

                ajax6 = objetoAjax();
                ajax6.onreadystatechange = function () {
                    if (ajax6.readyState == 4) {
                        //mostrar resultados en esta capa
                        divVerOrden.innerHTML = ajax6.responseText
                    }
                }
                ajax6.send(null)
            }

            function Rechazar() {
                obj = document.getElementById('autorizadas');

                var pas1 = 0;
                for (j = 0; opt = obj.options[j]; j++) {
                    pas1++;
                }

                if (obj.selectedIndex == -1) {
                    return;
                }

                for (i = 0; opt = obj.options[i]; i++)
                    if (opt.selected) {
                        valor = opt.value; // almacenar value
                        txt = obj.options[i].text; // almacenar el texto
                        obj.options[i] = null; // borrar el item si está seleccionado
                        obj2 = document.getElementById('rechazadas');
                        if (obj2.options[0].value == '-') // si solo está la opción inicial borrarla
                            obj2.options[0] = null;
                        opc = new Option(txt, valor);
                        eval(obj2.options[obj2.options.length] = opc);
                    }

                if (pas1 == 1) {
                    //obj.options[0].text='';
                    obj.options[0] = null;
                    opc1 = new Option('-', '-');
                    eval(obj.options[0] = opc1);
                }

                document.frmConsultaDiferida.autorizadas.focus();
                obj.selectedIndex = 0;
                
                UnidadesNbu();
            }

            function Autorizar() {
                obj = document.frmConsultaDiferida.rechazadas;

                var pas = 0;
                for (j = 0; opt = obj.options[j]; j++) {
                    pas++;
                }

                if (obj.selectedIndex == -1) {
                    return;
                }

                for (i = 0; opt = obj.options[i]; i++)
                    if (opt.selected) {
                        valor = opt.value; // almacenar value
                        txt = obj.options[i].text; // almacenar el texto
                        obj.options[i] = null; // borrar el item si está seleccionado
                        obj2 = document.frmConsultaDiferida.autorizadas;
                        if (obj2.options[0].value == '-') // si solo está la opción inicial borrarla
                            obj2.options[0] = null;
                        opc = new Option(txt, valor);
                        eval(obj2.options[obj2.options.length] = opc);
                    }

                if (pas == 1) {
                    obj.options[0] = null;
                    opc = new Option('-', '-');
                    eval(obj.options[0] = opc);
                }

                document.frmConsultaDiferida.rechazadas.focus();
                obj.selectedIndex = 0;
                
                UnidadesNbu();
            }

            function CargarAutorizado() {
                //donde se mostrar los registros
                var aleatorio = Math.random();
                divDet = document.getElementById('determinacion');

                ajax9 = objetoAjax();
                //uso del medoto GET
                ajax9.open('GET', './operaciones/alta_determinacion_auditoria.php?aleatorio=' + aleatorio + '&modo=1');
                ajax9.onreadystatechange = function () {
                    if (ajax9.readyState == 4) {
                        //mostrar resultados en esta capa
                        divDet.innerHTML = ajax9.responseText
                    }
                    document.frmConsultaDiferida.codigo.focus();
                }
                ajax9.send(null);
            }

            function CerrarCodigo() {
                divDet = document.getElementById('determinacion');
                divDes = document.getElementById('descripdeter');
                ajaxx = objetoAjax();
                divDet.innerHTML = '';
                divDes.innerHTML = '';
                ajaxx.send(null)
            }

            function CargarRechazado() {
                //donde se mostrar los registros
                var aleatorio = Math.random();
                divDet = document.getElementById('determinacion');

                ajax9 = objetoAjax();
                //uso del medoto GET
                ajax9.open('GET', './operaciones/alta_determinacion_auditoria.php?aleatorio=' + aleatorio + '&modo=2');
                ajax9.onreadystatechange = function () {
                    if (ajax9.readyState == 4) {
                        //mostrar resultados en esta capa
                        divDet.innerHTML = ajax9.responseText
                    }
                    document.frmConsultaDiferida.codigo.focus();
                }
                ajax9.send(null)
            }

            function ValidarCodigo(e, xcodigo, xmodo) {
                e = (window.event) ? event : e;
                intKey = (e.keyCode) ? e.keyCode : e.charCode;
                if (intKey == 13) {
                    return VerificarCodigo(xcodigo, xmodo);
                }
            }

            function VerificarCodigo(xcodigo, xmodo) {

                if (xcodigo.length != 6) {
                    alert('Codigo Incorrecto ...!');
                    return false;
                }

                var aleatorio = Math.random();
                divCod = document.getElementById('descripdeter');

                ajaxcod = objetoAjax();
                ajaxcod.open('GET', './operaciones/verificar_codigo_nbu.php?codigo=' + xcodigo + '&aleatorio=' + aleatorio);
                ajaxcod.onreadystatechange = function () {
                    if (ajaxcod.readyState == 4) {
                        //mostrar resultados en esta capa
                        var contenido = ajaxcod.responseText;
                        divCod.innerHTML = contenido;

                        var cont = contenido.substring(0, 10);
                        var cont1 = contenido.substring(0, 3);
                        if (cont.length > 0 & cont1 != '***') {  // En base al contenido del TAG se si el objeto existe o no
                            if (xmodo == 1) {
                                AgregarCodigoAutorizado(xcodigo, contenido);
                                UnidadesNbu();
                            }
                            if (xmodo == 2) {
                                AgregarCodigoRechazado(xcodigo, contenido);
                            }

                            document.frmConsultaDiferida.codigo.focus();
                        }
                        return true;
                    }
                }

                ajaxcod.send(null);

                return true;
            }

            function AgregarCodigoAutorizado(xcodigo, xdescrip) {
                var obj = document.frmConsultaDiferida.autorizadas;
                for (i = 1; opt = obj.options[i]; i++)
                    ;
                if (obj.options[0].value == '-') {
                    i = 0;
                }

                opc1 = new Option(xcodigo + '-' + xdescrip, xcodigo);
                eval(obj.options[i] = opc1);

                document.frmConsultaDiferida.codigo.value = '';
            }

            function AgregarCodigoRechazado(xcodigo, xdescrip) {
                obj = document.frmConsultaDiferida.rechazadas;

                for (i = 1; opt = obj.options[i]; i++)
                    ;
                if (obj.options[0].value == '-') {
                    i = 0;
                }

                opc1 = new Option(xcodigo + '-' + xdescrip, xcodigo);
                eval(obj.options[i] = opc1);

                document.frmConsultaDiferida.codigo.value = '';
            }

            function ControlAutorizadas(e) {
                e = (window.event) ? event : e;
                intKey = (e.keyCode) ? e.keyCode : e.charCode;
                if (intKey == 43) {
                    CargarAutorizado();
                }
                if (intKey == 32) {
                    Rechazar();
                }
                if (intKey == 45) {
                    if (confirm('¿ Seguro para Borrar Items ?')) {
                        obj = document.frmConsultaDiferida.autorizadas;

                        i = obj.selectedIndex;

                        obj.options[i] = null;

                        if (obj.length == 0) {
                            opc1 = new Option('-');
                            eval(obj.options[i] = opc1);
                        }
                        
                        UnidadesNbu();

                        return;

                    }
                }
            }

            function ControlRechazadas(e) {
                e = (window.event) ? event : e;
                intKey = (e.keyCode) ? e.keyCode : e.charCode;
                if (intKey == 43) {
                    CargarRechazado();
                }
                if (intKey == 32) {
                    Autorizar();
                }
                if (intKey == 45) {
                    if (confirm('¿ Seguro para Borrar Items ?')) {
                        obj = document.frmConsultaDiferida.rechazadas;

                        i = obj.selectedIndex;

                        obj.options[i] = null;

                        if (obj.length == 0) {
                            opc1 = new Option('-');
                            eval(obj.options[i] = opc1);
                        }

                        return;

                    }
                }
            }

            function ControlCapitadas(e) {
                e = (window.event) ? event : e;
                intKey = (e.keyCode) ? e.keyCode : e.charCode;
                if (intKey == 32) {
                    AutorizarCapita();
                }
                if (intKey == 45) {
                    if (confirm('¿ Seguro para Borrar Items ?')) {
                        obj = document.frmConsultaDiferida.capitadas;

                        i = obj.selectedIndex;

                        obj.options[i] = null;

                        if (obj.length == 0) {
                            opc1 = new Option('-');
                            eval(obj.options[i] = opc1);
                        }

                        return;

                    }
                }
            }

            function FinalizarCambios(xnroauditoria) {

                // Validamos que el auditor sea Válido
                var uss = document.getElementById('usuario').value;
                var ajax20 = objetoAjax();
                ajax20.open('GET', './operaciones/verificar_auditor.php?auditor=' + uss);
                ajax20.onreadystatechange = function () {
                    if (ajax20.readyState == 4) {
                        var ss = ajax20.responseText;
                        if (ss == "error") {
                            alert('ERROR. El Usuario ' + uss + ' con el que Intenta Registrar la Orden No tiene Permisos. Ingrese Nuevamente y Vuelva Realizar el Proceso');
                            var aleatorio = Math.random();
                            var host = location.protocol + '//' + location.hostname + '/' + document.getElementById('ruta').value + 'login.php?aleatorio=' + aleatorio;
                            window.location.href = host;
                            return false;
                        }
                    }
                }
                ajax20.send(null);

                // Validamos el usuario
                //alert(document.getElementById('_auditor1').value + '  ' + document.getElementById('_efector').value);

                //return;


                if (confirm('¿ Seguro para Finalizar los Cambios en Determinaciones ? (us.: ' + uss + ')')) {
                    // Aislamos los codigos autorizados y los rechazados
                    showdiv("parametros");
                    showdiv("ListarTotales");
                    showdiv("Lista");

                    var obj1 = document.getElementById('autorizadas');
                    var obj2 = document.getElementById('rechazadas');
                    var obj3 = document.getElementById('capitadas');

                    var uss = document.getElementById('usuario').value;

                    if (document.getElementById('observacion') == null) {
                        var observacion_auditor = 'nullzztop95';
                    } else {
                        var observacion_auditor = document.getElementById('observacion').value;
                        observacion_auditor = sanitizeString(observacion_auditor);
                        //alert(observacion_auditor);
                    }

                    var autorizadas = '';
                    var rechazadas = '';
                    var capitadas = '';

                    for (i = 0; opt = obj1.options[i]; i++) {
                        if (obj1.options[i].value != '-') {
                            autorizadas = autorizadas + obj1.options[i].value;
                        }
                    }

                    for (i = 0; opt = obj2.options[i]; i++) {
                        if (obj2.options[i].value != '-') {
                            rechazadas = rechazadas + obj2.options[i].value;
                        }
                    }

                    for (i = 0; opt = obj3.options[i]; i++) {
                        if (obj3.options[i].value != '-') {
                            capitadas = capitadas + obj3.options[i].value;
                        }
                    }

                    var aleatorio = Math.random();
                    divGrabar = document.getElementById('mensaje');
                    divDet = document.getElementById('ListarOrden');
                    divGrabar.innerHTML = '<i>Procesando</i>';
                    divDet.innerHTML = '';

                    //alert('./operaciones/actualizar_orden_usuario.php?nrotrans=' + xnroauditoria + '&autorizadas=' + autorizadas + '&rechazadas=' + rechazadas + '&capitadas=' + capitadas + '&observaciones=' + observacion_auditor + '&aleatorio=' + aleatorio + '&usuario=' + uss);

                    var ajax10 = objetoAjax();
                    //ajax10.open('GET', './operaciones/actualizar_orden_usuario.php?nrotrans=' + xnroauditoria + '&autorizadas=' + autorizadas + '&rechazadas=' + rechazadas + '&capitadas=' + capitadas + '&observaciones=' + observacion_auditor + '&aleatorio=' + aleatorio + '&usuario=' + uss);
                    ajax10.open('GET', './operaciones/actualizar_orden_usuario_ins.php?nrotrans=' + xnroauditoria + '&autorizadas=' + autorizadas + '&rechazadas=' + rechazadas + '&capitadas=' + capitadas + '&observaciones=' + observacion_auditor + '&aleatorio=' + aleatorio + '&usuario=' + uss);
                    ajax10.onreadystatechange = function () {
                        if (ajax10.readyState == 4) {
                            divGrabar.innerHTML = ajax10.responseText
                        }
                        MostrarTotales('');
                        MarcarAuditada(xnroauditoria);
                    }
                    ajax10.send(null);
                    //MostrarTotales('');
                    //MarcarAuditada(xnroauditoria);
                }
            }

            function UnidadesNbu() {
                var aleatorio = Math.random();                
                var codos = document.getElementById('listObsocial').value;
                var obj1 = document.getElementById('autorizadas');                
                var autorizadas = '';
                for (i = 0; opt = obj1.options[i]; i++) {
                    if (obj1.options[i].value != '-') {
                        autorizadas = autorizadas + obj1.options[i].value;
                    }
                }
                
                var ajax20 = objetoAjax();
                ajax20.open('GET', './operaciones/calcular_unidades_nbu.php?aleatorio=' + aleatorio + '&autorizadas=' + autorizadas + '&codos=' + codos);
                ajax20.onreadystatechange = function () {
                    if (ajax20.readyState == 4) {                        
                        divunidades.innerHTML = ajax20.responseText
                    }                    
                }
                ajax20.send(null);
            }

            function MarcarAuditada(xnroauditoria) {
                divMarcar = document.getElementById(xnroauditoria);

                if (document.getElementById('observacion') != null) {
                    document.getElementById('observacion').value = '';
                }

                ajax11 = objetoAjax();
                divMarcar.innerHTML = '<b>[OK ]<b>';
                ajax11.onreadystatechange = function () {
                    if (ajax11.readyState == 4) {
                        divMarcar.innerHTML = ajax11.responseText
                    }
                    
                }
                ajax11.send(null);
            }

            function MostrarTotales() {
                var aleatorio = Math.random();
                var divTotales = document.getElementById('ListarTotales');
                divTotales.innerHTML = '<i>Calculando Totales ...</i>';
                var codos = document.getElementById('listObsocial').value;
                var fecha = document.getElementById('hasta').value;

                var ajax13 = objetoAjax();
                ajax13.open('GET', './operaciones/totales_auditados.php?aleatorio=' + aleatorio + '&codos=' + codos + '&fecha=' + fecha);
                ajax13.onreadystatechange = function () {
                    if (ajax13.readyState == 4) {
                        divTotales.innerHTML = ajax13.responseText
                    }                    
                    // 22/04/2020
                    var desde = document.getElementById('desde').value;
                    var hasta = document.getElementById('hasta').value;
                    var codos = document.getElementById("listObsocial").value;

                    comboObrasSociales(desde, hasta, codos);
                }
                ajax13.send(null);
            }

            function CancelarCambios() {
                if (confirm('¿ Seguro para Cancelar Modificaciones en Orden ?')) {
                    showdiv("parametros");
                    showdiv("ListarTotales");
                    showdiv("Lista");
                    var aleatorio = Math.random();
                    divDet = document.getElementById('ListarOrden');

                    ajax9 = objetoAjax();
                    //uso del medoto GET
                    divDet.innerHTML = '';
                    ajax9.onreadystatechange = function () {
                        if (ajax9.readyState == 4) {
                            //mostrar resultados en esta capa
                            divDet.innerHTML = ajax9.responseText
                        }
                    }
                    ajax9.send(null)
                }
            }

            function ObservacionAuditor(xnroauditoria) {
                var aleatorio = Math.random();
                divOA = document.getElementById('determinacion');
                divDe = document.getElementById('descripdeter');
                divDe.innerHTML = '';

                ajax9 = objetoAjax();
                //uso del medoto GET
                ajax9.open('GET', './operaciones/alta_observacion_auditor.php?nrotrans=' + xnroauditoria + '&aleatorio=' + aleatorio);
                ajax9.onreadystatechange = function () {
                    if (ajax9.readyState == 4) {
                        //mostrar resultados en esta capa
                        divOA.innerHTML = ajax9.responseText
                    }
                    document.frmConsultaDiferida.observacion.focus();
                }
                ajax9.send(null)
            }

            function ValidarObservacion(e, xobservacion, xnroauditoria) {
                e = (window.event) ? event : e;
                intKey = (e.keyCode) ? e.keyCode : e.charCode;
                if (intKey == 13) {
                    return true;
                }
            }

            function CambiarOS() {
                ConsultarOrdenesPendientes();
                return true;
            }

            function BuscarValor(e, nrodoc) {
                e = (window.event) ? event : e;
                intKey = (e.keyCode) ? e.keyCode : e.charCode;
                if (intKey == 13) {
                    ProcederBuscar(nrodoc);
                    return true;
                }
            }

            function ConsultarDocumento() {
                var aleatorio = Math.random();
                divBuscar = document.getElementById('Buscar');

                ajaxb = objetoAjax();
                ajaxb.open('GET', './operaciones/buscar_documento_afiliado.php?aleatorio=' + aleatorio);
                ajaxb.onreadystatechange = function () {
                    if (ajaxb.readyState == 4) {
                        divBuscar.innerHTML = ajaxb.responseText
                    }
                    document.getElementById('buscarvalor').focus();
                }
                ajaxb.send(null)
            }

            function ProcederBuscar(nrodoc) {
                var m = document.getElementById("modo");
                m.value = 10;
                var ndoc = document.getElementById("nrodoc");
                ndoc.value = nrodoc;

                divBuscar = document.getElementById('Buscar');
                divBuscar.innerHTML = '';

                Pagina(1);

                ajax1 = objetoAjax();

                ajax1.send(null);
            }

            function DejarPendiente(xnroauditoria) {
                if (confirm('¿ Seguro para Marcar Orden para Auditoría Posterior ?')) {
                    // Aislamos los codigos autorizados y los rechazados
                    showdiv("parametros");
                    showdiv("ListarTotales");
                    showdiv("Lista");

                    if (document.getElementById('observacion') == null) {
                        var observacion_auditor = 'nullzztop95';
                    } else {
                        var observacion_auditor = document.getElementById('observacion').value;
                    }

                    var aleatorio = Math.random();
                    var divMarca = document.getElementById(xnroauditoria);
                    var divDet = document.getElementById('ListarOrden');
                    var uss = document.getElementById('usuario').value;

                    ajaxpost = objetoAjax();
                    divDet.innerHTML = '';
                    ajaxpost.open('GET', './operaciones/marcar_orden_pendiente_auditoria.php?aleatorio=' + aleatorio + '&nrotrans=' + xnroauditoria + '&usuario=' + uss + '&observacion=' + observacion_auditor);
                    ajaxpost.onreadystatechange = function () {
                        if (ajaxpost.readyState == 4) {
                            divMarca.innerHTML = '<b>[ PE ]</b>';
                        }
                    }
                    ajaxpost.send(null)
                }
            }

            function Anular_Orden(xnroauditoria) {
                if (confirm('¿ Seguro para Anular Orden ' + xnroauditoria + ' ?')) {
                    // Aislamos los codigos autorizados y los rechazados
                    showdiv("parametros");
                    showdiv("ListarTotales");
                    showdiv("Lista");

                    var aleatorio = Math.random();

                    ajaxan = objetoAjax();
                    ajaxan.open('GET', './operaciones/ProcederAnulacionOrden.php?nrotrans=' + xnroauditoria + '&aleatorio=' + aleatorio);
                    ajaxan.onreadystatechange = function () {
                        if (ajaxan.readyState == 4) {
                        }
                        ConsultarOrdenesStandBy();
                    }
                    ajaxan.send(null)
                }
            }

            function CancelarBusqueda() {
                var ajax = objetoAjax();
                divBuscar = document.getElementById('Buscar');
                divBuscar.innerHTML = '';
                document.getElementById('desde').focus();
                ajax.send(null);
            }

            function RechazarCapita() {
                var obj = document.frmConsultaDiferida.rechazadas;

                var pas = 0;
                for (j = 0; opt = obj.options[j]; j++) {
                    pas++;
                }

                if (obj.selectedIndex == -1) {
                    return;
                }

                for (i = 0; opt = obj.options[i]; i++)
                    if (opt.selected) {
                        valor = opt.value; // almacenar value
                        txt = obj.options[i].text; // almacenar el texto
                        obj.options[i] = null; // borrar el item si está seleccionado
                        obj2 = document.frmConsultaDiferida.capitadas;
                        if (obj2.options[0].value == '-') // si solo está la opción inicial borrarla
                            obj2.options[0] = null;
                        opc = new Option(txt, valor);
                        eval(obj2.options[obj2.options.length] = opc);
                    }

                if (pas == 1) {
                    obj.options[0] = null;
                    opc = new Option('-', '-');
                    eval(obj.options[0] = opc);
                }

                document.frmConsultaDiferida.rechazadas.focus();
                obj.selectedIndex = 0;
            }

            function AutorizarCapita() {
                obj = document.frmConsultaDiferida.capitadas;

                var pas = 0;
                for (j = 0; opt = obj.options[j]; j++) {
                    pas++;
                }

                if (obj.selectedIndex == -1) {
                    return;
                }

                for (i = 0; opt = obj.options[i]; i++)
                    if (opt.selected) {
                        valor = opt.value; // almacenar value
                        txt = obj.options[i].text; // almacenar el texto
                        obj.options[i] = null; // borrar el item si está seleccionado
                        obj2 = document.frmConsultaDiferida.rechazadas;
                        if (obj2.options[0].value == '-') // si solo está la opción inicial borrarla
                            obj2.options[0] = null;
                        opc = new Option(txt, valor);
                        eval(obj2.options[obj2.options.length] = opc);
                    }

                if (pas == 1) {
                    obj.options[0] = null;
                    opc = new Option('-', '-');
                    eval(obj.options[0] = opc);
                }

                document.frmConsultaDiferida.capitadas.focus();
                obj.selectedIndex = 0;
            }

        </script>

        <link rel="stylesheet" type="text/css" href="/css1.css">

    </head>

    <body onLoad="javascript: if (Init()) {
                desde.focus()
            }
            ;
            return true;">

        <div style="margin:auto;width:550px;text-align:center;">

            <form name="frmConsultaDiferida" method="post" onsubmit="return false">

                <?php include('menu_admin.php'); ?>

                <div id="MenuConsulta">
                    <table width="500" border="0" align="center">
                        <tr>
                            <?php
                            echo '<td width="54"><input name="usuario" type="hidden" id="usuario" value="' . $usuario . '" /></td>';
                            ?>
                            <td width="326" border="0" align="left">
                                <div align="left">
                                    <input type="hidden" id="nrodoc" />
                                    <input type="hidden" id="modo" />
                                </div>
                            </td>
                            <td width="65" border="0" align="left">
                                <div align="left">
                                    <?php
                                    $trans = rand(1, 100000);
                                    $link = '?p111989=' . $usuario . '&reinit=' . $trans;

                                    echo '</td>';
                                    echo '<td width="50">';

                                    echo '</td>';
                                    ?>
                                </div>
                            </td>

                        </tr>
                    </table>
                </div>

                <div id="parametros">

                    <table width="550px" border="0" align="center">
                        <tr>
                        </tr>
                        <td width="120px" align="left">Auditoría de Ordenes</td>
                        <td width="430px"><hr></td>
                        <tr>
                    </table>

                    <table width="510" border="0" align="center">
                        <tr>
                            <td width="64" align = "right">
                                F.Desde:</td>
                            <td width="190" align = "left">
                                <input name="desde" id="desde" type="text" style="font-size: 10px;" maxlength="10" width="2" size="10" onkeypress="javascript: if (ValidarFechaDesde(event, desde.value)) {
                                            hasta.focus()
                                        }
                                        ;
                                        return true" />
                                (dd/mm/aaaa)</td>

                            <td width="92" align = "right">
                                Hasta:</td>
                            <td width="63"><div align="left">
                                    <input name="hasta" id="hasta" type="text" style="font-size: 10px;" maxlength="10" width="2" size="10" onkeypress="javascript: if (ValidarFechaHasta(event, hasta.value)) {
                                                hasta.focus()
                                            }
                                            ;
                                            return true" />
                                </div></td>
                        </tr>

                        <tr>
                            <td align = "right">Ob.Social:</td>
                            <td height="23" align = "left">
                                <div id="listaobrassociales">
                                <?
                                include('operaciones/lista_os_auditarordenes_srn.php');
                                ?>
                                </div>
                            </td>
                            <td><a href="javascript://" onclick="ConsultarOrdenesPendientes()">Sin Auditar</a>
                                <td><div align="left"><a href="javascript://" onclick="ConsultarOrdenesStandBy()">Pendientes</a></div>    <a href="javascript://" onclick="ConsultarOrdenesPendientes()"></a></td>
                        </tr>


                        <tr>
                            <td width="64" align = "right">
                                Efector:</td>
                            <td width="147" height="21" align = "left">
                                <?
                                include('operaciones/lista_efectores.php');
                                ?>
                            </td>

                            <td width="92"><a href="javascript://" onclick="ConsultarDocumento()">Cons. Doc.</a>
                                <td width="63"><div align="left"><a href="javascript://" onclick="ConsultarDocumento()"></a>
                                        <a href="javascript://" onclick="ConsultarOrdenes(2)">Todas</a></div></td>
                        </tr>
                    </table>

                    <table width="550px" border="0" align="center">
                        <tr>
                            <td><hr></td>
                        </tr>
                    </table>

                </div>

                <div id="ListarTotales" align="center">
                </div>

                <div id="Botones" align="center"></div>

                <div id="Buscar" align="left"></div>

                <div id="Estado" align="center"></div>

                <div id="ListarOrden" align="left"></div>

                <div id="ObservacionAuditor" align="left"></div>

                <div id="Mensaje" align="center"></div>

                <div id="mensaje" align="left"></div>

                <div id="Lista"></div>

                <div id="auditores">
                    <?
                    if ($auditor1 != '') {
                        echo 'Auditor 1 ' . $nauditor1 . ' / ' . 'Auditor 2 ' . $nauditor2;
                        echo '<input type="hidden" id="_auditor1" value="' . $auditor1 . '">';
                        echo '<input type="hidden" id="_auditor2" value="' . $auditor2 . '">';
                        echo '<input type="hidden" id="_efector" value="' . $efector . '">';
                    }
                    ?>
                </div>

                <script>
                    init();
                </script>               


            </form>
        </div>
    </body>

</html>