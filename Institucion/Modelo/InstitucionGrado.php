<?php
require_once("../../database.php");

class InstitucionGrado
{
    public function __construct()
    {
    }

    // Método para guardar un nuevo grado de institución nivel
    public function guardar($nombre, $institucion_nivel_id)
    {
        $sql = "INSERT INTO institucion_grado (nombre, institucion_nivel_id, estado) 
                VALUES ('$nombre', '$institucion_nivel_id', '1')";
        return ejecutarConsulta($sql);
    }

    // Método para editar un grado de institución nivel existente
    public function editar($id, $nombre, $institucion_nivel_id)
    {
        $sql = "UPDATE institucion_grado 
                SET nombre='$nombre', institucion_nivel_id='$institucion_nivel_id' 
                WHERE id='$id'";
        return ejecutarConsulta($sql);
    }

    // Método para mostrar los detalles de un grado de institución nivel específico
    public function mostrar($id)
    {
        $sql = "SELECT * FROM institucion_grado WHERE id='$id'";
        return ejecutarConsultaSimpleFila($sql);
    }

    // Método para listar todos los grados de instituciones nivel
    public function listar()
    {
        $sql = "SELECT ig.id, ig.nombre, iniv.nombre AS institucion_nivel, ig.estado
                FROM institucion_grado ig
                LEFT JOIN institucion_nivel iniv ON ig.institucion_nivel_id = iniv.id";
        return ejecutarConsulta($sql);
    }

    // Método para desactivar un grado de institución nivel
    public function desactivar($id)
    {
        $sql = "UPDATE institucion_grado SET estado='0' WHERE id='$id'";
        return ejecutarConsulta($sql);
    }

    // Método para activar un grado de institución nivel
    public function activar($id)
    {
        $sql = "UPDATE institucion_grado SET estado='1' WHERE id='$id'";
        return ejecutarConsulta($sql);
    }

    // Método para listar los niveles de instituciones lectivas activas
    public function listarNivelesActivos()
    {
        $sql = "SELECT iniv.id, CONCAT(il.nombre, ' - ', iniv.nombre) AS nombre
                FROM institucion_nivel iniv
                JOIN institucion_lectivo il ON iniv.institucion_lectivo_id = il.id
                WHERE iniv.estado = '1' AND il.estado = '1'";
        return ejecutarConsulta($sql);
    }
}
?>
