<?php
require_once("../../database.php");

class Documentos
{
    // Método para obtener el reporte dinámico basado en un ID específico
    public function obtenerReporteDinamico($id)
    {
        $sql = "CALL ObtenerReporteDinamico($id)";
        return ejecutarConsulta($sql);
    }
}
?>
