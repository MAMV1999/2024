<?php
require_once("../../database.php");

class ReporteMatricula
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

    public function matriculadospagos()
    {
        $sql = "SELECT 
                md.id,
                il.nombre AS institucion_lectivo,
                niv.nombre AS institucion_nivel,
                ig.nombre AS institucion_grado,
                mr.nombre AS matricula_razon,
                md.apoderado_id,
                a.dni AS apoderado_dni,
                a.nombreyapellido AS apoderado,
                a.telefono AS apoderado_telefono,
                md.alumno_id,
                al.dni AS alumno_dni,
                al.nombreyapellido AS alumno,
                DATE_FORMAT(al.nacimiento, '%d/%m/%Y') AS alumno_nacimiento,
                FLOOR(DATEDIFF(CURDATE(), al.nacimiento) / 365.25) AS alumno_edad,
                mp.numeracion,
                DATE_FORMAT(mp.fecha, '%d/%m/%Y') AS pago_fecha,
                mp.monto AS pago_monto,
                mm.nombre AS metodo_pago,
                mp.observaciones AS pago_observaciones
            FROM 
                matricula_detalle md
            JOIN 
                matricula_razon mr ON md.matricula_razon_id = mr.id
            JOIN 
                matricula m ON md.matricula_id = m.id
            JOIN 
                institucion_grado ig ON m.institucion_grado_id = ig.id
            JOIN 
                institucion_nivel niv ON ig.institucion_nivel_id = niv.id
            JOIN 
                institucion_lectivo il ON niv.institucion_lectivo_id = il.id
            JOIN 
                apoderado a ON md.apoderado_id = a.id
            JOIN 
                alumno al ON md.alumno_id = al.id
            JOIN 
                trabajador t ON md.trabajador_sesion_id = t.id
            JOIN 
                matricula_pago mp ON md.id = mp.matricula_detalle_id
            JOIN 
                matricula_metodo mm ON mp.matricula_metodo_id = mm.id
            ORDER BY 
                mp.fecha ASC,
                mm.nombre ASC,
                mp.numeracion ASC";
        return ejecutarConsulta($sql);
    }

    public function matriculadospagos_apoderado()
    {
        $sql = "SELECT 
                md.id,
                il.nombre AS institucion_lectivo,
                niv.nombre AS institucion_nivel,
                ig.nombre AS institucion_grado,
                mr.nombre AS matricula_razon,
                md.apoderado_id,
                a.dni AS apoderado_dni,
                a.nombreyapellido AS apoderado,
                a.telefono AS apoderado_telefono,
                md.alumno_id,
                al.dni AS alumno_dni,
                al.nombreyapellido AS alumno,
                DATE_FORMAT(al.nacimiento, '%d/%m/%Y') AS alumno_nacimiento,
                FLOOR(DATEDIFF(CURDATE(), al.nacimiento) / 365.25) AS alumno_edad,
                mp.numeracion,
                DATE_FORMAT(mp.fecha, '%d/%m/%Y') AS pago_fecha,
                mp.monto AS pago_monto,
                mm.nombre AS metodo_pago,
                mp.observaciones AS pago_observaciones
            FROM 
                matricula_detalle md
            JOIN 
                matricula_razon mr ON md.matricula_razon_id = mr.id
            JOIN 
                matricula m ON md.matricula_id = m.id
            JOIN 
                institucion_grado ig ON m.institucion_grado_id = ig.id
            JOIN 
                institucion_nivel niv ON ig.institucion_nivel_id = niv.id
            JOIN 
                institucion_lectivo il ON niv.institucion_lectivo_id = il.id
            JOIN 
                apoderado a ON md.apoderado_id = a.id
            JOIN 
                alumno al ON md.alumno_id = al.id
            JOIN 
                trabajador t ON md.trabajador_sesion_id = t.id
            JOIN 
                matricula_pago mp ON md.id = mp.matricula_detalle_id
            JOIN 
                matricula_metodo mm ON mp.matricula_metodo_id = mm.id
            ORDER BY 
            a.nombreyapellido ASC";
        return ejecutarConsulta($sql);
    }

    public function matriculadospagos_grado()
    {
        $sql = "SELECT 
                md.id,
                il.nombre AS institucion_lectivo,
                niv.nombre AS institucion_nivel,
                ig.nombre AS institucion_grado,
                mr.nombre AS matricula_razon,
                md.apoderado_id,
                a.dni AS apoderado_dni,
                a.nombreyapellido AS apoderado,
                a.telefono AS apoderado_telefono,
                md.alumno_id,
                al.dni AS alumno_dni,
                al.nombreyapellido AS alumno,
                DATE_FORMAT(al.nacimiento, '%d/%m/%Y') AS alumno_nacimiento,
                FLOOR(DATEDIFF(CURDATE(), al.nacimiento) / 365.25) AS alumno_edad,
                mp.numeracion,
                DATE_FORMAT(mp.fecha, '%d/%m/%Y') AS pago_fecha,
                mp.monto AS pago_monto,
                mm.nombre AS metodo_pago,
                mp.observaciones AS pago_observaciones
            FROM 
                matricula_detalle md
            JOIN 
                matricula_razon mr ON md.matricula_razon_id = mr.id
            JOIN 
                matricula m ON md.matricula_id = m.id
            JOIN 
                institucion_grado ig ON m.institucion_grado_id = ig.id
            JOIN 
                institucion_nivel niv ON ig.institucion_nivel_id = niv.id
            JOIN 
                institucion_lectivo il ON niv.institucion_lectivo_id = il.id
            JOIN 
                apoderado a ON md.apoderado_id = a.id
            JOIN 
                alumno al ON md.alumno_id = al.id
            JOIN 
                trabajador t ON md.trabajador_sesion_id = t.id
            JOIN 
                matricula_pago mp ON md.id = mp.matricula_detalle_id
            JOIN 
                matricula_metodo mm ON mp.matricula_metodo_id = mm.id
            ORDER BY 
            il.nombre ASC, niv.nombre ASC, ig.nombre ASC, mp.fecha ASC, mm.nombre ASC";
        return ejecutarConsulta($sql);
    }
}
