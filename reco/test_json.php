<?php

$json = '{
"responsable":
    {
    "Nombre" : "Juan",
    "Edad": 28,
    "Aficiones": ["Música", "Cine", "Tenis"],
    "Residencia": "Madrid"
    },
"empleados":
    [
        {
        "Nombre" : "Elena",
        "Edad": 26,
        "Aficiones": ["Música", "Cine"],
        "Residencia": "Madrid"
        },
        {
        "Nombre" : "Luis",
        "Edad": 31,
        "Aficiones": ["Teatro", "Cine", "Fútbol"],
        "Residencia": "Madrid"
        }
    ]
}';



$obj = json_decode($json, true);

echo '<pre>';
//var_dump($obj);
echo '</pre>';

echo "<hr/>";

echo $obj['responsable']['Nombre'] . ' - ' . $obj['responsable']['Residencia'];

echo '<hr/>';

echo $obj['responsable']['Aficiones'][1] . ' empleado: ' . $obj['empleados'][0]['Nombre'];

echo '<hr/>';

//echo var_dump($obj['empleados'][0]['Nombre']);


$json = '{"$id":"1","$type":"Salud.DTO.ExpedienteAutorizacion.ExpedienteAutorizacionDTO, Salud.DTO","NumeroExpedienteAutorizacion":4125037,"IdSocio":182203,"FechaPrescipcion":"2013-06-18T00:00:00","DetalleExpedienteAutorizacion":{"$id":"2","$type":"System.Collections.Generic.List`1[[Salud.DTO.ExpedienteAutorizacion.DetalleExpedienteAutorizacionDTO, Salud.DTO]], mscorlib","$values":[{"$id":"3","$type":"Salud.DTO.ExpedienteAutorizacion.DetalleExpedienteAutorizacionDTO, Salud.DTO","CodigoNomenclador":"66.04.75 ","Cantidad":1,"Descripcion":"No requiere autorizaciÃ³n","Autorizado":true},{"$id":"4","$type":"Salud.DTO.ExpedienteAutorizacion.DetalleExpedienteAutorizacionDTO, Salud.DTO","CodigoNomenclador":"66.04.12 ","Cantidad":1,"Descripcion":"No requiere autorizaciÃ³n","Autorizado":true},{"$id":"5","$type":"Salud.DTO.ExpedienteAutorizacion.DetalleExpedienteAutorizacionDTO, Salud.DTO","CodigoNomenclador":"66.09.04 ","Cantidad":1,"Descripcion":"No requiere autorizaciÃ³n","Autorizado":true},{"$id":"6","$type":"Salud.DTO.ExpedienteAutorizacion.DetalleExpedienteAutorizacionDTO, Salud.DTO","CodigoNomenclador":"66.10.95 ","Cantidad":1,"Descripcion":"Autorizado","Autorizado":true},{"$id":"7","$type":"Salud.DTO.ExpedienteAutorizacion.DetalleExpedienteAutorizacionDTO, Salud.DTO","CodigoNomenclador":"66.08.70 ","Cantidad":1,"Descripcion":"Autorizado","Autorizado":true},{"$id":"8","$type":"Salud.DTO.ExpedienteAutorizacion.DetalleExpedienteAutorizacionDTO, Salud.DTO","CodigoNomenclador":"66.00.63 ","Cantidad":1,"Descripcion":"Autorizado","Autorizado":true},{"$id":"9","$type":"Salud.DTO.ExpedienteAutorizacion.DetalleExpedienteAutorizacionDTO, Salud.DTO","CodigoNomenclador":"66.07.11 ","Cantidad":1,"Descripcion":"No requiere autorizaciÃ³n","Autorizado":true}]}}';

$obj = json_decode($json, true);

echo '<hr/><pre>';

//var_dump($obj);

echo '</pre><hr/>';

echo $obj['NumeroExpedienteAutorizacion'] . '   <pre>' . $obj['DetalleExpedienteAutorizacion'][0]['Descripcion'][0];

foreach ($obj['DetalleExpedienteAutorizacion'] as $key => $value) {
    if (is_array($value)) {
        foreach ($value as $key1 => $rs) {
            foreach ($rs as $key2 => $rss) {
                if ($key2 == 'CodigoNomenclador')
                    $cod = $rss;
                if ($key2 == 'Autorizado')
                    $aut = $rss;
            }
            
            echo $cod . ' - ' . $aut . '<br/>';
        }
    }
}

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
