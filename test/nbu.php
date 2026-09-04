<?php

include_once($_SERVER['DOCUMENT_ROOT'] . "/reco/classes/cNBU.php");

$nbu = new cNBU();

echo $nbu->getPracticaAdicional('121057', '660475');


?>

