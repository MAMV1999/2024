<?php
require_once("../../database.php");

class GradoCompetencia
{
    public function __construct()
    {
    }

    // Método para guardar una nueva relación grado-competencia
    public function guardar($grado_id, $competencia_id)
    {
        $sql = "INSERT INTO grado_competencia (grado_id, competencia_id, estado) 
                VALUES ('$grado_id', '$competencia_id', '1')";
        return ejecutarConsulta($sql);
    }

    // Método para editar una relación grado-competencia existente
    public function editar($id, $grado_id, $competencia_id)
    {
        $sql = "UPDATE grado_competencia 
                SET grado_id='$grado_id', competencia_id='$competencia_id' 
                WHERE id='$id'";
        return ejecutarConsulta($sql);
    }

    // Método para mostrar los detalles de una relación grado-competencia específica
    public function mostrar($id)
    {
        $sql = "SELECT gc.id, gc.grado_id, gc.competencia_id, ig.nombre AS grado, c.nombre AS competencia, 
                       il.nombre AS institucion_lectivo, iniv.nombre AS institucion_nivel, ac.nombre AS area_curricular
                FROM grado_competencia gc
                LEFT JOIN institucion_grado ig ON gc.grado_id = ig.id
                LEFT JOIN competencia c ON gc.competencia_id = c.id
                LEFT JOIN area_curricular ac ON c.area_curricular_id = ac.id
                LEFT JOIN institucion_nivel iniv ON ac.institucion_nivel_id = iniv.id
                LEFT JOIN institucion_lectivo il ON iniv.institucion_lectivo_id = il.id
                WHERE gc.id='$id'";
        return ejecutarConsultaSimpleFila($sql);
    }

    // Método para listar todas las relaciones grado-competencia
    public function listar()
    {
        $sql = "SELECT gc.id, il.nombre AS institucion_lectivo, iniv.nombre AS institucion_nivel, ac.nombre AS area_curricular, 
                       ig.nombre AS grado, c.nombre AS competencia, gc.estado, gc.fechacreado
                FROM grado_competencia gc
                LEFT JOIN institucion_grado ig ON gc.grado_id = ig.id
                LEFT JOIN competencia c ON gc.competencia_id = c.id
                LEFT JOIN area_curricular ac ON c.area_curricular_id = ac.id
                LEFT JOIN institucion_nivel iniv ON ac.institucion_nivel_id = iniv.id
                LEFT JOIN institucion_lectivo il ON iniv.institucion_lectivo_id = il.id";
        return ejecutarConsulta($sql);
    }

    // Método para desactivar una relación grado-competencia
    public function desactivar($id)
    {
        $sql = "UPDATE grado_competencia SET estado='0' WHERE id='$id'";
        return ejecutarConsulta($sql);
    }

    // Método para activar una relación grado-competencia
    public function activar($id)
    {
        $sql = "UPDATE grado_competencia SET estado='1' WHERE id='$id'";
        return ejecutarConsulta($sql);
    }

    // Método para listar los grados activos
    public function listarGradosActivos()
    {
        $sql = "SELECT ig.id, CONCAT(il.nombre, ' - ', iniv.nombre, ' - ', ig.nombre) AS nombre
                FROM institucion_grado ig
                LEFT JOIN institucion_nivel iniv ON ig.institucion_nivel_id = iniv.id
                LEFT JOIN institucion_lectivo il ON iniv.institucion_lectivo_id = il.id
                WHERE ig.estado='1'";
        return ejecutarConsulta($sql);
    }

    // Método para listar las competencias activas
    public function listarCompetenciasActivas()
    {
        $sql = "SELECT c.id, CONCAT(il.nombre, ' - ', iniv.nombre, ' - ', ac.nombre, ' - ', c.nombre) AS nombre
                FROM competencia c
                LEFT JOIN area_curricular ac ON c.area_curricular_id = ac.id
                LEFT JOIN institucion_nivel iniv ON ac.institucion_nivel_id = iniv.id
                LEFT JOIN institucion_lectivo il ON iniv.institucion_lectivo_id = il.id
                WHERE c.estado='1'";
        return ejecutarConsulta($sql);
    }
}
?>
