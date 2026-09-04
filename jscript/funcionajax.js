/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function objetoAjax(){
    // Crea una variable bool para comprobar si el navegador es IE
    var xmlhttp=false;
    try {   // Comprobar si estamos en IE
        // Si la versión JavaScript es superior a la 5
        xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
        try {
            // Si no, utilizar el antiguo control ActiveX
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        } catch (E) {
            // No estamos utilizando IE
            xmlhttp = false;
        }
    }

    if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
        // En cualquier otro navegador, creamos un objeto JavaScript
        xmlhttp = new XMLHttpRequest();
    }
    return xmlhttp;
}

function verificarIE(){
    var xmlhttp1=false;
    var r;
    try {
        xmlhttp1 = new ActiveXObject("Msxml2.XMLHTTP");
        r = true;
    } catch (e) {
        try {
            xmlhttp1 = new ActiveXObject("Microsoft.XMLHTTP");
            r = true;
        } catch (E) {
            r = false;
        }
    }

    if (!xmlhttp1 && typeof XMLHttpRequest!='undefined') {
        xmlhttp1 = new XMLHttpRequest();
        r = false;
    }

    return r;
}

