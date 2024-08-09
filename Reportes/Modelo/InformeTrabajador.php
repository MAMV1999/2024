<?php
require_once("../../database.php");

class Trabajador
{
    public function __construct()
    {
    }

    public function listarTrabajadorPorId($id)
    {
        $sql = "SELECT 
                    id,
                    usuario,
                    contraseña,
                    dni,
                    nombre_apellido,
                    DATE_FORMAT(nacimiento, '%d/%m/%Y') AS nacimiento,
                    TIMESTAMPDIFF(YEAR, nacimiento, CURDATE()) AS edad,
                    sexo,
                    estado_civil,
                    cargo,
                    UPPER(direccion) AS direccion,
                    telefono,
                    UPPER(correo) AS correo,
                    sueldo,
                    cuenta_bcp,
                    interbancario_bcp,
                    sunat_ruc,
                    sunat_usuario,
                    sunat_contraseña,
                    observaciones,
                    usuariocrea,
                    fechacreado,
                    estado
                FROM trabajador
                WHERE id = '$id'";
        return ejecutarConsulta($sql);
    }
}
?>
