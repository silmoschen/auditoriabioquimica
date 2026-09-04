/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function LlenarIzquierda(cadena, largo, caracter) {
    var r = '';
    var l = largo - cadena.length;
    for (i = 1; i <= l; i++) {
        r = r + caracter;
    }

    return r + cadena;
}

function lTrim(sStr) {
    while (sStr.charAt(0) == " ")
        sStr = sStr.substr(1, sStr.length - 1);
    return sStr;
}

function rTrim(sStr) {
    while (sStr.charAt(sStr.length - 1) == " ")
        sStr = sStr.substr(0, sStr.length - 1);
    return sStr;
}

function allTrim(sStr) {
    return rTrim(lTrim(sStr));
}

function ValidarFecha(Cadena) {
    // Valida Fecha By Luciano 1998
    // Uso: Simple... se debe pasar la cadena de la fecha y devuelve false si no es válida...
    // El Formato es dd-mm-aaaa
    // Ejemplo: if (Validar('14-08-1981')==false) { alert('Entrada Incorrecta') }
    // Uso en formularios: onSubmit="return Validar(this.fecha.value)"
    //
    // Este script y otros muchos pueden
    // descarse on-line de forma gratuita
    // en El Código: www.elcodigo.com

    var Fecha = new String(Cadena)	// Crea un string

    if (Fecha.length != 10) {
        alert('Fecha Incorrecta, el formato es dd/mm/aaaa ...!');
        return false;
    }

    var RealFecha = new Date();	// Para sacar la fecha de hoy
    // Cadena Año
    var Ano = new String(Fecha.substring(Fecha.lastIndexOf("/") + 1, Fecha.length));
    // Cadena Mes
    var Mes = new String(Fecha.substring(Fecha.indexOf("/") + 1, Fecha.lastIndexOf("/")));
    // Cadena Día
    var Dia = new String(Fecha.substring(0, Fecha.indexOf("/")));

    if (Ano.length != 4 && Mes.length != 2 && Dia.length != 2) {
        alert('Fecha Incorrecta, el formato es dd/mm/aaaa ...!');
        return false;
    }

    // Valido el año
    if (isNaN(Ano) || Ano.length < 4 || parseFloat(Ano) < 1900) {
        alert('Año inválido');
        return false;
    }
    // Valido el Mes
    if (isNaN(Mes) || parseFloat(Mes) < 1 || parseFloat(Mes) > 12) {
        alert('Mes inválido');
        return false;
    }
    // Valido el Dia
    if (isNaN(Dia) || parseInt(Dia, 10) < 1 || parseInt(Dia, 10) > 31) {
        alert('Día inválido');
        return false;
    }
    if (Mes == 4 || Mes == 6 || Mes == 9 || Mes == 11 || Mes == 2) {
        if (Mes == 2 && Dia > 29 || Dia > 30) {
            alert('Día inválido');
            return false;
        }
    }

    return true;
}

function VerificarPeriodo(xperiodo) {
    var periodo = '';
    periodo = xperiodo;
    if (periodo.length != 7) {
        alert('Período Incorrecto ...!');
        return false;
    } else {
        var m = xperiodo.substring(0, 2);
        var a = xperiodo.substring(3, 7);
        if (m > '00' && m < '13') {
            return true;
        } else {
            alert('Período Incorrecto ...!');
            return false;
        }
    }

    return true;
}

function ValidarNumero(str) {
    if (str.length == 0) {
        alert('El Número es Incorrecto ...!');
        return false;
    }
    if (isNaN(str)) {
        alert('El Número es Incorrecto ...!');
        return false;
    }
    return true;
}

function sanitizeString(str) {
    str = str.replace(/([^a-z0-9áéíóúñü_-\s\.,]|[\t\n\f\r\v\0])/gim, "");
    return str.trim();
}