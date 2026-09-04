<table width="500px">
    <tr>
        <?php
        if (isset($_GET['leyenda'])) {
            $leyenda = $_REQUEST['leyenda'];
        } else {
            $leyenda = "";
        }
        ?>
        <?
        if (strlen($leyenda) == 0) {
            $l = "Buscar:";
        } else {
            $l = $leyenda;
        }

        echo "<td width='120px' align='right'>" . $l . "</td>";
        ?>

        <td width='100px' align='left'><input name="buscarvalor" id="buscarvalor" style="font-size: 10px;" type="text" maxlength="100" size="30" onkeypress="javascript: if(BuscarValor(event, buscarvalor.value)) {buscarvalor.focus()}; return true"></td>
        <td width="30px" align="right"><input type="button" name="btnBuscar" value="Buscar" class="button gray small" onClick="ProcederBuscar(buscarvalor.value); return false" /></td>
        <td width="30px" align="left"><input name="btnCancelar" class="button gray small" type="button" value="Cancelar" onClick="javascript: if(CancelarBusqueda()); return true"></td>
    </tr>
</table>