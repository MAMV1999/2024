<?php
require_once("../../database.php");

class ReportePersonal
{
    public function __construct()
    {
    }

    public function ReciboDePagoMatricula($id)
    {
        $sql = "SELECT 
                    md.id,
                    l.nombre AS lectivo, 
                    n.nombre AS nivel, 
                    g.nombre AS grado, 
                    a.nombreyapellido AS alumno, 
                    a.dni AS alumno_dni,
                    DATE_FORMAT(a.nacimiento, '%d/%m/%Y') AS alumno_nacimiento,
                    ap.nombreyapellido AS apoderado,
                    ap.dni AS apoderado_dni,
                    ap.telefono AS apoderado_telefono,
                    r.nombre AS razon,
                    mp.numeracion,
                    DATE_FORMAT(mp.fecha, '%d/%m/%Y') AS fecha,
                    mp.monto,
                    mm.nombre AS metodo,
                    mp.observaciones,
                    t.nombre_apellido AS trabajador
                FROM matricula_detalle md
                INNER JOIN matricula m ON md.matricula_id = m.id
                INNER JOIN institucion_grado g ON m.institucion_grado_id = g.id
                INNER JOIN institucion_nivel n ON g.institucion_nivel_id = n.id
                INNER JOIN institucion_lectivo l ON n.institucion_lectivo_id = l.id
                INNER JOIN alumno a ON md.alumno_id = a.id
                INNER JOIN apoderado ap ON md.apoderado_id = ap.id
                INNER JOIN matricula_razon r ON md.matricula_razon_id = r.id
                INNER JOIN matricula_pago mp ON md.id = mp.matricula_detalle_id
                INNER JOIN matricula_metodo mm ON mp.matricula_metodo_id = mm.id
                INNER JOIN trabajador t ON m.trabajador_id = t.id
                WHERE md.estado = '1' AND md.id = '$id'";
        
        return ejecutarConsulta($sql);
    }
}
?>
