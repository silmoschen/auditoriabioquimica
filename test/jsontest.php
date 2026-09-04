<?php

$jsonString = '{"token":"263998","nroSocio":185,"nroOrden":1,"valido":true}';

$object = json_decode($jsonString);

echo $object->valido;

?>