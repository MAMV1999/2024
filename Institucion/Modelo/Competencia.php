<?php
require_once("../../database.php");

class Competencia
{
    public function __construct()
    {
    }

    // Método para guardar una nueva competencia
    public function guardarMultiple($datos)
    {
        foreach ($datos as $dato) {
            $area_curricular_id = $dato['area_curricular_id'];
            $nombre = $dato['nombre'];
            $sql = "INSERT INTO competencia (area_curricular_id, nombre, estado) 
                    VALUES ('$area_curricular_id', '$nombre', '1')";
            $resultado = ejecutarConsulta($sql);
            if (!$resultado) {
                return false; // Si alguna inserción falla, retornamos falso
            }
        }
        return true; // Si todas las inserciones son exitosas, retornamos verdadero
    }
    

    // Método para editar una competencia existente
    public function editar($id, $area_curricular_id, $nombre)
    {
        $sql = "UPDATE competencia 
                SET area_curricular_id='$area_curricular_id', nombre='$nombre' 
                WHERE id='$id'";
        return ejecutarConsulta($sql);
    }

    // Método para mostrar los detalles de una competencia específica
    public function mostrar($id)
    {
        $sql = "SELECT c.id, c.nombre, ac.id AS area_curricular_id, ac.nombre AS area_curricular, iniv.nombre AS institucion_nivel, il.nombre AS institucion_lectivo 
                FROM competencia c
                LEFT JOIN area_curricular ac ON c.area_curricular_id = ac.id
                LEFT JOIN institucion_nivel iniv ON ac.institucion_nivel_id = iniv.id
                LEFT JOIN institucion_lectivo il ON iniv.institucion_lectivo_id = il.id
                WHERE c.id='$id'";
        return ejecutarConsultaSimpleFila($sql);
    }

    // Método para listar todas las competencias
    public function listar()
    {
        $sql = "SELECT c.id, c.nombre, ac.nombre AS area_curricular, iniv.nombre AS institucion_nivel, il.nombre AS institucion_lectivo, c.estado
                FROM competencia c
                LEFT JOIN area_curricular ac ON c.area_curricular_id = ac.id
                LEFT JOIN institucion_nivel iniv ON ac.institucion_nivel_id = iniv.id
                LEFT JOIN institucion_lectivo il ON iniv.institucion_lectivo_id = il.id";
        return ejecutarConsulta($sql);
    }

    // Método para desactivar una competencia
    public function desactivar($id)
    {
        $sql = "UPDATE competencia SET estado='0' WHERE id='$id'";
        return ejecutarConsulta($sql);
    }

    // Método para activar una competencia
    public function activar($id)
    {
        $sql = "UPDATE competencia SET estado='1' WHERE id='$id'";
        return ejecutarConsulta($sql);
    }

    // Método para listar las áreas curriculares activas
    public function listarAreasCurricularesActivas()
    {
        $sql = "SELECT ac.id, CONCAT(il.nombre, ' - ', iniv.nombre, ' - ', ac.nombre) AS nombre
                FROM area_curricular ac
                LEFT JOIN institucion_nivel iniv ON ac.institucion_nivel_id = iniv.id
                LEFT JOIN institucion_lectivo il ON iniv.institucion_lectivo_id = il.id
                WHERE ac.estado='1'";
        return ejecutarConsulta($sql);
    }
}
?>
