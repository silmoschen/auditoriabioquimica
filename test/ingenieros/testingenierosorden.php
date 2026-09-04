<?php

$str = " Hello World! ";
echo "Without trim: " . $str;
echo '  ' . strlen(trim($str));
echo "<br>";
echo "With trim: " . trim($str) .  ' ' . strlen($str);

exit;

$data = '{
  "PrestadorId": false,
  "Numero": 0,
  "Diagnostico": "Diagnóstico 1",
  "Observacion": "Observación 1",
  "Estado": "",
  "Fecha": "2009-05-31",
  "Tipo": "BIO",
  "Compania": {
    "Nombre": "MOSCHEN SILVIO",
    "Cuit": 20226864351
  },
  "Afiliado": {
    "Id": 0,
    "Apellido": "",
    "Nombre": "",
    "Sexo": "",
    "EstadoCivil": "",
    "FechaNacimiento": "1961-04-03",
    "DocumentoNumero": "13999553",
    "DocumentoTipo": "DNI",
    "EstadoRegimenAsistencial": "",
    "NumeroAfiliado": ""
  },
  "Prestaciones": [
    {
      "Cantidad": 1,
      "Codigo": 661035,
      "Autorizado": null,
      "Zona": "",
      "Subzona": ""
    },
    {
      "Cantidad": 1,
      "Codigo": 661070,
      "Autorizado": null,
      "Zona": "",
      "Subzona": ""
    },
    {
      "Cantidad": 1,
      "Codigo": 660001,
      "Autorizado": null,
      "Zona": "",
      "Subzona": ""
    }
  ],
  "Prescriptor": {
    "Apellido": "DOLDANJOSE GUSTAVO",
    "Nombre": "JOSE GUSTAVO",
    "Especialidad": "PRO",
    "Matricula": "202"
  },
  "Efector": {
    "PrestadorId": false,
    "Especialidad": "BIO",
    "Matricula": "202",
    "Persona": {
      "PrestadorId": false,
      "Apellido": "DOLDAN",
      "Nombre": "JOSE GUSTAVO",
      "Sexo": "M",
      "EstadoCivil": "C",
      "FechaNacimiento": "1980-08-05T00:00:00",
      "DocumentoNumero": "33622525",
      "DocumentoTipo": "DNI",
      "DomicilioCalle": "LAS HERAS",
      "DomicilioNumero": 345,
      "CUILCUIT": "20-33622525-9",
      "Localidad": "SANTA FE",
      "Departamento": "LA CAPITAL",
      "Telefono": "0342-4525859, 0342-4605829",
      "Celular": "0342-155584852, 0342-156105425",
      "Mail": "JOSEDOLDAN@HOTMAIL.COM",
      "CodPostal": 3000
    }
  }
}';

/*
$data = '{
  "PrestadorId": false,
  "Numero": 0,
  "Diagnostico": "Diagnóstico 1",
  "Observacion": "Observación 1",
  "Estado": "",
  "Fecha": "2009-05-31",
  "Tipo": "BIO",
  "Compania": {
    "Nombre": "MOSCHEN SILVIO",
    "Cuit": 20226864351
  },
  "Afiliado": {
    "Id": 6667,
    "Apellido": "GARCIA",
    "Nombre": "ARMANDO JOSE",
    "Sexo": "M",
    "EstadoCivil": "CA",
    "FechaNacimiento": "1961-04-03T00:00:00",
    "DocumentoNumero": "13999553",
    "DocumentoTipo": "DNI",
    "EstadoRegimenAsistencial": "ACTIVO",
    "NumeroAfiliado": "6716 - 0"
  },
  "Prestaciones": [
    {
      "Cantidad": 1,
      "Codigo": 661035,
      "Autorizado": null,
      "Zona": "",
      "Subzona": ""
    },
    {
      "Cantidad": 1,
      "Codigo": 661070,
      "Autorizado": null,
      "Zona": "",
      "Subzona": ""
    },
    {
      "Cantidad": 1,
      "Codigo": 660001,
      "Autorizado": null,
      "Zona": "",
      "Subzona": ""
    }
  ],
  "Prescriptor": {
    "Apellido": "DOLDANJOSE GUSTAVO",
    "Nombre": "JOSE GUSTAVO",
    "Especialidad": "PRO",
    "Matricula": "202"
  },
  "Efector": {
    "PrestadorId": false,
    "Especialidad": "BIO",
    "Matricula": "202",
    "Persona": {
      "PrestadorId": false,
      "Apellido": "DOLDAN",
      "Nombre": "JOSE GUSTAVO",
      "Sexo": "M",
      "EstadoCivil": "C",
      "FechaNacimiento": "1980-08-05T00:00:00",
      "DocumentoNumero": "33622525",
      "DocumentoTipo": "DNI",
      "DomicilioCalle": "LAS HERAS",
      "DomicilioNumero": 345,
      "CUILCUIT": "20-33622525-9",
      "Localidad": "SANTA FE",
      "Departamento": "LA CAPITAL",
      "Telefono": "0342-4525859, 0342-4605829",
      "Celular": "0342-155584852, 0342-156105425",
      "Mail": "JOSEDOLDAN@HOTMAIL.COM",
      "CodPostal": 3000
    }
  }
}';
*/

$data = '{ "PrestadorId": false, "Numero": 0, "Diagnostico": "R10.0", "Observacion": "ABDOMEN AGUDO", "Estado": "", "Fecha": "2009-05-31", "Tipo": "BIO","Compania": { "Nombre": "Centro Bioqu�micos Litoral Norte Sta. Fe", "Cuit": 30582312059},"Afiliado": { "Id": 0, "Apellido": "", "Nombre": "", "Sexo": "", "EstadoCivil": "", "FechaNacimiento": "1961-04-03", "DocumentoNumero": "13999553", "DocumentoTipo": "DNI", "EstadoRegimenAsistencial": "", "NumeroAfiliado": "" },"Prestaciones":[{"Cantidad":"1","Codigo":"660475","Autorizado":null,"Zona":"","Subzona":""},{"Cantidad":"1","Codigo":"660412","Autorizado":null,"Zona":"","Subzona":""},{"Cantidad":"1","Codigo":"660001","Autorizado":null,"Zona":"","Subzona":""}],"Prescriptor": { "Apellido": "LOPEZ ALBERTO EMILIANO", "Nombre": "LOPEZ ALBERTO EMILIANO", "Especialidad": "PRO", "Matricula": "00311" },"Efector": { "PrestadorId": false, "Especialidad": "BIO", "Matricula": "000001", "Persona": { "PrestadorId": false, "Apellido": "*** EFECTOR TESTING ***", "Nombre": "*** EFECTOR TESTING ***", "Sexo": "M", "EstadoCivil": "C", "FechaNacimiento": "1980-08-05T00:00:00", "DocumentoNumero": "30-58231205-9", "DocumentoTipo": "DNI", "DomicilioCalle": "XX", "DomicilioNumero": 345, "CUILCUIT": "30-58231205-9", "Localidad": "XX", "Departamento": "XX", "Telefono": "0000", "Celular": "00", "Mail": "foo@bar.com.ar", "CodPostal": 3560 } }}';

$parameters = '{"url":"https://aoapiprueba.cajaingenieria.org.ar/api/Orden","metodo":"","api":"Authorization","apikey":"Basic c2lsdmlvbToxMjM0","username":"silviom","password":"1234"}';

$json_data = '{"parameters":' . $parameters . ',' .
             '"orden":' . $data . '}';

//echo $json_data;

//exit;

$json_data = '{"parameters":{"url":"https://aoapiprueba.cajaingenieria.org.ar/api/Orden","metodo":"","api":"Authorization","apikey":"Basic c2lsdmlvbToxMjM0","username":"silviom","password":"1234"},"orden":{ "PrestadorId": false, "Numero": 0, "Diagnostico": "R10.0", "Observacion": "ABDOMEN AGUDO", "Estado": "", "Fecha": "2009-05-31", "Tipo": "BIO","Compania": { "Nombre": "Centro Bioquï¿½micos Litoral Norte Sta. Fe", "Cuit": 30582312059},"Afiliado": { "Id": 0, "Apellido": "", "Nombre": "", "Sexo": "", "EstadoCivil": "", "FechaNacimiento": "1961-04-03", "DocumentoNumero": "13999553", "DocumentoTipo": "DNI", "EstadoRegimenAsistencial": "", "NumeroAfiliado": "" },"Prestaciones":[{"Cantidad":"1","Codigo":"660475","Autorizado":null,"Zona":"","Subzona":""},{"Cantidad":"1","Codigo":"660412","Autorizado":null,"Zona":"","Subzona":""},{"Cantidad":"1","Codigo":"660001","Autorizado":null,"Zona":"","Subzona":""}],"Prescriptor": { "Apellido": "LOPEZ ALBERTO EMILIANO", "Nombre": "LOPEZ ALBERTO EMILIANO", "Especialidad": "PRO", "Matricula": "00311" },"Efector": { "PrestadorId": false, "Especialidad": "BIO", "Matricula": "000001", "Persona": { "PrestadorId": false, "Apellido": "*** EFECTOR TESTING ***", "Nombre": "*** EFECTOR TESTING ***", "Sexo": "M", "EstadoCivil": "C", "FechaNacimiento": "1980-08-05T00:00:00", "DocumentoNumero": "30-58231205-9", "DocumentoTipo": "DNI", "DomicilioCalle": "XX", "DomicilioNumero": 345, "CUILCUIT": "30-58231205-9", "Localidad": "XX", "Departamento": "XX", "Telefono": "0000", "Celular": "00", "Mail": "foo@bar.com.ar", "CodPostal": 3560 } }}}';

echo '<hr/>';

$url = 'http://localhost:10060/api/Ingenieros/orden';  

$context = stream_context_create(array(
     'http' => array(
        'protocol_version' => 1.1,
        'user_agent'       => 'PHPExample',
        "Cookie => foo=bar\r\n",
        'method'           => 'PUT',
        'header'           => "Content-type: application/json\r\n" .
                              "Connection: close\r\n" .
                              "Content-length: " . strlen($json_data) . "\r\n",                              
        'content'          => $json_data,
        'Expect' => '100-continue'        
    ),
));

$post = file_get_contents($url, false, $context); //, -1, $l);

//$response = json_decode($post);

if ($post) {
    echo $post;
    $result = json_decode($post);
    echo '<hr/>' . $result->Numero;
    for ($p = 0; $p < count($result->Prestaciones); $p++) {
        echo $result->Prestaciones[$p]->Codigo . '<br/>'; 
        //PrestacionesRtaItem[$p]->Prestacion;
    }
    
} else {
    echo "PUT failed";    
}
  
  
  


?>
