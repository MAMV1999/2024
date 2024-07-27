<?php
require_once("../../database.php");

class Trabajador
{
    public function __construct()
    {
    }

    public function guardar($usuario, $contraseña, $dni, $nombre_apellido, $nacimiento, $sexo, $estado_civil, $cargo, $direccion, $telefono, $correo, $sueldo, $cuenta_bcp, $interbancario_bcp, $sunat_ruc, $sunat_usuario, $sunat_contraseña, $observaciones, $usuariocrea)
    {
        $sql = "INSERT INTO trabajador (usuario, contraseña, dni, nombre_apellido, nacimiento, sexo, estado_civil, cargo, direccion, telefono, correo, sueldo, cuenta_bcp, interbancario_bcp, sunat_ruc, sunat_usuario, sunat_contraseña, observaciones, usuariocrea) 
                VALUES ('$usuario', '$contraseña', '$dni', '$nombre_apellido', '$nacimiento', '$sexo', '$estado_civil', '$cargo', '$direccion', '$telefono', '$correo', '$sueldo', '$cuenta_bcp', '$interbancario_bcp', '$sunat_ruc', '$sunat_usuario', '$sunat_contraseña', '$observaciones', '$usuariocrea')";
        return ejecutarConsulta($sql);
    }

    public function editar($id, $usuario, $contraseña, $dni, $nombre_apellido, $nacimiento, $sexo, $estado_civil, $cargo, $direccion, $telefono, $correo, $sueldo, $cuenta_bcp, $interbancario_bcp, $sunat_ruc, $sunat_usuario, $sunat_contraseña, $observaciones)
    {
        $sql = "UPDATE trabajador SET usuario='$usuario', contraseña='$contraseña', dni='$dni', nombre_apellido='$nombre_apellido', nacimiento='$nacimiento', sexo='$sexo', estado_civil='$estado_civil', cargo='$cargo', direccion='$direccion', telefono='$telefono', correo='$correo', sueldo='$sueldo', cuenta_bcp='$cuenta_bcp', interbancario_bcp='$interbancario_bcp', sunat_ruc='$sunat_ruc', sunat_usuario='$sunat_usuario', sunat_contraseña='$sunat_contraseña', observaciones='$observaciones' WHERE id='$id'";
        return ejecutarConsulta($sql);
    }

    public function mostrar($id)
    {
        $sql = "SELECT * FROM trabajador WHERE id='$id'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function listar()
    {
        $sql = "SELECT * FROM trabajador";
        return ejecutarConsulta($sql);
    }

    public function desactivar($id)
    {
        $sql = "UPDATE trabajador SET estado='0' WHERE id='$id'";
        return ejecutarConsulta($sql);
    }

    public function activar($id)
    {
        $sql = "UPDATE trabajador SET estado='1' WHERE id='$id'";
        return ejecutarConsulta($sql);
    }
}
?>
