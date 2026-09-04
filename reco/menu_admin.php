<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<div style="margin:auto;width:550px;text-align:center;" />

<?php
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/conexion.php');

echo '<input type="hidden" id="ruta" value=' . $ruta . '/>';

if (!isset($_SESSION['susuario']) || $_SESSION["nivel"] != 1) {
  echo '<h1>Se ha Producido un Error al Intentar Ingresar al Sitio</h1>';
  ?>
  <script type="text/javascript">;
    function redireccionar(){
        var aleatorio = Math.random();
        var host = location.protocol + '//' + location.hostname  + '/'+ document.getElementById('ruta').value + 'login.php?aleatorio='+aleatorio;
        window.location.href = host;
    }
    setTimeout ("redireccionar()", 1000); //tiempo expresado en milisegundos
  </script>
  <?php
  exit;
}
?>

<?php
include_once('__routes.php');
include('operaciones/entidad1.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cUtiles.php');
$utiles = new cUtiles;
$t = rand(1, 999999);
$tr = substr($t, 0, 6);
$trans = substr($utiles->StringLlenarDerecha($tr, 6, '0'), 0, 6);
?>

<style type="text/css">
    /* Estilos de configuraci�n del men� desplegable.*/

    #dhtmlgoodies_menu ul li ul{
        display:none; /* Necesario para visualizarse bien en opera */
    }

    #dhtmlgoodies_menu{
        visibility:hidden;
    }
    #dhtmlgoodies_menu ul{
        margin:0px; /* Sin sangria */
        padding:0px; /* Sin sangria */
    }
    #dhtmlgoodies_menu li{
        list-style-type:none; /* Sin iconos */
    }
    #dhtmlgoodies_menu a{
        margin:0px;
        padding:0px;
    }
    /* Estilos de la est�tica del men�. Aqu� puedes modificarlos de acuerdo al estilo de tu web
    Estilos que se aplican a todo el men�*/

    #dhtmlgoodies_menu ul{
        border:1px solid #000;
        background-color:#FFF;
        padding:1px;
    }
    #dhtmlgoodies_menu ul.menuBlock1{ /* Barra de men� - elementos del menu principal*/
        border:0px;
        padding:1px;
        background-color:#CCCCCC;
        overflow:visible;
    }
    #dhtmlgoodies_menu ul.menuBlock2{ /* Barra de men� - elementos del menu principal*/
        border:0px;
        padding:1px;
        border:1px solid #555;
    }
    #dhtmlgoodies_menu a{
        color: #000;
        text-decoration:none;
        padding-left:2px;
        padding-right:2px;
    }

    /* Estilos del men� principal. El que esta siempre visible */

    #dhtmlgoodies_menu .currentDepth1{
        padding-left:5px;
        padding-right:5px;
        border:1px solid #E2EBED;
    }
    #dhtmlgoodies_menu .currentDepth1over{
        padding-left:5px;
        padding-right:5px;
        background-color:#317082;
        border:1px solid #000;
    }
    #dhtmlgoodies_menu .currentDepth1 a{
        font-weight:bold;
    }
    #dhtmlgoodies_menu .currentDepth1over a{
        color:#FFF;
        font-weight:bold;
    }

    /* Estilos submenu nivel 1 */

    #dhtmlgoodies_menu .currentDepth2{
        padding-right:2px;
        border:1px solid #FFF;
        width:250px;
    }
    #dhtmlgoodies_menu .currentDepth2over{
        padding-right:2px;
        background-color:#E2EBED;
        border:1px solid #000;
        width:250px;
    }
    #dhtmlgoodies_menu .currentDepth2over a{
        color:#000;
    }

    /* Estilos submenu nivel 2 */

    #dhtmlgoodies_menu .currentDepth3{
        padding-right:2px;
        border:1px solid #FFF;
    }
    #dhtmlgoodies_menu .currentDepth3over{
        padding-right:2px;
        background-color:#EDE3EB;
        border:1px solid #000;
    }

    /* Estilos submenu nivel 3 */

    #dhtmlgoodies_menu .currentDepth4{
        padding-right:2px;
        border:1px solid #FFF;
    }
    #dhtmlgoodies_menu .currentDepth4over{
        padding-right:2px;
        background-color:#EBEDE3;
        border:1px solid #000;
    }

</style>

<script type="text/javascript">

    /************************************************************************************************************
(C) www.dhtmlgoodies.com, October 2005

Update log:
Version 1.1 December, 1st 2005: Critical update for the new Firefox 1.5 browser
Version 1.2: December, 21th 2005 : Mouseover effect when mouse moves outside of a submenu items text

This is a script from www.dhtmlgoodies.com. You will find this and a lot of other scripts at our website.

Terms of use:
You are free to use this script as long as the copyright message is kept intact. However, you may not
redistribute, sell or repost it without our permission.

Thank you!

www.dhtmlgoodies.com
Alf Magne Kalleland

     ************************************************************************************************************/

    var dhtmlgoodies_menuObj; // Referenc�a al div del men�
    var currentZIndex = 1000;
    var liIndex = 0;
    var visibleMenus = new Array();
    var activeMenuItem = false;
    var timeBeforeAutoHide = 1200; // Cantidad de microsegundos antes de que los men�s se oculten automaticamente
    var dhtmlgoodies_menu_arrow = 'anim.gif'; //Ruta a la imagen de flecha hacia abajo

    var MSIE = navigator.userAgent.indexOf('MSIE')>=0?true:false;
    var navigatorVersion = navigator.appVersion.replace(/.*?MSIE ([0-9]\.[0-9]).*/g,'$1')/1;
    var menuBlockArray = new Array();
    var menuParentOffsetLeft = false;
    function getTopPos(inputObj)
    {

        var returnValue = inputObj.offsetTop;
        if(inputObj.tagName=='LI' && inputObj.parentNode.className=='menuBlock1'){
            var aTag = inputObj.getElementsByTagName('A')[0];
            if(aTag)returnValue += aTag.parentNode.offsetHeight;
        }
        while((inputObj = inputObj.offsetParent) != null)returnValue += inputObj.offsetTop;
        return returnValue;
    }

    function getLeftPos(inputObj)
    {
        var returnValue = inputObj.offsetLeft;
        while((inputObj = inputObj.offsetParent) != null)returnValue += inputObj.offsetLeft;
        return returnValue;
    }

    function showHideSub()
    {
        var attr = this.parentNode.getAttribute('currentDepth');
        if(navigator.userAgent.indexOf('Opera')>=0){
            attr = this.parentNode.currentDepth;
        }

        this.className = 'currentDepth' + attr + 'over';

        if(activeMenuItem && activeMenuItem!=this){
            activeMenuItem.className=activeMenuItem.className.replace(/over/,'');
        }
        activeMenuItem = this;

        var numericIdThis = this.id.replace(/[^0-9]/g,'');
        var exceptionArray = new Array();
        // Mostrar subitem LI
        var sub = document.getElementById('subOf' + numericIdThis);
        if(sub){
            visibleMenus.push(sub);
            sub.style.display='';
            sub.parentNode.className = sub.parentNode.className + 'over';
            exceptionArray[sub.id] = true;
        }

        // Showing parent items of this one

        var parent = this.parentNode;
        while(parent && parent.id && parent.tagName=='UL'){
            visibleMenus.push(parent);
            exceptionArray[parent.id] = true;
            parent.style.display='';

            var li = document.getElementById('dhtmlgoodies_listItem' + parent.id.replace(/[^0-9]/g,''));
            if(li.className.indexOf('over')<0)li.className = li.className + 'over';
            parent = li.parentNode;

        }

        hideMenuItems(exceptionArray);
    }
    function hideMenuItems(exceptionArray)
    {
        /*
Ocultar el men� visible en ese momento
         */
        var newVisibleMenuArray = new Array();
        for(var no=0;no<visibleMenus.length;no++){
            if(visibleMenus[no].className!='menuBlock1' && visibleMenus[no].id){
                if(!exceptionArray[visibleMenus[no].id]){
                    var el = visibleMenus[no].getElementsByTagName('A')[0];
                    visibleMenus[no].style.display = 'none';
                    var li = document.getElementById('dhtmlgoodies_listItem' + visibleMenus[no].id.replace(/[^0-9]/g,''));
                    if(li.className.indexOf('over')>0)li.className = li.className.replace(/over/,'');
                }else{
                    newVisibleMenuArray.push(visibleMenus[no]);
                }
            }
        }
        visibleMenus = newVisibleMenuArray;
    }


    var menuActive = true;
    var hideTimer = 0;
    function mouseOverMenu()
    {
        menuActive = true;
    }

    function mouseOutMenu()
    {
        menuActive = false;
        timerAutoHide();
    }

    function timerAutoHide()
    {
        if(menuActive){
            hideTimer = 0;
            return;
        }

        if(hideTimer<timeBeforeAutoHide){
            hideTimer+=100;
            setTimeout('timerAutoHide()',99);
        }else{
            hideTimer = 0;
            autohideMenuItems();
        }
    }

    function autohideMenuItems()
    {
        if(!menuActive){
            hideMenuItems(new Array());
            if(activeMenuItem)activeMenuItem.className=
                activeMenuItem.className.replace(/over/,'');
        }
    }


    function initSubMenus(inputObj,initOffsetLeft,currentDepth)
    {
        var subUl = inputObj.getElementsByTagName('UL');
        if(subUl.length>0){
            var ul = subUl[0];

            ul.id = 'subOf' + inputObj.id.replace(/[^0-9]/g,'');
            ul.setAttribute('currentDepth' ,currentDepth);
            ul.currentDepth = currentDepth;
            ul.className='menuBlock' + currentDepth;
            ul.onmouseover = mouseOverMenu;
            ul.onmouseout = mouseOutMenu;
            currentZIndex+=1;
            ul.style.zIndex = currentZIndex;
            menuBlockArray.push(ul);
            var topPos = getTopPos(inputObj);
            var leftPos = getLeftPos(inputObj)/1 + initOffsetLeft/1;
            ul = dhtmlgoodies_menuObj.appendChild(ul);
            ul.style.position = 'absolute';
            ul.style.left = leftPos + 'px';
            ul.style.top = topPos + 'px';
            var li = ul.getElementsByTagName('LI')[0];
            while(li){
                if(li.tagName=='LI'){
                    li.className='currentDepth' + currentDepth;
                    li.id = 'dhtmlgoodies_listItem' + liIndex;
                    liIndex++;
                    var uls = li.getElementsByTagName('UL');
                    li.onmouseover = showHideSub;
                    if(uls.length>0){
                        var offsetToFunction = li.getElementsByTagName('A')[0].offsetWidth+2;
                        if(navigatorVersion<6 && MSIE)offsetToFunction+=15; // MSIE 5.x fix
                        initSubMenus(li,offsetToFunction,(currentDepth+1));
                    }
                    if(MSIE){
                        var a = li.getElementsByTagName('A')[0];
                        a.style.width=li.offsetWidth+'px';
                        a.style.display='block';
                    }
                }
                li = li.nextSibling;
            }
            ul.style.display = 'none';
            if(!document.all){
                //dhtmlgoodies_menuObj.appendChild(ul);
            }
        }
    }

    function resizeMenu()
    {
        var offsetParent = getLeftPos(dhtmlgoodies_menuObj);

        for(var no=0;no<menuBlockArray.length;no++){
            var leftPos = menuBlockArray[no].style.left.replace('px','')/1;
            menuBlockArray[no].style.left = leftPos + offsetParent - menuParentOffsetLeft + 'px';
        }
        menuParentOffsetLeft = offsetParent;
    }

    /*
Inicializaci�n del men�
     */
    function initDhtmlGoodiesMenu()
    {
        dhtmlgoodies_menuObj = document.getElementById('dhtmlgoodies_menu');


        var aTags = dhtmlgoodies_menuObj.getElementsByTagName('A');
        for(var no=0;no<aTags.length;no++){
            var subUl = aTags[no].parentNode.getElementsByTagName('UL');
            if(subUl.length>0 && aTags[no].parentNode.parentNode.parentNode.id != 'dhtmlgoodies_menu'){
                var img = document.createElement('IMG');
                img.src = dhtmlgoodies_menu_arrow;
                aTags[no].appendChild(img);
            }
        }

        var mainMenu = dhtmlgoodies_menuObj.getElementsByTagName('UL')[0];
        mainMenu.className='menuBlock1';
        mainMenu.style.zIndex = currentZIndex;
        mainMenu.setAttribute('currentDepth' ,1);
        mainMenu.currentDepth = '1';
        mainMenu.onmouseover = mouseOverMenu;
        mainMenu.onmouseout = mouseOutMenu;
        var mainMenuItemsArray = new Array();
        var mainMenuItem = mainMenu.getElementsByTagName('LI')[0];
        mainMenu.style.height = mainMenuItem.offsetHeight + 2 + 'px';
        while(mainMenuItem){

            mainMenuItem.className='currentDepth1';
            mainMenuItem.id = 'dhtmlgoodies_listItem' + liIndex;
            mainMenuItem.onmouseover = showHideSub;
            liIndex++;
            if(mainMenuItem.tagName=='LI'){
                mainMenuItem.style.cssText = 'float:left;';
                mainMenuItem.style.styleFloat = 'left';
                mainMenuItemsArray[mainMenuItemsArray.length] = mainMenuItem;
                initSubMenus(mainMenuItem,0,2);
            }

            mainMenuItem = mainMenuItem.nextSibling;

        }
        for(var no=0;no<mainMenuItemsArray.length;no++){
            initSubMenus(mainMenuItemsArray[no],0,2);
        }

        menuParentOffsetLeft = getLeftPos(dhtmlgoodies_menuObj);
        window.onresize = resizeMenu;
        dhtmlgoodies_menuObj.style.visibility = 'visible';
    }

    window.onload = initDhtmlGoodiesMenu;
</script>

<link rel="stylesheet" type="text/css" href="css1.css">

<div id="dhtmlgoodies_menu" align="left">
    <ul>

        <!-- Creamos la primer pesta�a del men�: Blogger -->
        <!-- Metemos la primera opci�n -->
        <li><a href="">Auditorias</a>
            <ul> <!--Ahora un submenu nivel 1 para Blogger con un par de opciones -->
                <?php
                echo '<li><a href="/' . $ruta . '/auditoria_de_ordenesgeneradas.php">Auditoría de Ordenes Diferidas</a></li>';
                echo '<li><a href="/' . $ruta . '/consulta_de_ordenesgeneradas.php">Consulta de Ordenes Ingresadas</a></li>';
                echo '<li><a href="/' . $ruta . '/autorizacion_ordenusuario_admin.php">Validación de Prácticas en Orden</a></li>';
                echo '<li><a href="/' . $ruta . '/anular_ordenes_auditor.php">Anulación de Ordenes</a></li>';
                echo '<li><a href="/' . $ruta . '/ingresoordenesusuarioadminist.php">Ingreso de Ordenes (por Lote)</a></li>';
                echo '<li><a href="/' . $ruta . '/consulta_de_ordenes_administ.php">Consulta de Ordenes (por Lote)</a></li>';
                echo '<li><a href="/' . $ruta . '/consulta_de_bonos.php">Consulta Cantidad de Bonos</a></li>';
                echo '<li><a href="/' . $ruta . '/consultar_histclinica_pac.php">Consulta de Historias Clínicas</a></li>';
                echo '<li><a href="/' . $ruta . '/consultar_histclinica_pac_prof.php">Consulta de Pedidos por Médico</a></li>';
                echo '<li><a href="/' . $ruta . '/recalcular_monto_ordenes.php">Recalcular Montos en Determinaciones</a></li>';
                echo '<li><a href="/' . $ruta . '/recalcular_monto_ordenes_scos.php">Suprimir Coseguro por Tramos (Fecha)</a></li>';
                echo '<li><a href="/' . $ruta . '/depurar_ordenes.php">Depuración de Ordenes</a></li>';
                echo '<li><a href="/' . $ruta . '/depurar_logs_wsres.php">Depuración Logs Web Services</a></li>';
                echo '<li><a href="/' . $ruta . '/cerrar_session.php?' . $trans . '">Salir </a>';
                ?>
            </ul>
        </li>

        <!-- Ponemos una segunda pesta�a en el men� con la opci�n Javascript por ejemplo-->
        <li><a href="">Definiciones</a>
            <ul> <!-- Ponemos un submen� nivel 1 para la opci�n trucos con 1 opci�n-->
                <?php
                echo '<li><a href="/' . $ruta . '/modelos.php">Definición de Frecuencias y Diagnósticos</a></li>';
                echo '<li><a href="/' . $ruta . '/copiar_perfiles.php">Copiar Perfiles de Diagnosticos</a></li>';
                echo '<li><a href="/' . $ruta . '/codigos_diferidos.php">Códigos NBU para Auditoría Diferida</a></li>';
                echo '<li><a href="/' . $ruta . '/codigos_restringidos.php">Restricción en Ingreso de Códigos NBU</a></li>';
                echo '<li><a href="/' . $ruta . '/codigosexcluidos.php">Inclusión de Códigos en Prácticas NBU</a></li>';
                echo '<li><a href="/' . $ruta . '/codigos_equivalencia.php">Equivalencia Códigos NBU O. Soc. Exportación</a></li>';
                echo '<li><a href="/' . $ruta . '/codigos_equivalencia_nbu.php">Equivalencia Códigos NBU Obras Sociales</a></li>';
                echo '<li><a href="/' . $ruta . '/tramos.php">Tramos y Cantidad de Bonos</a></li>';
                echo '<li><a href="/' . $ruta . '/obsociales_planes.php">Planes en Obras Sociales</a></li>';
                echo '<li><a href="/' . $ruta . '/coseguroboletas.php">Montos Fijos Coseguros Boletas</a></li>';
                echo '<li><a href="/' . $ruta . '/nbufed.php">Topes de Prácticas en Ordenes</a></li>';
                echo '<li><a href="/' . $ruta . '/equivalencia_os_padrones.php">Unificar Padrón Afiliados en Obras Sociales</a></li>';
                echo '<li><a href="/' . $ruta . '/copiar_medicos.php">Copiar Médicos a otra Obra Social</a></li>';
                echo '<li><a href="/' . $ruta . '/excluir_efectores.php">Excluir Efectores de Obras Sociales</a></li>';
                echo '<li><a href="/' . $ruta . '/leyenda_obrasocial.php">Leyenda Cupones Obras Sociales</a></li>';
                echo '<li><a href="/' . $ruta . '/obsociales_notificaciones.php">Notificaciones Obras Sociales</a></li>';
                echo '<li><a href="/' . $ruta . '/obsociales_excluir_validacion.php">Obras Sociales Excluir Validación</a></li>';
                echo '<li><a href="/' . $ruta . '/entidades.php">Datos de la Entidad</a></li>';
                ?>
            </ul>
            <!-- Y por �ltimo una pesta�a con la opci�n Photoshop con s�lo una opci�n-->
        <li><a href="">Utilidades</a>
            <ul><!-- Ponemos una submen� nivel 1 para esta opcion Tutoriales-->
                <?php
                echo '<li><a href="/' . $ruta . '/estadisticas_autorizadas_rechazadas.php">Estadística Det. Autorizadas y Rechazadas</a></li>';
                echo '<li><a href="/' . $ruta . '/estadisticas_pedidos_medicos.php">Estadística de Pedidos por Médico</a></li>';
                echo '<li><a href="/' . $ruta . '/exportar_ordenes_soportemag.php">Exportación de Ordenes</a></li>';
                echo '<li><a href="/' . $ruta . '/inf_coseguros.php">Informe de Coseguros</a></li>';
                echo '<li><a href="/' . $ruta . '/exportar_ordenes_estadisticas.php">Exportación de Determinaciones Pedidas</a></li>';
                echo '<li><a href="/' . $ruta . '/exportar_detalle_ordenes.php">Exportación Detalle de Ordenes</a></li>';
                echo '<li><a href="/' . $ruta . '/consulta_resumen_obrassociales.php">Cuadro de Consumos</a></li>';
                echo '<li><a href="/' . $ruta . '/consulta_coseguros_obrassociales.php">Coseguros por Obras Sociales</a></li>';
                echo '<li><a href="/' . $ruta . '/consulta_coseguros_profesionales.php">Coseguros por Profesional</a></li>';
                echo '<li><a href="/' . $ruta . '/consultar_logs_ws.php">Consultar Log Tareas WS</a></li>';
                ?>
            </ul>
        </li>
        <!-- Y por �ltimo una pesta�a con la opci�n Photoshop con s�lo una opci�n-->
        <li><a href="">Archivo</a>
            <ul><!-- Ponemos una submen� nivel 1 para esta opcion Tutoriales-->
                <?php
                echo '<li><a href="/' . $ruta . '/afiliados.php">Padrón de Afiliados a Obras Sociales</a></li>';
                echo '<li><a href="/' . $ruta . '/obsocial.php">Nómina de Obras Sociales</a></li>';
                echo '<li><a href="/' . $ruta . '/capitas.php">Establecer Cápita en Obra Social</a></li>';
                echo '<li><a href="/' . $ruta . '/efectores.php">Efectores y Laboratorios</a></li>';
                echo '<li><a href="/' . $ruta . '/diagnosticos_oms.php">Tabla de Diagnósticos</a></li>';
                echo '<li><a href="/' . $ruta . '/medicos.php">Médicos Prestadores de Servicio</a></li>';
                echo '<li><a href="/' . $ruta . '/medicos_cab.php">Médicos de Cabecera</a></li>';
                echo '<li><a href="/' . $ruta . '/nbu.php">Nomenclador Unico Bioquímico</a></li>';
                echo '<li><a href="/' . $ruta . '/especialidades.php">Tabla de Especialidades</a></li>';
                echo '<li><a href="/' . $ruta . '/tipos_documento.php">Tipos de Documento</a></li>';
                echo '<li><a href="/' . $ruta . '/tipos_prestadores.php">Tipos de Prestadores</a></li>';
                ?>
                <!--
                <li><a href="/act_padron.php?init=1">Actualizar Padron Obra Social</a></li>
                -->
            </ul>
        </li>
        <!-- Y por �ltimo una pesta�a con la opci�n Photoshop con s�lo una opci�n-->
        <?php
        if ($_SESSION["susuario"] == 'administ') {
            echo '<li><a href="">Usuarios</a>';
            echo '<ul>';
            echo '<li><a href="/' . $ruta . '/alta_usuarios.php">Definición de Nuevos Usuarios</a></li>';
            echo '<li><a href="/' . $ruta . '/alta_usuarios_auditores_tramos.php">Tramo Auditores</a></li>';
            echo '<li><a href="/' . $ruta . '/consultar_logs.php">Consultar Log de Usuarios</a></li>';
            echo '<li><a href="/' . $ruta . '/depurar_logs.php">Depurar Log de Usuarios</a></li>';
            echo '</ul>';
            echo '</li>';
        }
        ?>
        <!-- Y por �ltimo una pesta�a con la opci�n Photoshop con s�lo una opci�n-->
        <li><a href="">Perfil</a>
            <ul><!-- Ponemos una submen� nivel 1 para esta opcion Tutoriales-->
                <?php
                echo '<li><a href="/' . $ruta . '/cambiar_pass_admin.php">Cambio de Contraseña de Usuario</a></li>';
                ?>
            </ul>
        </li>

    </ul>
</div>