<?php
require_once("../../database.php");

class Matricula
{
    public function __construct()
    {
    }

    // Método para guardar una nueva matrícula
    public function guardar($detalle, $institucion_grado_id, $trabajador_id, $preciomatricula, $preciomensualidad, $preciootros, $aforo, $observaciones, $trabajador_sesion_id)
    {
        $sql = "INSERT INTO matricula (detalle, institucion_grado_id, trabajador_id, preciomatricula, preciomensualidad, preciootros, aforo, observaciones, trabajador_sesion_id, estado) 
                VALUES ('$detalle', '$institucion_grado_id', '$trabajador_id', '$preciomatricula', '$preciomensualidad', '$preciootros', '$aforo', '$observaciones', '$trabajador_sesion_id', '1')";
        return ejecutarConsulta($sql);
    }

    // Método para editar una matrícula existente
    public function editar($id, $detalle, $institucion_grado_id, $trabajador_id, $preciomatricula, $preciomensualidad, $preciootros, $aforo, $observaciones, $trabajador_sesion_id)
    {
        $sql = "UPDATE matricula 
                SET detalle='$detalle', institucion_grado_id='$institucion_grado_id', trabajador_id='$trabajador_id', preciomatricula='$preciomatricula', preciomensualidad='$preciomensualidad', preciootros='$preciootros', aforo='$aforo', observaciones='$observaciones', trabajador_sesion_id='$trabajador_sesion_id' 
                WHERE id='$id'";
        return ejecutarConsulta($sql);
    }

    // Método para mostrar los detalles de una matrícula específica
    public function mostrar($id)
    {
        $sql = "SELECT * FROM matricula WHERE id='$id'";
        return ejecutarConsultaSimpleFila($sql);
    }

    // Método para listar todas las matrículas
    public function listar()
    {
        $sql = "SELECT m.id, m.detalle, CONCAT(il.nombre, ' - ', iniv.nombre, ' - ', ig.nombre) AS institucion_grado, t.nombre_apellido AS trabajador, m.preciomatricula, m.preciomensualidad, m.preciootros, m.aforo, m.estado
                FROM matricula m
                LEFT JOIN institucion_grado ig ON m.institucion_grado_id = ig.id
                LEFT JOIN institucion_nivel iniv ON ig.institucion_nivel_id = iniv.id
                LEFT JOIN institucion_lectivo il ON iniv.institucion_lectivo_id = il.id
                LEFT JOIN trabajador t ON m.trabajador_id = t.id";
        return ejecutarConsulta($sql);
    }

    // Método para desactivar una matrícula
    public function desactivar($id)
    {
        $sql = "UPDATE matricula SET estado='0' WHERE id='$id'";
        return ejecutarConsulta($sql);
    }

    // Método para activar una matrícula
    public function activar($id)
    {
        $sql = "UPDATE matricula SET estado='1' WHERE id='$id'";
        return ejecutarConsulta($sql);
    }

    // Método para listar los grados de instituciones activos que no están asociados con una matrícula activa
    public function listarGradosDisponibles()
    {
        $sql = "SELECT ig.id, CONCAT(il.nombre, ' - ', iniv.nombre, ' - ', ig.nombre) AS nombre
                FROM institucion_grado ig
                JOIN institucion_nivel iniv ON ig.institucion_nivel_id = iniv.id
                JOIN institucion_lectivo il ON iniv.institucion_lectivo_id = il.id
                WHERE ig.estado = '1' AND iniv.estado = '1' AND il.estado = '1'
                AND ig.id NOT IN (SELECT institucion_grado_id FROM matricula WHERE estado = '1')";
        return ejecutarConsulta($sql);
    }

    // Método para listar los trabajadores activos
    public function listarTrabajadoresActivos()
    {
        $sql = "SELECT id, CONCAT(nombre_apellido, ' - ', cargo) AS nombre_cargo FROM trabajador WHERE estado='1'";
        return ejecutarConsulta($sql);
    }
}
?>
