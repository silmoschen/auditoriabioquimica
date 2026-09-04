<?php
    $url = "https://caws.acasalud.com.ar:4444/cawsProd/Servicios?WSDL";
    $opts = array(
                  'ssl' => array(
                                 'ciphers' => 'RC4-SHA',
                                 'verify_peer' => false,
                                 'verify_peer_name' => false
                                 )
                  );
    $params = array(
                    'encoding' => 'UTF-8',
                    'verifypeer' => false,
                    'verifyhost' => false,
                   // 'soap_version' => SOAP_1_2,
                    'trace' => 1,
                    'location' => $url,
                    'exceptions' => 1,
                    'connection_timeout' => 180,
                    'stream_context' => stream_context_create($opts)
                    );
    
    try {
        $client = new SoapClient($url, $params);
    
        echo "<hr/>";
        
        //TEST MENSAJE
        echo "<h4>TEST</h4>";
        echo $client->testWs('Marcelo');
        echo "<hr/>";
    } catch (Exception $e)  {
        echo ($e->getMessage());
        echo ($client->__getLastRequest());
        echo ($client->__getLastResponse());
    }
    
   
    
    ?>
