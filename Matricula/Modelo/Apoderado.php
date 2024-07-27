<?php
require_once("../../database.php");

class Apoderado
{
    public function __construct()
    {
    }

    // Método para guardar un nuevo apoderado
    public function guardar($usuario, $contraseña, $dni, $nombreyapellido, $telefono, $observaciones)
    {
        $sql = "INSERT INTO apoderado (usuario, contraseña, dni, nombreyapellido, telefono, observaciones, estado) 
                VALUES ('$usuario', '$contraseña', '$dni', '$nombreyapellido', '$telefono', '$observaciones', '1')";
        return ejecutarConsulta($sql);
    }

    // Método para editar un apoderado existente
    public function editar($id, $usuario, $contraseña, $dni, $nombreyapellido, $telefono, $observaciones)
    {
        $sql = "UPDATE apoderado 
                SET usuario='$usuario', contraseña='$contraseña', dni='$dni', nombreyapellido='$nombreyapellido', telefono='$telefono', observaciones='$observaciones' 
                WHERE id='$id'";
        return ejecutarConsulta($sql);
    }

    // Método para mostrar los detalles de un apoderado específico
    public function mostrar($id)
    {
        $sql = "SELECT * FROM apoderado WHERE id='$id'";
        return ejecutarConsultaSimpleFila($sql);
    }

    // Método para listar todos los apoderados
    public function listar()
    {
        $sql = "SELECT * FROM apoderado";
        return ejecutarConsulta($sql);
    }

    // Método para desactivar un apoderado
    public function desactivar($id)
    {
        $sql = "UPDATE apoderado SET estado='0' WHERE id='$id'";
        return ejecutarConsulta($sql);
    }

    // Método para activar un apoderado
    public function activar($id)
    {
        $sql = "UPDATE apoderado SET estado='1' WHERE id='$id'";
        return ejecutarConsulta($sql);
    }
}
?>
