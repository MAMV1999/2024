<?php
require_once("../../database.php");

class Alumno
{
    public function __construct()
    {
    }

    // Método para guardar un nuevo alumno
    public function guardar($usuario, $contraseña, $apoderado_id, $dni, $nombreyapellido, $observaciones)
    {
        $sql = "INSERT INTO alumno (usuario, contraseña, apoderado_id, dni, nombreyapellido, observaciones, estado) 
                VALUES ('$usuario', '$contraseña', '$apoderado_id', '$dni', '$nombreyapellido', '$observaciones', '1')";
        return ejecutarConsulta($sql);
    }

    // Método para editar un alumno existente
    public function editar($id, $usuario, $contraseña, $apoderado_id, $dni, $nombreyapellido, $observaciones)
    {
        $sql = "UPDATE alumno 
                SET usuario='$usuario', contraseña='$contraseña', apoderado_id='$apoderado_id', dni='$dni', nombreyapellido='$nombreyapellido', observaciones='$observaciones' 
                WHERE id='$id'";
        return ejecutarConsulta($sql);
    }

    // Método para mostrar los detalles de un alumno específico
    public function mostrar($id)
    {
        $sql = "SELECT * FROM alumno WHERE id='$id'";
        return ejecutarConsultaSimpleFila($sql);
    }

    // Método para listar todos los alumnos
    public function listar()
    {
        $sql = "SELECT a.id, a.usuario, a.dni, a.nombreyapellido, ap.nombreyapellido AS apoderado, a.estado
                FROM alumno a
                LEFT JOIN apoderado ap ON a.apoderado_id = ap.id";
        return ejecutarConsulta($sql);
    }

    // Método para desactivar un alumno
    public function desactivar($id)
    {
        $sql = "UPDATE alumno SET estado='0' WHERE id='$id'";
        return ejecutarConsulta($sql);
    }

    // Método para activar un alumno
    public function activar($id)
    {
        $sql = "UPDATE alumno SET estado='1' WHERE id='$id'";
        return ejecutarConsulta($sql);
    }

    // Método para listar los apoderados activos
    public function listarApoderadosActivos()
    {
        $sql = "SELECT id, nombreyapellido FROM apoderado WHERE estado='1'";
        return ejecutarConsulta($sql);
    }
}
?>
