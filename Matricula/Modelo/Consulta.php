<?php
require_once("../../database.php");

class Consulta
{
    public function __construct()
    {
    }

    public function listar()
    {
        $sql = "SELECT 
                    il.nombre as lectivo,
                    iniv.nombre as nivel,
                    ig.nombre as grado,
                    al.nombreyapellido as alumno
                FROM 
                    matricula_detalle md
                JOIN 
                    institucion_grado ig ON md.matricula_id = ig.id
                JOIN 
                    institucion_nivel iniv ON ig.institucion_nivel_id = iniv.id
                JOIN 
                    institucion_lectivo il ON iniv.institucion_lectivo_id = il.id
                JOIN 
                    alumno al ON md.alumno_id = al.id
                WHERE 
                    md.estado = '1'
                ORDER BY 
                    il.nombre ASC, 
                    iniv.nombre ASC, 
                    ig.nombre ASC, 
                    al.nombreyapellido ASC";
        return ejecutarConsulta($sql);
    }

    public function listarApoderadosAlumnos()
    {
        $sql = "SELECT 
                    a.nombreyapellido as apoderado,
                    a.telefono,
                    al.nombreyapellido as alumno
                FROM 
                    apoderado a
                JOIN 
                    matricula_detalle md ON a.id = md.apoderado_id
                JOIN 
                    alumno al ON md.alumno_id = al.id
                WHERE 
                    a.estado = '1'
                ORDER BY 
                    a.nombreyapellido ASC, 
                    al.nombreyapellido ASC";
        return ejecutarConsulta($sql);
    }

    public function listarAlumnosPorMes()
    {
        $sql = "SELECT 
                    MONTH(al.nacimiento) as mes,
                    DAY(al.nacimiento) as dia,
                    al.nombreyapellido as alumno
                FROM 
                    alumno al
                WHERE 
                    al.estado = '1'
                ORDER BY 
                    mes ASC, 
                    dia ASC, 
                    al.nombreyapellido ASC";
        return ejecutarConsulta($sql);
    }

    public function listarMatriculados()
    {
        $sql = "SELECT 
                    il.nombre as lectivo,
                    iniv.nombre as nivel,
                    ig.nombre as grado,
                    COUNT(md.id) as cantidad_matriculados
                FROM 
                    matricula_detalle md
                JOIN 
                    institucion_grado ig ON md.matricula_id = ig.id
                JOIN 
                    institucion_nivel iniv ON ig.institucion_nivel_id = iniv.id
                JOIN 
                    institucion_lectivo il ON iniv.institucion_lectivo_id = il.id
                WHERE 
                    md.estado = '1'
                GROUP BY 
                    il.nombre, 
                    iniv.nombre, 
                    ig.nombre
                ORDER BY 
                    il.nombre ASC, 
                    iniv.nombre ASC, 
                    ig.nombre ASC";
        return ejecutarConsulta($sql);
    }
}
?>
