<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$lector = simplexml_load_file("xml1.xml");

echo $lector->EncabezadoMensaje->Rta->MensajeDisplay;

$m = $lector->EncabezadoMensaje->Rta->MensajePrinter;

if (strpos($m, "SOLICITUD RECHAZADA") > 0) echo '<h1>Rechazada</h1>';

echo '<br/>';

for ($i = 0; $i <= 100; $i++) {
    if ($lector->DetalleProcedimientos[$i]->NroItem == '')
        break;
    $_es = 'R';
    if ($lector->DetalleProcedimientos[$i]->MensajeRta == 'Autorizado')
        $_es = 'A';

    /*
    if ($prefijo == '') {
        $_cod = $lector->DetalleProcedimientos[$i]->CodPrestacion;
    } else {
        $c = $lector->DetalleProcedimientos[$i]->CodPrestacion;
        $_cod = $prefijoinverso . substr($c, $longitud, strlen($c) - $longitud);
    }

    if ($_es == 'R')
        $diferida = 'S';

    */
    
    echo $lector->DetalleProcedimientos[$i]->CodPrestacion . '  ' . $_es . '<br/>';
}
?>

