<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$idprof = $_REQUEST['idprof'];

$archivo1 = 'cab_auditoria_' . $idprof . '.txt';
$archivo2 = 'det_auditoria_' . $idprof . '.txt';
$archivo3 = 'pac_auditoria_' . $idprof . '.txt';

unlink('../actualizar/' . $archivo1);
unlink('../actualizar/' . $archivo2);
unlink('../actualizar/' . $archivo3);

?>
