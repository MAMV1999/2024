<?php
require_once("../../database.php");

class InstitucionLectivo
{
    public function __construct()
    {
    }

    // Método para guardar una nueva institución lectiva
    public function guardar($nombre, $institucion_id, $lectivo_nombre)
    {
        $sql = "INSERT INTO institucion_lectivo (nombre, institucion_id, lectivo_nombre, estado) 
                VALUES ('$nombre', '$institucion_id', '$lectivo_nombre', '1')";
        return ejecutarConsulta($sql);
    }

    // Método para editar una institución lectiva existente
    public function editar($id, $nombre, $institucion_id, $lectivo_nombre)
    {
        $sql = "UPDATE institucion_lectivo 
                SET nombre='$nombre', institucion_id='$institucion_id', lectivo_nombre='$lectivo_nombre' 
                WHERE id='$id'";
        return ejecutarConsulta($sql);
    }

    // Método para mostrar los detalles de una institución lectiva específica
    public function mostrar($id)
    {
        $sql = "SELECT * FROM institucion_lectivo WHERE id='$id'";
        return ejecutarConsultaSimpleFila($sql);
    }

    // Método para listar todas las instituciones lectivas
    public function listar()
    {
        $sql = "SELECT il.id, il.nombre, il.lectivo_nombre, i.nombre AS institucion, il.estado
                FROM institucion_lectivo il
                LEFT JOIN institucion i ON il.institucion_id = i.id";
        return ejecutarConsulta($sql);
    }

    // Método para desactivar una institución lectiva
    public function desactivar($id)
    {
        $sql = "UPDATE institucion_lectivo SET estado='0' WHERE id='$id'";
        return ejecutarConsulta($sql);
    }

    // Método para activar una institución lectiva
    public function activar($id)
    {
        $sql = "UPDATE institucion_lectivo SET estado='1' WHERE id='$id'";
        return ejecutarConsulta($sql);
    }

    // Método para listar las instituciones activas
    public function listarInstitucionesActivas()
    {
        $sql = "SELECT id, nombre FROM institucion WHERE estado='1'";
        return ejecutarConsulta($sql);
    }
}
?>
