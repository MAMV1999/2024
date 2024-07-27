<?php
require_once("../../database.php");

class MatriculaPago
{
    public function __construct()
    {
    }

    public function guardar($matricula_detalle_id, $matricula_metodo_id, $numeracion, $fecha, $monto, $observaciones)
    {
        $sql = "INSERT INTO matricula_pago (matricula_detalle_id, matricula_metodo_id, numeracion, fecha, monto, observaciones, fechacreado, estado)
                VALUES ('$matricula_detalle_id', '$matricula_metodo_id', '$numeracion', '$fecha', '$monto', '$observaciones', CURRENT_TIMESTAMP, '1')";
        return ejecutarConsulta($sql);
    }

    public function editar($id, $matricula_detalle_id, $matricula_metodo_id, $numeracion, $fecha, $monto, $observaciones)
    {
        $sql = "UPDATE matricula_pago
                SET matricula_detalle_id='$matricula_detalle_id', matricula_metodo_id='$matricula_metodo_id', numeracion='$numeracion', fecha='$fecha', monto='$monto', observaciones='$observaciones'
                WHERE id='$id'";
        return ejecutarConsulta($sql);
    }

    public function mostrar($id)
    {
        $sql = "SELECT * FROM matricula_pago WHERE id='$id'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function activar($id)
    {
        $sql = "UPDATE matricula_pago SET estado='1' WHERE id='$id'";
        return ejecutarConsulta($sql);
    }

    public function desactivar($id)
    {
        $sql = "UPDATE matricula_pago SET estado='0' WHERE id='$id'";
        return ejecutarConsulta($sql);
    }

    public function listar()
    {
        $sql = "SELECT 
        mp.id, 
        mp.numeracion, 
        a.nombreyapellido AS apoderado, 
        al.nombreyapellido AS alumno, 
        il.nombre AS lectivo, 
        iniv.nombre AS nivel, 
        ig.nombre AS grado, 
        mr.nombre AS razon, 
        DATE_FORMAT(mp.fecha, '%d/%m/%Y') AS fecha, 
        mp.monto, 
        mm.nombre AS metodo_pago
    FROM 
        matricula_pago mp
    JOIN 
        matricula_detalle md ON mp.matricula_detalle_id = md.id
    JOIN 
        apoderado a ON md.apoderado_id = a.id
    JOIN 
        alumno al ON md.alumno_id = al.id
    JOIN 
        institucion_grado ig ON md.matricula_id = ig.id
    JOIN 
        institucion_nivel iniv ON ig.institucion_nivel_id = iniv.id
    JOIN 
        institucion_lectivo il ON iniv.institucion_lectivo_id = il.id
    JOIN 
        matricula_razon mr ON md.matricula_razon_id = mr.id
    JOIN 
        matricula_metodo mm ON mp.matricula_metodo_id = mm.id
    WHERE 
        mp.estado = '1'";
        return ejecutarConsulta($sql);
    }

    public function listarMatriculaDetalles()
    {
        $sql = "SELECT md.id, il.nombre as lectivo, iniv.nombre as nivel, ig.nombre as grado, a.nombreyapellido as apoderado, al.nombreyapellido as alumno, mr.nombre as razon
                FROM matricula_detalle md
                JOIN matricula m ON md.matricula_id = m.id
                JOIN institucion_grado ig ON m.institucion_grado_id = ig.id
                JOIN institucion_nivel iniv ON ig.institucion_nivel_id = iniv.id
                JOIN institucion_lectivo il ON iniv.institucion_lectivo_id = il.id
                JOIN apoderado a ON md.apoderado_id = a.id
                JOIN alumno al ON md.alumno_id = al.id
                JOIN matricula_razon mr ON md.matricula_razon_id = mr.id
                WHERE md.estado='1'";
        return ejecutarConsulta($sql);
    }

    public function listarMatriculaDetallesPorId($id)
    {
        $sql = "SELECT md.id, il.nombre as lectivo, iniv.nombre as nivel, ig.nombre as grado, a.nombreyapellido as apoderado, al.nombreyapellido as alumno, mr.nombre as razon
                FROM matricula_detalle md
                JOIN matricula m ON md.matricula_id = m.id
                JOIN institucion_grado ig ON m.institucion_grado_id = ig.id
                JOIN institucion_nivel iniv ON ig.institucion_nivel_id = iniv.id
                JOIN institucion_lectivo il ON iniv.institucion_lectivo_id = il.id
                JOIN apoderado a ON md.apoderado_id = a.id
                JOIN alumno al ON md.alumno_id = al.id
                JOIN matricula_razon mr ON md.matricula_razon_id = mr.id
                WHERE md.id='$id' AND md.estado='1'";
        return ejecutarConsulta($sql);
    }

    public function listarMetodosPago()
    {
        $sql = "SELECT * FROM matricula_metodo WHERE estado='1'";
        return ejecutarConsulta($sql);
    }
}
?>
