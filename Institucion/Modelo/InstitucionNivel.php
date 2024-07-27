<?php
require_once("../../database.php");

class InstitucionNivel
{
    public function __construct()
    {
    }

    // Método para guardar un nuevo nivel de institución lectiva
    public function guardar($nombre, $institucion_lectivo_id)
    {
        $sql = "INSERT INTO institucion_nivel (nombre, institucion_lectivo_id, estado) 
                VALUES ('$nombre', '$institucion_lectivo_id', '1')";
        return ejecutarConsulta($sql);
    }

    // Método para editar un nivel de institución lectiva existente
    public function editar($id, $nombre, $institucion_lectivo_id)
    {
        $sql = "UPDATE institucion_nivel 
                SET nombre='$nombre', institucion_lectivo_id='$institucion_lectivo_id' 
                WHERE id='$id'";
        return ejecutarConsulta($sql);
    }

    // Método para mostrar los detalles de un nivel de institución lectiva específico
    public function mostrar($id)
    {
        $sql = "SELECT * FROM institucion_nivel WHERE id='$id'";
        return ejecutarConsultaSimpleFila($sql);
    }

    // Método para listar todos los niveles de instituciones lectivas
    public function listar()
    {
        $sql = "SELECT iniv.id, iniv.nombre, il.nombre AS institucion_lectivo, iniv.estado
                FROM institucion_nivel iniv
                LEFT JOIN institucion_lectivo il ON iniv.institucion_lectivo_id = il.id";
        return ejecutarConsulta($sql);
    }

    // Método para desactivar un nivel de institución lectiva
    public function desactivar($id)
    {
        $sql = "UPDATE institucion_nivel SET estado='0' WHERE id='$id'";
        return ejecutarConsulta($sql);
    }

    // Método para activar un nivel de institución lectiva
    public function activar($id)
    {
        $sql = "UPDATE institucion_nivel SET estado='1' WHERE id='$id'";
        return ejecutarConsulta($sql);
    }

    // Método para listar las instituciones lectivas activas
    public function listarInstitucionesLectivasActivas()
    {
        $sql = "SELECT id, nombre FROM institucion_lectivo WHERE estado='1'";
        return ejecutarConsulta($sql);
    }
}
?>
