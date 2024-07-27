<?php
require_once("../../database.php");

class MatriculaRazon
{
    public function __construct()
    {
    }

    // Método para guardar una nueva razón de matrícula
    public function guardar($nombre)
    {
        $sql = "INSERT INTO matricula_razon (nombre, estado) VALUES ('$nombre', '1')";
        return ejecutarConsulta($sql);
    }

    // Método para editar una razón de matrícula existente
    public function editar($id, $nombre)
    {
        $sql = "UPDATE matricula_razon SET nombre='$nombre' WHERE id='$id'";
        return ejecutarConsulta($sql);
    }

    // Método para mostrar los detalles de una razón específica
    public function mostrar($id)
    {
        $sql = "SELECT * FROM matricula_razon WHERE id='$id'";
        return ejecutarConsultaSimpleFila($sql);
    }

    // Método para listar todas las razones de matrícula
    public function listar()
    {
        $sql = "SELECT * FROM matricula_razon";
        return ejecutarConsulta($sql);
    }

    // Método para desactivar una razón de matrícula
    public function desactivar($id)
    {
        $sql = "UPDATE matricula_razon SET estado='0' WHERE id='$id'";
        return ejecutarConsulta($sql);
    }

    // Método para activar una razón de matrícula
    public function activar($id)
    {
        $sql = "UPDATE matricula_razon SET estado='1' WHERE id='$id'";
        return ejecutarConsulta($sql);
    }
}
?>
