<?php

include_once("MyIterator.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cNBU.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/conexion.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cUtiles.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cApfijosNBU.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cArancelesNBU.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cCapitas.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cObSocial.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cEquivalenciaPadrones.php");

if (isset($_GET['nrotrans'])) {
    $nroauditoria = $_REQUEST['nrotrans'];
}

class MyCollection implements IteratorAggregate {

    private $items = array();

    // Required definition of interface IteratorAggregate
    public function getIterator() {
        return new MyIterator($this->items);
    }

    public function add($value) {
        $this->items[$this->count++] = $value;
    }

}

class cItemsAuditoria {

    var $todoslositems;
    var $autoriazadas;
    var $rechazadas;
    var $capitadas;
    var $cNBU;
    var $utiles;
    var $apfijosnbu;
    var $arancel;
    var $capita;
    var $valor_modulo;
    var $obsocial;
    var $afiliado;    
    var $porcentajecoseguro = 0;

    function cItemsAuditoria() {
        $this->todoslositems = new MyCollection();
        $this->autorizadas = new MyCollection();
        $this->rechazadas = new MyCollection();
        $this->capitadas = new MyCollection();

        $this->cNBU = new cNBU;
        $this->utiles = new cUtiles;
        $this->apfijosnbu = new cApfijosNBU;
        $this->arancel = new cArancelNBU;
        $this->capita = new cCapitas;
        $this->obsocial = new cObsocial();        
    }

    function AgregarItems($codigo) {
        $this->todoslositems->add($codigo);
    }

    function AgregarItemsAutoriazado($codigo) {
        $this->autorizadas->add($codigo);
    }

    function AgregarItemsRechazado($codigo) {
        $this->rechazadas->add($codigo);
    }

    function MoverItemsComoAutorizados() {
        $this->autoriazadas->clear();
        foreach ($this->todoslositems as $key => $val) {
            $this->AgregarItemsAutoriazado($val);
        }
    }

    function MoverItemsComoRechazados() {
        $this->rechazadas->clear();
        foreach ($this->todoslositems as $key => $val) {
            $this->AgregarItemsRechazado($val);
        }
    }

    function AgregarItemsCapitada($codigo) {
        $this->capitadas->add($codigo);
    }

    function actualizarUnidadesNbu($nroauditoria) {

        $query = "SELECT codigo, items FROM det_auditoria WHERE nroauditoria = '$nroauditoria'";

        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $this->cNBU->getObject($fila['codigo']);
            $q = "update det_auditoria set unidades = " . $this->cNBU->getUnidad() . " where nroauditoria = '$nroauditoria' and items = '" . $fila['items'] . "'";
            mysql_query($q);
            //echo $q . '<br/>';
        }
    }

    function ListarItems($autorizados, $nroauditoria) {
        // Borramos si ya existe
        $query = "DELETE FROM det_auditoria WHERE nroauditoria = '$nroauditoria'";
        $result = mysql_query($query);

        $estado = '';
        $i = 0;
        foreach ($this->todoslositems as $key => $val) {
            $this->cNBU->getObject($val);
            echo '<tr>';
            if ($autorizados) {
                echo '<td align="left">' . "[$val]" . ' ' . substr($this->cNBU->getDescrip(), 0, 24) . '<br>' . '</td>';
                echo '<td align="left"></td>';
                $estado = 'A';
            } else {
                echo '<td align="left"></td>';
                echo '<td align="left">' . "[$val]" . ' ' . substr($this->cNBU->getDescrip(), 0, 24) . '<br>' . '</td>';
                $estado = 'R';
            }

            // Registramos los Items temporalmente
            $efector = substr($nroauditoria, 0, 6);
            $i++;
            $item = $this->utiles->LlenarIzquierda($i, 3, '0');
            $query = "INSERT INTO det_auditoria (nroauditoria, items, codigo, efector, estado, alta, anulada, nroauditoria_sk) VALUES
                                         ('$nroauditoria', '$item', '$val', '$efector', '$estado', 'N', 'N', '$nroauditoria')";
            $result = mysql_query($query);

            echo '</tr>';
        }
    }

    function getAuditoriaMasReciente($codos, $nrodoc, $codigo) {
        $query = "SELECT MAX(fecha) AS ultimafecha FROM det_auditoria WHERE codigo = " . "'" . $codigo . "'" . " and codos = " . "'" . $codos . "'" . " and nrodoc = " . "'" . $nrodoc . "'" . " and anulada <> 'S'";
        $this->res = mysql_query($query);
        return $this->res;
    }

    function GuardarItems($nroauditoria, $codos, $fecha, $nrodoc, $aut) {

        // Borramos si ya existe
        $query = "DELETE FROM det_auditoria WHERE nroauditoria = '$nroauditoria'";
        $result = mysql_query($query);        

        $estado = '';
        $i = 0;
        foreach ($this->todoslositems as $key => $val) {
            if ($aut) {
                $estado = 'A';
            } else {
                $estado = 'R';
            }

            // 05/07/2018
            $this->cNBU->getObject($val);
            $unidades = $this->cNBU->getUnidad();
            // End 05/07/2018
            // Registramos los Items temporalmente
            $efector = substr($nroauditoria, 0, 6);
            $i++;
            $item = $this->utiles->LlenarIzquierda($i, 3, '0');

            $per = substr($fecha, 4, 2) . '/' . substr($fecha, 0, 4);
            $monto = $this->getValorAnalisis($val, $codos, $per);
            $coseguro = $this->getValorCoseguro($val, $codos, $per);

            $monto1 = 0;
            $monto2 = 0;
            if ($this->obsocial->getNivel3() == 'S') {
                // 05/07/2018 $this->cNBU->getObject($val);
                if ($this->cNBU->getNivel() == '1') {
                    $monto1 = $monto;
                }
                if ($this->cNBU->getNivel() == '3') {
                    $monto2 = $monto;
                }
            }

            $query = "INSERT INTO det_auditoria (nroauditoria, items, codigo, efector, estado, fecha, codos, nrodoc, monto, anulada, nroauditoria_sk, coseguro, monto1, monto2, unidades) VALUES ('$nroauditoria', '$item', '$val', '$efector', '$estado', '$fecha', '$codos', '$nrodoc', $monto, 'N', '$nroauditoria', $coseguro, $monto1, $monto2, $unidades)";
            //echo $query;
            $result = mysql_query($query);

            // 25/09/2018
            if ($codos == '121046') {
                $q = "insert into xxsql (modulo, ssql, nroauditoria) values ('I1' , " . '"' . $query . '"' . ", '$nroauditoria')";
                //$result = mysql_query($q);
            }
        }

        //$this->actualizarUnidadesNbu($nroauditoria); // 05/07/2018
    }

    function getCantidadItems() {
        $i = 0;
        foreach ($this->todoslositems as $key => $val) {
            $i = $i + 1;
        }
        return $i;
    }

    function ActualizarItems($nroauditoria, $item, $codigo, $efector, $codos, $fecha, $nrodoc, $estado) {
        // Borramos si ya existe        
        if ($item == '001') {
            $query = "DELETE FROM det_auditoria WHERE nroauditoria = '$nroauditoria'";
            $result = mysql_query($query);
        }

        $f = $this->utiles->getFechaAAAAMMDD($fecha);
        $periodo = substr($f, 4, 2) . '/' . substr($f, 0, 4);
        $monto = $this->getValorAnalisis($codigo, $codos, $periodo);
        $coseguro = $this->getValorCoseguro($codigo, $codos, $periodo);

        $this->obsocial->getObject($codos);
        $monto1 = 0;
        $monto2 = 0;
        if ($this->obsocial->getNivel3() == 'S') {
            $this->cNBU->getObject($val);
            if ($this->cNBU->getNivel() == '1') {
                $monto1 = $monto;
            }
            if ($this->cNBU->getNivel() == '3') {
                $monto2 = $monto;
            }
        }

        // 05/07/2018
        $this->cNBU->getObject($codigo);
        $unidades = $this->cNBU->getUnidad();
        // End 05/07/2018

        $query = "INSERT INTO det_auditoria (nroauditoria, items, codigo, efector, estado, fecha, codos, nrodoc, monto, anulada, nroauditoria_sk, coseguro, monto1, monto2, depura, unidades) VALUES
                                        ('$nroauditoria', '$item', '$codigo', '$efector', '$estado', '$f', '$codos', '$nrodoc', $monto, 'N', '$nroauditoria', $coseguro, $monto1, $monto2, '', $unidades)";

        $result = mysql_query($query);

        $q = "insert into xxsql (modulo, ssql, nroauditoria) values ('M1' , " . '"' . $query . '"' . ", '$nroauditoria')";
        //$result = mysql_query($q);

        //$this->actualizarUnidadesNbu($nroauditoria); // 04/07/2018
    }
    
    function addPractica($nroauditoria, $codigo, $efector, $codos, $fecha, $nrodoc, $estado) {
         $query = "SELECT MAX(items) AS items FROM det_auditoria WHERE nroauditoria = '$nroauditoria'";
         $result = mysql_query($query);
         
         $it = 0;
          while ($fila = mysql_fetch_array($result)) {
            $it = $fila['items'];
        }
        
        $it = $it + 1;
        
        $it =  $this->utiles->LlenarIzquierda($it, 3, '0');
        
        $monto1 = 0;
        $monto2 = 0;
        $f = $this->utiles->getFechaAAAAMMDD($fecha);
        $periodo = substr($f, 4, 2) . '/' . substr($f, 0, 4);
        $monto = $this->getValorAnalisis($codigo, $codos, $periodo);
        $coseguro = $this->getValorCoseguro($codigo, $codos, $periodo);
        
        $query = "INSERT INTO det_auditoria (nroauditoria, items, codigo, efector, estado, fecha, codos, nrodoc, monto, anulada, nroauditoria_sk, coseguro, monto1, monto2, observacion) VALUES
                                        ('$nroauditoria', '$it', '$codigo', '$efector', '$estado', '$f', '$codos', '$nrodoc', $monto, 'N', '$nroauditoria', $coseguro, $monto1, $monto2, 'AM')";
        //echo $query . '<hr/>';
        $result = mysql_query($query);
        
    }

    function actualizarItemsExternos($nroauditoria, $item, $codigo, $efector, $codos, $fecha, $nrodoc, $estado) {
        // Borramos si ya existe
        if ($item == '1') {
            $query = "DELETE FROM det_auditoria WHERE nroauditoria = '$nroauditoria' and items >= '300'";
            $result = mysql_query($query);
        }

        $f = $this->utiles->getFechaAAAAMMDD($fecha);
        $periodo = substr($f, 4, 2) . '/' . substr($f, 0, 4);
        $monto = $this->getValorAnalisis($codigo, $codos, $periodo);
        $coseguro = $this->getValorCoseguro($codigo, $codos, $periodo);

        $this->obsocial->getObject($codos);
        $monto1 = 0;
        $monto2 = 0;
        if ($this->obsocial->getNivel3() == 'S') {
            $this->cNBU->getObject($val);
            if ($this->cNBU->getNivel() == '1') {
                $monto1 = $monto;
            }
            if ($this->cNBU->getNivel() == '3') {
                $monto2 = $monto;
            }
        }

        $it = $item + 299;

        $query = "INSERT INTO det_auditoria (nroauditoria, items, codigo, efector, estado, fecha, codos, nrodoc, monto, anulada, nroauditoria_sk, coseguro, monto1, monto2) VALUES
                                        ('$nroauditoria', '$it', '$codigo', '$efector', '$estado', '$f', '$codos', '$nrodoc', $monto, 'N', '$nroauditoria', $coseguro, $monto1, $monto2)";
        //echo $query . '<hr/>';
        $result = mysql_query($query);
    }

    function ActualizarCoseguroMonto($nroauditoria, $items, $coseguro) {
        $nroauditoria = trim($nroauditoria);
        $query = "UPDATE det_auditoria SET coseguro = $coseguro WHERE nroauditoria = '$nroauditoria' AND items = '$items'";       
        $result = mysql_query($query);
    }
    
    function resetCoseguroMonto($nroauditoria) {
        $nroauditoria = trim($nroauditoria);
        $query = "UPDATE det_auditoria SET coseguro = 0 WHERE nroauditoria = '$nroauditoria'";       
        $result = mysql_query($query);
    }
    
    function ActualizarCoseguro($nroauditoria, $coseguro) {
        $query = "UPDATE det_auditoria SET coseguro = $coseguro WHERE nroauditoria = '$nroauditoria' AND items = '001'";
        $result = mysql_query($query);
    }

    function getValorAnalisis($codigo, $codos, $periodo) {
        $codigoos = $this->getEquivalencia($codos);
        $this->valor_modulo = 0;
        $monto = $this->apfijosnbu->getMontoFijo($codigoos, $codigo, $periodo);        
        
        if ($monto == 0) {            
            $aran = $this->arancel->getArancelNBU($codigoos, $periodo);
            $this->valor_modulo = $this->arancel->getModulo();
            $this->cNBU->getObject($codigo);
            
            //$monto = $this->cNBU->getUnidad() * $aran; // calculo original 
            
            $monto = $this->cNBU->getUnidadObsocial($codos) * $aran; // 20/07/2026 NBU Adicional, 660475 5 un. iapos
        }
        
        return $monto;
    }

    function getValorCoseguro($codigo, $codos, $periodo) {
        $codigoos = $this->getEquivalencia($codos);
        $valor_modulo = 0;
        $monto = 0;
        if ($monto == 0) {
            $aran = $this->arancel->getArancelNBUDiferencial($codigoos, $periodo);
            $this->cNBU->getObject($codigo);
            $monto = $this->cNBU->getUnidad() * $aran;
        }
        return $monto;
    }

    function getItems($nroauditoria) {
        $nroauditoria = str_replace("'", '', $nroauditoria);
        $query = "SELECT codigo, items, estado, monto, coseguro FROM det_auditoria WHERE nroauditoria = '$nroauditoria'";        
        $this->res = mysql_query($query);        
        return $this->res;
    }

    function getItemsUnionCapitas($nroauditoria) {
        $query = "SELECT codigo, estado, monto, coseguro FROM det_auditoria WHERE nroauditoria = " . $nroauditoria .
                " UNION " .
                "SELECT codigo, estado, monto, coseguro FROM det_capitas WHERE nroauditoria = " . $nroauditoria . " order by codigo";

        //$query = "SELECT codigo, estado, monto, coseguro FROM det_auditoria WHERE nroauditoria = " . $nroauditoria;
        $this->res = mysql_query($query);
        //echo $query . '<br/>';
        //$query = "SELECT codigo, estado, monto, coseguro FROM det_auditoria WHERE nroauditoria = " . $nroauditoria . ' order by estado';
        return $this->res;
    }

    function getCantidad_Determinaciones_Export($codos, $codigo, $xdesde, $xhasta) {

        $desde = $this->utiles->getFechaAAAAMMDD($xdesde);
        $hasta = $this->utiles->getFechaAAAAMMDD($xhasta);

        //$query = "SELECT COUNT(codigo) AS cant FROM det_auditoria where fecha >= '" . $desde . "' and fecha <= '" . $hasta . "' and codigo = '" . $codigo . "' and estado = 'A' and codos = '" . $codos . "'" .
        //        " UNION " .
        //        "SELECT COUNT(codigo) AS cant FROM det_capitas where fecha >= '" . $desde . "' and fecha <= '" . $hasta . "' and codigo = '" . $codigo . "' and estado = 'A' and codos = '" . $codos . "'";

        $query = "SELECT COUNT(codigo) AS cant FROM det_auditoria where fecha >= '" . $desde . "' and fecha <= '" . $hasta . "' and codigo = '" . $codigo . "' and estado = 'A' and codos = '" . $codos . "'";

        $cant = 0;
        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $cant = $cant + $fila['cant'];
        }

        $query = "SELECT COUNT(codigo) AS cant FROM det_capitas where fecha >= '" . $desde . "' and fecha <= '" . $hasta . "' and codigo = '" . $codigo . "' and estado = 'A' and codos = '" . $codos . "'";

        echo $query . '<br/>';
        //$cant = 0;

        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $cant = $cant + $fila['cant'];
        }

        return $cant;
    }

    function RecaulcularMontos($xcodos, $xdesde, $xhasta) {
        $monto = 0;
        $registro = 0;

        $codigo = $this->getEquivalencia($xcodos);

        $desde = $this->utiles->getFechaAAAAMMDD($xdesde);
        $hasta = $this->utiles->getFechaAAAAMMDD($xhasta);

        $this->obsocial->getObject($xcodos);

        $query = "SELECT nroauditoria, items, codos, codigo, fecha FROM det_auditoria WHERE codos = '$codigo' AND fecha >= '$desde' AND fecha <= '$hasta'";

        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {

            $periodo = substr($fila['fecha'], 4, 2) . '/' . substr($fila['fecha'], 0, 4);
            
            $this->cNBU->getObject($fila['codigo']);

            $monto = $this->getValorAnalisis($fila['codigo'], $fila['codos'], $periodo);
            $coseguro = $this->getValorCoseguro($fila['codigo'], $fila['codos'], $periodo);
            
            $monto1 = 0;
            $monto2 = 0;
            if ($this->obsocial->getNivel3() == 'S') {
                $this->cNBU->getObject($val);
                if ($this->cNBU->getNivel() == '1') {
                    $monto1 = $monto;
                }
                if ($this->cNBU->getNivel() == '3') {
                    $monto2 = $monto;
                }
            }                        

            $actsql = "UPDATE det_auditoria SET unidades = " . $this->cNBU->getUnidadObsocial($xcodos) . ", monto = " . $monto . ", coseguro =  " . $coseguro . ", monto1 = " . $monto1 . ", monto2 = " . $monto2 . " WHERE nroauditoria = " . "'" . $fila['nroauditoria'] . "'" . " AND codigo = " . "'" . $fila['codigo'] . "'" . " AND items = " . "'" . $fila['items'] . "'";
            $rs = mysql_query($actsql);

            $registro = $registro + 1;
        }

        //-------------------------------------------------------------------

        $query = "SELECT nroauditoria, items, codos, codigo, fecha FROM det_capitas WHERE codos = '$codigo' AND fecha >= '$desde' AND fecha <= '$hasta'";

        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {

            $periodo = substr($fila['fecha'], 4, 2) . '/' . substr($fila['fecha'], 0, 4);

            $monto = $this->getValorAnalisis($fila['codigo'], $fila['codos'], $periodo);
            $coseguro = $this->getValorCoseguro($fila['codigo'], $fila['codos'], $periodo);

            $monto1 = 0;
            $monto2 = 0;
            if ($this->obsocial->getNivel3() == 'S') {
                $this->cNBU->getObject($val);
                if ($this->cNBU->getNivel() == '1') {
                    $monto1 = $monto;
                }
                if ($this->cNBU->getNivel() == '3') {
                    $monto2 = $monto;
                }
            }

            $actsql = "UPDATE det_capitas SET monto = " . $monto . ", coseguro =  " . $coseguro . ", monto1 = " . $monto1 . ", monto2 = " . $monto2 . " WHERE nroauditoria = " . "'" . $fila['nroauditoria'] . "'" . " AND codigo = " . "'" . $fila['codigo'] . "'" . " AND items = " . "'" . $fila['items'] . "'";
            $rs = mysql_query($actsql);

            $registro = $registro + 1;
        }

        echo $registro . ' Items Procesados - Trabajo Realizado ...!';
    }
    
    function RecaulcularMontosScos($xcodos, $xdesde, $xhasta) {
        $monto = 0;
        $registro = 0;

        $codigo = $this->getEquivalencia($xcodos);

        $desde = $this->utiles->getFechaAAAAMMDD($xdesde);
        $hasta = $this->utiles->getFechaAAAAMMDD($xhasta);       

        $query = "UPDATE det_auditoria set coseguro = monto WHERE codos = '$codigo' AND fecha >= '$desde' AND fecha <= '$hasta'";

        $resultado = mysql_query($query);
        
        echo ' Trabajo Realizado ...!';
    }
    
    function RecaulcularMontosOrden($nroauditoria, $xcodos) {
        $monto = 0;
        $registro = 0;

        $codigo = $this->getEquivalencia($xcodos);

        $this->obsocial->getObject($xcodos);

        $query = "SELECT nroauditoria, items, codos, codigo, fecha FROM det_auditoria WHERE nroauditoria_sk = '$nroauditoria'";

        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {

            $periodo = substr($fila['fecha'], 4, 2) . '/' . substr($fila['fecha'], 0, 4);

            $monto = $this->getValorAnalisis($fila['codigo'], $fila['codos'], $periodo);
            $coseguro = $this->getValorCoseguro($fila['codigo'], $fila['codos'], $periodo);

            $monto1 = 0;
            $monto2 = 0;
            if ($this->obsocial->getNivel3() == 'S') {
                $this->cNBU->getObject($val);
                if ($this->cNBU->getNivel() == '1') {
                    $monto1 = $monto;
                }
                if ($this->cNBU->getNivel() == '3') {
                    $monto2 = $monto;
                }
            }

            $this->cNBU->getObject($fila['codigo']);
            $this->cNBU->getUnidadObsocial($xcodos);

            $actsql = "UPDATE det_auditoria SET unidades = " . $this->cNBU->getUnidadNbu() . ", monto = " . $monto . ", coseguro =  " . $coseguro . ", monto1 = " . $monto1 . ", monto2 = " . $monto2 . " WHERE nroauditoria = " . "'" . $fila['nroauditoria'] . "'" . " AND codigo = " . "'" . $fila['codigo'] . "'" . " AND items = " . "'" . $fila['items'] . "'";
            $rs = mysql_query($actsql);
                        
            $registro = $registro + 1;
        }

        //-------------------------------------------------------------------

        $query = "SELECT nroauditoria, items, codos, codigo, fecha FROM det_capitas WHERE nroauditoria = '$nroauditoria'";

        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {

            $periodo = substr($fila['fecha'], 4, 2) . '/' . substr($fila['fecha'], 0, 4);

            $monto = $this->getValorAnalisis($fila['codigo'], $fila['codos'], $periodo);
            $coseguro = $this->getValorCoseguro($fila['codigo'], $fila['codos'], $periodo);

            $monto1 = 0;
            $monto2 = 0;
            if ($this->obsocial->getNivel3() == 'S') {
                $this->cNBU->getObject($val);
                if ($this->cNBU->getNivel() == '1') {
                    $monto1 = $monto;
                }
                if ($this->cNBU->getNivel() == '3') {
                    $monto2 = $monto;
                }
            }

            $actsql = "UPDATE det_capitas SET monto = " . $monto . ", coseguro =  " . $coseguro . ", monto1 = " . $monto1 . ", monto2 = " . $monto2 . " WHERE nroauditoria = " . "'" . $fila['nroauditoria'] . "'" . " AND codigo = " . "'" . $fila['codigo'] . "'" . " AND items = " . "'" . $fila['items'] . "'";
            $rs = mysql_query($actsql);

            $registro = $registro + 1;
        }
        
        echo $registro . ' Items Actualizados.';
    }

    function TotalesAuditados($xcodos, $xfecha) {

        $desde = substr($this->utiles->getFechaAAAAMMDD($xfecha), 0, 6) . '01';
        $hasta = substr($this->utiles->getFechaAAAAMMDD($xfecha), 0, 6) . '31';

        $monto = 0;
        $monto1 = 0;
        $monto2 = 0;
        $monto3 = 0;
        $unidades = 0;

        //----------------------------------------------------------------------

        if ($this->obsocial->verificarRPC($xcodos)) {

            if ($this->obsocial->_parametro7 == 'TOT1') {
                // FESALUD

                $oslist = '';
                $ress = $this->obsocial->getObrasSocialesRPC($this->obsocial->_reglaNegocio);
                while ($fila = mysql_fetch_array($ress)) {
                    $oslist = $oslist . $fila['codos'] . ',';
                }

                if (strlen($oslist) == 0)
                    exit;

                $oslist = '(' . substr($oslist, 0, strlen($oslist) - 1) . ')';

                $query = "SELECT SUM(det_auditoria.monto) AS total FROM det_auditoria, cab_auditoria WHERE cab_auditoria.codos IN " . $oslist . " AND cab_auditoria.fecha >= '$desde' AND cab_auditoria.fecha <= '$hasta' AND det_auditoria.estado = 'A' " .
                        " AND cab_auditoria.anulada <> 'S' AND cab_auditoria.nroauditoria = det_auditoria.nroauditoria";

                $resultado = mysql_query($query);
                while ($fila = mysql_fetch_array($resultado)) {
                    $monto = $fila['total'];
                }

                $m = number_format($monto, 2, '.', ',');

                echo "<table width='100%' align='left'><tr>";
                echo '<td align="left">';
                echo 'Consumo Mensual en $:<b><font color="#CC0033">' . ' ' . $m . '</font></b>';
                echo "</td>";
                echo "</tr>";
                echo "<tr><td><hr/></td><tr>";
                echo "</table>";

                exit;
            }
        }

        //----------------------------------------------------------------------

        $this->arancel->getArancelNBU($xcodos, substr($xfecha, 3, 7));

        $query = "SELECT SUM(monto) AS total, SUM(monto1) AS total1, SUM(monto2) AS total2, SUM(unidades) AS unidades FROM det_auditoria WHERE codos = '$xcodos' AND fecha >= '$desde' AND fecha <= '$hasta' AND estado = 'A'";

        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $monto = $fila['total'];
            $monto1 = $fila['total1'];
            $monto2 = $fila['total2'];
            $unidades = $fila['unidades'];
        }

        //$query = "SELECT SUM(monto) AS total, SUM(monto1) AS total1, SUM(monto2) AS total2 FROM det_capitas WHERE codos = '$xcodos' AND fecha >= '$desde' AND fecha <= '$hasta' AND estado = 'A'";

        $query = "SELECT SUM(monto) AS total FROM det_capitas WHERE codos = '$xcodos' AND fecha >= '$desde' AND fecha <= '$hasta' AND estado = 'A'";
        $total3c = 0;

        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $total3c = $fila['total'];
        }

        $capita = $this->capita->getMontoCapita($xcodos, substr($xfecha, 3, 7));
        $capita2 = $this->capita->getCapita2();
        $capita3 = $this->capita->getCapita3();
        $c = number_format($capita, 2, '.', ',');
        $c2 = number_format($capita2, 2, '.', ',');
        $c3 = number_format($capita3, 2, '.', ',');

        $m = number_format($monto, 2, '.', ',');

        $s = number_format($capita - $monto, 2, '.', ',');
        $s3 = number_format($capita3 - $unidades, 2, '.', ',');

        $m1 = number_format($monto1, 2, '.', ',');
        $m2 = number_format($monto2, 2, '.', ',');
        $un = number_format($unidades, 2, '.', ',');

        $s1 = number_format($capita - $monto1, 2, '.', ',');
        $s2 = number_format($capita2 - $monto2, 2, '.', ',');

        $m3c = number_format($total3c, 2, '.', ',');

        if ($porcentaje > 0) {
            $porcentaje = ( ($monto1 + $monto3) * 100) / $capita;
        } else {
            $porcentaje = 0;
        }

        $this->obsocial->getObject($xcodos);

        echo "<table border='0px' width='550px'>";
        if ($this->obsocial->getNivel3() != 'S') {
            echo "<tr>";
            echo '<td width="50%" align="right">';
            echo 'Cápita Nivel 1:<b><font color="#CC0033">' . ' ' . $c . '</font></b>';
            echo "</td>";
            echo '<td width="50%" align="right">';
            echo 'Tot.Cons.Per.: <b>' . substr($xfecha, 3, 7) . ' : <font color="#CC0033">' . $m . '</font></b>';
            echo "</td>";
            echo "</tr><tr>";
            echo '<td width="50%" align="right">';
            echo 'Disponible: ' . '<b><font color="#009966">' . $s . '</font></b>';
            echo "</td>";
            echo "</tr>";
            if ($capita3 > 0) {
                echo "<tr>";
                echo '<td width="50%" align="right">';
                echo 'Cápita Unidades:<b><font color="#CC0033">' . ' ' . $c3 . '</font></b>';
                echo "</td>";
                echo '<td width="50%" align="right">';
                echo 'Consumo Unidades: <b>' . substr($xfecha, 3, 7) . ' : <font color="#CC0033">' . $un . '</font></b>';
                echo "</td>";
                echo "</tr><tr>";
                echo '<td width="50%" align="right">';
                echo 'Unidades Disponible: ' . '<b><font color="navy">' . $s3 . '</font></b>';
                echo "</td>";
                echo "</tr>";
            }
        } else {
            $porcentajeos = (($monto1 * $this->arancel->getNbu_os() / $this->arancel->getArancel()) +
                    ( $total3c * $this->arancel->getNbu_os() / $this->arancel->getArancel() )) / $capita * 100;

            echo "<tr>";
            echo '<td width="50%" align="right">';
            echo 'Cápita Nivel 1:<b><font color="#CC0033">' . ' ' . $c . '</font></b>';
            echo "</td>";
            echo '<td width="50%" align="right">';
            echo 'Tot.Cons.Per.: <b>' . substr($xfecha, 3, 7) . ' : <font color="#CC0033">' . $m1 . '</font></b>';
            echo "</td>";
            echo "</tr><tr>";
            echo '<td width="50%" align="right">';
            echo 'Disponible: ' . '<b><font color="#009966">' . $s1 . '</font></b>';
            echo "</td>";
            echo "</tr>";
            echo "<tr>";
            echo '<td width="50%" align="right">';
            echo 'Cápita Nivel 3:<b><font color="#CC0033">' . ' ' . $c2 . '</font></b>';
            echo "</td>";
            echo '<td width="50%" align="right">';
            echo 'Subtotal 3Col.: <b>' . substr($xfecha, 3, 7) . ' : <font color="#CC0033">' . $m3c . '</font></b>';
            echo "</td>";
            echo "</tr><tr>";
            echo '<td width="50%" align="right">';
            echo 'Disponible: ' . '<b><font color="#009966">' . $s2 . '</font></b>';
            echo "</td>";
            echo "</tr>";
            echo "<tr>";
            echo '<td width="50%" align="right">';
            echo '% Cons.Cart.: ' . '<b><font color="#009966">' . number_format($porcentajeos, 2, '.', ',') . '</font></b>';
            //echo '((' . $monto1 .' * ' . $this->arancel->getNbu_os() . ' / ' . $this->arancel->getArancel() . ') .  + ' .
            //      '(' . $total3c . ' * ' . $this->arancel->getNbu_os() . ' / ' . $this->arancel->getArancel() . ')) / ' . $capita . '* 100';
            echo "</td>";
            echo '<td width="50%" align="right">';
            //echo 'Subtotal: ' . '<b><font color="#009966">' . number_format($monto3, 2, '.', ',') . '</font></b>';
            echo "</td>";
            echo '<td width="50%" align="right">';
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<table border='0px' width='550px'>";

        echo "<br/>";
    }

    function getCodigosEstadistica($desde, $hasta, $codos, $estado) {
        $d = $this->utiles->getFechaAAAAMMDD($desde);
        $h = $this->utiles->getFechaAAAAMMDD($hasta);
        $query = "select distinct(codigo) from det_auditoria where fecha >= '$d' and fecha <= '$h' and estado = '$estado' and codos = '$codos' and codigo <> '660000' order by codigo";
        $this->res = mysql_query($query);
        return $this->res;
    }

    function getCantidadCodigosEstadistica($codigo, $desde, $hasta, $codos, $estado) {
        $d = $this->utiles->getFechaAAAAMMDD($desde);
        $h = $this->utiles->getFechaAAAAMMDD($hasta);
        $query = "select count(det_auditoria.codigo) AS cantidad from cab_auditoria, det_auditoria where cab_auditoria.nroauditoria = det_auditoria.nroauditoria and cab_auditoria.fecha >= '$d' and cab_auditoria.fecha <= '$h' and det_auditoria.codigo = '$codigo' and det_auditoria.estado = '$estado' and cab_auditoria.auditada <> 'P' and cab_auditoria.codos = '$codos'";
        $this->res = mysql_query($query);
        return $this->res;
    }

    function getCantidadOrdenesEstadistica($desde, $hasta, $codos, $estado) {
        $d = $this->utiles->getFechaAAAAMMDD($desde);
        $h = $this->utiles->getFechaAAAAMMDD($hasta);
        $query = "select distinct cab_auditoria.nroauditoria from cab_auditoria, det_auditoria where cab_auditoria.nroauditoria = det_auditoria.nroauditoria and cab_auditoria.fecha >= '$d' and cab_auditoria.fecha <= '$h' and det_auditoria.estado = '$estado' and cab_auditoria.auditada <> 'P'";
        $this->res = mysql_query($query);
        $nr = mysql_num_rows($this->res);
        return $nr;
    }

    function getCantidadPacientesEstadisticas($desde, $hasta, $codos, $estado) {
        $d = $this->utiles->getFechaAAAAMMDD($desde);
        $h = $this->utiles->getFechaAAAAMMDD($hasta);
        $query = "select distinct cab_auditoria.nrodoc from cab_auditoria, det_auditoria where cab_auditoria.nroauditoria = det_auditoria.nroauditoria and cab_auditoria.fecha >= '$d' and cab_auditoria.fecha <= '$h' and det_auditoria.estado = '$estado' and cab_auditoria.auditada <> 'P'";
        $this->res = mysql_query($query);
        $nr = mysql_num_rows($this->res);
        return $nr;
    }

    function VerificarCodigo($codigo) {
        $query = "SELECT DISTINCT codigo FROM det_auditoria WHERE codigo = '$codigo'";

        $result = false;
        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $result = true;
            break;
        }
        return $result;
    }

    function getMontoTotalRechazados($nroauditoria) {
        $monto = 0;

        $query = "SELECT SUM(monto) AS total FROM det_auditoria WHERE nroauditoria = '$nroauditoria' AND estado = 'R'";

        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $monto = $fila['total'];
        }

        $m = number_format($monto, 2, '.', ',');

        return $m;
    }

    function getValorModulo($codos, $periodo) {
        $this->arancel->getArancelNBU($codos, $periodo);
        return $this->arancel->getModulo();
    }

    function getEquivalencia($codigo) {
        $codos = $codigo;
        // Verificamos si la obra social no utiliza el padrón de otra
        $eq = new cEquivalenciaPadrones();
        $found = $eq->getObject($codigo);
        if ($found) {
            // Si existe un código equivalente, modificamos la Obra Social
            $codos = $eq->getCodigo2();
        }
        return $codos;
    }

    function MarcarOrdenesParaDepurar($xcodos, $xdesde) {
        $desde = $this->utiles->getFechaAAAAMMDD($xdesde);
        $query = "UPDATE det_auditoria SET depura = 'S' WHERE codos = '$xcodos' AND fecha <= '$desde'";
        $resultado = mysql_query($query);
    }

    function DepurarOrdenesMarcadas($xcodos, $xdesde) {
        $desde = $this->utiles->getFechaAAAAMMDD($xdesde);
        $query = "SELECT nroauditoria, items, efector, fecha, codos, codigo, monto, estado, montodif, nrodoc, anulada, depura, coseguro, monto1, monto2, observacion, unidades, transaccion FROM det_auditoria WHERE depura = 'S' AND codos = '$xcodos' AND fecha <= '$desde'";
        $resultado = mysql_query($query);

        while ($fila = mysql_fetch_array($resultado)) {
            $nroauditoria = $fila['nroauditoria'];
            $items = $fila['items'];
            $efector = $fila['efector'];
            $fecha = $fila['fecha'];
            $codos = $fila['codos'];
            $codigo = $fila['codigo'];
            $monto = $fila['monto'];
            $estado = $fila['estado'];
            $montodif = $fila['montodif'];
            $nrodoc = $fila['nrodoc'];
            $anulada = $fila['anulada'];
            $coseguro = $fila['coseguro'];
            $monto1 = $fila['monto1'];
            $monto2 = $fila['monto2'];
            if ($montodif == '') {
                $montodif = 0;
            }
            $observacion = $fila['observacion'];
            $unidades = $fila['unidades'];
            $transaccion = $fila['transaccion'];
            
            $strsql = "INSERT IGNORE INTO det_auditoria_hist (nroauditoria, items, efector, fecha, codos, codigo, monto, montodif, nrodoc, anulada, nroauditoria_sk, coseguro, monto1, monto2, observacion, unidades, transaccion) VALUES
                                                      ('$nroauditoria', '$items', '$efector', '$fecha', '$codos', '$codigo', $monto, $montodif, '$nrodoc', '$anulada', '$nroauditoria', $coseguro, $monto1, $monto2, '$observacion', $unidades, '$transaccion')";

            $resins = mysql_query($strsql);
        }
    }

    function EliminarOrdenesMarcadasParaDepurar($xcodos, $xdesde) {
        $desde = $this->utiles->getFechaAAAAMMDD($xdesde);
        $query = "DELETE FROM det_auditoria WHERE depura = 'S' AND codos = '$xcodos' AND fecha <= '$desde'";
        $resultado = mysql_query($query);
    }

    function GuardarItemsCapitas($nroauditoria, $item, $codigo, $estado, $fecha, $codos) {

        // Borramos si ya existe
        if ($item == '001') {
            $query = "DELETE FROM det_capitas WHERE nroauditoria = '$nroauditoria'";
            $result = mysql_query($query);
        }

        $f = $this->utiles->getFechaAAAAMMDD($fecha);
        $per = substr($f, 4, 2) . '/' . substr($f, 0, 4);
        $monto = $this->getValorAnalisis($codigo, $codos, $per);
        $coseguro = $this->getValorCoseguro($codigo, $codos, $per);

        $monto1 = 0;
        $monto2 = 0;
        $this->obsocial->getObject($codos);
        if ($this->obsocial->getNivel3() == 'S') {
            $this->cNBU->getObject($val);
            if ($this->cNBU->getNivel() == '1') {
                $monto1 = $monto;
            }
            if ($this->cNBU->getNivel() == '3') {
                $monto2 = $monto;
            }
        }

        $query = "INSERT INTO det_capitas (nroauditoria, items, codigo, estado, fecha, codos, monto, nroauditoria_sk, coseguro, monto1, monto2) VALUES
                                               ('$nroauditoria', '$item', '$codigo', '$estado', '$f', '$codos', $monto, '$nroauditoria', $coseguro, $monto1, $monto2)";
        $result = mysql_query($query);
    }

    function AjustarItemsCapitas($nroauditoria) {
        $query = "DELETE FROM det_capitas WHERE nroauditoria = '$nroauditoria'";
        $result = mysql_query($query);
    }

    function getItemsCapitas($nroauditoria) {
        $query = "SELECT codigo, items, estado FROM det_capitas WHERE nroauditoria = " . $nroauditoria;
        $this->res = mysql_query($query);
        return $this->res;
    }

    function getListCoseguros($codos, $desde, $hasta) {
        $query = "SELECT nroauditoria, items, efector, codos, monto, coseguro, fecha, estado FROM det_auditoria WHERE codos = " . "'" . $codos . "'" . " and fecha >= " . "'" .
                $this->utiles->getFechaAAAAMMDD($desde) . "'" . " and fecha <= " . "'" . $this->utiles->getFechaAAAAMMDD($hasta) . "'" . " and coseguro > 0 order by efector, nroauditoria, items";
        $this->res = mysql_query($query);
        return $this->res;
    }

    function marcarItemsAutorizados($nroauditoria) {
        $query = "UPDATE det_auditoria SET estado = 'A' WHERE nroauditoria = '$nroauditoria'";
        $result = mysql_query($query);
    }
    
    function findPracticaAuditoria($nroauditoria, $codigo) {
        
        $query = "SELECT codigo FROM det_auditoria WHERE nroauditoria = '$nroauditoria' AND codigo = '$codigo'";

        $m = false;
        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $m = true;
        }

        return $m;
        
    }
    
    function addItemsExterno($nroauditoria, $item, $codigo, $efector, $codos, $fecha, $nrodoc, $estado) {

        $f = $this->utiles->getFechaAAAAMMDD($fecha);
        $periodo = substr($f, 4, 2) . '/' . substr($f, 0, 4);
        $monto = $this->getValorAnalisis($codigo, $codos, $periodo);
        $coseguro = $this->getValorCoseguro($codigo, $codos, $periodo);

        $this->obsocial->getObject($codos);
        $monto1 = 0;
        $monto2 = 0;
        if ($this->obsocial->getNivel3() == 'S') {
            $this->cNBU->getObject($val);
            if ($this->cNBU->getNivel() == '1') {
                $monto1 = $monto;
            }
            if ($this->cNBU->getNivel() == '3') {
                $monto2 = $monto;
            }
        }

        $it = $item;

        $query = "INSERT INTO det_auditoria (nroauditoria, items, codigo, efector, estado, fecha, codos, nrodoc, monto, anulada, nroauditoria_sk, coseguro, monto1, monto2) VALUES
                                        ('$nroauditoria', '$it', '$codigo', '$efector', '$estado', '$f', '$codos', '$nrodoc', $monto, 'N', '$nroauditoria', $coseguro, $monto1, $monto2)";
        
        $result = mysql_query($query);
    }


}

?>