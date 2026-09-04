<?php

$js = '[
    {
        "Tipo": "B",
        "Codigo": 660001,
        "Descripción": "ACTO BIOQUIMICO"
    }
]';

$js = '[]';

$result = json_decode($js);

$result[0]->Codigo;

if ($result[0]->Codigo != '') echo 'si'; else echo 'no';


?>
