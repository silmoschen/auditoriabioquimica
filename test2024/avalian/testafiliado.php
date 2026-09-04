<?

$curl = curl_init();

echo 'Inicio <br/>';

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://ca.avalian.com:443/SSCawsProd/ServiciosProd',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://caws/ServiciosProd.wsdl">
   <soapenv:Header/>
   <soapenv:Body>
      <ser:transaccionstr>
      <pSolicitud><![CDATA[<SOLICITUD>
            <EMISOR>
               <ID>00001-22222</ID>
               <PROT>CA_V20</PROT>
               <MSGID>10001</MSGID>
               <TER>Web</TER>
               <APP>HMS_CAWeb</APP>
               <TIME>2023-11-17 22:42:09</TIME>
            </EMISOR>
            <SEGURIDAD>
               <TIPOAUT>U</TIPOAUT>
               <USRID>7001110001</USRID>
               <USRPASS>DAT_MGR</USRPASS>
            </SEGURIDAD>
            <OPER>
               <TIPO>ELG</TIPO>
               <FECHA>2023-11-17</FECHA>
               <IDASEG/>
               <IDPRESTADOR>7001110001</IDPRESTADOR>
            </OPER>
            <PID>
               <ID>34105816</ID>
               <VERIFID>MANUAL</VERIFID>
            </PID>
            <CONTEXTO>
               <TIPO>A</TIPO>
            </CONTEXTO>
         </SOLICITUD>]]></pSolicitud>
      </ser:transaccionstr>
   </soapenv:Body>
</soapenv:Envelope>',
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;

echo 'Fin';

?>
