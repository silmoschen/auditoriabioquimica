<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$j = '{
    "cabecera": {
        "transacAlta": "20190729 16:33:57",
        "transac": 196192985,
        "rechaCabecera": 0,
        "rechaCabeDeno": "OK",
        "apeNom": null,
        "gravado": null,
        "planCodi": null,
        "pmi": null,
        "sexo": null,
        "edad": null,
        "leyimp": null
    },
    "detalle": null
}';

$result = json_decode($j);
    echo '<hr/>' . $result->cabecera->rechaCabeDeno;

?>
