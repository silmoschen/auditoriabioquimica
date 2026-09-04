


<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$client = new SoapClient(null,
                array(
                    'location' => 'http://localhost/test/ws/service.php',
                    'uri' => 'urn:webservices',
        ));


// Llamar el metodo como si fuera del cliente
print_r($client->__getFunctions());
echo $client->sumar(3, 4);
?>
