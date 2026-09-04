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
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Obras Sociales</title>

        <script src="../jscript/funcionajax.js"></script>
        <script src="../jscript/funciones.js"></script>
        <script>

            function Pagina(nropagina, filtro, edbaja){
                var aleatorio = Math.random();
                divBuscar = document.getElementById('buscar');
                divBuscar.innerHTML = '';
                divListar = document.getElementById('lista_aranceles');
                divListar.innerHTML = '';
                divAranceles = document.getElementById('aranceles');
                divAranceles.innerHTML = '';
                divBorrar = document.getElementById('borrar');
                divBorrar.innerHTML = '';

                //donde se mostrar los registros
                divContenido = document.getElementById('contenido');

                var ajax=objetoAjax();
                //uso del medoto GET
                //indicamos el archivo que realizar� el proceso de paginar
                //junto con un valor que representa el nro de pagina
                ajax.open('GET', './operaciones/paginador_obsocial.php?pag='+nropagina+'&filtro='+filtro+'&edbaja='+edbaja+'&aleatorio='+aleatorio);
                divContenido.innerHTML= '<img src="anim.gif"> :: Procesando ...';
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divContenido.innerHTML = ajax.responseText
                    }
                }

                ajax.send(null);
            }

            function Nuevo(){
                //donde se mostrará los registros
                var aleatorio = Math.random();
                divBuscar = document.getElementById('buscar');
                divBuscar.innerHTML = '';
                divNuevo = document.getElementById('nuevo');
                ajax=objetoAjax();
                //uso del medoto GET
                //indicamos el archivo que realizar el proceso de paginar
                //junto con un valor que representa el nro de pagina
                ajax.open('GET', './operaciones/editar_obsocial.php?aleatorio='+aleatorio+'&modo=1');
                divNuevo.innerHTML= '<img src="anim.gif"> :: Nuevo';
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divNuevo.innerHTML = ajax.responseText
                    }
                    document.getElementById('codigo').focus();
                }

                ajax.send(null)
            }

            function ValidarCodigo(e, codigo){
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    if (codigo.length != 6) {
                        alert('Código de Obra Social Incorrecto ...!');
                        return false;
                    } else {
                        return true;
                    }
                }
            }

            function ValidarNombre(e, nombre){
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    if (nombre.length == 0) {
                        alert('Nombre de Obra Social Incorrecto ...!');
                        return false;
                    } else {
                        return true;
                    }
                }
            }

            function ValidarFactNBU(e, xfactnbu){
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    var factnbu = '';
                    factnbu = xfactnbu.toUpperCase();
                    if (factnbu == 'S' || factnbu == 'N') {
                        return true;
                    } else {
                        alert('Las Opciones son S ó N ...!');
                        return false;
                    }
                }
            }

            function ValidarBonos(e, bono){
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    return true;
                }
            }

            function ValidarMedicos_cab(e, m_c){
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    return true;
                }
            }

            function ValidarSoportemag(e, sm){
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    return true;
                }
            }

            function ValidarDerivacion(e, sm){
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    return true;
                }
            }

            function ValidarUsuario(e, us){
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    return true;
                }
            }

            function ValidarPass(e, pass){
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    return true;
                }
            }

            function ValidarInc_leyenda(e, pass){
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    return true;
                }
            }

            function ValidarLeyenda(e, pass){
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    return true;
                }
            }

            function ValidarIng_continuo(e, pass){
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    return true;
                }
            }

            function ValidarENTER(e){
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    return true;
                }
            }

            function validarMF() {
                if (document.getElementById('coseguro_mf').checked) {
                    document.getElementById('coseguro').checked = false;
                }
            }


            function Registrar(codigo, nombre, factnbu, xmodo, bonos, medicos_cab, soportemag, usuario, pass, derivacion, inc_leyenda, leyenda, ing_continuo, incluye_ab, aut_directa, coseguro, alta_paciente, coseguro_mf, xnivel3, xtope_anual, xpracticas_rechazadas, xorden_completa, xinactiva, gen_nroautorizacion, xnivel2){
                
                if (codigo.length != 6) {
                    alert('Código de Obra Social Incorrecto ...!');
                    return false;
                }
                if (nombre.length == 0) {
                    alert('Nombre de Obra Social Incorrecto ...!');
                    return false;
                }
                var s = factnbu.toUpperCase();
                if (s == 'S' || s == 'N' ) {
                } else {
                    alert('Las Opciones son S ó N ...!');
                    return false;
                }

                var xbonos = '';
                var xmedicos_cab = '';

                if (bonos) {
                    xbonos = '1';
                }
                if (medicos_cab) {
                    xmedicos_cab = '1';
                }

                var xsoportemag = 'N';
                if (soportemag) {
                    xsoportemag = 'S';
                }

                var xderivacion = 'N';
                if (derivacion) {
                    xderivacion = 'S';
                }
                var xinc_leyenda = 'N';
                if (inc_leyenda) {
                    xinc_leyenda = 'S';
                }

                var xing_continuo = 'N';
                if (ing_continuo) {
                    xing_continuo = 'S';
                }

                var xincluye_ab = 'N';
                if (incluye_ab) {
                    xincluye_ab = 'S';
                }

                var xaut_directa = 'N';
                if (aut_directa) {
                    xaut_directa = 'S';
                }

                var xcoseguro = 'N';
                if (coseguro) {
                    xcoseguro = 'S';
                }

                var xalta_paciente = 'N';
                if (alta_paciente) {
                    xalta_paciente = 'S';
                }

                var xcoseguro_mf = 'N';
                if (coseguro_mf) {
                    xcoseguro_mf = 'S';
                }

                var nivel3 = 'N';
                if (xnivel3) {
                    nivel3 = 'S';
                }
                
                var tope_anual = 'N';
                if (xtope_anual) {
                    tope_anual = 'S';
                }
                
                var practicas_rechazadas = 'N';
                if (xpracticas_rechazadas) {
                    practicas_rechazadas = 'S';
                }
                
                var orden_completa = 0;
                if (xorden_completa) {
                    orden_completa = 1;
                }
                
                var inactiva = 0;
                if (xinactiva) {
                    inactiva = 1;
                }                               

                var aleatorio = Math.random();
                divMensaje = document.getElementById('mensaje');
                divMensaje.innerHTML= '<img src="anim.gif"> :: Registrando ...';
                divNuevo = document.getElementById('nuevo');
                divNuevo.innerHTML = '';
                //instanciamos el objetoAjax
                ajax=objetoAjax();
                //uso del medoto POST
                //archivo que realizará la operacion
                //registro.php                
                if (xmodo == 1) {
                    ajax.open("GET", "./operaciones/registro_obsocial.php?codigo="+codigo+"&nombre="+nombre+"&factnbu="+s+"&aleatorio="+aleatorio+'&bonos='+xbonos+'&medicos_cab='+xmedicos_cab+'&soportemag='+xsoportemag+'&us='+usuario+'&pa='+pass+'&derivacion='+xderivacion+'&inc_leyenda='+xinc_leyenda+'&leyenda='+leyenda+'&ing_continuo='+xing_continuo+'&incluye_ab='+xincluye_ab+'&aut_directa='+xaut_directa+'&coseguro='+xcoseguro+'&alta_paciente='+xalta_paciente+'&coseguro_mf='+xcoseguro_mf+'&nivel3='+nivel3+'&tope_anual='+tope_anual+'&practicas_rechazadas='+practicas_rechazadas+'&orden_completa='+orden_completa+'&inactiva='+inactiva+'&gen_nroautorizacion='+gen_nroautorizacion+'&nivel2='+xnivel2,true);
                }
                if (xmodo == 2) {                    
                    ajax.open("GET", "./operaciones/actualizo_obsocial.php?codigo="+codigo+"&nombre="+nombre+"&factnbu="+s+"&aleatorio="+aleatorio+'&bonos='+xbonos+'&medicos_cab='+xmedicos_cab+'&soportemag='+xsoportemag+'&us='+usuario+'&pa='+pass+'&derivacion='+xderivacion+'&inc_leyenda='+xinc_leyenda+'&leyenda='+leyenda+'&ing_continuo='+xing_continuo+'&incluye_ab='+xincluye_ab+'&aut_directa='+xaut_directa+'&coseguro='+xcoseguro+'&alta_paciente='+xalta_paciente+'&coseguro_mf='+xcoseguro_mf+'&nivel3='+nivel3+'&tope_anual='+tope_anual+'&practicas_rechazadas='+practicas_rechazadas+'&orden_completa='+orden_completa+'&inactiva='+inactiva+'&gen_nroautorizacion='+gen_nroautorizacion+'&nivel2='+xnivel2,true);
                }
                ajax.onreadystatechange=function() {

                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divMensaje.innerHTML = ajax.responseText;

                        //llamar a funcion para limpiar los inputs
                        //RecargarControl();
                        Pagina(1, nombre, 'N');
                        LimpiarCampos();
                    }
                }

                ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
                //enviando los valores
                ajax.send(null);
            }

            function Editar(){
                divBuscar = document.getElementById('buscar');
                divBuscar.innerHTML = '';
                Pagina(1, '', 'E');
            }

            function EditarRegistro(codigo) {
                var aleatorio = Math.random();
                divBuscar = document.getElementById('buscar');
                divBuscar.innerHTML = '';
                divEditar = document.getElementById('nuevo');
                ajax=objetoAjax();
                ajax.open('GET', './operaciones/editar_obsocial.php?codigo='+codigo+'&aleatorio='+aleatorio+'&modo=2', true);
                divEditar.innerHTML= '<img src="anim.gif"> :: Editando ...';
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divEditar.innerHTML = ajax.responseText
                    }
                    document.getElementById('nombre').focus();
                }
                ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
                //enviando los valores
                ajax.send(null);
            }

            function RecargarObsocial(){
                ajax=objetoAjax();
                divRecargar = document.getElementById('nuevo');
                divRecargar.innerHTML = '';
            }

            function QuitarFiltro(){
                divNuevo = document.getElementById('aranceles');
                divNuevo.innerHTML = '';
                divList = document.getElementById('lista_aranceles');
                divList.innerHTML = '';
                divBuscar = document.getElementById('buscar');
                divBuscar.innerHTML = '';
                divMs = document.getElementById('mensaje');
                divMs.innerHTML = '';
                Pagina(1, '', 'N');
            }

            function Bajas(){
                divBuscar = document.getElementById('buscar');
                divBuscar.innerHTML = '';
                Pagina(1, '', 'B');
            }

            function Borrar(codigo) {
                var aleatorio = Math.random();
                ajax=objetoAjax();
                divBorrar = document.getElementById('borrar');
                ajax.open('GET', './operaciones/BorraObsocial.php?codigo='+codigo+'&aleatorio='+aleatorio, true);
                divBorrar.innerHTML= '<img src="anim.gif"> :: Procesando ...';
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divBorrar.innerHTML = ajax.responseText
                    }
                }
                ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
                //enviando los valores
                ajax.send(null);
            }

            function ProcederBaja(codigo) {
                var aleatorio = Math.random();
                divBorrar = document.getElementById('borrar');
                ajax=objetoAjax();
                ajax.open('GET', './operaciones/baja_obsocial.php?codigo='+codigo+'&aleatorio='+aleatorio, true);
                divBorrar.innerHTML= '<img src="anim.gif"> :: Borrando ...';
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divBorrar.innerHTML = ajax.responseText
                        if (divBorrar.innerHTML.length == 0) {
                            // Si la Baja fue OK, refrescamos la página
                            Pagina(1, '', 'N');
                        }
                    }
                }

                ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
                //enviando los valores
                ajax.send('codigo='+codigo);
            }

            function CancelarBaja() {
                divBorrar = document.getElementById('borrar');
                divBorrar.innerHTML = '';
                QuitarFiltro();
            }

            function Buscar() {
                var aleatorio = Math.random();
                divBuscar = document.getElementById('buscar');
                ajax=objetoAjax();
                ajax.open('GET', './operaciones/buscar.php?aleatorio='+aleatorio);
                divBuscar.innerHTML= '<img src="anim.gif"> :: Procesando ...';
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divBuscar.innerHTML = ajax.responseText;
                        document.getElementById('buscarvalor').focus();
                    }
                }

                ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
                //enviando los valores
                ajax.send(null);
            }

            function ProcederBuscar(valor){
                Pagina(1, valor, 'T');
                divBuscar = document.getElementById('buscar');
                divBuscar.innerHTML = '';
            }
            
             function BuscarValor(e, xcodos) {
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    //return VerificarPeriodo(xperiodo);
                    ProcederBuscar(xcodos)
                }
            }

            //----  Aranceles NBU

            function ValidarPeriodo(e, xperiodo) {
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    return VerificarPeriodo(xperiodo);
                }
            }

            function ValidarUnidad(e, xunidad) {
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    return ValidarNumero(xunidad);
                }
            }

            function ValidarUnidadDif(e, xunidad) {
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    return ValidarNumero(xunidad);
                }
            }

            function ValidarModulo(e, xmodulo) {
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    return ValidarNumero(xmodulo);
                }
            }
            
            function ValidarNbuos(e, xnbuos) {
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    return ValidarNumero(xnbuos);
                }
            }

            function NuevoArancelNBU(codos){
                //donde se mostrará los registros
                var aleatorio = Math.random();
                divContenido = document.getElementById('contenido');
                divContenido.innerHTML = '';
                divNuevo = document.getElementById('aranceles');
                ajaxr=objetoAjax();
                //uso del medoto GET
                //indicamos el archivo que realizar� el proceso de paginar
                //junto con un valor que representa el nro de pagina
                ajaxr.open('GET', './operaciones/nuevo_arancelnbu.php?codos='+codos+'&aleatorio='+aleatorio);
                divNuevo.innerHTML= '<img src="anim.gif"> :: Procesando ...';
                ajaxr.onreadystatechange=function() {
                    if (ajaxr.readyState==4) {
                        //mostrar resultados en esta capa
                        divNuevo.innerHTML = ajaxr.responseText
                    }
                    PaginaArancelesNBU(1, '', '', codos);
                    document.getElementById('periodo').focus();
                }

                ajaxr.send(null)
            }

            function ValidarOS(e) {
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    return true;
                }
            }

            function ValidarMonto(e, xmonto) {
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    return ValidarNumero(xmonto);
                }
            }

            function ValidarPeriodo1(e, xperiodo) {
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    if (xperiodo.length == 0) {
                        return true;
                    } else {
                        return VerificarPeriodo(xperiodo);
                    }
                }
            }

            function registrarArancelNBU(xcodos, periodo, arancel, aranceldif, modulo, nbuos){
                //donde se mostrará lo resultados
                var aleatorio = Math.random();
                divMensaje = document.getElementById('mensaje');
                divMensaje.innerHTML= '<img src="anim.gif"> :: Registrando Arancel ...';
                //valores de las cajas de texto
                var c = xcodos;
                l = c.indexOf("-");
                codos = c.substring(0, l);
                //instanciamos el objetoAjax
                var ajax1=objetoAjax();
                //uso del medoto POST
                //archivo que realizará la operacion
                //registro.php
                ajax1.open("GET", "./operaciones/registro_arancelnbu.php?codos="+codos+"&periodo="+periodo+"&arancel="+arancel+"&aranceldif="+aranceldif+"&modulo="+modulo+"&aleatorio="+aleatorio+"&nbu_os="+nbuos);
                ajax1.onreadystatechange=function() {
                    if (ajax1.readyState==4) {
                        //mostrar resultados en esta capa
                        divMensaje.innerHTML = ajax1.responseText;
                        document.getElementById('periodo').value = '';
                        document.getElementById('unidad').value = '';
                        document.getElementById('unidaddif').value = '';
                        document.getElementById('modulo').value = '';
                        document.getElementById('nbu_os').value = '';
                        document.getElementById('periodo').focus();
                    }
                    RecargarArancelNBU();
                }


                //enviando los valores
                ajax1.send(null);
            }

            function BorraArancelNBU(codos, periodo) {
                var aleatorio = Math.random();
                divNuevo = document.getElementById('aranceles');
                divNuevo.innerHTML = '';
                ajax=objetoAjax();
                divBorrar = document.getElementById('borrar');
                ajax.open('GET', './operaciones/BorraArancelNBU.php?codos='+codos+'&periodo='+periodo+'&aleatorio='+aleatorio, true);
                divBorrar.innerHTML= '<img src="anim.gif"> :: Procesando ...';
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divBorrar.innerHTML = ajax.responseText
                    }

                }
                ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
                //enviando los valores
                ajax.send(null);
            }

            function ProcederBajaArancelNBU(codos, periodo) {
                var aleatorio = Math.random();
                divBorrar = document.getElementById('borrar');
                //divLista = document.getElementById('lista_aranceles');
                //divLista.innerHTML = '';
                ajax.open('GET', './operaciones/baja_arancelNBU.php?codos='+codos+'&periodo='+periodo+'&aleatorio='+aleatorio, true);
                divBorrar.innerHTML= '<img src="anim.gif"> :: Borrando Arancel ...';
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divBorrar.innerHTML = ajax.responseText
                        RecargarArancelNBU();
                    }
                }
                ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
                //enviando los valores
                ajax.send(null);
            }

            function PaginaArancelesNBU(nropagina, filtro, edbaja, codos){
                var aleatorio=Math.random();
                //donde se mostrar los registros
                divListar = document.getElementById('lista_aranceles');
                divListar.innerHTML = '';
                divAranceles = document.getElementById('lista_aranceles');
                ajax=objetoAjax();
                //uso del medoto GET
                //indicamos el archivo que realizar� el proceso de paginar
                //junto con un valor que representa el nro de pagina
                ajax.open('GET', './operaciones/paginador_arancelesnbu.php?pag='+nropagina+'&filtro='+filtro+'&edbaja='+edbaja+'&codos='+codos+'&aleatorio='+aleatorio);                
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divAranceles.innerHTML = ajax.responseText
                    }
                }
                //como hacemos uso del metodo GET
                //colocamos null ya que enviamos
                //el valor por la url ?pag=nropagina

                ajax.send(null)
            }

            function RecargarArancelNBU(){
                divRecargar = document.getElementById('aranceles');
                divRecargar.innerHTML = '';
                Pagina(1, '', 'N');
            }


            //----  Montos Fijos NBU

            function NuevoApfijosNBU(codos){
                var aleatorio = Math.random();
                divRecargar = document.getElementById('nuevo');
                divRecargar.innerHTML = '';
                //donde se mostrará los registros
                divContenido = document.getElementById('contenido');
                divContenido.innerHTML = '';
                divNuevo = document.getElementById('aranceles');
                ajax=objetoAjax();
                //uso del medoto GET
                //indicamos el archivo que realizar� el proceso de paginar
                //junto con un valor que representa el nro de pagina
                ajax.open('GET', './operaciones/nuevo_apfijosnbu.php?ID=0&codos='+codos+'&aleatorio='+aleatorio);
                divNuevo.innerHTML= '<img src="anim.gif"> :: Nuevo ...';
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divNuevo.innerHTML = ajax.responseText
                        PaginaApfijosNBU(1, '', '', codos);
                    }
                    document.getElementById('codigo1').focus();
                }
                ajax.send(null)
            }

            function EditaApfijosNBU(id){
                var aleatorio = Math.random();
                divRecargar = document.getElementById('nuevo');
                divRecargar.innerHTML = '';
                divNuevo = document.getElementById('aranceles');
                ajax=objetoAjax();
                //uso del medoto GET
                //indicamos el archivo que realizar� el proceso de paginar
                //junto con un valor que representa el nro de pagina
                ajax.open('GET', './operaciones/nuevo_apfijosnbu.php?ID='+id+'&aleatorio='+aleatorio);
                divNuevo.innerHTML= '<img src="anim.gif"> :: Editando ...';
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divNuevo.innerHTML = ajax.responseText
                    }
                    document.getElementById('codigo1').focus();
                }
                ajax.send(null)
            }

            function registrarApfijosNBU(codos, codanalisis, periodo, monto, perhasta, perbaja){
                //donde se mostrará lo resultados
                var aleatorio = Math.random();
                divMensaje = document.getElementById('mensaje');
                divMensaje.innerHTML= '<img src="anim.gif"> :: Registrando Monto Fijo Determinación ...';
                var ajax=objetoAjax();
                ajax.open("GET", "./operaciones/registro_apfijosnbu.php?codos="+codos+"&codanalisis="+codanalisis+"&periodo="+periodo+"&monto="+monto+"&perhasta="+perhasta+"&perbaja="+perbaja+"&aleatorio="+aleatorio,true);
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divMensaje.innerHTML = ajax.responseText;

                        //llamar a funcion para limpiar los inputs
                        document.getElementById('periodo').value = '';
                        document.getElementById('codigo1').value = '';
                        document.getElementById('monto').value = '';
                        document.getElementById('perhasta').value = '';
                        document.getElementById('perbaja').value = '';
                    }
                    PaginaApfijosNBU(1, '', '', codos);
                    document.getElementById('codigo1').focus();
                }

                ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
                //enviando los valores
                ajax.send(null);
            }

            function PaginaApfijosNBU(nropagina, filtro, edbaja, codos){
                var aleatorio=Math.random();
                //donde se mostrar los registros
                divListar = document.getElementById('lista_aranceles');
                divListar.innerHTML = '';
                divAranceles = document.getElementById('lista_aranceles');
                var ajax=objetoAjax();
                //uso del medoto GET
                //indicamos el archivo que realizar� el proceso de paginar
                //junto con un valor que representa el nro de pagina
                ajax.open('GET', './operaciones/paginador_apfijosnbu.php?pag='+nropagina+'&filtro='+filtro+'&edbaja='+edbaja+'&codos='+codos+'&aleatorio='+aleatorio);
                divAranceles.innerHTML= '<img src="anim.gif"> :: Procesando ...';
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divAranceles.innerHTML = ajax.responseText
                    }
                }
                ajax.send(null)
            }

            function BorraApfijosNBU(id, codanalisis, monto, periodo) {
                var aleatorio = Math.random();
                divNuevo = document.getElementById('aranceles');
                divNuevo.innerHTML = '';
                ajax=objetoAjax();
                divBorrar = document.getElementById('borrar');
                ajax.open('GET', './operaciones/BorraApfijosNBU.php?id='+id+'&codanalisis='+codanalisis+'&monto='+monto+'&periodo='+periodo+'&aleatorio='+aleatorio, true);
                divBorrar.innerHTML= '<img src="anim.gif"> :: Procesando ...';
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divBorrar.innerHTML = ajax.responseText
                    }

                }

                ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
                //enviando los valores
                ajax.send(null);
            }

            function ProcederBajaApfijosNBU(id) {
                var aleatorio = Math.random();
                divBorrar = document.getElementById('borrar');
                ajax.open('GET', './operaciones/baja_apfijosNBU.php?id='+id+'&aleatorio='+aleatorio, true);
                divBorrar.innerHTML= '<img src="anim.gif"> :: Borrnado Monto Fijo';
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divBorrar.innerHTML = ajax.responseText
                        RecargarArancelNBU();
                        //ListarArancelesNBU(codos);
                    }
                }

                ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
                //enviando los valores
                ajax.send(null);
            }

            function ValidarCodNBU(e, xcodigo){
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    return VerificarCodigo(xcodigo);
                }
            }

            function VerificarCodigo(xcodigo) {
                if (xcodigo.length != 6) {
                    alert('Codigo Incorrecto ...!');
                    return false;
                }

                var aleatorio = Math.random();
                divCod  = document.getElementById('determinacion');

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
                            document.getElementById('periodo').disabled = false;
                            document.getElementById('monto').disabled = false;
                            document.getElementById('perhasta').disabled = false;
                            document.getElementById('perbaja').disabled = false;
                            document.getElementById('registrar').disabled = false;
                        } else {
                            document.getElementById('periodo').disabled = true;
                            document.getElementById('monto').disabled = true;
                            document.getElementById('perhasta').disabled = true;
                            document.getElementById('perbaja').disabled = true;
                            document.getElementById('registrar').disabled = true;
                        }

                    }
                }
                ajaxcod.send(null);

                if (document.getElementById('periodo').disabled) {
                    return false
                } else {
                    return true
                }
            }

            function CancelarBusqueda(){
                ajax=objetoAjax();
                divBuscar = document.getElementById('buscar');
                divBuscar.innerHTML = '';
                ajax.send(null);
            }

            function Salir()  {
                var aleatorio = Math.random();
                var host = location.protocol + '//' + location.hostname + '/usuarios.php?aleatorio=' + aleatorio;
                window.location.href = host;
            }

        </script>

        <link rel="stylesheet" type="text/css" href="css1.css" />

    </head>

    <body>

        <div style="margin:auto;width:550px;text-align:center;">
            <?php include('menu_admin.php') ?>

            <div id="botones">
                <?php
                echo "<FIELDSET>";
                echo "<LEGEND>Mantenimiento Obras Sociales</LEGEND>";

                echo '<table align = "center"><tr>';
                echo '<td><a href="javascript://" onclick="Nuevo()">Nuevo</a></td>';
                echo '<td><a href="javascript://" onclick="Bajas()">Bajas</a></td>';
                echo '<td><a href="javascript://" onclick="Editar()">Editar</a></td>';
                echo '<td><a href="javascript://" onclick="Buscar()">Buscar</a></td>';
                echo '<td><a href="javascript://" onclick="QuitarFiltro()">Refrescar</a></td>';
                $trans = rand(1, 100000);
                $link = '?p111989=' . $usuario . '&reinit=' . $trans;
                echo '</tr></table>';
                echo "</FIELDSET>";
                ?>
            </div>

            <div id="nuevo">

            </div>

            <div id="buscar">
            </div>

            <div id="mensaje">
            </div>

            <div id="borrar">
            </div>

            <div id="aranceles">
            </div>

            <div id="lista_aranceles">
            </div>

            <div id="contenido">
                <?php include('operaciones/paginador_obsocial.php') ?>
            </div>

        </div>

    </body>

    <script>
        Init();
    </script>

</html>