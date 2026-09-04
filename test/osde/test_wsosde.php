<?php

$fecha = date("Y") . date("m") .date("d");
$hora = date("His");

$msg = "<Mensaje>
	<EncabezadoMensaje>
		<VersionMsj>1.0</VersionMsj>
		<TipoTransaccion>01A</TipoTransaccion>
		<IdMsj>2</IdMsj>
		<InicioTrx>
			<FechaTrx>$fecha</FechaTrx>
			<HoraTrx>$hora</HoraTrx>
		</InicioTrx>
                <Terminal>
                        <TipoTerminal>PC</TipoTerminal>
                        <NumeroTerminal>1234</NumeroTerminal>
                </Terminal>
		<Financiador>                       
			<CodigoFinanciador>11</CodigoFinanciador>        
			<CuitFinanciador>30546741253</CuitFinanciador>
                </Financiador>
                <Prestador>
			<CuitPrestador>30708402911</CuitPrestador>
		</Prestador>
	</EncabezadoMensaje>
	<EncabezadoAtencion>
		<Efector/>
		<Prescriptor/>
		<Credencial>
			<NumeroCredencial>60671956201</NumeroCredencial>
		</Credencial>
		<Preautorizacion/>
		<Documentacion/>
		<Atencion/>
		<Diagnostico/>
		<CodFinalizacionTratamiento/>
		<MensajeParaFinanciador/>
	</EncabezadoAtencion>
	<DetalleProcedimientos/>
</Mensaje>";

$msg = str_replace('<', '%3C', $msg);
$msg = str_replace('>', '%3E', $msg);
$msg = str_replace(' ', '', $msg);
$msg = preg_replace('[\s+]', '', $msg);

//echo "%3CMensaje%3E%3CEncabezadoMensaje%3E%3CVersionMsj%3E1.0%3C/VersionMsj%3E%3CTipoTransaccion%3E01A%3C/TipoTransaccion%3E%3CIdMsj%3E333%3C/IdMsj%3E%3CInicioTrx%3E%3CFechaTrx%3E20130520%3C/FechaTrx%3E%3CHoraTrx%3E190620%3C/HoraTrx%3E%3C/InicioTrx%3E%3CFinanciador%3E%3CCodigoFinanciador%3E10%3C/CodigoFinanciador%3E%3CCuitFinanciador%3E30546741253%3C/CuitFinanciador%3E%3C/Financiador%3E%3CPrestador%3E%3CCuitPrestador%3E30708402911%3C/CuitPrestador%3E%3C/Prestador%3E%3C/EncabezadoMensaje%3E%3CEncabezadoAtencion%3E%3CEfector/%3E%3CPrescriptor/%3E%3CCredencial%3E%3CNumeroCredencial%3E60671956201%3C/NumeroCredencial%3E%3C/Credencial%3E%3CPreautorizacion/%3E%3CDocumentacion/%3E%3CAtencion/%3E%3CDiagnostico/%3E%3CCodFinalizacionTratamiento/%3E%3CMensajeParaFinanciador/%3E%3C/EncabezadoAtencion%3E%3CDetalleProcedimientos/%3E%3C/Mensaje%3E";
//echo $msg;
echo "<hr/>";

$url = "http://ws.itcsoluciones.com:48080/jSitelServlet/Do?pas=32dbf220f1ab2303592b4a076162c221600ef704&msj=" . $msg; // url de la pagina que queremos obtener  
//$url =  "http://ws.itcsoluciones.com:48080/jSitelServlet/Do?pas=32dbf220f1ab2303592b4a076162c221600ef704&msj=%3CMensaje%3E%3CEncabezadoMensaje%3E%3CVersionMsj%3E1.0%3C/VersionMsj%3E%3CTipoTransaccion%3E01A%3C/TipoTransaccion%3E%3CIdMsj%3E333%3C/IdMsj%3E%3CInicioTrx%3E%3CFechaTrx%3E20130520%3C/FechaTrx%3E%3CHoraTrx%3E190620%3C/HoraTrx%3E%3C/InicioTrx%3E%3CFinanciador%3E%3CCodigoFinanciador%3E10%3C/CodigoFinanciador%3E%3CCuitFinanciador%3E30546741253%3C/CuitFinanciador%3E%3C/Financiador%3E%3CPrestador%3E%3CCuitPrestador%3E30708402911%3C/CuitPrestador%3E%3C/Prestador%3E%3C/EncabezadoMensaje%3E%3CEncabezadoAtencion%3E%3CEfector/%3E%3CPrescriptor/%3E%3CCredencial%3E%3CNumeroCredencial%3E60671956201%3C/NumeroCredencial%3E%3C/Credencial%3E%3CPreautorizacion/%3E%3CDocumentacion/%3E%3CAtencion/%3E%3CDiagnostico/%3E%3CCodFinalizacionTratamiento/%3E%3CMensajeParaFinanciador/%3E%3C/EncabezadoAtencion%3E%3CDetalleProcedimientos/%3E%3C/Mensaje%3E";

$url_content = '';
$file = @fopen($url, 'r');
if ($file) {
    while (!feof($file)) {
        $url_content .= @fgets($file, 4096);
    }
    fclose($file);

    echo "<hr/>";
    //echo $url_content;
    // PARSE XML
    echo "<h4>XML RETURN</h4>";
    $lector = new SimpleXMLElement($url_content);
    echo '<pre>' . $lector->asXML() . '</pre>';
//echo $lector->asXML();

    echo "<h4>XML PARSEADO AFILIADO</h4>";

//$lector->saveXML('test.xml');

    echo 'Gen. Respuesta: ' . $lector->EncabezadoMensaje->GeneradorRespuesta . '<br/>';
    echo 'Referencia: ' . $lector->EncabezadoMensaje->NroReferencia . '<br/>';
    echo 'Afiliado: ' . $lector->EncabezadoMensaje->Rta->MensajeDisplay . '<br/>';

    $nombre = $lector->EncabezadoMensaje->Rta->MensajeDisplay;
    $nombre = substr($nombre, 4, 100);
    $p = strpos($nombre, ',');
    $nombre = substr($nombre, 0, $p - 4);
    echo '*******************' . substr($nombre, 0, 30) . '<hr/>';
}
exit;

// Test Determinaciones

echo '<h1>DETERMINACIONES</h1>';

$msg = "<Mensaje>
         <EncabezadoMensaje>
		<VersionMsj>1.0</VersionMsj>
		<TipoTransaccion>02L</TipoTransaccion>
		<IdMsj>101130113629</IdMsj>
		<InicioTrx>
			<FechaTrx>$fecha</FechaTrx>
			<HoraTrx>$hora</HoraTrx>
		</InicioTrx>
		<Financiador>
			<CodigoFinanciador>11</CodigoFinanciador>
			<CuitFinanciador>30546741253</CuitFinanciador>
		</Financiador>
		<Prestador>
			<CuitPrestador>30708402911</CuitPrestador>
			<RazonSocial>OSDE</RazonSocial>
			<CodigoParaFinanciador>00010073</CodigoParaFinanciador>
			<NroTransaccionInterno/>
		</Prestador>
	</EncabezadoMensaje>
	<EncabezadoAtencion>
                <Efector/>
		
		<Prescriptor>
			<FechaReceta>10112010</FechaReceta>
			<ApellidoPrescriptor>NONINO</ApellidoPrescriptor>
			<NombrePrescriptor>ALBERTO ANGEL</NombrePrescriptor>
			<ProvinciaPrescriptor>S</ProvinciaPrescriptor>
			<TipoPrescriptor>M</TipoPrescriptor>
			<NroMatriculaPrescriptor>3622</NroMatriculaPrescriptor>
		</Prescriptor>
		<Credencial>
			<NumeroCredencial>60671956201</NumeroCredencial>
		</Credencial>
		<Preautorizacion/>
		<Documentacion/>
		<Atencion>
			<FechaAtencion>$fecha</FechaAtencion>
			<HoraAtencion>$hora</HoraAtencion>
		</Atencion>
		<Diagnostico/>
		<CodFinalizacionTratamiento/>
		<MensajeParaFinanciador/>
	</EncabezadoAtencion>
	
	<DetalleProcedimientos>
		<NroItem>1</NroItem>
		<CodPrestacion>660475</CodPrestacion>
		<TipoPrestacion>1</TipoPrestacion>
		<ArancelPrestacion>0</ArancelPrestacion>
		<CantidadSolicitada>1</CantidadSolicitada>
		<DescripcionPrestacion></DescripcionPrestacion>
	</DetalleProcedimientos>
	
	<DetalleProcedimientos>
		<NroItem>2</NroItem>
		<CodPrestacion>660297</CodPrestacion>
		<TipoPrestacion>1</TipoPrestacion>
		<ArancelPrestacion>0</ArancelPrestacion>
		<CantidadSolicitada>1</CantidadSolicitada>
		<DescripcionPrestacion>ERITROSEDIMENTACION</DescripcionPrestacion>
	</DetalleProcedimientos>
	
	<DetalleProcedimientos>
		<NroItem>3</NroItem>
		<CodPrestacion>660005</CodPrestacion>
		<TipoPrestacion>1</TipoPrestacion>
		<ArancelPrestacion>0</ArancelPrestacion>
		<CantidadSolicitada>1</CantidadSolicitada>
		<DescripcionPrestacion>LDL COLESTEROL</DescripcionPrestacion>
	</DetalleProcedimientos>
	
	<DetalleProcedimientos>
		<NroItem>4</NroItem>
		<CodPrestacion>660711</CodPrestacion>
		<TipoPrestacion>1</TipoPrestacion>
		<ArancelPrestacion>0</ArancelPrestacion>
		<CantidadSolicitada>1</CantidadSolicitada>
		<DescripcionPrestacion>RESOL.ANSSAL 07/09/97 (CARGA VIRAL HIV)</DescripcionPrestacion>
	</DetalleProcedimientos>
	
	<DetalleProcedimientos>
		<NroItem>5</NroItem>
		<CodPrestacion>660001</CodPrestacion>
		<TipoPrestacion>1</TipoPrestacion>
		<ArancelPrestacion>0</ArancelPrestacion>
		<CantidadSolicitada>1</CantidadSolicitada>
		<DescripcionPrestacion>MATERIAL DESCARTABLE</DescripcionPrestacion>
	</DetalleProcedimientos>
	
</Mensaje>";

/*
$msg = "<Mensaje>
         <EncabezadoMensaje>
		<VersionMsj>1.0</VersionMsj>
		<TipoTransaccion>02L</TipoTransaccion>
		<IdMsj>101130113629</IdMsj>
		<InicioTrx>
			<FechaTrx>20101130</FechaTrx>
			<HoraTrx>113629</HoraTrx>
		</InicioTrx>
		<Financiador>
			<CodigoFinanciador>11</CodigoFinanciador>
			<CuitFinanciador>30546741253</CuitFinanciador>
		</Financiador>
		<Prestador>
			<CuitPrestador>30708402911</CuitPrestador>
			<RazonSocial>OSDE</RazonSocial>
			<CodigoParaFinanciador>00010073</CodigoParaFinanciador>
			<NroTransaccionInterno/>
		</Prestador>
	</EncabezadoMensaje>
	<EncabezadoAtencion>
                <Efector/>
		
		<Prescriptor>
			<FechaReceta>10112010</FechaReceta>
			<ApellidoPrescriptor>NONINO</ApellidoPrescriptor>
			<NombrePrescriptor>ALBERTO ANGEL</NombrePrescriptor>
			<ProvinciaPrescriptor>S</ProvinciaPrescriptor>
			<TipoPrescriptor>M</TipoPrescriptor>
			<NroMatriculaPrescriptor>3622</NroMatriculaPrescriptor>
		</Prescriptor>
		<Credencial>
			<NumeroCredencial>60671956201</NumeroCredencial>
		</Credencial>
		<Preautorizacion/>
		<Documentacion/>
		<Atencion>
			<FechaAtencion>20101130</FechaAtencion>
			<HoraAtencion>113629</HoraAtencion>
		</Atencion>
		<Diagnostico/>
		<CodFinalizacionTratamiento/>
		<MensajeParaFinanciador/>
	</EncabezadoAtencion>
        
    <DetalleProcedimientos>
        <NroItem>1</NroItem>
        <CodPrestacion>475</CodPrestacion>
        <TipoPrestacion>1</TipoPrestacion>
        <ArancelPrestacion>0</ArancelPrestacion>
        <CantidadSolicitada>1</CantidadSolicitada>
        <DescripcionPrestacion></DescripcionPrestacion>
    </DetalleProcedimientos>
    <DetalleProcedimientos>
        <NroItem>2</NroItem>
        <CodPrestacion>412</CodPrestacion>
        <TipoPrestacion>1</TipoPrestacion>
        <ArancelPrestacion>0</ArancelPrestacion>
        <CantidadSolicitada>1</CantidadSolicitada>
        <DescripcionPrestacion></DescripcionPrestacion>
    </DetalleProcedimientos>
</Mensaje>";
 */ 

$msg = str_replace('<', '%3C', $msg);
$msg = str_replace('>', '%3E', $msg);
$msg = str_replace(' ', '', $msg);
$msg = preg_replace('[\s+]', '', $msg);

echo '<pre>' . $msg . "</pre><hr/>";

$url = "http://ws.itcsoluciones.com:48080/jSitelServlet/Do?pas=32dbf220f1ab2303592b4a076162c221600ef704&msj=" . $msg; // url de la pagina que queremos obtener  

$url_content = '';
$file = @fopen($url, 'r');
if ($file) {
    while (!feof($file)) {
        $url_content .= @fgets($file, 4096);
    }
    fclose($file);

   echo $url_content;

    echo "<hr/>";


// PARSE XML
    echo "<h4>XML RETURN</h4>";
    $lector = new SimpleXMLElement($url_content);
    echo '<pre>' . $lector->asXML() . '</pre>';

    //$lector->saveXML('test_orden.xml');

    echo "<h4>XML PARSEADO ORDEN</h4>";

   echo 'Referencia: ' . $lector->EncabezadoMensaje->NroReferencia . '<br/>';
    for ($i = 0; $i <= 100; $i++) {
        if ($lector->DetalleProcedimientos[$i]->NroItem == '') break;
        echo $lector->DetalleProcedimientos[$i]->NroItem . ' - ' . $lector->DetalleProcedimientos[$i]->CodPrestacion . ' - ' . $lector->DetalleProcedimientos[$i]->DescripcionPrestacion . ' - ' . $lector->DetalleProcedimientos[$i]->MensajeRta . '<br/>';
    }
}

exit;

$msg = "<Mensaje>
	<EncabezadoMensaje>
		<VersionMsj>1.0</VersionMsj>
                <NroReferenciaCancel>70504554</NroReferenciaCancel>
		<TipoTransaccion>04A</TipoTransaccion>
		<IdMsj>3354</IdMsj>
		<InicioTrx>
			<FechaTrx>20091005</FechaTrx>
			<HoraTrx>193020</HoraTrx>
		</InicioTrx>
		<Financiador>
			<CodigoFinanciador>11</CodigoFinanciador>
			<CuitFinanciador>30546741253</CuitFinanciador>
                </Financiador>
		<Prestador>
			<CuitPrestador>30708402911</CuitPrestador>
		</Prestador>
	</EncabezadoMensaje>
	<EncabezadoAtencion>
		<Efector/>
		<Prescriptor/>
		<Credencial>
			<NumeroCredencial>60671956201</NumeroCredencial>
		</Credencial>
		<Atencion>
			<FechaAtencion>20091001</FechaAtencion>
		</Atencion>
	</EncabezadoAtencion>
</Mensaje>";

$msg = str_replace('<', '%3C', $msg);
$msg = str_replace('>', '%3E', $msg);
$msg = str_replace(' ', '', $msg);
$msg = preg_replace('[\s+]', '', $msg);

echo '<pre>' . $msg . "</pre><hr/>";

$url = "http://ws.itcsoluciones.com:48080/jSitelServlet/Do?pas=32dbf220f1ab2303592b4a076162c221600ef704&msj=" . $msg; // url de la pagina que queremos obtener  

$url_content = '';
$file = @fopen($url, 'r');
if ($file) {
    while (!feof($file)) {
        $url_content .= @fgets($file, 4096);
    }
    fclose($file);

   echo $url_content;

    echo "<hr/>";


// PARSE XML
    echo "<h4>XML RETURN</h4>";
    $lector = new SimpleXMLElement($url_content);
    echo '<pre>' . $lector->asXML() . '</pre>';

    //$lector->saveXML('test_orden.xml');

    echo "<h4>XML PARSEADO BORRADO ORDEN</h4>";

   echo 'Referencia: ' . $lector->EncabezadoMensaje->NroReferencia . '<br/>';
    
}

?>
 * 
 */