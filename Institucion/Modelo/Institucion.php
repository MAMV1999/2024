<?php
require_once("../../database.php");

class Institucion
{
    public function __construct()
    {
    }

    // Método para guardar una nueva institución
    public function guardar($nombre, $id_trabajador, $telefono, $correo, $direccion)
    {
        $sql = "INSERT INTO institucion (nombre, id_trabajador, telefono, correo, direccion) 
                VALUES ('$nombre', '$id_trabajador', '$telefono', '$correo', '$direccion')";
        return ejecutarConsulta($sql);
    }

    // Método para editar una institución existente
    public function editar($id, $nombre, $id_trabajador, $telefono, $correo, $direccion)
    {
        $sql = "UPDATE institucion 
                SET nombre='$nombre', id_trabajador='$id_trabajador', telefono='$telefono', correo='$correo', direccion='$direccion' 
                WHERE id='$id'";
        return ejecutarConsulta($sql);
    }

    // Método para mostrar los detalles de una institución específica
    public function mostrar($id)
    {
        $sql = "SELECT * FROM institucion WHERE id='$id'";
        return ejecutarConsultaSimpleFila($sql);
    }

    // Método para listar todas las instituciones
    public function listar()
    {
        $sql = "SELECT i.id, i.nombre, t.nombre_apellido AS trabajador, i.telefono, i.correo, i.direccion, i.estado
                FROM institucion i
                LEFT JOIN trabajador t ON i.id_trabajador = t.id";
        return ejecutarConsulta($sql);
    }

    // Método para desactivar una institución
    public function desactivar($id)
    {
        $sql = "UPDATE institucion SET estado='0' WHERE id='$id'";
        return ejecutarConsulta($sql);
    }

    // Método para activar una institución
    public function activar($id)
    {
        $sql = "UPDATE institucion SET estado='1' WHERE id='$id'";
        return ejecutarConsulta($sql);
    }

    // Método para listar los directores activos que no están asignados a una institución activa
    public function listarDirectoresActivos()
    {
        $sql = "SELECT t.id, t.nombre_apellido 
                FROM trabajador t 
                LEFT JOIN institucion i ON t.id = i.id_trabajador AND i.estado = '1'
                WHERE t.cargo='DIRECTOR' AND t.estado='1' AND i.id_trabajador IS NULL";
        return ejecutarConsulta($sql);
    }
}
?>
