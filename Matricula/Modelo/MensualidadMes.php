<?php
require_once("../../database.php");

class MensualidadMes
{
    public function __construct()
    {
    }

    // Método para guardar una nueva mensualidad
    public function guardar($nombre, $observaciones)
    {
        $sql = "INSERT INTO mensualidad_mes (nombre, observaciones, estado) 
                VALUES ('$nombre', '$observaciones', '1')";
        return ejecutarConsulta($sql);
    }

    // Método para editar una mensualidad existente
    public function editar($id, $nombre, $observaciones)
    {
        $sql = "UPDATE mensualidad_mes 
                SET nombre='$nombre', observaciones='$observaciones' 
                WHERE id='$id'";
        return ejecutarConsulta($sql);
    }

    // Método para mostrar los detalles de una mensualidad específica
    public function mostrar($id)
    {
        $sql = "SELECT * FROM mensualidad_mes WHERE id='$id'";
        return ejecutarConsultaSimpleFila($sql);
    }

    // Método para listar todas las mensualidades
    public function listar()
    {
        $sql = "SELECT * FROM mensualidad_mes";
        return ejecutarConsulta($sql);
    }

    // Método para desactivar una mensualidad
    public function desactivar($id)
    {
        $sql = "UPDATE mensualidad_mes SET estado='0' WHERE id='$id'";
        return ejecutarConsulta($sql);
    }

    // Método para activar una mensualidad
    public function activar($id)
    {
        $sql = "UPDATE mensualidad_mes SET estado='1' WHERE id='$id'";
        return ejecutarConsulta($sql);
    }
}
?>
