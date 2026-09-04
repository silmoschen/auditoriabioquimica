<?php

//$url = "https://cawsdes.acasalud.com.ar:8004/cawsTest/Servicios?WSDL";
$url = "https://caws.acasalud.com.ar:4444/cawsProd/Servicios?WSDL";
//$url = "https://caws.acasalud.com.ar:4444/cawsProd/Servicios?operation=transaccionStr";

$options["connection_timeout"] = 25;
$options["location"] = $url;
$options['trace'] = 1;

$client = new SoapClient($url, $options);

//IMPRIME LAS FUNCIONES
echo "<h4>Funciones WS</h4>";
print_r($client->__getFunctions());
echo "<hr/>";

//TEST MENSAJE
echo "<h4>TEST</h4>";
echo $client->testWs('Silvio');
echo "<hr/>";

// 2013-01-01T09:30:47-05:00

$datetime = new DateTime();

echo '<H1>Transaccion ELG</H1>';


$msg = '                             <SOLICITUD>
                                          <EMISOR>                               
                             <ID>00001-22222</ID>                   
                                  <PROT>CA_V20</PROT>                    
                                  <MSGID>10001</MSGID>                   
                               <TER>CBLNSF</TER>                    
                               <APP>autorizacion_online_aca</APP>                  
                 <TIME>' . $datetime->format('Y/m/d H:i:s') . '</TIME> 
                                            </EMISOR>                              
                                        <SEGURIDAD>                            
                              <TIPOAUT>U</TIPOAUT>                   
                                <USRID>7001110001</USRID>              
                           <USRPASS>DAT_MGR</USRPASS>             
                                           </SEGURIDAD>                           
                                                <OPER>                                 
                                       <TIPO>ELG</TIPO>                       
                              <FECHA>2013-01-01</FECHA>              
                     <IDASEG>ACA_SALUD</IDASEG>             
                   <IDPRESTADOR>7001110001</IDPRESTADOR>  
                                          </OPER>                                
                                             <PID>                                  
                                    <ID>59999900</ID>                      
                                   <VERIFID>MANUAL</VERIFID>              
                                           </PID>
                                           
<CONTEXTO>
<TIPO>A</TIPO>
</CONTEXTO>

<PR>
<TIPO>P</TIPO>
<ID>030475</ID>
<CANT>1</CANT>
</PR>

<PR>
<TIPO>P</TIPO>
<ID>030412</ID>
<CANT>1</CANT>
</PR>

<PR>
<TIPO>P</TIPO>
<ID>030711</ID>
<CANT>1</CANT>
</PR>

<PR>
<TIPO>P</TIPO>
<ID>030001</ID>
<CANT>1</CANT>
</PR>


                                    </SOLICITUD>  ';


//echo '<pre>' . $msg .'</pre>';

echo "<h4>TRANSACCION</h4>";
$resultados = $client->transaccionStr($msg);
echo $resultados;
echo "<hr/>";

// PARSE XML
echo "<h4>XML RETURN</h4>";
$lector = new SimpleXMLElement($resultados);
//echo '<pre>' . $lector->asXML() . '</pre>' ;

for ($i = 0; $i <= 10; $i++) {
    if ($lector->PR[$i]->ID == '')
        break;
    echo $lector->PR[$i]->ID . ' - ' . $lector->PR[$i]->STATUS . '<br/>';
}

echo "<br/>";

echo $lector->AFIAPE;

$datetime = new DateTime();
echo '<hr>' . '2013-01-01T09:30:47-05:00 -  ' . $datetime->format('Y/m/d H:i:s'); // Prints "2011/03/20 07:16:17"



echo '<H1>Transaccion AP</H1>';

$msg = '                             
<SOLICITUD>
<EMISOR>
<ID>00001-22222</ID>
<PROT>CA_V20</PROT>
<MSGID>10001</MSGID>
<TER>Web</TER>
<APP>HMS_CAWeb</APP>

<TIME>2014-04-07T09:30:47-05:00</TIME>
</EMISOR>
<SEGURIDAD>
<TIPOAUT>U</TIPOAUT>
<USRID>7001110001</USRID>
<USRPASS>DAT_MGR</USRPASS>
</SEGURIDAD>
<OPER>
<TIPO>AP</TIPO>
<FECHA>2014-04-07</FECHA>
<IDASEG>ACA_SALUD</IDASEG>
<IDPRESTADOR>7001110001</IDPRESTADOR>
</OPER>
<PID>
<ID>59999900</ID>
</PID>

<PRESCRIP>
 <ORG>MP S</ORG>
 <MAT>2599/01102125</MAT>
 <FECHA>2014-04-07</FECHA>
</PRESCRIP>

<CONTEXTO>
<TIPO>A</TIPO>
</CONTEXTO>

<PR>
<TIPO>P</TIPO>
<ID>030475</ID>
<CANT>1</CANT>
</PR>

<PR>
<TIPO>P</TIPO>
<ID>030711</ID>
<CANT>1</CANT>
</PR>

<PR>
<TIPO>P</TIPO>
<ID>030001</ID>
<CANT>1</CANT>
</PR>

</SOLICITUD>        
';

echo "<h4>TRANSACCION</h4>";
$resultados = $client->transaccionStr($msg);
echo $resultados;
echo "<hr/>";

// PARSE XML
echo "<h4>XML RETURN</h4>";
$lector = new SimpleXMLElement($resultados);
//echo '<pre>' . $lector->asXML() . '</pre>' ;

$transaccion = $lector->IDTRAN;
     
for ($i = 0; $i <= 10; $i++) {
    if ($lector->PR[$i]->ID == '')
        break;
    echo 'Trans.: ' . $transaccion - ' ' . $lector->PR[$i]->ID . ' - ' . $lector->PR[$i]->STATUS . ' - ' . $lector->PR[$i]->RSPMSGP . ' -  ' . $lector->PR[$i]->RSPMSGPADIC .  '<br/>';
}

echo "<br/>";

echo '<H1>Anulacion Transaccion AP</H1>';

$msg = '                             
<SOLICITUD>
<EMISOR>
<ID>00001-22222</ID>
<PROT>CA_V20</PROT>
<MSGID>10001</MSGID>
<TER>Web</TER>
<APP>HMS_CAWeb</APP>

<TIME>2014-04-07T09:30:47-05:00</TIME>
</EMISOR>
<SEGURIDAD>
<TIPOAUT>U</TIPOAUT>
<USRID>7001110001</USRID>
<USRPASS>DAT_MGR</USRPASS>
</SEGURIDAD>
<OPER>
<TIPO>AAP</TIPO>
<FECHA>2014-04-07</FECHA>
<IDASEG>ACA_SALUD</IDASEG>
<IDPRESTADOR>7001110001</IDPRESTADOR>
<IDANUL>' . $transaccion . '</IDANUL>
<ACID>REVERSO</ACID>    
</OPER>
<PID>
<ID>59999900</ID>
</PID>

<PRESCRIP>
 <ORG>MP S</ORG>
 <MAT>1/01200000</MAT>
 <FECHA>2014-04-07</FECHA>
</PRESCRIP>

<CONTEXTO>
<TIPO>A</TIPO>
</CONTEXTO>


</SOLICITUD>        
';

echo "<h4>TRANSACCION</h4>";
$resultados = $client->transaccionStr($msg);
echo $resultados;
echo "<hr/>";

// PARSE XML
echo "<h4>XML RETURN</h4>";
$lector = new SimpleXMLElement($resultados);
//echo '<pre>' . $lector->asXML() . '</pre>' ;

for ($i = 0; $i <= 10; $i++) {
    if ($lector->PR[$i]->ID == '')
        break;
    echo $lector->PR[$i]->ID . ' - ' . $lector->PR[$i]->STATUS . ' - ' . $lector->PR[$i]->RSPMSGP . ' -  ' . $lector->PR[$i]->RSPMSGPADIC .  '<br/>';
}

echo $datetime->format('Y-m-d');

echo "<br/>";

        $_cod = ''; //$obj->getParametro4();
        $c = '660001';
        $prefijo = '030';
        $longitud = strlen($prefijo);
        if ($longitud == 0)
            $_cod = $c;
        else {
            $_cod = substr($c, $longitud, strlen($c) - $longitud);
            $_cod = $prefijo . $_cod;
        }
echo $_cod;

echo '<hr>' . "test finalizado";
?>