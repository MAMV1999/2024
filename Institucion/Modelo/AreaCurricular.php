<?php
require_once("../../database.php");

class AreaCurricular
{
    public function __construct()
    {
    }

    // Método para guardar múltiples áreas curriculares
    public function guardarMultiple($datos)
    {
        $valores = [];
        foreach ($datos as $dato) {
            $nombre = $dato['nombre'];
            $institucion_nivel_id = $dato['institucion_nivel_id'];
            $valores[] = "('$nombre', '$institucion_nivel_id')";
        }
        $valoresSQL = implode(', ', $valores);
        $sql = "INSERT INTO area_curricular (nombre, institucion_nivel_id) VALUES $valoresSQL";
        return ejecutarConsulta($sql);
    }

    // Método para editar un área curricular existente
    public function editar($id, $nombre, $institucion_nivel_id)
    {
        $sql = "UPDATE area_curricular 
                SET nombre='$nombre', institucion_nivel_id='$institucion_nivel_id' 
                WHERE id='$id'";
        return ejecutarConsulta($sql);
    }

    // Método para mostrar los detalles de un área curricular específica
    public function mostrar($id)
    {
        $sql = "SELECT * FROM area_curricular WHERE id='$id'";
        return ejecutarConsultaSimpleFila($sql);
    }

    // Método para listar todas las áreas curriculares
    public function listar()
    {
        $sql = "SELECT ac.id, ac.nombre, niv.nombre AS institucion_nivel, ac.fechacreado, ac.estado
                FROM area_curricular ac
                LEFT JOIN institucion_nivel niv ON ac.institucion_nivel_id = niv.id";
        return ejecutarConsulta($sql);
    }

    // Método para desactivar un área curricular
    public function desactivar($id)
    {
        $sql = "UPDATE area_curricular SET estado='0' WHERE id='$id'";
        return ejecutarConsulta($sql);
    }

    // Método para activar un área curricular
    public function activar($id)
    {
        $sql = "UPDATE area_curricular SET estado='1' WHERE id='$id'";
        return ejecutarConsulta($sql);
    }

    // Método para listar los niveles de instituciones activas
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
