<?php

set_include_path('../');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/conexion.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cUtiles.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cItemsAuditoria.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cObSocial.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cMedicos.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cDiagnosticosOMS.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cAfiliados.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cEntidad.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cNBU.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cTramos.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cEquivalenciaPadrones.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cMedicosCab.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cMFBoletas.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cWsRespuestas.php");
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cNbuReglas.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cObsocialNotificaciones.php");

//implementamos la clase empleado
class cAuditoria {

//constructor
    var $nroauditoria;
    var $efector;
    var $fecha;
    var $codos;
    var $idzona;
    var $nrodoc;
    var $idprof;
    var $observacion;
    var $obsauditor;
    var $iddiag;
    var $idperfil;
    var $utiles;
    var $nbu;
    var $entidad;
    var $tramos;
    var $profcab;
    var $detalle;
    var $obrasocial;
    var $medico;
    var $diagnostico;
    var $afiliado;
    var $medico_cab;
    var $mfboleta;
    var $res;
    var $sql;
    var $diferida;
    var $auditada;
    var $codigos_autorizados;
    var $codigos_rechazados;
    var $bonos_auditoria;
    var $coseguro_auditoria;
    var $equivalencia;
    var $transaccion;
    var $transaccion_anulada;
    var $expediente;
    var $wsres;
    var $nroautorizacion;
    var $regla;
    var $osnot;

    function cAuditoria() {
        $this->utiles = new cUtiles;
        $this->detalle = new cItemsAuditoria;
        $this->obrasocial = new cObSocial;
        $this->medico = new cMedicos;
        $this->diagnostico = new cDiagnosticosOMS;
        $this->afiliado = new cAfiliados;
        $this->nbu = new cNBU;
        $this->entidad = new cEntidad;
        $this->tramos = new cTramos;
        $this->medico_cab = new cMedicosCab();
        $this->mfboleta = new cMFBoletas();
        $this->equivalencia = new cEquivalenciaPadrones();
        $this->wsres = new cWsRespuestas();
        $this->regla = new cNbuReglas();
        $this->osnot = new cObsocialNotificaciones();
    }

    function initItems() {
        $this->detalle = new cItemsAuditoria;
    }

    // inserta tupla
    function crear($nroauditoria, $efector, $fecha, $codos, $idzona, $nrodoc, $idprof, $observacion, $iddiag, $idperfil, $fepedido, $autoriza, $profcab) {
        $f = substr($nroauditoria, 6, 8);
        $fp = $this->utiles->getFechaAAAAMMDD($fepedido);
        $diferida = 'N';
        if ($autoriza) {
            $diferida = 'N';
        } else {
            $diferida = 'S';
        }

        // Antes de guardar chequeamos que existan items
        if ($this->detalle->getCantidadItems() > 0) {
            $query = "INSERT INTO cab_auditoria (nroauditoria, efector, fecha, codos, idzona, nrodoc, idprof, observacion, iddiag, idperfil, fepedido, diferida, auditada, anulada, profcab) VALUES
                                         ('$nroauditoria', '$efector', '$f', '$codos', '$idzona', '$nrodoc', '$idprof', '$observacion', '$iddiag', '$idperfil', '$fp', '$diferida', 'N', 'N', '$profcab')";
            $result = mysql_query($query);
            $this->sql = $result;
            //if ($codos = '121045') echo $query . '<br/>';
            if (!$result) {
                echo 'Se Produjo un Error al Grabar la Orden ' . $nroauditoria . '<br>' . $query;
                return false;
            } else {
                $this->detalle->GuardarItems($nroauditoria, $codos, $f, $nrodoc, $autoriza);

                //Recálulo de Coseguro - 17/03/2020
                $this->aplicarCoseguroReglas($nroauditoria, $codos, $nrodoc);

                echo '<br>';
                echo 'La Orden<b> [ ' . $nroauditoria . ' ]</b> se Grabó Correctamente';
                echo '<hr>';
                sleep(1);
                return true;
            }
        } else {
            echo 'Se Produjo un Error al Grabar la Orden ' . $nroauditoria;
            return false;
        }
    }
    
        // inserta tupla con token
    function crear2($nroauditoria, $efector, $fecha, $codos, $idzona, $nrodoc, $idprof, $observacion, $iddiag, $idperfil, $fepedido, $autoriza, $profcab, $token, $numerodoc) {
        $f = substr($nroauditoria, 6, 8);
        $fp = $this->utiles->getFechaAAAAMMDD($fepedido);
        $diferida = 'N';
        if ($autoriza) {
            $diferida = 'N';
        } else {
            $diferida = 'S';
        }

        // Antes de guardar chequeamos que existan items
        if ($this->detalle->getCantidadItems() > 0) {
            $query = "INSERT INTO cab_auditoria (nroauditoria, efector, fecha, codos, idzona, nrodoc, idprof, observacion, iddiag, idperfil, fepedido, diferida, auditada, anulada, profcab, token, numerodoc) VALUES
                                         ('$nroauditoria', '$efector', '$f', '$codos', '$idzona', '$nrodoc', '$idprof', '$observacion', '$iddiag', '$idperfil', '$fp', '$diferida', 'N', 'N', '$profcab', '$token', '$numerodoc')";
            $result = mysql_query($query);
            $this->sql = $result;
            //if ($codos = '121045') echo $query . '<br/>';
            if (!$result) {
                echo 'Se Produjo un Error al Grabar la Orden ' . $nroauditoria . '<br>' . $query;
                return false;
            } else {
                $this->detalle->GuardarItems($nroauditoria, $codos, $f, $nrodoc, $autoriza);

                //Recálulo de Coseguro - 17/03/2020
                $this->aplicarCoseguroReglas($nroauditoria, $codos, $nrodoc);

                echo '<br>';
                echo 'La Orden<b> [ ' . $nroauditoria . ' ]</b> se Grabó Correctamente';
                echo '<hr>';
                sleep(1);
                return true;
            }
        } else {
            echo 'Se Produjo un Error al Grabar la Orden ' . $nroauditoria;
            return false;
        }
    }

    // inserta tupla
    // 12/07/2018
    function crearPR($nroauditoria, $efector, $fecha, $codos, $idzona, $nrodoc, $idprof, $observacion, $iddiag, $idperfil, $fepedido, $autoriza, $profcab) {
        $f = substr($nroauditoria, 6, 8);
        $fp = $this->utiles->getFechaAAAAMMDD($fepedido);
        $diferida = 'N';
        if ($autoriza) {
            $diferida = 'N';
        } else {
            $diferida = 'S';
        }

        // Antes de guardar chequeamos que existan items
        if ($this->detalle->getCantidadItems() > 0) {
            $query = "INSERT INTO cab_auditoria (nroauditoria, efector, fecha, codos, idzona, nrodoc, idprof, observacion, iddiag, idperfil, fepedido, diferida, auditada, anulada, profcab) VALUES
                                         ('$nroauditoria', '$efector', '$f', '$codos', '$idzona', '$nrodoc', '$idprof', '$observacion', '$iddiag', '$idperfil', '$fp', '$diferida', 'N', 'N', '$profcab')";
            $result = mysql_query($query);
            if (!$result) {
                echo 'Se Produjo un Error al Grabar la Orden ' . $nroauditoria . '<br>' . $query;
                return false;
            } else {
                $this->detalle->GuardarItems($nroauditoria, $codos, $f, $nrodoc, false);
                echo '<br>';
                echo 'La Orden<b> [ ' . $nroauditoria . ' ]</b> se Grabó Correctamente';
                echo '<hr>';
                sleep(1);
                return true;
            }
        } else {
            echo 'Se Produjo un Error al Grabar la Orden ' . $nroauditoria;
            return false;
        }
    }

    function actualizarItemsExternos($nroauditoria, $item, $codigo, $efector, $codos, $fecha, $nrodoc, $estado) {
        $this->detalle->ActualizarItemsExternos($nroauditoria, $item, $codigo, $efector, $codos, $fecha, $nrodoc, $estado);
    }

    function addItemsExterno($nroauditoria, $item, $codigo, $efector, $codos, $fecha, $nrodoc, $estado) {
        $this->detalle->addItemsExterno($nroauditoria, $item, $codigo, $efector, $codos, $fecha, $nrodoc, $estado);
    }

    function findPracticaAuditoria($nroauditoria, $codigo) {
        return $this->detalle->findPracticaAuditoria($nroauditoria, $codigo);
    }

    // update tupla
    function marcarPracticasStanBy($nroauditoria) {
        $query = "UPDATE det_auditoria SET estado = 'R' WHERE nroauditoria = " . $nroauditoria;
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    // borra tupla
    function borrar($nroauditoria) {
        $query = "DELETE FROM cab_auditoria WHERE nroauditoria = " . $nroauditoria;
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    // borra orden
    function borrarOrden($nroauditoria) {
        $query = "DELETE FROM cab_auditoria WHERE nroauditoria = '$nroauditoria'";
        $result = mysql_query($query);
        $query = "DELETE FROM det_auditoria WHERE nroauditoria = '$nroauditoria'";
        $result = mysql_query($query);
    }

    // actualiza tupla
    function actualizar($nroauditoria, $efector, $fecha, $codos, $idzona, $nrodoc, $idprof, $observacion, $iddiag, $idperfil, $profcab) {
        $f = $this->utiles->getFechaAAAAMMDD($fecha);
        $query = "UPDATE cab_auditoria SET efector = '$efector' .
                                     ', fecha = '$f'.
                                     ', codos = '$codos' .
                                     ', idzona = '$idzona' .
                                     ', nrodoc =  '$nrodoc' .
                                     ', idprof = '$idprof' .
                                     ', observacion = '$observacion' .
                                     ', iddiag = '$iddiag' .
                                     ', profcab = '$profcab' .
                                     ', idperfil = '$idperfil'" .
                " WHERE nroauditoria = '$nroauditoria'";
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    function getObject($nroauditoria) {
        $query = "SELECT nroauditoria, codos, idprof, fecha, nrodoc, iddiag, nombre, diferida, auditada, efector, obsauditor, 
                  observacion, idzona, idperfil, profcab, transaccion, transaccion_anulada, nroautorizacion, expediente FROM cab_auditoria WHERE nroauditoria = '$nroauditoria'";

        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $this->fecha = $this->utiles->getFechaDDMMAAAA($fila['fecha']);
            $this->codos = $fila['codos'];
            $this->efector = $fila['efector'];
            $this->idzona = $fila['idzona'];
            $this->nrodoc = $fila['nrodoc'];
            $this->idprof = $fila['idprof'];
            $this->observacion = $fila['observacion'];
            $this->obsauditor = $fila['obsauditor'];
            $this->iddiag = $fila['iddiag'];
            $this->idperfil = $fila['idperfil'];
            $this->profcab = $fila['profcab'];
            $this->diferida = $fila['diferida'];
            $this->auditada = $fila['auditada'];
            $this->transaccion = $fila['transaccion'];
            $this->transaccion_anulada = $fila['transaccion_anulada'];
            $this->expediente = $fila['expediente'];
            $this->nroautorizacion = $fila['nroautorizacion'];
        }
    }

    function getCodos() {
        return $this->codos;
    }

    function getFecha() {
        return $this->fecha;
    }

    function getNrodoc() {
        return $this->nrodoc;
    }

    function getEfector() {
        return $this->efector;
    }

    function getObservacion() {
        return $this->obsauditor;
    }

    function getObservacionEfector() {
        return $this->observacion;
    }

    function getIdprof() {
        return $this->idprof;
    }

    function getIddiag() {
        return $this->iddiag;
    }

    function getProfcab() {
        return $this->profcab;
    }

    function getDiferida() {
        return $this->diferida;
    }

    function getAuditada() {
        return $this->auditada;
    }

    function getOrdenesAfiliado($codos, $nrodoc) {
        if ($codos != '*') {
            $query = "SELECT nroauditoria, fecha, idprof, iddiag, anulada, obsauditor, efector, obsauditor, auditada, diferida, anulada, nombre FROM cab_auditoria WHERE codos = '$codos' AND nrodoc = '$nrodoc' ORDER BY fecha DESC";
        } else {
            $query = "SELECT nroauditoria, codos, fecha, idprof, iddiag, anulada, obsauditor, efector, obsauditor, auditada, diferida, anulada, nombre FROM cab_auditoria WHERE nrodoc = '$nrodoc' ORDER BY fecha DESC";
        }
        $this->res = mysql_query($query);
        //echo $query;
        return $this->res;
    }

    function getOrdenesAfiliadoHist($codos, $nrodoc) {
        if ($codos != '*') {
            $query = "SELECT nroauditoria, fecha, idprof, iddiag, anulada, obsauditor, efector, obsauditor, auditada, diferida, anulada, nombre FROM cab_auditoria_hist WHERE codos = '$codos' AND nrodoc = '$nrodoc' ORDER BY fecha DESC";
        } else {
            $query = "SELECT nroauditoria, codos, fecha, idprof, iddiag, anulada, obsauditor, efector, obsauditor, auditada, diferida, anulada, nombre FROM cab_auditoria_hist WHERE nrodoc = '$nrodoc' ORDER BY fecha DESC";
        }
        $this->res = mysql_query($query);
        return $this->res;
    }

    function getOrdenesAfiliadoProfesional($codos, $idprof) {
        $query = "SELECT nroauditoria, fecha, idprof, iddiag, anulada, obsauditor, efector, obsauditor, auditada, diferida, anulada, nombre, nrodoc FROM cab_auditoria WHERE codos = '$codos' AND idprof = '$idprof' ORDER BY fecha DESC";
        $this->res = mysql_query($query);
        $this->sql = $query;
        return $this->res;
    }

    function getConsultaOrdenes($codos, $idprof, $xdesde, $xhasta, $xtipo_consulta, $RegistrosAEmpezar, $RegistrosAMostrar) {
        $desde = $this->utiles->getFechaAAAAMMDD($xdesde);
        $hasta = $this->utiles->getFechaAAAAMMDD($xhasta);

        if ($xtipo_consulta == 1) {
            $query = "SELECT nroauditoria, codos, nrodoc, fecha, efector, idprof, iddiag, diferida, auditor, token FROM cab_auditoria WHERE codos = '$codos' AND efector = '$idprof' AND fecha >= '$desde' AND fecha <= '$hasta' AND diferida = 'S' AND anulada <> 'S' ORDER BY fecha, efector LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
        }
        if ($xtipo_consulta == 2) {
            $query = "SELECT nroauditoria, codos, nrodoc, fecha, efector, idprof, iddiag, diferida, auditor, token FROM cab_auditoria WHERE codos = '$codos' AND efector = '$idprof' AND fecha >= '$desde' AND fecha <= '$hasta' AND diferida = 'N' AND anulada <> 'S' ORDER BY fecha, efector  LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
        }
        if ($xtipo_consulta == 3) {
            $query = "SELECT nroauditoria, codos, nrodoc, fecha, efector, idprof, iddiag, diferida, auditada, auditor, token FROM cab_auditoria WHERE codos = '$codos' AND efector = '$idprof' AND fecha >= '$desde' AND fecha <= '$hasta' AND anulada <> 'S' ORDER BY nroauditoria  LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
        }
        if ($xtipo_consulta == 4) {
            $query = "SELECT nroauditoria, codos, nrodoc, fecha, efector, idprof, iddiag, diferida, auditada, anulada, auditor, token FROM cab_auditoria WHERE codos = '$codos' AND fecha >= '$desde' AND fecha <= '$hasta' AND anulada <> 'S' ORDER BY fecha LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
        }
        if ($xtipo_consulta == 5) {
            $query = "SELECT nroauditoria, codos, nrodoc, fecha, efector, idprof, iddiag, diferida, anulada, auditada, auditor, token FROM cab_auditoria WHERE codos = '$codos' AND efector = '$idprof' AND fecha >= '$desde' AND fecha <= '$hasta' AND ( (auditada <> 'S' AND auditada <> 'P' OR transaccion <> '') OR (nroautorizacion <> '') ) ORDER BY fecha, efector LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
        }
        if ($xtipo_consulta == 6) {
            $query = "SELECT nroauditoria, codos, nrodoc, fecha, efector, idprof, iddiag, diferida, auditada, auditor, token FROM cab_auditoria WHERE codos = '$codos' AND efector = '$idprof' AND fecha >= '$desde' AND fecha <= '$hasta' AND anulada <> 'S' AND diferida = 'S' ORDER BY fecha, efector  LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
        }
        if ($xtipo_consulta == 7) {
            $query = "SELECT nroauditoria, codos, nrodoc, fecha, efector, idprof, iddiag, diferida, auditada, anulada, auditor, token FROM cab_auditoria WHERE codos = '$codos' AND fecha >= '$desde' AND fecha <= '$hasta' ORDER BY fecha LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
        }
        if ($xtipo_consulta == 8) {
            $query = "SELECT nroauditoria, codos, nrodoc, fecha, efector, idprof, iddiag, diferida, auditada, anulada, auditor, token FROM cab_auditoria WHERE codos = '$codos' AND efector = '$idprof' AND fecha >= '$desde' AND fecha <= '$hasta' ORDER BY nroauditoria  LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
        }
        if ($xtipo_consulta == 9) {
            $query = "SELECT nroauditoria, codos, nrodoc, fecha, efector, idprof, iddiag, diferida, auditada, auditor, token FROM cab_auditoria WHERE codos = '$codos' AND efector = '$idprof' AND fecha >= '$desde' AND fecha <= '$hasta' AND anulada <> 'S' ORDER BY fecha, efector  LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
        }

        if ($xtipo_consulta == 10) {
            $query = "SELECT distinct(cab_auditoria.NROAUDITORIA) as nroauditoria, det_auditoria.ESTADO, cab_auditoria.codos, cab_auditoria.nrodoc, cab_auditoria.fecha, cab_auditoria.efector, cab_auditoria.idprof, cab_auditoria.iddiag, cab_auditoria.diferida, cab_auditoria.auditada, cab_auditoria.auditor " .
                    "FROM cab_auditoria, det_auditoria WHERE cab_auditoria.NROAUDITORIA = det_auditoria.nroauditoria_sk AND cab_auditoria.fecha between '$desde' AND '$hasta' AND cab_auditoria.codos = '$codos' AND cab_auditoria.efector = '$idprof' AND det_auditoria.estado = 'R' ORDER BY cab_auditoria.fecha " .
                    "LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
        }

        //echo $query . ' - ' . $xtipo_consulta;        
        //echo $query;

        $this->res = mysql_query($query);
        return $this->res;
    }

    function getConsultaOrdenesCount($codos, $idprof, $xdesde, $xhasta, $xtipo_consulta, $RegistrosAEmpezar, $RegistrosAMostrar) {
        $desde = $this->utiles->getFechaAAAAMMDD($xdesde);
        $hasta = $this->utiles->getFechaAAAAMMDD($xhasta);

        if ($xtipo_consulta == 1) {
            $query = "SELECT count(nroauditoria) as cant FROM cab_auditoria WHERE codos = '$codos' AND efector = '$idprof' AND fecha >= '$desde' AND fecha <= '$hasta' AND diferida = 'S' AND anulada <> 'S'";
        }
        if ($xtipo_consulta == 2) {
            $query = "SELECT count(nroauditoria) as cant FROM cab_auditoria WHERE codos = '$codos' AND efector = '$idprof' AND fecha >= '$desde' AND fecha <= '$hasta' AND diferida = 'N' AND anulada <> 'S'";
        }
        if ($xtipo_consulta == 3) {
            $query = "SELECT count(nroauditoria) as cant FROM cab_auditoria WHERE codos = '$codos' AND efector = '$idprof' AND fecha >= '$desde' AND fecha <= '$hasta' AND anulada <> 'S'";
        }
        if ($xtipo_consulta == 4) {
            $query = "SELECT count(nroauditoria) as cant FROM cab_auditoria WHERE codos = '$codos' AND fecha >= '$desde' AND fecha <= '$hasta' AND anulada <> 'S'";
        }
        if ($xtipo_consulta == 5) {
            $query = "SELECT count(nroauditoria) as cant FROM cab_auditoria WHERE codos = '$codos' AND efector = '$idprof' AND fecha >= '$desde' AND fecha <= '$hasta' AND ( (auditada <> 'S' AND auditada <> 'P' OR transaccion <> '') OR (nroautorizacion <> '') )";
        }
        if ($xtipo_consulta == 6) {
            $query = "SELECT count(nroauditoria) as cant FROM cab_auditoria WHERE codos = '$codos' AND efector = '$idprof' AND fecha >= '$desde' AND fecha <= '$hasta' AND anulada <> 'S' AND diferida = 'S'";
        }
        if ($xtipo_consulta == 7) {
            $query = "SELECT count(nroauditoria) as cant FROM cab_auditoria WHERE codos = '$codos' AND fecha >= '$desde' AND fecha <= '$hasta'";
        }
        if ($xtipo_consulta == 8) {
            $query = "SELECT count(nroauditoria) as cant FROM cab_auditoria WHERE codos = '$codos' AND efector = '$idprof' AND fecha >= '$desde' AND fecha <= '$hasta'";
        }
        if ($xtipo_consulta == 9) {
            $query = "SELECT nroauditoria, codos, nrodoc, fecha, efector, idprof, iddiag, diferida, auditada, auditor FROM cab_auditoria WHERE codos = '$codos' AND efector = '$idprof' AND fecha >= '$desde' AND fecha <= '$hasta' AND anulada <> 'S' ORDER BY fecha, efector  LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
        }

        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $cantidad = $fila['cant'];
        }
        return $cantidad;


        //$this->res = mysql_query($query);
        //return $this->res;
    }

    function getConsultaOrdenesAuditor($codos, $idprof, $xdesde, $xhasta, $xtipo_consulta, $xefexcluir, $xefincluir, $RegistrosAEmpezar, $RegistrosAMostrar) {

        $desde = $this->utiles->getFechaAAAAMMDD($xdesde);
        $hasta = $this->utiles->getFechaAAAAMMDD($xhasta);

        $extsql = '';
        if ($xefexcluir != '')
            $extsql = " AND efector <> '$xefexcluir' ";
        if ($xefincluir != '')
            $extsql = " AND efector = '$xefincluir' ";

        if ($xefexcluir == '' && $xefincluir == '')
            $extsql = '';

        if ($xtipo_consulta == 4) {
            $query = "SELECT nroauditoria, codos, nrodoc, fecha, efector, idprof, iddiag, diferida, auditada, anulada, auditor FROM cab_auditoria WHERE codos = '$codos' AND fecha >= '$desde' AND fecha <= '$hasta' AND anulada <> 'S' " . $extsql . " ORDER BY fecha LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
        }
        if ($xtipo_consulta == 5) {
            $query = "SELECT nroauditoria, codos, nrodoc, fecha, efector, idprof, iddiag, diferida, anulada, auditada, auditor FROM cab_auditoria WHERE codos = '$codos' AND efector = '$idprof' AND fecha >= '$desde' AND fecha <= '$hasta' AND (auditada <> 'S' AND auditada <> 'P' OR transaccion > '')  " . $extsql . " ORDER BY fecha, efector LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
        }
        if ($xtipo_consulta == 3) {
            $query = "SELECT nroauditoria, codos, nrodoc, fecha, efector, idprof, iddiag, diferida, auditada, anulada, auditor FROM cab_auditoria WHERE codos = '$codos' AND fecha >= '$desde' AND fecha <= '$hasta' AND anulada <> 'S' AND efector = '$idprof' " . $extsql . " ORDER BY fecha LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
        }

        $this->res = mysql_query($query);
        return $this->res;
    }

    function getConsultaOrdenesPorEstado($codos, $idprof, $xdesde, $xhasta, $xmodo, $RegistrosAEmpezar, $RegistrosAMostrar) {
        $desde = $this->utiles->getFechaAAAAMMDD($xdesde);
        $hasta = $this->utiles->getFechaAAAAMMDD($xhasta);

        if ($xmodo == 1) {
            // Auditoria Directa
            if ($idprof == '000000') {
                $query = "SELECT nroauditoria, codos, nrodoc, fecha, efector, idprof, iddiag, diferida FROM cab_auditoria WHERE codos = '$codos' AND fecha >= '$desde' AND fecha <= '$hasta' AND diferida = 'N' AND anulada <> 'S' ORDER BY fecha LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
            } else {
                $query = "SELECT nroauditoria, codos, nrodoc, fecha, efector, idprof, iddiag, diferida FROM cab_auditoria WHERE codos = '$codos' AND efector = '$idprof' AND fecha >= '$desde' AND fecha <= '$hasta' AND diferida = 'N' AND anulada <> 'S' ORDER BY fecha, efector LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
            }
        }

        if ($xmodo == 2) {
            // Auditoria Diferida
            if ($idprof == '000000') {
                $query = "SELECT nroauditoria, codos, nrodoc, fecha, efector, idprof, iddiag, diferida FROM cab_auditoria WHERE codos = '$codos' AND fecha >= '$desde' AND fecha <= '$hasta' AND diferida = 'S' AND anulada <> 'S' ORDER BY fecha LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
            } else {
                $query = "SELECT nroauditoria, codos, nrodoc, fecha, efector, idprof, iddiag, diferida FROM cab_auditoria WHERE codos = '$codos' AND efector = '$idprof' AND fecha >= '$desde' AND fecha <= '$hasta' AND diferida = 'S' AND anulada <> 'S' ORDER BY fecha, efector LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
            }
        }

        $this->res = mysql_query($query);
        return $this->res;
    }

    function getConsultaOrdenesDiferidas($codos, $idprof, $xdesde, $xhasta, $xtipo_consulta, $xefexcluir, $xefincluir, $RegistrosAEmpezar, $RegistrosAMostrar) {
        $desde = $this->utiles->getFechaAAAAMMDD($xdesde);
        $hasta = $this->utiles->getFechaAAAAMMDD($xhasta);

        $extsql = '';
        if ($xefexcluir != '')
            $extsql = " AND efector <> '$xefexcluir' ";
        if ($xefincluir != '')
            $extsql = " AND efector = '$xefincluir' ";

        if ($xtipo_consulta == 3) {
            $query = "SELECT nroauditoria, codos, nrodoc, fecha, efector, idprof, iddiag, diferida, auditor FROM cab_auditoria WHERE codos = '$codos' AND efector = '$idprof' AND fecha >= '$desde' AND fecha <= '$hasta' AND anulada <> 'S' AND diferida = 'S' " . $extsql . " ORDER BY fecha  LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
        }
        if ($xtipo_consulta == 4) {
            $query = "SELECT nroauditoria, codos, nrodoc, fecha, efector, idprof, iddiag, diferida, auditor FROM cab_auditoria WHERE codos = '$codos' AND fecha >= '$desde' AND fecha <= '$hasta' AND anulada <> 'S' AND diferida = 'S' " . $extsql . " ORDER BY fecha LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
        }
        //echo $query . '  ' . $xtipo_consulta .  ' ' . $efexcluir . ' ' . $xefincluir;
        $this->res = mysql_query($query);
        return $this->res;
    }

    function getConsultaOrdenesAfiliado($codos, $nrodoc, $xdesde, $xhasta, $xtipo_consulta, $xefxcluir, $xefincluir, $RegistrosAEmpezar, $RegistrosAMostrar) {
        $desde = $this->utiles->getFechaAAAAMMDD($xdesde);
        $hasta = $this->utiles->getFechaAAAAMMDD($xhasta);

        $extsql = '';
        if ($efexcluir != '')
            $extsql = " AND efector <> '$efxcluir' ";
        if ($xefincluir != '')
            $extsql = " AND efector = '$xefincluir' ";

        $query = "SELECT nroauditoria, codos, nrodoc, fecha, efector, idprof, iddiag, diferida FROM cab_auditoria WHERE codos = '$codos' AND nrodoc = '$nrodoc' AND anulada <> 'S' " . $extsql . " ORDER BY fecha DESC LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
        $this->sql = $query;
        $this->res = mysql_query($query);
        return $this->res;
    }

    function getConsultaOrdenesAfiliadoEfector($codos, $efector, $nrodoc, $xdesde, $xhasta, $xtipo_consulta, $RegistrosAEmpezar, $RegistrosAMostrar) {
        $desde = $this->utiles->getFechaAAAAMMDD($xdesde);
        $hasta = $this->utiles->getFechaAAAAMMDD($xhasta);

        //$query = "SELECT nroauditoria, codos, nrodoc, fecha, efector, idprof, iddiag, diferida FROM cab_auditoria WHERE codos = '$codos' AND nrodoc = '$nrodoc' AND efector = '$efector' AND anulada <> 'S' AND nroauditoria > '0000000000000000000' ORDER BY fecha DESC LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
        $query = "SELECT nroauditoria, codos, nrodoc, fecha, efector, idprof, iddiag, diferida, anulada FROM cab_auditoria WHERE codos = '$codos' AND nrodoc = '$nrodoc' AND efector = '$efector' AND nroauditoria > '0000000000000000000' ORDER BY fecha DESC LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";

        $this->res = mysql_query($query);
        return $this->res;
    }

    function getConsultaOrdenesDiferidasPendientes($codos, $idprof, $xdesde, $xhasta, $xtipo_consulta, $xefexcluir, $xefincluir, $RegistrosAEmpezar, $RegistrosAMostrar) {
        $desde = $this->utiles->getFechaAAAAMMDD($xdesde);
        $hasta = $this->utiles->getFechaAAAAMMDD($xhasta);

        $extsql = '';
        if ($xefexcluir != '')
            $extsql = " AND efector <> '$xefexcluir' ";
        if ($xefincluir != '')
            $extsql = " AND efector = '$xefincluir' ";

        if ($xtipo_consulta == 3) {
            $query = "SELECT nroauditoria, codos, nrodoc, fecha, efector, idprof, iddiag, diferida, auditor FROM cab_auditoria WHERE codos = '$codos' AND efector = '$idprof' AND fecha >= '$desde' AND fecha <= '$hasta'  AND anulada <> 'S' AND diferida = 'S' AND auditada <> 'S' AND auditada <> 'P' " . $extsql . " ORDER BY fecha, efector  LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
        }
        if ($xtipo_consulta == 4) {
            $query = "SELECT nroauditoria, codos, nrodoc, fecha, efector, idprof, iddiag, diferida, auditor FROM cab_auditoria WHERE codos = '$codos' AND fecha >= '$desde' AND fecha <= '$hasta' AND anulada <> 'S' AND diferida = 'S' AND auditada <> 'S' AND auditada <> 'P' " . $extsql . " ORDER BY fecha, efector LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
        }
        if ($xtipo_consulta == 5) {
            $query = "SELECT nroauditoria, codos, nrodoc, fecha, efector, auditor, idprof, iddiag, diferida FROM cab_auditoria WHERE codos = '$codos' AND fecha >= '$desde' AND fecha <= '$hasta' AND anulada <> 'S' AND diferida = 'S' AND auditada = 'P' " . $extsql . " ORDER BY fecha, efector LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
        }

        //echo $query . ' -- ' . $xefincluir;

        $this->res = mysql_query($query);
        return $this->res;
    }

    function getConsultaOrdenesStandBy($codos, $idprof, $xdesde, $xhasta, $xtipo_consulta, $efexcluir, $xefincluir, $RegistrosAEmpezar, $RegistrosAMostrar) {
        $hasta = $this->utiles->getFechaAAAAMMDD($xhasta);

        $extsql = '';
        if ($efxcluir != '')
            $extsql = " AND efector <> '$efexcluir' ";
        if ($xefincluir != '')
            $extsql = " AND efector = '$xefincluir' ";

        if ($xtipo_consulta == 4) {
            $query = "SELECT nroauditoria, codos, nrodoc, auditor, fecha, efector, idprof, iddiag, diferida FROM cab_auditoria WHERE codos = '$codos' AND fecha <= '$hasta' AND anulada <> 'S' AND diferida = 'S' AND auditada = 'P' " . $extsql . " ORDER BY fecha, efector LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
        }
        if ($xtipo_consulta == 3) {
            $query = "SELECT nroauditoria, codos, nrodoc, auditor, fecha, efector, idprof, iddiag, diferida FROM cab_auditoria WHERE codos = '$codos' AND efector = '$idprof' AND fecha <= '$hasta' AND anulada <> 'S' AND diferida = 'S' AND auditada = 'P' " . $extsql . "  ORDER BY fecha, efector LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
        }

        $this->res = mysql_query($query);
        return $this->res;
    }

    function getDeterminaciones($nroauditoria) {
        $query = "SELECT items, codigo, monto, estado, codos, observacion FROM det_auditoria WHERE nroauditoria = " . $nroauditoria . " ORDER BY items, estado";
        $this->res = mysql_query($query);
        //echo $query;
        return $this->res;
    }

    function getDeterminacionesHist($nroauditoria) {
        $query = "SELECT items, codigo, monto, estado FROM det_auditoria_hist WHERE nroauditoria = " . $nroauditoria . " ORDER BY items, estado";
        $this->res = mysql_query($query);
        return $this->res;
    }

    function getFechaDeterminacion($nroauditoria) {
        $f = '01-01-1980';
        if (strlen(trim($nroauditoria)) > 0) {
            $query = "SELECT fecha FROM cab_auditoria WHERE nroauditoria = '$nroauditoria'";
            $resultado = mysql_query($query);
            while ($fila = mysql_fetch_array($resultado)) {
                $f = $fila['fecha'];
            }
        }
        return $f;
    }

    function ListarOrden($nroauditoria) {
        // Objetivo...: Imprimir el Talón de la Autorización
        $query = "SELECT nroauditoria, transaccion, nroautorizacion, codos, idprof, fecha, fepedido, nrodoc, iddiag, nombre, diferida, auditada, auditor, auditado, profcab, expediente, anulada, obsauditor, token FROM cab_auditoria WHERE nroauditoria = '$nroauditoria'";
        $resultado = mysql_query($query);

        while ($fila = mysql_fetch_array($resultado)) {
            // Código equivalente
            $codigoeq = $this->getObsocialEquivalente($fila['codos']);

            // Armamos la llamada rpc
            $this->obrasocial->verificarRPC($fila['codos']);

            $this->obrasocial->getObject($fila['codos']);
            $this->medico->getObject($codigoeq, $fila['idprof']);
            $this->diagnostico->getObject($fila['iddiag']);
            $this->afiliado->getObject($codigoeq, $fila['nrodoc']);
            $this->entidad->getObject();

            // Verificamos si se imprime el monto de lo rechazado y donde coloca la leyenda
            $montodif = 0;
            $ldf = "";
            if ($this->obrasocial->getInc_leyenda() == 'S') {
                $fecha = $fila['fecha'];
                $periodo = substr($fecha, 4, 2) . '/' . substr($fecha, 0, 4);
                $modulo = $this->detalle->getValorModulo($codigoeq, $periodo);
                if ($modulo <= 0) {
                    $modulo = 1;
                }
                $montodif = $this->detalle->getMontoTotalRechazados($fila['nroauditoria']) * $modulo;
                $montodif = number_format($montodif, 2, '.', ',');
                if (strlen($this->obrasocial->getLeyenda()) == 0) {
                    $ldf = $montodif;
                }
            }

            $pper = substr($fila['fecha'], 4, 2) . '/' . substr($fila['fecha'], 0, 4);
            $ccss = $fila['codos'];

            $retiva = "";
            if ($this->afiliado->retiene_iva != null) {
                if ($this->afiliado->getRetiva() == 'S')
                    $retiva = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[ Ret. I.V.A.: <b>S</b> ]';
                if ($this->afiliado->getRetiva() == 'N')
                    $retiva = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[ Ret. I.V.A.: <b>N</b> ]';
            }

            $tipo_plan = '';  // Jerarquico
            if ($this->obrasocial->_reglaNegocio == 3) {
                $tipo_plan = ' Plan: ' . $this->afiliado->getObservacion();
            }
            
            $token = ' ';
            if ($fila['token'] != '') $token = ' Token: <b>' . $fila['token'] . '</b>'; 

            echo '<br><b>' . $this->entidad->getNombre() . '</b><br>';
            echo '<font face= "Arial" size="1px"> Obra Social: <b>' . $this->obrasocial->getNombre() . '</b>' . ' - Trans.: ' . substr($fila['nroauditoria'], 6, 30) . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . '<i>' . $ldf . '</i>' . $token . '<br>'; //substr($fila['nroauditoria'], strlen($fila['nroauditoria'])- 6, 6) . '<br>';
            echo 'Paciente: <b>' . $fila['nrodoc'] . ' - ' . $this->afiliado->getNombre() . '</b>' . $retiva . $tipo_plan . '<br>';
            echo 'Fecha: <b>' . $this->utiles->getFechaDDMMAA($fila['fecha']) . '</b>   Médico: <b>' . $this->medico->getNombre() . '</b>' . '&nbsp;&nbsp;F. Pedido: <b>' . $this->utiles->getFechaDDMMAA($fila['fepedido']) . '</b><br>';
            if (strlen($fila['profcab']) > 0) {
                $this->medico->getObject($codigoeq, $fila['profcab']);
                echo 'Médico de Cabecera: <b>' . $this->medico->getNombre() . '</b><br>';
            }
            echo 'Diagnóstico: <b>' . $fila['iddiag'] . ' - ' . $this->diagnostico->getDescrip() . '</b><br>';

            if (strlen($this->obrasocial->getLeyenda()) > 0) {
                echo "<i>" . $this->obrasocial->getLeyenda() . "   " . $montodif . "</i><br>";
            }

            $autorizadas = '';
            $rechazadas = '';
            $tramo1 = 0;
            $tramo2 = 0;
            $tramo3 = 0;
            $cantbonos1 = 0;
            $cantbonos2 = 0;
            $cantbonos3 = 0;
            $cantdeterminaciones = 0;
            $bonosregla = 0;

            $coseguro = 0;
            $coseguroR10 = 0;
            $total_css = 0;

            // 29/06/2016
            $lista_aut = array();
            $lista_rec = array();
            $_a = 0;
            $_r = 0;

            // Recalculo de Bonos 23/01/2018           
            $this->regla->getObject($codigoeq);

            $_regla = $this->regla->regla;

            $nro = "'" . $fila['nroauditoria'] . "'";
            $deter = $this->detalle->getItems($nro);
            while ($f = mysql_fetch_array($deter)) {

                // Llenamos los arrays - 29/06/2016

                $this->nbu->getObject($f['codigo']);
                if ($f['estado'] == 'A') {
                    $lista_aut[$_a] = $f['codigo'] . ' - ' . $this->nbu->getDescrip();
                    //echo $f['codigo'] . ' - ' . $this->nbu->getDescrip();
                    $_a++;
                }
                if ($f['estado'] == 'R') {
                    $lista_rec[$_r] = $f['codigo'] . ' - ' . $this->nbu->getDescrip();
                    $_r++;
                }

                if ($f['estado'] == 'A') {
                    $cantdeterminaciones++;
                    $autorizadas = $autorizadas . $f['codigo'] . '  ';

                    // Buscamos los tramos
                    $this->nbu->getObject($f['codigo']);
                    // Sumamos los tramos
                    if ($this->nbu->getTramo() == 1) {
                        $tramo1 = $tramo1 + 1;
                    }
                    if ($this->nbu->getTramo() == 2) {
                        $tramo2 = $tramo2 + 1;
                    }
                    if ($this->nbu->getTramo() == 3) {
                        $tramo3 = $tramo3 + 1;
                    }

                    // Nuevo calculo de bonos 23/01/2018                    
                    if ($_regla == 1 && $fila['fecha'] >= '20180301') {
                        $rr1 = $this->regla->getDeterminacionR1($codigoeq, $f['codigo']);

                        while ($ff1 = mysql_fetch_array($rr1)) {
                            $bonosregla = $bonosregla + $ff1['bonos'];
                        }
                    }

                    // Coseguro
                    $coseguro = $coseguro + ($f['coseguro'] - $f['monto']);
                    $coseguroR10 = $coseguroR10 + $f['coseguro'];
                    //----------------------------------------------------------
                } else {
                    $rechazadas = $rechazadas . $f['codigo'] . '  ';
                }
            }

            $obsocial = new cObSocial();
            $obsocial->getObject($codigoeq);

            // Deducimos la Cantidad de Bonos
            if ($tramo1 > 0 and $obsocial->getBonos() == '1') {
                $this->tramos->getObject($codigoeq, 1);
                // cant. determinaciones / det. que abarca
                if ($this->tramos->getTope() > 0) {
                    $cb1 = $tramo1 / $this->tramos->getTope();
                    $cb1 = intval($cb1);
                }

                $cantbonos1 = $cb1 * $this->tramos->getCantbonos();
                if ($this->tramos->getTope() > 0) {
                    if (($tramo1 % $this->tramos->getTope()) > 0) {
                        $cantbonos1 = $cantbonos1 + $this->tramos->getCantbonos();
                    }
                }
            }

            if ($tramo2 > 0 and $obsocial->getBonos() == '1') {
                $this->tramos->getObject($codigoeq, 2);
                // cant. determinaciones / det. que abarca
                $cb2 = $tramo2 / $this->tramos->getTope();
                $cb2 = intval($cb2);

                $cantbonos2 = $cb2 * $this->tramos->getCantbonos();
                if (($tramo2 % $this->tramos->getTope()) > 0) {
                    $cantbonos2 = $cantbonos2 + $this->tramos->getCantbonos();
                }
            }

            if ($tramo3 > 0 and $obsocial->getBonos() == '1') {
                $this->tramos->getObject($codigoeq, 3);
                // cant. determinaciones / det. que abarca
                $cb3 = $tramo3 / $this->tramos->getTope();
                $cb3 = intval($cb3);

                $cantbonos3 = $cb3 * $this->tramos->getCantbonos();
                if (($tramo3 % $this->tramos->getTope()) > 0) {
                    $cantbonos3 = $cantbonos3 + $this->tramos->getCantbonos();
                }
            }

            $totalbonos = $cantbonos1 + $cantbonos2 + $cantbonos3;

            // Agregamos como rechazadas las ordenes a Capita

            $rss = $this->detalle->getItemsCapitas($nro);
            while ($fs = mysql_fetch_array($rss)) {
                $rechazadas = $rechazadas . $fs['codigo'] . '  ';
            }

            if ($obsocial->getOrdenCompleta() != 1) {  // Orden simple 29/6/2016
                echo 'Autorizadas: <b>' . $autorizadas . '</b><br>';

                if (($fila['diferida'] == 'S' and $fila['auditada'] != 'S') || ($obsocial->getPracticasRechazadas() == 'S')) {
                    if (strlen($rechazadas) > 0)
                        echo 'Pendientes: <b>' . $rechazadas . '</b><br>';
                } else {
                    if ($this->entidad->getParametro3() == '1') {
                        echo 'Rechazadas: <b>' . $rechazadas . '</b><br>';
                    }
                }
            }

            if ($obsocial->getOrdenCompleta() == 1) {  // Orden completa 29/6/2016
                echo '<br/>';

                $arrlength = count($lista_aut);
                for ($x = 0; $x < $arrlength; $x++) {
                    echo '<b>[ A ]  ' . $lista_aut[$x] . '</b><br>';
                }

                $arrlength = count($lista_rec);
                for ($x = 0; $x < $arrlength; $x++) {
                    echo '<b>[ R ]  ' . $lista_rec[$x] . '</b><br>';
                }

                echo '<br/>';
            }

            $this->codigos_autorizados = $autorizadas;
            $this->codigos_rechazados = $rechazadas;
            $this->bonos_auditoria = $totalbonos;

            if (strlen($autorizadas) > 0 and $this->obrasocial->getBonos() == '1') {

                if ($_regla == 1 && $fila['fecha'] >= '20180301')   // 23/01/2018
                    $totalbonos = $bonosregla;

                // 22-11-2018 - tome maximo: 20
                //if ($totalbonos > 20)
                    //$totalbonos = 20;

                echo 'Corresponden <b>' . $totalbonos . '</b> Bonos.';

                if ($fila['diferida'] == 'S') {
                    echo ' - Auditó: [ ' . $fila['auditor'] . '  ' . $fila['auditado'] . ' ]';
                }

                echo '<br>';
            }

            // Transacción externa
            $estado = '';
            if ($fila['anulada'] == 'S')
                $estado = ' *** Orden ANULADA ***';

            if ($fila['nroautorizacion'] != '') {
                echo 'Nro. de Autorización: [ <b>' . $fila['nroautorizacion'] . '</b> ]';

                if ($fila['expediente'] != '') {
                    echo '  Expediente/Formulario: [ <b>' . $fila['expediente'] . '</b> ]';
                }
            }
            if ($fila['transaccion'] != '') {
                echo 'Transacción Externa: [ <b>' . $fila['transaccion'] . '</b> ] ' . $estado . ' <br/>';
            }

            // Coseguro por diferencia de aranceles
            // Si está anulada el coseguro es 0
            if ($fila['diferida'] == 'S' and $fila['auditada'] != 'S' and $fila['auditada'] != 'P')
                $coseguro = 0;

            //==================================================================
            // Control anula coseguro
            if ($fila['fecha'] >= '20231101')
                $coseguroR10 = 0;

            // Cambio Coseguro FE SALUD
            $anula_coseguto_fesalud = false;
            if ($fila['fecha'] >= '20240120')
                $anula_coseguto_fesalud = true;

            //==================================================================    

            if ($this->obrasocial->getCoseguro() == 'S') {
                if ($this->obrasocial->_reglaNegocio != 10 and $coseguro > 0 or $anula_coseguto_fesalud) {
                    echo '<br/><font face= "Arial" size="3px">COPAGO por diferencia arancelaria:  $ ' . number_format($coseguro, 2, '.', ',') . '</font><br>';
                    $this->coseguro_auditoria = $coseguro;
                }

                if ($this->obrasocial->_reglaNegocio == 10 and $anula_coseguto_fesalud == false) {
                    echo '<font face= "Arial" size="3px">Coseguro:  $ ' . number_format($coseguroR10, 2, '.', ',') . '</font><br>';
                    $this->coseguro_auditoria = $coseguroR10;
                }
            }

            // Coseguro monto fijo            
            if ($this->obrasocial->getCoseguroMontoFijo() == 'S' or $anula_coseguto_fesalud == false) {

                $montocoss = $this->mfboleta->getMontoFijo($ccss, $pper, $fila['fecha']);

                $mmc = 0;
                if ($cantdeterminaciones > 0) {
                    $mmc = $montocoss;   // monto x boleta
                }

                if ($this->mfboleta->getTipo() == 2) {
                    $mmc = $montocoss * $cantdeterminaciones; // monto x determinaciones
                }

                if ($this->mfboleta->getTipo() == 1) {
                    $mmc = $montocoss * 1; // monto x boleta
                }


                $con = 'COPAGO por diferencia arancelaria';
                if ($this->mfboleta->getConcepto() != '') {
                    $con = $this->mfboleta->getConcepto();
                }

                if ($mmc > 0) {
                    echo '<font face= "Arial" size="3px">' . $con . ': $ ' . number_format($mmc, 2, '.', ',') . '</font><br>';
                    $this->coseguro_auditoria = $mmc;
                }

                // Actualizamos el coseguro en el items de la orden               
                if ($this->obrasocial->_reglaNegocio > 0)
                    if ($this->obrasocial->_reglaNegocio != 10)     // Cobran coseguro por otro lado
                        $this->detalle->ActualizarCoseguro($nroauditoria, $mmc);

                $total_css = $mmc + $coseguroR10;

                // 27/11/2024
                if ($this->obrasocial->_reglaNegocio == 0) {
                    $total_css = $coseguro + $mmc;
                }

                if ($mmc > 0) {
                    echo '<font face= "Arial" size="3px">TOTAL A ABONAR:  $ ' . number_format($total_css, 2, '.', ',') . '</font><br>';
                }
            }

            if (strlen($autorizadas) > 0 and $this->obrasocial->getBonos() != '1') {
                if ($fila['diferida'] == 'S') {
                    echo 'Auditó: [ ' . $fila['auditor'] . '  ' . $fila['auditado'] . ' ]';
                    if ($fila['expediente'] != '')
                        echo '    Expediente: ' . $fila['expediente'];
                }

                echo '<br>';
            }

            echo '<br>';

            $ent = new cEntidad;
            $ent->getObject();
            $tam = $ent->getParametro1();
            $ft = $ent->getParametro2();

            if ($fila['diferida'] == 'S' and $fila['auditada'] != 'S') {
                
            } else {
                if (strlen($ft) == 0) {
                    $ft = 'IDAutomationCode39';
                }

                // 19/02/2020       
                if ($_a == 0) {
                    $__rsp = $this->obrasocial->getRespuestaRPC($fila['codos'], $fila['nrodoc'], $fila['nroautorizacion'], 2);
                    if ($__rsp != '')
                        echo '<br/>' . $__rsp . '<br/><br/>';
                }

                echo '<font face= "' . $ft . '" size="' . $tam . 'px' . '">' . "!" . substr($fila['nroauditoria'], 6, 23) . "!" . '</font>';
            }

            if (strlen($fila['profcab']) > 0 and $this->obrasocial->getDerivacion() == 'S') {
                if ($fila['profcab'] != $fila['idprof']) {
                    echo '<p align="center">';
                    echo '<b><font size="3" color="#330099">DEBE ADJUNTAR DERIVACIÓN DEL MÉDICO DE CABECERA</font></b>';
                    echo '</p>';
                }
            }

            if ($fila['diferida'] == 'S' and $fila['auditada'] != 'S' and $fila['auditada'] != 'P') {
                //if ($fila['diferida'] == 'S' or $fila['auditada'] != 'S' and $fila['auditada'] != 'P') {
                echo '<br>';
                echo '<p align="center"><font size="5" color="#6666FF">***  Orden para Autorización Diferida  ***</font></p>';
            }
            if ($fila['diferida'] == 'S' and $fila['auditada'] != 'S' and $fila['auditada'] == 'P') {
                echo '<br>';
                echo '<p align="center"><font size="5" color="#6666FF">***  Orden para Autorización Diferida  ***</font><br>';
                echo '<p align="center"><font size="4" color="#009966">Sujeta a Autorización. Controle la Observación del Auditor.</font></p>';
                if ($fila['obsauditor'] != '') {
                    echo '<p align="center"><font size="3" color="#CD5C5C">' . $fila['obsauditor'] . '</font></p>';
                }
            }
            
            $this->obrasocial->getObjectLeyenda($fila['codos']);
            if ($this->obrasocial->getLeyendaOS() != '') {
                echo '</br>';
                echo '<b><font size="3" color="#330099">' . $this->obrasocial->getLeyendaOS() . '</font></b>';
            }                       

            echo '</font><br>';

            //ACA SALUD
            if ($this->obrasocial->_reglaNegocio == 1) {
                if ($fila['anulada'] == 'S') {
                    echo $this->wsres->getWsErrorRegla1($nroauditoria);
                }
            }

            //===== MENSAJES o NOTIFICACIONES

            /*
            $osn = json_decode($this->osnot->getObjectJSActiva($fila['codos']));
            if ($osn != null) {
                echo '<br/>';
                if ($osn[0]->m1 != '')
                    echo $osn[0]->m1 . '<br/>';
                if ($osn[0]->m2 != '')
                    echo $osn[0]->m2 . '<br/>';
                if ($osn[0]->m3 != '')
                    echo $osn[0]->m3 . '<br/>';
                if ($osn[0]->m4 != '')
                    echo $osn[0]->m4 . '<br/>';
                if ($osn[0]->m5 != '')
                    echo $osn[0]->m5 . '<br/>';
                if ($osn[0]->m6 != '')
                    echo $osn[0]->m6 . '<br/>';
                if ($osn[0]->m7 != '')
                    echo $osn[0]->m7 . '<br/>';
            }             
             */

            //==================================================================
        }
    }

    function ListarObsAuditor($nroauditoria) {
        // Objetivo...: Imprimir el Talón de la Autorización
        $query = "SELECT nroauditoria, obsauditor FROM cab_auditoria WHERE nroauditoria = '$nroauditoria'";
        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            echo '<b>Obs. Auditor: </b><font color="#FF0000">' . $fila['obsauditor'] . '</font>';
        }
        echo '<hr>';
    }

    function RecalcularCoseguro($nroauditoria, $codos, $fecha) {

        $this->obrasocial->getObject($codos);
        $this->getObject($nroauditoria);
        $pper = substr($fecha, 4, 2) . '/' . substr($fecha, 0, 4);

        // Coseguro Normal
        $this->detalle->RecaulcularMontosOrden($nroauditoria, $codos);
        return;

        /*
          // Coseguro monto fijo
          if ($this->obrasocial->getCoseguroMontoFijo() == 'S') {
          $montocoss = $this->mfboleta->getMontoFijo($codos, $pper);

          $mmc = $montocoss;   // monto x boleta

          if ($this->mfboleta->getTipo() == 2) {
          $mmc = $montocoss * $cantdeterminaciones; // monto x determinaciones
          }

          // Actualizamos el coseguro en el items de la orden
          $this->detalle->ActualizarCoseguro($nroauditoria, $mmc);
          }
         */
    }

    function Exportar($desde, $hasta) {
        $archivo_cab = '../actualizar/cab_auditoria.txt';
        $archivo_det = '../actualizar/det_auditoria.txt';
        $archivo_pac = '../actualizar/pac_auditoria.txt';
        $archivo_pen = '../actualizar/pendientes.txt';
        $archivo_dx = '../actualizar/diagnosticos.txt';
        $archivo_med = '../actualizar/medicos.txt';
        $archivo_mca = '../actualizar/medicos_cab.txt';
        $archivo_aut = '../actualizar/autorizaciones.txt';
        $cab = fopen($archivo_cab, 'w');
        $det = fopen($archivo_det, 'w');
        $pac = fopen($archivo_pac, 'w');
        $pen = fopen($archivo_pen, 'w');
        $dx = fopen($archivo_dx, 'w');
        $med = fopen($archivo_med, 'w');
        $mca = fopen($archivo_mca, 'w');
        $aut = fopen($archivo_aut, 'w');

        $query = "SELECT nroauditoria, efector, fecha, codos, idzona, nrodoc, idprof,
                    federivacion, nroderivacion, fepedido, iddiag, profcab, token, numerodoc,
                    diferida, observacion, anulada, obsauditor, auditor, auditado, expediente, nroautorizacion
                    FROM cab_auditoria
                    WHERE fecha >= '$desde' and fecha <= '$hasta' order by codos, fecha";

        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $n = $fila['nrodoc'];
            $ndoc = $n;
            if (substr($n, 0, 7) == '8000060' || substr($n, 0, 7) == '8000061' || substr($n, 0, 7) == '8000062' || substr($n, 0, 7) == '8000063' || substr($n, 0, 7) == '8000064' || substr($n, 0, 7) == '8000065' || substr($n, 0, 7) == '8000066')
                $ndoc = substr($n, 7, 11);
            
            $o = '';
            $a = '';
            
            $linea = $this->utiles->StringLongitudFija($fila['nroauditoria'], 30) .
                    $this->utiles->StringLongitudFija($fila['efector'], 6) .
                    $this->utiles->StringLongitudFija($fila['fecha'], 8) .
                    $this->utiles->StringLongitudFija($fila['codos'], 6) .
                    $this->utiles->StringLongitudFija($fila['idzona'], 3) .
                    //$this->utiles->StringLongitudFija($fila['nrodoc'], 16) .
                    $this->utiles->StringLongitudFija($ndoc, 16) .
                    //$this->utiles->StringLongitudFija($fila['idprof'], 5) .
                    $this->utiles->StringLongitudFija(trim($fila['idprof']), 12) .
                    $this->utiles->StringLongitudFija($fila['federivacion'], 9) .
                    $this->utiles->StringLongitudFija($fila['nroderivacion'], 9) .
                    $this->utiles->StringLongitudFija($fila['fepedido'], 10) .
                    $this->utiles->StringLongitudFija($fila['iddiag'], 9) .
                    $this->utiles->StringLongitudFija($fila['diferida'], 1) .
                    $this->utiles->StringLongitudFija($fila['anulada'], 2) .
                    //$this->utiles->StringLongitudFija(trim($fila['observacion']), 150) . "*** End of Efector ***" .
                    $this->utiles->StringLongitudFija(trim($o), 150) . "*** End of Efector ***" .
                    //$this->utiles->StringLongitudFija(trim($fila['obsauditor']), 150) . "*** End of Auditor ***" .
                    $this->utiles->StringLongitudFija(trim($a), 150) . "*** End of Efector ***" .
                    $this->utiles->StringLongitudFija($fila['auditor'], 15) .
                    $this->utiles->StringLongitudFija($fila['auditado'], 35) .
                    $this->utiles->StringLongitudFija($fila['profcab'], 12) .
                    $this->utiles->StringLongitudFija($fila['expediente'], 20) .
                    $this->utiles->StringLongitudFija($fila['nroautorizacion'], 20) .
                    
                    $this->utiles->StringLongitudFija($fila['token'], 20) .
                    $this->utiles->StringLongitudFija($fila['numerodoc'], 20) .
                    " \r\n";

            fwrite($cab, $linea);
            
            $lineaaut = $this->utiles->StringLongitudFija($fila['nroauditoria'], 30) . $this->utiles->StringLongitudFija($fila['nroautorizacion'], 20) .
                    " \r\n";
            fwrite($aut, $lineaaut);

            $querydet = "SELECT nroauditoria, items, codigo, monto, estado, coseguro FROM det_auditoria WHERE nroauditoria = " . "'" . $fila['nroauditoria'] . "'";
            $result = mysql_query($querydet);
            while ($filadet = mysql_fetch_array($result)) {
                $lineadet = $this->utiles->StringLongitudFija($filadet['nroauditoria'], 30) .
                        $this->utiles->StringLongitudFija($filadet['items'], 4) .
                        $this->utiles->StringLongitudFija($filadet['codigo'], 7) .
                        $this->utiles->StringLongitudFija($filadet['estado'], 2) .
                        $this->utiles->StringLongitudFija($filadet['monto'], 25) .
                        $this->utiles->StringLongitudFija($filadet['coseguro'], 25) .
                        " \r\n";

                fwrite($det, $lineadet);
            }

            $codigoos = $fila['codos'];
            $this->equivalencia->getObject($fila['codos']);
            if ($this->equivalencia->getCodigo2() > '')
                $codigoos = $this->equivalencia->getCodigo2();

            $this->afiliado->Exportar($pac, $codigoos, $fila['nrodoc']);
        }

        $query = "SELECT nroauditoria FROM cab_auditoria WHERE auditada = 'P'";
        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $linea = $this->utiles->StringLongitudFija($fila['nroauditoria'], 30) . " \r\n";
            fwrite($pen, $linea);
        }

        // Exportamos los Diagnósticos
        $query = "SELECT DISTINCT iddiag FROM cab_auditoria WHERE fecha >= '$desde' and fecha <= '$hasta'";
        $result = mysql_query($query);
        while ($fila = mysql_fetch_array($result)) {
            $this->diagnostico->Exportar($dx, $fila['iddiag']);
        }

        // Exportamos los Medicos
        $query = "SELECT DISTINCT codos, idprof FROM cab_auditoria WHERE fecha >= '$desde' and fecha <= '$hasta'";
        $result = mysql_query($query);
        while ($fila = mysql_fetch_array($result)) {
            $this->medico->Exportar($med, $fila['codos'], $fila['idprof']);
        }

        // Exportamos los Medicos de Cabecera
        $query = "SELECT DISTINCT codos, profcab FROM cab_auditoria WHERE fecha >= '$desde' and fecha <= '$hasta' AND profcab > '0'";
        $result = mysql_query($query);
        while ($fila = mysql_fetch_array($result)) {
            $this->medico_cab->Exportar($mca, $fila['codos'], $fila['profcab']);
        }

        fclose($cab);
        fclose($det);
        fclose($pac);
        fclose($pen);
        fclose($dx);
        fclose($med);
        fclose($mca);
        fclose($aut);
    }

    function Importar() {
        $archivo_cab = '../actualizar/cab_auditoria_mod.txt';
        $archivo_det = '../actualizar/det_auditoria_mod.txt';
        $cab = fopen($archivo_cab, 'r');

        while (!feof($cab)) {
            $linea1 = fgets($cab);

            $idonline = substr($linea1, 0, 30);
            $idonline = trim($idonline);
            $auditada = substr($linea1, 33, 1);
            $obsaudit = substr($linea1, 37, strlen($linea1) - 37);

            if (strlen($idonline) > 0) {

                $query = "UPDATE cab_auditoria SET auditada = " . "'" . $auditada . "'" . ", obsauditor = " . "'" . $obsaudit . "'" . " WHERE nroauditoria = " . "'" . $idonline . "'";
                $result = mysql_query($query);

                $querydel = 'delete from det_auditoria where nroauditoria = ' . "'" . $idonline . "'";

                $resultd = mysql_query($querydel);


                $det = fopen($archivo_det, 'r');
                while (!feof($det)) {
                    $linea2 = fgets($det);

                    if (strlen(trim($linea2)) > 0) {

                        $nroauditoria = trim(substr($linea2, 0, 30));

                        if ($nroauditoria == trim($idonline)) {
                            $items = substr($linea2, 33, 3);
                            $codigo = substr($linea2, 38, 6);
                            $monto = substr($linea2, 46, 15);
                            $monto = str_replace(",", ".", $monto);
                            $monto = number_format($monto, 2, '.', '');
                            $montodif = substr($linea2, 62, 15);
                            $montodif = str_replace(",", ".", $montodif);
                            $montodif = number_format($montodif, 2, '.', '');
                            $estado = substr($linea2, 78, 1);
                            $efector = substr($linea2, 80, 6);
                            $fecha = substr($linea2, 88, 8);
                            $codos = substr($linea2, 98, 6);
                            $nrodoc = trim(substr($linea2, 106, 15));

                            $queryins = "insert into det_auditoria (nroauditoria, items, codigo, monto, montodif, estado, efector, fecha, codos, nrodoc) values (" .
                                    "'" . $nroauditoria . "'" . ',' .
                                    "'" . $items . "'" . ',' .
                                    "'" . $codigo . "'" . ',' .
                                    $monto . ',' .
                                    $montodif . "," .
                                    "'" . $estado . "'" . ',' .
                                    "'" . $efector . "'" . ',' .
                                    "'" . $fecha . "'" . ',' .
                                    "'" . $codos . "'" . ',' .
                                    "'" . $nrodoc . "'" . ")";
                            $resins = mysql_query($queryins);
                        }
                    }
                }

                fclose($det);
            }
        }

        fclose($cab);
    }

    function AnularOrden($nroauditoria) {
        $query = "UPDATE cab_auditoria SET anulada = 'S' WHERE nroauditoria = " . '"' . $nroauditoria . '"';
        $result = mysql_query($query);
        $query = "UPDATE det_auditoria SET anulada = 'S' WHERE nroauditoria = " . '"' . $nroauditoria . '"';
        $result = mysql_query($query);
        $this->sql = $query;
    }

    function ActualizarObservacionAuditor($nroauditoria, $observacion) {
        $query = "UPDATE cab_auditoria SET auditada = 'S', obsauditor = '$observacion' WHERE nroauditoria = '$nroauditoria'";
        $result = mysql_query($query);
    }

    function MarcarComoAuditada($nroauditoria, $auditor) {
        $auditado = $this->utiles->getFechaHoraActual();
        $query = "UPDATE cab_auditoria SET auditada = 'S', anulada = 'N', auditor = '$auditor', auditado = '$auditado' WHERE nroauditoria = '$nroauditoria'";
        $result = mysql_query($query);
    }

    function MarcarComoPendiente($nroauditoria, $auditor, $observacion) {
        $auditado = $this->utiles->getFechaHoraActual();
        if ($observacion != 'nullzztop95') {
            $query = "UPDATE cab_auditoria SET auditada = 'P', auditor = '$auditor', auditado = '$auditado', obsauditor = '$observacion' WHERE nroauditoria = '$nroauditoria'";
        } else {
            $query = "UPDATE cab_auditoria SET auditada = 'P', auditor = '$auditor', auditado = '$auditado' WHERE nroauditoria = '$nroauditoria'";
        }
        $result = mysql_query($query);
    }

    function VerificarOrdenPaciente($nrodoc, $fecha, $iddiag) {
        $controlOK = true;
        $f = $this->utiles->getFechaAAAAMMDD($fecha);
        if (strlen($f) < 8) {
            $f = '20' . $f;
        }
        $query = "SELECT nroauditoria, anulada FROM cab_auditoria WHERE nrodoc = '$nrodoc' AND fecha = '$f' AND iddiag = '$iddiag'";

        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            if ($fila['anulada'] != 'S') {
                $controlOK = false;
            }
        }
        return $controlOK;
    }

    function getObraSocial($codos) {
        $this->obrasocial->getObject($codos);
        return $this->obrasocial->getNombre();
    }

    function VerificarPaciente($codos, $nrodoc) {
        $query = "SELECT nrodoc FROM cab_auditoria WHERE codos = '$codos' and nrodoc = '$nrodoc'";

        $result = false;
        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $result = true;
            break;
        }
        return $result;
    }

    function VerificarEfector($efector) {
        $query = "SELECT efector FROM cab_auditoria WHERE efector = '$efector'";

        $result = false;
        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $result = true;
            break;
        }
        return $result;
    }

    function VerificarObraSocial($codos) {
        $query = "SELECT codos FROM cab_auditoria WHERE codos = '$codos'";

        $result = false;
        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $result = true;
            break;
        }
        return $result;
    }

    function VerificarMedico($idprof) {
        $query = "SELECT idprof FROM cab_auditoria WHERE idprof = '$idprof'";

        $result = false;
        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $result = true;
            break;
        }
        return $result;
    }

    function VerificarDiagnostico($iddiag) {
        $query = "SELECT idprof FROM cab_auditoria WHERE iddiag = '$iddiag'";

        $result = false;
        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $result = true;
            break;
        }
        return $result;
    }

    function VerificarMedicoCabecera($cod) {
        $query = "SELECT profcab FROM cab_auditoria WHERE profcab = '$cod'";

        $result = false;
        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $result = true;
            break;
        }
        return $result;
    }

    function getAuditoriaMasReciente($codos, $nrodoc) {
        $ultima_fecha = '';
        $query = "SELECT MAX(fecha) AS ultimafecha FROM cab_auditoria WHERE codos = '$codos' and nrodoc = '$nrodoc' and anulada <> 'S'";
        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $ultima_fecha = $fila['ultimafecha'];
        }
        return $ultima_fecha;
    }

    function getObsocialEquivalente($codos) {
        $codigoret = $codos;
        // Verificamos si la obra social no utiliza el padr�n de otra
        $eq = new cEquivalenciaPadrones();
        $found = $eq->getObject($codos);
        if ($found) {
            // Si existe un código equivalente, modificamos la Obra Social
            $codigoret = $eq->getCodigo2();
        }
        return $codigoret;
    }

    function getMedicosConOrdenes($xcodos, $xdesde, $xhasta) {
        $desde = $this->utiles->getFechaAAAAMMDD($xdesde);
        $hasta = $this->utiles->getFechaAAAAMMDD($xhasta);
        $query = "SELECT DISTINCT(idprof) FROM cab_auditoria WHERE codos = '$xcodos' AND fecha >= '$desde' AND fecha <= '$hasta'";
        $this->res = mysql_query($query);
        return $this->res;
    }

    function getCantidadOrdenesMedico($xcodos, $xdesde, $xhasta, $xidprof, $xcodigo) {
        $cantidad = 0;
        $desde = $this->utiles->getFechaAAAAMMDD($xdesde);
        $hasta = $this->utiles->getFechaAAAAMMDD($xhasta);
        //$query = "SELECT COUNT(cab_auditoria.nroauditoria) AS cant FROM cab_auditoria, det_auditoria WHERE cab_auditoria.idprof = '$xidprof' AND cab_auditoria.nroauditoria = det_auditoria.nroauditoria AND det_auditoria.codigo = '$xcodigo' AND " .
        //      "cab_auditoria.fecha >= '$desde' AND cab_auditoria.fecha <= '$hasta'";
        $query = "SELECT COUNT(cab_auditoria.nroauditoria) AS cant FROM cab_auditoria WHERE cab_auditoria.idprof = '$xidprof' AND cab_auditoria.fecha >= '$desde' AND cab_auditoria.fecha <= '$hasta'";
        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $cantidad = $fila['cant'];
        }
        return $cantidad;
    }

    function getCantidadDeterminacionesMedico($xcodos, $xdesde, $xhasta, $xidprof, $xcodigo) {
        $cantidad = 0;
        $desde = $this->utiles->getFechaAAAAMMDD($xdesde);
        $hasta = $this->utiles->getFechaAAAAMMDD($xhasta);
        $query = "SELECT COUNT(cab_auditoria.nroauditoria) AS cant FROM cab_auditoria, det_auditoria WHERE cab_auditoria.idprof = '$xidprof' AND cab_auditoria.nroauditoria = det_auditoria.nroauditoria AND det_auditoria.codigo <> '$xcodigo' AND " .
                "cab_auditoria.fecha >= '$desde' AND cab_auditoria.fecha <= '$hasta'";
        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $cantidad = $fila['cant'];
        }

        // tercer columna
        $query = "SELECT COUNT(cab_auditoria.nroauditoria) AS cant FROM cab_auditoria, det_capitas WHERE cab_auditoria.idprof = '$xidprof' AND cab_auditoria.nroauditoria = det_capitas.nroauditoria AND det_capitas.codigo <> '$xcodigo' AND " .
                "cab_auditoria.fecha >= '$desde' AND cab_auditoria.fecha <= '$hasta'";
        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $cantidad = $cantidad + $fila['cant'];
        }

        return $cantidad;
    }

    function MarcarOrdenesParaDepurar($xcodos, $xdesde) {
        $desde = $this->utiles->getFechaAAAAMMDD($xdesde);
        $query = "UPDATE cab_auditoria SET depura = 'S' WHERE codos = '$xcodos' AND fecha <= '$desde'";
        $resultado = mysql_query($query);

        $this->detalle->MarcarOrdenesParaDepurar($xcodos, $xdesde);
    }

    function DepurarOrdenesMarcadas($xcodos, $xdesde) {
        $desde = $this->utiles->getFechaAAAAMMDD($xdesde);
        $query = "SELECT nroauditoria, efector, fecha, codos, idzona, nrodoc, idprof, diagnostico, 
 	 		 federivacion, nroderivacion, observacion, idfact, fechafac, laboratorio, codosfact, fepedido,
			 profcab, iddiag, nombre, idperfil, diferida, auditada, obsauditor, anulada, auditado, coseguro, transaccion_anulada,
                         expediente, nroautorizacion FROM cab_auditoria WHERE depura = 'S' AND codos = '$xcodos' AND fecha <= '$desde'";
        $resultado = mysql_query($query);

        while ($fila = mysql_fetch_array($resultado)) {
            $nroauditoria = $fila['nroauditoria'];
            $efector = $fila['efector'];
            $fecha = $fila['fecha'];
            $codos = $fila['codos'];
            $idzona = $fila['idzona'];
            $nrodoc = $fila['nrodoc'];
            $idprof = $fila['idprof'];
            $diagnostico = $fila['diagnostico'];
            $federivacion = $fila['federivacion'];
            $nroderivacion = $fila['nroderivacion'];
            $observacion = $fila['observacion'];
            $idfact = $fila['idfact'];
            $fechafac = $fila['fechafac'];
            $laboratorio = $fila['laboratorio'];
            $codosfact = $fila['codosfact'];
            $fepedido = $fila['fepedido'];
            $profcab = $fila['profcab'];
            $iddiag = $fila['iddiag'];
            $nombre = $fila['nombre'];
            $idperfil = $fila['idperfil'];
            $diferida = $fila['diferida'];
            $auditada = $fila['auditada'];
            $obsauditor = $fila['obsauditor'];
            $anulada = $fila['anulada'];
            $auditor = $fila['auditor'];
            $auditado = $fila['auditado'];
            $coseguro = $fila['coseguro'];
            $transaccion_anulada = $fila['transaccion_anulada'];
            $expediente = $fila['expediente'];
            $nroautorizacion = $fila['nroautorizacion'];

            $strsql = "INSERT IGNORE INTO cab_auditoria_hist (nroauditoria, efector, fecha, codos, idzona, nrodoc, idprof, diagnostico, 
			federivacion, nroderivacion, observacion, idfact, fechafac, laboratorio, codosfact, fepedido,
			profcab, iddiag, nombre, idperfil, diferida, auditada, obsauditor, anulada, auditado, coseguro, transaccion_anulada,
                        expediente, nroautorizacion) VALUES (
			'$nroauditoria', '$efector', '$fecha', '$codos', '$idzona', '$nrodoc', '$idprof', '$diagnostico', 
			'$federivacion', '$nroderivacion', '$observacion', '$idfact', '$fechafac', '$laboratorio', '$codosfact', '$fepedido',
			'$profacab', '$iddiag', '$nombre', '$idperfil', '$diferida', '$auditada', '$obsauditor', '$anulada', '$auditado', '$coseguro', '$transaccion_anulada', '$expediente', '$nroautorizacion' )";
            $resins = mysql_query($strsql);
        }

        $this->detalle->DepurarOrdenesMarcadas($xcodos, $xdesde);
    }

    function EliminarOrdenesMarcadasParaDepurar($xcodos, $xdesde) {
        $desde = $this->utiles->getFechaAAAAMMDD($xdesde);
        $query = "DELETE FROM cab_auditoria WHERE depura = 'S' AND codos = '$xcodos' AND fecha <= '$desde'";
        $resultado = mysql_query($query);

        $this->detalle->EliminarOrdenesMarcadasParaDepurar($xcodos, $xdesde);
    }

    function getItemsCapitas($nroauditoria) {
        return $this->detalle->getItemsCapitas($nroauditoria);
    }

    function cantidadPracticasPMO($codos, $codigo, $nrodoc, $tiempo) {
        $fecha = $this->utiles->getFechaAAAAMMDD($this->utiles->getFecha($tiempo));
        $query = "SELECT COUNT(nroauditoria) AS cant FROM det_auditoria WHERE nrodoc = '$nrodoc' and codos = '$codos' AND codigo = '$codigo' and fecha >= '$fecha'";
        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $cantidad = $fila['cant'];
        }
        return $cantidad;
    }

    function ExportarFacturacionProfesional($idprof, $desde, $hasta) {
        $archivo_cab = '../actualizar/cab_auditoria_' . $idprof . '.txt';
        $archivo_det = '../actualizar/det_auditoria_' . $idprof . '.txt';
        $archivo_pac = '../actualizar/pac_auditoria_' . $idprof . '.txt';
        $cab = fopen($archivo_cab, 'w');
        $det = fopen($archivo_det, 'w');
        $pac = fopen($archivo_pac, 'w');

        $query = "SELECT cab_auditoria.nroauditoria, cab_auditoria.efector, cab_auditoria.fecha, cab_auditoria.codos, cab_auditoria.idzona, cab_auditoria.nrodoc, cab_auditoria.idprof, cab_auditoria.federivacion, cab_auditoria.nroderivacion, cab_auditoria.fepedido, cab_auditoria.iddiag, cab_auditoria.profcab, cab_auditoria.diferida, cab_auditoria.observacion, cab_auditoria.anulada, cab_auditoria.obsauditor, cab_auditoria.auditor, cab_auditoria.auditado, obsocial.nombre,
                    cab_auditoria.expediente, cab_auditoria.nroautorizacion 
                    FROM cab_auditoria, obsocial
                    WHERE cab_auditoria.codos = obsocial.codos
                    AND cab_auditoria.efector = '$idprof'
                    AND cab_auditoria.fecha >= '$desde'
                    AND cab_auditoria.fecha <= '$hasta'                    
                    ORDER BY codos, fecha";

        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $linea = $this->utiles->StringLongitudFija($fila['nroauditoria'], 30) .
                    $this->utiles->StringLongitudFija($fila['efector'], 6) .
                    $this->utiles->StringLongitudFija($fila['fecha'], 8) .
                    $this->utiles->StringLongitudFija($fila['codos'], 6) .
                    $this->utiles->StringLongitudFija($fila['idzona'], 3) .
                    $this->utiles->StringLongitudFija($fila['nrodoc'], 16) .
                    //$this->utiles->StringLongitudFija($fila['idprof'], 5) .
                    $this->utiles->StringLongitudFija(trim($fila['idprof']), 25) .
                    $this->utiles->StringLongitudFija($fila['federivacion'], 9) .
                    $this->utiles->StringLongitudFija($fila['nroderivacion'], 9) .
                    $this->utiles->StringLongitudFija($fila['fepedido'], 10) .
                    $this->utiles->StringLongitudFija($fila['iddiag'], 9) .
                    $this->utiles->StringLongitudFija($fila['diferida'], 1) .
                    $this->utiles->StringLongitudFija($fila['anulada'], 2) .
                    $this->utiles->StringLongitudFija($fila['observacion'], 150) . "*** End of Efector ***" .
                    $this->utiles->StringLongitudFija($fila['obsauditor'], 150) . "*** End of Auditor ***" .
                    $this->utiles->StringLongitudFija($fila['auditor'], 15) .
                    $this->utiles->StringLongitudFija($fila['auditado'], 35) .
                    $this->utiles->StringLongitudFija($fila['profcab'], 6) .
                    $this->utiles->StringLongitudFija($fila['expediente'], 30) .
                    $this->utiles->StringLongitudFija($fila['nroautorizacion'], 30) .
                    " \r\n";

            fwrite($cab, $linea);

            $querydet = "SELECT nroauditoria, items, codigo, monto, estado, coseguro FROM det_auditoria WHERE nroauditoria = " . "'" . $fila['nroauditoria'] . "'";
            $result = mysql_query($querydet);
            while ($filadet = mysql_fetch_array($result)) {
                $lineadet = $this->utiles->StringLongitudFija($filadet['nroauditoria'], 30) .
                        $this->utiles->StringLongitudFija($filadet['items'], 4) .
                        $this->utiles->StringLongitudFija($filadet['codigo'], 7) .
                        $this->utiles->StringLongitudFija($filadet['estado'], 2) .
                        $this->utiles->StringLongitudFija($filadet['monto'], 25) .
                        $this->utiles->StringLongitudFija($filadet['coseguro'], 25) .
                        " \r\n";

                fwrite($det, $lineadet);
            }

            $this->afiliado->Exportar($pac, $fila['codos'], $fila['nrodoc']);
        }

        fclose($cab);
        fclose($det);
        fclose($pac);
    }

    function estadisticasSM1($fecha_desde, $fecha_hasta, $codos) {

        $result = mysql_query('delete from export_estadisticas1');

        $query = "SELECT nroauditoria, codos, fecha, idprof, nrodoc FROM cab_auditoria WHERE fecha >= '$fecha_desde'
                    AND fecha <= '$fecha_hasta' AND codos = '$codos' ORDER BY fecha";

        $cant = 0;

        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {

            $campo = array();
            $valor = array();

            $nroauditoria = $fila['nroauditoria'];
            $i = 0;
            $campo[$i] = 'nroauditoria';
            $valor[$i] = $fila['nroauditoria'];
            $i++;
            $campo[$i] = 'codos';
            $valor[$i] = $fila['codos'];
            $i++;
            $campo[$i] = 'nrodoc';
            $valor[$i] = $fila['nrodoc'];
            $i++;
            $campo[$i] = 'idprof';
            $valor[$i] = $fila['idprof'];
            $i++;
            $campo[$i] = 'fecha';
            $valor[$i] = $fila['fecha'];

            $cols = 0;
            $j = 0;
            $q = "select codigo from det_auditoria where nroauditoria = '$nroauditoria' order by items";
            $r = mysql_query($q);
            while ($f = mysql_fetch_array($r)) {
                $j++;
                $i++;
                $campo[$i] = 'cod' . $j;
                $valor[$i] = $f['codigo'];
                $cols++;
            }

            $valores = '';
            $strsql = 'insert into export_estadisticas1 (';
            for ($i = 0; $i < count($campo); $i++) {
                $strsql = $strsql . $campo[$i] . ', ';
                $valores = $valores . "'" . $valor[$i] . "', ";
            }

            $strsql = $strsql . 'maxcol, ';
            $valores = $valores . $cols . ", ";

            if ($j > 0) {  // eliminamos residuos
                $strsql = substr($strsql, 0, strlen($strsql) - 2) . ') values (';
                $strval = substr($valores, 0, strlen($valores) - 2) . ')';

                $strquery = $strsql . $strval;

                // insert
                $result = mysql_query($strquery);
                if (!$result) {
                    echo 'Se Produjo un Error al Grabar la Orden ' . $nroauditoria;
                    return false;
                } else
                    $cant++;
            }
        }

        echo $cant . ' ordenes procesadas.';
        echo '<br/><br/>';

        return $cant;
    }

    function getEstadisticas() {
        $query = "SELECT * FROM export_estadisticas1";
        $this->res = mysql_query($query);
        return $this->res;
    }

    function getMaxCodigos() {
        $mc = 0;
        $query = "SELECT MAX(maxcol) AS m FROM export_estadisticas1";
        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $mc = $fila['m'];
        }
        return $mc;
    }

    function actualizarDeterminacion($nroauditoria, $codigo, $estado) {
        $query = "UPDATE det_auditoria SET estado = " . "'" . $estado . "'" . " WHERE nroauditoria = " . '"' . $nroauditoria . '"' . " and codigo = " . '"' . $codigo . '"';
        $result = mysql_query($query);
    }

    function actualizarDeterminacionXCantidad($nroauditoria, $codigo, $estado, $cantidad) {
        $query = "UPDATE det_auditoria SET estado = 'R' WHERE nroauditoria = " . '"' . $nroauditoria . '"' . " and codigo = " . '"' . $codigo . '"';
        $result = mysql_query($query);

        $query = "SELECT codigo, items FROM det_auditoria WHERE nroauditoria = " . '"' . $nroauditoria . '"' . " and codigo = " . '"' . $codigo . '"' . " ORDER BY items LIMIT 0, $cantidad";
        $result = mysql_query($query);
        while ($fila = mysql_fetch_array($result)) {
            $query = "UPDATE det_auditoria SET estado = " . '"' . 'A' . '"' . " WHERE nroauditoria = " . '"' . $nroauditoria . '"' . " and codigo = " . '"' . $codigo . '"' . " and items = " . '"' . $fila[items] . '"';
            //echo $query . '<br/>';
            $rst = mysql_query($query);
        }
    }

    function actualizarDeterminacionPorItem($nroauditoria, $item, $estado, $transaccion) {
        $query = "UPDATE det_auditoria SET estado = " . "'" . $estado . "', transaccion = " . "'" . $transaccion . "'" . " WHERE nroauditoria = " . '"' . $nroauditoria . '"' . " and items = " . '"' . $item . '"';
        $result = mysql_query($query);
    }

    function actualizarDeterminacionPorCodigo($nroauditoria, $codigo, $estado, $transaccion) {
        $query = "UPDATE det_auditoria SET estado = " . "'" . $estado . "', transaccion = " . "'" . $transaccion . "'" . " WHERE nroauditoria = " . '"' . $nroauditoria . '"' . " and codigo = " . '"' . $codigo . '"';
        $result = mysql_query($query);
    }

    function actualizarDeterminacionPorItemAnulada($nroauditoria, $item, $transaccion) {
        $estado = 'R';
        $query = "UPDATE det_auditoria SET estado = " . "'" . $estado . "', observacion = " . "'" . $transaccion . "'" . " WHERE nroauditoria = " . '"' . $nroauditoria . '"' . " and items = " . '"' . $item . '"';
        $result = mysql_query($query);
    }

    function actualizarDeterminacionObservaciones($nroauditoria, $codigo, $estado, $observacion) {
        $query = "UPDATE det_auditoria SET estado = " . "'" . $estado . "', observacion = " . "'" . $observacion . "'" . " WHERE nroauditoria = " . '"' . $nroauditoria . '"' . " and codigo = " . '"' . $codigo . '"';
        $result = mysql_query($query);

        $query = "INSERT INTO det_auditoria_observaciones VALUES ('$nroauditoria', '$codigo', '$observacion')";
        $result = mysql_query($query);
    }

    function getObservacionDeterminacion($nroauditoria, $codigo) {
        $query = "select observaciones from det_auditoria_observaciones where nroauditoria = '$nroauditoria' and codigo = '$codigo'";
        // echo $query;
        $mc = '';
        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $mc = $fila['observaciones'];
        }
        return $mc;
    }

    function actualizarTransaccion($nroauditoria, $transaccion, $diferida) {
        $query = "UPDATE cab_auditoria SET transaccion = " . "'" . $transaccion . "'" . ", diferida = " . "'" . $diferida . "'" . " WHERE nroauditoria = " . '"' . $nroauditoria . '"';
        $result = mysql_query($query);
    }
    
     function actualizarTransaccionToken($nroauditoria, $transaccion, $diferida, $token) {
        $query = "UPDATE cab_auditoria SET transaccion = '$transaccion' , diferida = '$diferida', token = '$token' WHERE nroauditoria = '$nroauditoria'";
        $result = mysql_query($query);
    }

    function borrarTransaccion($nroauditoria) {
        $query = "DELETE cab_auditoria WHERE nroauditoria = " . '"' . $nroauditoria . '"';
        $result = mysql_query($query);
        $query = "DELETE det_auditoria WHERE nroauditoria = " . '"' . $nroauditoria . '"';
        $result = mysql_query($query);
    }

    function actualizarTransaccionAutorizada($nroauditoria, $transaccion, $diferida, $autorizacion) {
        $query = "UPDATE cab_auditoria SET transaccion = " . "'" . $transaccion . "'" . ", diferida = " . "'" . $diferida . "'" . ", nroautorizacion = " . "'" . $autorizacion . "'" . " WHERE nroauditoria = " . '"' . $nroauditoria . '"';
        $result = mysql_query($query);
    }
    
    function actualizarTransaccionAutorizadaToken($nroauditoria, $transaccion, $diferida, $autorizacion, $token) {
        $query = "UPDATE cab_auditoria SET transaccion = " . "'" . $transaccion . "'" . ", diferida = " . "'" . $diferida . "'" . ", nroautorizacion = " . "'" . $autorizacion . "'" . ", token = " . "'" . $token . "'" . 
                 " WHERE nroauditoria = " . '"' . $nroauditoria . '"';
        $result = mysql_query($query);
    }

    function actualizarTransaccionAnulada($nroauditoria, $transaccion) {
        $query = "UPDATE det_auditoria SET anulada = 'S' WHERE nroauditoria = " . '"' . $nroauditoria . '"';
        $result = mysql_query($query);
        $query = "UPDATE cab_auditoria SET anulada = 'S', transaccion_anulada = " . "'" . $transaccion . "'" . " WHERE nroauditoria = " . '"' . $nroauditoria . '"';
        $result = mysql_query($query);
    }

    function actualizarExpediente($nroauditoria, $expediente) {
        $query = "UPDATE cab_auditoria SET expediente = '$expediente' WHERE nroauditoria = '$nroauditoria'";
        $result = mysql_query($query);
        //echo $query;
    }

    function getOrdenesPendientesAutorizacion($xdesde, $xhasta, $xidprof, $xcodos) {
        $desde = $this->utiles->getFechaAAAAMMDD($xdesde);
        $hasta = $this->utiles->getFechaAAAAMMDD($xhasta);
        $mc = 0;
        $query = "SELECT COUNT(*) AS m FROM cab_auditoria WHERE fecha >= '$desde' AND fecha <= '$hasta' AND codos = '$xcodos' AND efector = '$xidprof' AND diferida = 'S' AND auditor is null";
        $this->sql = $query;
        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $mc = $fila['m'];
        }
        return $mc;
    }

    function anularPracticas($nroauditoria) {
        $query = "UPDATE det_auditoria SET estado = 'R' WHERE nroauditoria = '$nroauditoria'";
        $result = mysql_query($query);
    }

    function verificarDeterminacion($nroauditoria, $codigo) {
        $query = "SELECT codigo FROM det_auditoria WHERE nroauditoria = '$nroauditoria' and codigo = '$codigo'";

        $result = false;
        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $result = true;
            break;
        }
        return $result;
    }

    function guardarMensajeRPC($nroauditoria, $mensaje) {
        $query = "insert into cab_auditoria_mensajes (nroauditoria, mensaje) values ('$nroauditoria', '$mensaje')";
        $result = mysql_query($query);
    }

    function getMensajeRPC($nroauditoria) {
        $query = "select mensaje from cab_auditoria_mensajes where nroauditoria = '$nroauditoria'";
        $result = mysql_query($query);

        $r = '';
        while ($fila = mysql_fetch_array($resultado)) {
            $r = $fila['mensaje'];
            break;
        }
        return $r;
    }

    function getDetalleOperaciones($xdesde, $xhasta, $codos) {
        $desde = $this->utiles->getFechaAAAAMMDD($xdesde);
        $hasta = $this->utiles->getFechaAAAAMMDD($xhasta);
        $query = "select cab_auditoria.nroauditoria, cab_auditoria.efector, cab_auditoria.fecha, cab_auditoria.codos, cab_auditoria.idzona, cab_auditoria.nrodoc, cab_auditoria.idprof, cab_auditoria.iddiag, cab_auditoria.idprof, det_auditoria.items, det_auditoria.codigo, det_auditoria.monto, det_auditoria.estado, nbu.unidad from cab_auditoria inner join det_auditoria on cab_auditoria.nroauditoria = det_auditoria.nroauditoria inner join nbu on det_auditoria.codigo = nbu.codigo where cab_auditoria.fecha >= '$desde' and cab_auditoria.fecha <= '$hasta' and cab_auditoria.codos = '$codos' order by nroauditoria, items";
        $this->res = mysql_query($query);
        $this->sql = $query;
        return $this->res;
        echo $this->res;
    }

    function marcarItemsAutorizados($nroauditoria) {
        $this->detalle->marcarItemsAutorizados($nroauditoria);
    }

    function getCantidadBonos($codos, $xdesde, $xhasta) {
        $desde = $this->utiles->getFechaAAAAMMDD($xdesde);
        $hasta = $this->utiles->getFechaAAAAMMDD($xhasta);

        $query = "SELECT nroauditoria FROM cab_auditoria WHERE codos = '$codos' AND fecha >= '$desde' AND fecha <= '$hasta' AND anulada <> 'S'";

        $result = mysql_query($query);

        $bonos = 0;

        while ($fila = mysql_fetch_array($result)) {
            $bonos = $bonos + $this->getBonosOrden($fila['nroauditoria']);
        }

        return $bonos;
    }

    function getBonosOrden($nroauditoria) {
        // Objetivo...: Imprimir el Talón de la Autorización
        $query = "SELECT nroauditoria, transaccion, nroautorizacion, codos, idprof, fecha, fepedido, nrodoc, iddiag, nombre, diferida, auditada, auditor, auditado, profcab, expediente, anulada FROM cab_auditoria WHERE nroauditoria = '$nroauditoria'";
        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            // Código equivalente
            $codigoeq = $this->getObsocialEquivalente($fila['codos']);

            $pper = substr($fila['fecha'], 4, 2) . '/' . substr($fila['fecha'], 0, 4);
            $ccss = $fila['codos'];

            $autorizadas = '';
            $rechazadas = '';
            $tramo1 = 0;
            $tramo2 = 0;
            $tramo3 = 0;
            $cantbonos1 = 0;
            $cantbonos2 = 0;
            $cantbonos3 = 0;
            $cantdeterminaciones = 0;

            $coseguro = 0;

            // 29/06/2016
            $lista_aut = array();
            $lista_rec = array();
            $_a = 0;
            $_r = 0;

            $nro = "'" . $fila['nroauditoria'] . "'";
            $deter = $this->detalle->getItems($nro);
            while ($f = mysql_fetch_array($deter)) {

                // Llenamos los arrays - 29/06/2016

                $this->nbu->getObject($f['codigo']);
                if ($f['estado'] == 'A') {
                    $lista_aut[$_a] = $f['codigo'] . ' - ' . $this->nbu->getDescrip();
                    //echo $f['codigo'] . ' - ' . $this->nbu->getDescrip();
                    $_a++;
                }
                if ($f['estado'] == 'R') {
                    $lista_rec[$_r] = $f['codigo'] . ' - ' . $this->nbu->getDescrip();
                    $_r++;
                }

                if ($f['estado'] == 'A') {
                    $cantdeterminaciones++;
                    $autorizadas = $autorizadas . $f['codigo'] . '  ';

                    // Buscamos los tramos
                    $this->nbu->getObject($f['codigo']);
                    // Sumamos los tramos
                    if ($this->nbu->getTramo() == 1) {
                        $tramo1 = $tramo1 + 1;
                    }
                    if ($this->nbu->getTramo() == 2) {
                        $tramo2 = $tramo2 + 1;
                    }
                    if ($this->nbu->getTramo() == 3) {
                        $tramo3 = $tramo3 + 1;
                    }

                    // Coseguro
                    $coseguro = $coseguro + ($f['coseguro'] - $f['monto']);
                } else {
                    $rechazadas = $rechazadas . $f['codigo'] . '  ';
                }
            }

            $obsocial = new cObSocial();
            $obsocial->getObject($codigoeq);

            // Deducimos la Cantidad de Bonos
            if ($tramo1 > 0 and $obsocial->getBonos() == '1') {
                $this->tramos->getObject($codigoeq, 1);
                // cant. determinaciones / det. que abarca
                if ($this->tramos->getTope() > 0) {
                    $cb1 = $tramo1 / $this->tramos->getTope();
                    $cb1 = intval($cb1);
                }

                $cantbonos1 = $cb1 * $this->tramos->getCantbonos();
                if ($this->tramos->getTope() > 0) {
                    if (($tramo1 % $this->tramos->getTope()) > 0) {
                        $cantbonos1 = $cantbonos1 + $this->tramos->getCantbonos();
                    }
                }
            }

            if ($tramo2 > 0 and $obsocial->getBonos() == '1') {
                $this->tramos->getObject($codigoeq, 2);
                // cant. determinaciones / det. que abarca
                $cb2 = $tramo2 / $this->tramos->getTope();
                $cb2 = intval($cb2);

                $cantbonos2 = $cb2 * $this->tramos->getCantbonos();
                if (($tramo2 % $this->tramos->getTope()) > 0) {
                    $cantbonos2 = $cantbonos2 + $this->tramos->getCantbonos();
                }
            }

            if ($tramo3 > 0 and $obsocial->getBonos() == '1') {
                $this->tramos->getObject($codigoeq, 3);
                // cant. determinaciones / det. que abarca
                $cb3 = $tramo3 / $this->tramos->getTope();
                $cb3 = intval($cb3);

                $cantbonos3 = $cb3 * $this->tramos->getCantbonos();
                if (($tramo3 % $this->tramos->getTope()) > 0) {
                    $cantbonos3 = $cantbonos3 + $this->tramos->getCantbonos();
                }
            }

            $totalbonos = $cantbonos1 + $cantbonos2 + $cantbonos3;

            return $totalbonos;
        }
    }

    // borra tupla
    function delete($nroauditoria) {
        $query = "DELETE FROM cab_auditoria WHERE nroauditoria = '" . $nroauditoria . "'";
        $result = mysql_query($query);
        $query = "DELETE FROM det_auditoria WHERE nroauditoria = '" . $nroauditoria . "'";
        $result = mysql_query($query);
    }

    function countPracticas($nroauditoria, $codigo) {
        $query = "SELECT COUNT(codigo) AS cant FROM det_auditoria WHERE nroauditoria = '$nroauditoria' and codigo = '$codigo'";
        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $cantidad = $fila['cant'];
        }
        return $cantidad;
    }

    function countPracticasOrden($nroauditoria) {
        $query = "SELECT COUNT(codigo) AS cant FROM det_auditoria WHERE nroauditoria = '$nroauditoria'";
        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $cantidad = $fila['cant'];
        }
        return $cantidad;
    }

    function getAuditoriaPracticas($nroauditoria) {
        $query = "SELECT DISTINCT(codigo) FROM det_auditoria WHERE nroauditoria = '$nroauditoria' ORDER BY items";
        $this->res = mysql_query($query);
        return $this->res;
    }

    function verificarCodigoCoseguroExcluido($regla, $codigo) {
        $query = "SELECT COUNT(regla) AS cant FROM nbu_coseguro_excluir WHERE regla = $regla AND codigo= '$codigo'";
        $resultado = mysql_query($query);
        $found = false;
        while ($fila = mysql_fetch_array($resultado)) {
            if ($fila['cant'] > 0)
                $found = true;
        }
        return $found;
    }

    function aplicarCoseguroReglas($nroauditoria, $codos, $nrodoc) {

        // 01/11/2023
        return 0;

        $pocentajecoseguro = 0;

        $this->obrasocial->verificarRPC($codos);

        $monto1 = $this->detalle->getValorAnalisis($this->obrasocial->_parametro2, $codos, $this->utiles->getPeriodoActual());
        $monto2 = $this->detalle->getValorAnalisis($this->obrasocial->_parametro3, $codos, $this->utiles->getPeriodoActual());

        $rs = "";
        $total = 0;
        $cos_0 = 0;

        //echo $monto1 . '  ' . $monto2;
        //======================================================================
        // Cálculo para FESALUD - 17/03/2020
        if ($this->obrasocial->_reglaNegocio == 10) {
            $this->afiliado->getObject($codos, $nrodoc);
            $this->afiliado->getCoseguro($codos, $nrodoc);

            $cos1 = $this->afiliado->coseguro1;
            $cos2 = $this->afiliado->coseguro2;
            $cos3 = $this->afiliado->coseguro3;

            //$porcentajecoseguro = $this->afiliado->id_beneficio;

            $deter = $this->detalle->getItems($nroauditoria);
            while ($f = mysql_fetch_array($deter)) {

                $coseguro = 0;
                $cc = 0;

                $realizado = false;

                if ($cos1 == $cos2) {
                    if ($cos2 == $cos3) {
                        // Si los tres son iguales
                        if ($cos1 > 0) {
                            $coseguro = ($f['monto'] * floatval($cos1)) / 100;
                            $realizado = true;
                            $cc = $cos1;
                        }
                    }
                }

                // Sin son diferidos                
                if ($realizado == false) {
                    // Se aplica el % de acuerdo a la complejidad
                    if ($f['monto'] >= $monto2) {
                        $coseguro = ($f['monto'] * floatval($cos3)) / 100;
                        $cc = $cos3;
                    } else {
                        $coseguro = ($f['monto'] * floatval($cos1)) / 100;
                        $cc = $cos1;
                    }
                }

                $rs = $rs . 'codigo: ' . $f['codigo'] . ' - monto: ' . $f['monto'] . ' - coseguro: ' . $coseguro . ' - porcentaje: ' . $cc . ' Estado: ' . $f['estado'] . '<br/>';
                if ($f['estado'] == 'A')
                    $total = $total + $coseguro;

                // Averiguamos si el coseguro es 0 - para todas las reglas
                if ($this->verificarCodigoCoseguroExcluido($this->obrasocial->_reglaNegocio, $f['codigo']))
                    $cos_0 = $cos_0 + 1;

                // Coseguro 0 - Embarazadas - 27/04/2020
                if ($this->afiliado->getId_beneficio() == 'N')
                    $cos_0 = $cos_0 + 1;

                //$coseguro = ($f['monto'] * floatval($porcentajecoseguro)) / 100;                                    
                $this->detalle->actualizarCoseguroMonto($nroauditoria, $f['items'], $coseguro);
            }

            // Eliminamos Coseguro
            if ($cos_0 > 0) {
                $total = 0;
                $this->detalle->resetCoseguroMonto($nroauditoria);
            }

            $rs = $rs . '<h3>TOTAL COSEGURO: ' . $total . '</h3>';

            return $rs;
        }

        //======================================================================
    }

    function getConsumos($desde, $hasta, $codos) {
        $f1 = $this->utiles->getFechaAAAAMMDD($desde);
        $f2 = $this->utiles->getFechaAAAAMMDD($hasta);
        $query = "select sum(det_auditoria.monto) as monto, sum(det_auditoria.coseguro) as coseguro, cab_auditoria.codos, " .
                "cab_auditoria.fecha FROM det_auditoria, cab_auditoria WHERE cab_auditoria.codos = '$codos' " .
                "and cab_auditoria.fecha between '$f1' and '$f2' and det_auditoria.estado = 'A' and cab_auditoria.anulada <> 'S' " .
                "and cab_auditoria.nroauditoria = det_auditoria.nroauditoria";
        $this->res = mysql_query($query);
        return $this->res;
    }

    //--------------------------------------------------------------------------

    function addPractica($nroauditoria, $codigo, $nroautorizacion) {
        $this->getObject($nroauditoria);
        $this->detalle->addPractica($nroauditoria, $codigo, $this->getEfector(), $this->getCodos(), $this->getFecha(), $this->getNrodoc(), 'A');
        if ($nroautorizacion != '----') {
            $query = "UPDATE cab_auditoria SET expediente = '$nroautorizacion' WHERE nroauditoria = '$nroauditoria'";
            $result = mysql_query($query);
        }
    }

    function countOrdenesPendientesObraSocial($codos, $desde, $hasta) {
        $f1 = $this->utiles->getFechaAAAAMMDD($desde);
        $f2 = $this->utiles->getFechaAAAAMMDD($hasta);

        $query = "SELECT COUNT(nroauditoria) AS cant FROM cab_auditoria WHERE codos = '$codos' AND fecha BETWEEN '$f1' AND '$f2' AND anulada <> 'S' AND auditada = 'N' AND diferida = 'S'";
        $resultado = mysql_query($query);

        $cantidad = 0;
        while ($fila = mysql_fetch_array($resultado)) {
            $cantidad = $fila['cant'];
        }

        return $cantidad;
    }

    function verificarPracticaDia($codos, $fecha, $nrodoc, $codigo) {
        $f1 = $this->utiles->getFechaAAAAMMDD($fecha);

        $query = "SELECT codigo FROM det_auditoria WHERE codos = '$codos' AND fecha = '$f1' AND anulada <> 'S' AND nrodoc = '$nrodoc' AND codigo = '$codigo'";
        $resultado = mysql_query($query);
        //echo $query;

        $result = false;
        while ($fila = mysql_fetch_array($resultado)) {
            $result = true;
        }

        return $result;
    }

    function verificarCantidadPracticaDia($codos, $fecha, $nrodoc, $codigo) {
        $f1 = $this->utiles->getFechaAAAAMMDD($fecha);

        $query = "SELECT COUNT(codigo) AS cant FROM det_auditoria WHERE codos = '$codos' AND fecha = '$f1' AND anulada <> 'S' AND nrodoc = '$nrodoc' AND codigo = '$codigo'";
        $resultado = mysql_query($query);
        //echo $query;

        $cantidad = 0;
        while ($fila = mysql_fetch_array($resultado)) {
            $cantidad = $fila['cant'];
        }

        return $cantidad;
    }

    function getObrasSocialesCoseguro($desde, $hasta) {
        $f1 = $this->utiles->getFechaAAAAMMDD($desde);
        $f2 = $this->utiles->getFechaAAAAMMDD($hasta);
        //$query = "select codos, efector, sum(coseguro) as coseguro from det_auditoria where fecha between '$f1' and '$f2' and coseguro > 0 group by codos, efector order by codos, efector";
        $query = "select det_auditoria.codos, det_auditoria.efector, sum(det_auditoria.coseguro) as coseguro from det_auditoria, cab_auditoria where det_auditoria.fecha BETWEEN '$f1' AND '$f2' and det_auditoria.coseguro > 0 AND det_auditoria.NROAUDITORIA = cab_auditoria.NROAUDITORIA AND cab_auditoria.anulada <> 'S' group by det_auditoria.codos, det_auditoria.efector order by det_auditoria.codos, det_auditoria.efector";
        $this->res = mysql_query($query);
        //echo $query;
        return $this->res;
    }

    function getProfesionalCoseguro($desde, $hasta) {
        $f1 = $this->utiles->getFechaAAAAMMDD($desde);
        $f2 = $this->utiles->getFechaAAAAMMDD($hasta);
        //$query = "select codos, efector, sum(coseguro) as coseguro from det_auditoria where fecha between '$f1' and '$f2' and coseguro > 0 group by codos, efector order by codos, efector";
        $query = "select det_auditoria.codos, det_auditoria.efector, sum(det_auditoria.coseguro) as coseguro, sum(det_auditoria.monto) as monto from det_auditoria, cab_auditoria where det_auditoria.fecha BETWEEN '$f1' AND '$f2' and det_auditoria.coseguro > 0 AND det_auditoria.NROAUDITORIA = cab_auditoria.NROAUDITORIA AND cab_auditoria.anulada <> 'S' group by det_auditoria.efector, det_auditoria.codos order by det_auditoria.efector, det_auditoria.codos";
        $this->res = mysql_query($query);
        //echo $query;
        return $this->res;
    }

}

?>
