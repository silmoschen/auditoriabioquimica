<?php

echo 'test aca... <br/>';

$client = new SoapClient("https://caws.acasalud.com.ar:4444/cawsProd/Servicios?WSDL", array(
    "trace" => 1,
    "location" => "https://caws.acasalud.com.ar:4444/cawsProd/Servicios",
    'exceptions' => 1,
    "stream_context" => stream_context_create(
            array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                )
            )
    )
        )
);

/*
$soapmsg["_this"] = array("_" => "ServiceInstance", "type" => "ServiceInstance");

$result = $client->RetrieveServiceContent($soapmsg);
$ServiceContent = $result->returnval;

$soapmsg = NULL;
$soapmsg["_this"] = $ServiceContent->sessionManager;
$soapmsg["userName"] = "USERNAME";
$soapmsg["password"] = "PASSWORD";

$result = $client->Login($soapmsg);
$UserSession = $result->returnval;

echo "User, " . $UserSession->userName . ", successfully logged in!\n";

$soapmsg = NULL;
$soapmsg["_this"] = $ServiceContent->sessionManager;
$result = $client->Logout($soapmsg);

echo $result;
*/

$datetime = new DateTime();

$rpc_xml = '
<pSolicitud><![CDATA[        
<SOLICITUD>
<EMISOR>
<ID>00001-22222</ID>
<PROT>CA_V20</PROT>
<MSGID>10001</MSGID>
<TER>Web</TER>
<APP>HMS_CAWeb</APP>
<TIME>' . $datetime->format('Y-m-d H:i:s') . '</TIME> 
</EMISOR>
<SEGURIDAD>
<TIPOAUT>U</TIPOAUT>
<USRID>7001110001</USRID>
<USRPASS>DAT_MGR</USRPASS>
</SEGURIDAD>
<OPER>
<TIPO>ELG</TIPO>
<FECHA>' . $datetime->format('Y-m-d') . '</FECHA>
<IDASEG>ACA_SALUD</IDASEG>
<IDPRESTADOR>7001110001</IDPRESTADOR>
</OPER>
<PID>
<ID>11023621</ID>
<VERIFID>MANUAL</VERIFID>
</PID>

<CONTEXTO>
<TIPO>A</TIPO>
</CONTEXTO>

</SOLICITUD>
]]></pSolicitud>';

$client = new SoapClient($url, $options);

$resultados = $client->transaccionStr($rpc_xml);

$lector = new SimpleXMLElement($resultados);

var_dump($resultados);

echo $lector->AFIAPE . ' ' . $lector->AFINOM;

echo 'Fin Test';
?>
