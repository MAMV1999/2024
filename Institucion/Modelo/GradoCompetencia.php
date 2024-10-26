<?php
require_once("../../database.php");

class GradoCompetencia
{
    public function __construct()
    {
    }

    // Método para guardar múltiples relaciones grado-competencia
    public function guardarMultiple($datos)
    {
        foreach ($datos as $dato) {
            $grado_id = $dato['grado_id'];
            $competencia_id = $dato['competencia_id'];
            $sql = "INSERT INTO grado_competencia (grado_id, competencia_id, estado) 
                    VALUES ('$grado_id', '$competencia_id', '1')";
            $resultado = ejecutarConsulta($sql);
            if (!$resultado) {
                return "Error en la consulta: " . $GLOBALS['conectar']->error; // Si alguna inserción falla, devuelve el error
            }
        }
        return true; // Si todas las inserciones son exitosas, retorna verdadero
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

    // Nuevo método para listar grados disponibles (no registrados en grado_competencia)
    public function listarGradosDisponibles()
    {
        $sql = "SELECT ig.id, CONCAT(il.nombre, ' - ', iniv.nombre, ' - ', ig.nombre) AS nombre
                FROM institucion_grado ig
                LEFT JOIN institucion_nivel iniv ON ig.institucion_nivel_id = iniv.id
                LEFT JOIN institucion_lectivo il ON iniv.institucion_lectivo_id = il.id
                WHERE ig.estado='1'";
        return ejecutarConsulta($sql);
    }

    // Nuevo método para listar competencias disponibles (no registradas en grado_competencia)
    public function listarCompetenciasDisponibles()
    {
        $sql = "SELECT c.id, CONCAT(il.nombre, ' - ', iniv.nombre, ' - ', ac.nombre, ' - ', c.nombre) AS nombre
                FROM competencia c
                LEFT JOIN area_curricular ac ON c.area_curricular_id = ac.id
                LEFT JOIN institucion_nivel iniv ON ac.institucion_nivel_id = iniv.id
                LEFT JOIN institucion_lectivo il ON iniv.institucion_lectivo_id = il.id
                WHERE c.estado='1' 
                AND c.id NOT IN (SELECT competencia_id FROM grado_competencia)";
        return ejecutarConsulta($sql);
    }
}
?>
