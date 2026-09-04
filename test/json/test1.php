<?php
$json = '{"$id":"1","$type":"ServiciosJs.DTO.Practica.PracticaRequiereAutorizacionConvenioDTO, ServiciosJs.DTO","CodigoNomenclador":"66.04.75 ","CodigoNomencladorConvenio":"660475","Descripcion":"HEMOGRAMA","IdConvenio":207,"RequiereAutorizacion":false}';

echo $json;

echo '<hr/>';

$obj = json_decode($json);

if ($obj->{RequiereAutorizacion}) echo 'true'; else echo 'false';

 for ($i = 0; $i <= 10; $i++) {
     
     echo $i . '<br/>';
     
 }
 
 

?>
