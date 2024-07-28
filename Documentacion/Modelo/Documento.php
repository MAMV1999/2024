<?php
require_once("../../database.php");

class Documento
{
    public function __construct()
    {
    }

    public function guardar($detalle, $nombre, $obligatorio, $observaciones)
    {
        $sql = "INSERT INTO documento (detalle, nombre, obligatorio, observaciones, estado) VALUES ('$detalle', '$nombre', '$obligatorio', '$observaciones', '1')";
        return ejecutarConsulta($sql);
    }

    public function listar()
    {
        $sql = "SELECT * FROM documento WHERE estado='1'";
        return ejecutarConsulta($sql);
    }

    public function desactivar($id)
    {
        $sql = "UPDATE documento SET estado='0' WHERE id='$id'";
        return ejecutarConsulta($sql);
    }

    public function activar($id)
    {
        $sql = "UPDATE documento SET estado='1' WHERE id='$id'";
        return ejecutarConsulta($sql);
    }

    public function mostrar($id)
    {
        $sql = "SELECT * FROM documento WHERE id='$id'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function editar($id, $detalle, $nombre, $obligatorio, $observaciones)
    {
        $sql = "UPDATE documento SET detalle='$detalle', nombre='$nombre', obligatorio='$obligatorio', observaciones='$observaciones' WHERE id='$id'";
        return ejecutarConsulta($sql);
    }
}
?>
