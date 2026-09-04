<?php

//implementamos la clase empleado
class cUtiles {

    //constructor

    function cUtiles() {
        
    }

    function getPeriodoMMAAAA($periodo) {
        return substr($periodo, 0, 2) . substr($periodo, 3, 4);
    }

    function getPeriodoMM_AAAA($periodo) {
        return substr($periodo, 0, 2) . '/' . substr($periodo, 2, 4);
    }

    function getPeriodoMM_AA($fecha) {
        return substr($fecha, 3, 2) . '-' . substr($fecha, 8, 2);
    }

    function getPeriodoMM_AAAA_FAAAAMM($periodo) {
        return substr($periodo, 4, 2) . '/' . substr($periodo, 0, 4);
    }

    function validarPeriodo($periodo) {
        if (substr($periodo, 0, 2) > '00' and substr($periodo, 0, 2) < '13') {
            return true;
        } else {
            return false;
        }
    }

    function validarNumero($valor) {
        $ok = is_numeric($valor);
        return $ok;
    }

    function sionost($valor, $cadena) {
        $pos = strpos($cadena, $valor);
        if ($pos === false) {
            return false;
        } else {
            return true;
        }
    }

    function getFechaDDMMAA($fecha) {
        return substr($fecha, 6, 2) . '/' . substr($fecha, 4, 2) . '/' . substr($fecha, 2, 2);
    }

    function getFechaAAAAMMDD($fecha) {
        return substr($fecha, 6, 4) . substr($fecha, 3, 2) . substr($fecha, 0, 2);
    }

    function getFechaAAAA_MM_DD($fecha) {
        return substr($fecha, 6, 4) . '-' . substr($fecha, 3, 2) . '-' . substr($fecha, 0, 2);
    }

    function getFechaDDMMAAAA($fecha) {
        return substr($fecha, 6, 2) . '/' . substr($fecha, 4, 2) . '/' . substr($fecha, 0, 4);
    }

    function restaFechas($dFecIni, $dFecFin) {
        $dFecIni = str_replace('-', '', $dFecIni);
        $dFecIni = str_replace('/', '', $dFecIni);
        $dFecFin = str_replace('-', '', $dFecFin);
        $dFecFin = str_replace('/', '', $dFecFin);

        $aFeIni = '';
        $aFeIni[1] = substr($dFecIni, 0, 2);
        $aFeIni[2] = substr($dFecIni, 2, 2);
        $aFeIni[3] = substr($dFecIni, 4, 4);
        $aFeFin = '';
        $aFeFin[1] = substr($dFecFin, 0, 2);
        $aFeFin[2] = substr($dFecFin, 2, 2);
        $aFeFin[3] = substr($dFecFin, 4, 4);

        $date1 = mktime(0, 0, 0, $aFeIni[2], $aFeIni[1], $aFeIni[3]);
        $date2 = mktime(0, 0, 0, $aFeFin[2], $aFeFin[1], $aFeFin[3]);

        return round(($date2 - $date1) / (60 * 60 * 24));
    }

    function sumarDiasFecha($fecha, $dias) {
        $dia = substr($fecha, 8, 2);
        $mes = substr($fecha, 5, 2);
        $anio = substr($fecha, 0, 4);

        $ultimo_dia = date("d", mktime(0, 0, 0, $mes + 1, 0, $anio));
        $dias_adelanto = $dias;
        $siguiente = $dia + $dias_adelanto;
        if ($ultimo_dia < $siguiente) {
            $dia_final = $siguiente - $ultimo_dia;
            $mes++;
            if ($mes == '13') {
                $anio++;
                $mes = '01';
            }
            $fecha_final = $this->LlenarIzquierda($dia_final, 2, "0") . '/' . $this->LlenarIzquierda($mes, 2, "0") . '/' . $anio;
        } else {
            $fecha_final = $this->LlenarIzquierda($siguiente, 2, "0") . '/' . $this->LlenarIzquierda($mes, 2, "0") . '/' . $anio;
        }
        return $fecha_final;
    }

    function LlenarIzquierda($cadena, $largo, $caracter) {
        $r = '';
        for ($i = 1; $i <= ($largo - strlen($cadena)); $i += 1) {
            $r = $r . $caracter;
        }

        return $r . $cadena;
    }

    function getFechaActual() {
        date_default_timezone_set('America/Argentina/Ushuaia');
        $ahora = getdate();
        $fecha_actual = $this->LlenarIzquierda($ahora["mday"], 2, '0') . "/" . $this->LlenarIzquierda($ahora["mon"], 2, '0') . "/" . $ahora["year"];
        return $fecha_actual;
    }

    function getPeriodoActual() {
        date_default_timezone_set('America/Argentina/Ushuaia');
        $ahora = getdate();
        $fecha_actual = $this->LlenarIzquierda($ahora["mday"], 2, '0') . "/" . $this->LlenarIzquierda($ahora["mon"], 2, '0') . "/" . $ahora["year"];
        $periodo = substr($fecha_actual, 3, 2) . '/' . substr($fecha_actual, 6, 4);

        return $periodo;
    }

    function getFechaHoraActual() {
        date_default_timezone_set('America/Argentina/Ushuaia');
        $ahora = getdate();
        $hora_actual = $ahora["hours"] . ":" . $ahora["minutes"] . ":" . $ahora["seconds"];
        $fecha_actual = $ahora["mday"] . "." . $ahora["mon"] . "." . $ahora["year"];
        return $fecha_actual . ' ' . $hora_actual;
    }

    function getFechaHoraActualYYYY_MM_DD() {
        date_default_timezone_set('America/Argentina/Ushuaia');
        $ahora = getdate();
        $hora_actual = $ahora["hours"] . ":" . $ahora["minutes"] . ":" . $ahora["seconds"];
        $fecha_actual = $ahora["year"] . "-" . $this->LlenarIzquierda($ahora["mon"], 2, '0') . "-" . $this->LlenarIzquierda($ahora["mday"], 2, '0')
                . ' ' . $hora_actual;
        return $fecha_actual;
    }

    function ValidarFecha($fecha) {
        $dia = substr($fecha, 0, 2);
        $mes = substr($fecha, 3, 2);
        $anio = substr($fecha, 6, 4);
        if (checkdate($mes, $dia, $anio)) {
            return true;
        } else {
            return false;
        }
    }

    function StringLongitudFija($string, $largo) {
        $s = $string;
        $r = ' ';
        for ($i = strlen($string); $i <= $largo; $i += 1) {
            $s = $s . $r;
        }

        return $s;
    }

    function StringLlenarDerecha($string, $largo, $caracter) {
        $s =  $string;
        $str = '';
        for ($i = 1; $i <= ($largo - strlen($string)); $i += 1) {
            $str = $str . $caracter;
        }
        return $s . $str;
    }

    function getFecha($meses_a_restar) {
        $m = "-" . $meses_a_restar . " month";
        return date('d-m-Y', strtotime($m));
    }

    function getUUID() {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                // 32 bits for "time_low"
                mt_rand(0, 0xffff), mt_rand(0, 0xffff),
                // 16 bits for "time_mid"
                mt_rand(0, 0xffff),
                // 16 bits for "time_hi_and_version",
                // four most significant bits holds version number 4
                mt_rand(0, 0x0fff) | 0x4000,
                // 16 bits, 8 bits for "clk_seq_hi_res",
                // 8 bits for "clk_seq_low",
                // two most significant bits holds zero and one for variant DCE1.1
                mt_rand(0, 0x3fff) | 0x8000,
                // 48 bits for "node"
                mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    function parseJson($post) {
        $i = 0;
        $j = 0;
        for ($x = 0; $x <= strlen($post); $x++) {
            $cc = substr($post, $x, 1);

            if ($cc == '{' && $i == 0)
                $i = $x;
            if ($cc == '}')
                $j = $x;
        }

        $json = substr($post, $i, $j - ($i - 1));
        
        return $json;
    }

}

?>