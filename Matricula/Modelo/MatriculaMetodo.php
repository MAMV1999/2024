<?php
require_once("../../database.php");

class MatriculaMetodo
{
    public function __construct()
    {
    }

    // Método para guardar un nuevo método de matrícula
    public function guardar($nombre)
    {
        $sql = "INSERT INTO matricula_metodo (nombre, estado) VALUES ('$nombre', '1')";
        return ejecutarConsulta($sql);
    }

    // Método para editar un método de matrícula existente
    public function editar($id, $nombre)
    {
        $sql = "UPDATE matricula_metodo SET nombre='$nombre' WHERE id='$id'";
        return ejecutarConsulta($sql);
    }

    // Método para mostrar los detalles de un método específico
    public function mostrar($id)
    {
        $sql = "SELECT * FROM matricula_metodo WHERE id='$id'";
        return ejecutarConsultaSimpleFila($sql);
    }

    // Método para listar todos los métodos de matrícula
    public function listar()
    {
        $sql = "SELECT * FROM matricula_metodo";
        return ejecutarConsulta($sql);
    }

    // Método para desactivar un método de matrícula
    public function desactivar($id)
    {
        $sql = "UPDATE matricula_metodo SET estado='0' WHERE id='$id'";
        return ejecutarConsulta($sql);
    }

    // Método para activar un método de matrícula
    public function activar($id)
    {
        $sql = "UPDATE matricula_metodo SET estado='1' WHERE id='$id'";
        return ejecutarConsulta($sql);
    }
}
?>
