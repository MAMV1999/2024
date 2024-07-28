<?php
require_once("../../database.php");

class DocumentoDetalle
{
    public function __construct()
    {
    }

    public function listar()
    {
        $sql = "SELECT md.id, l.nombre AS lectivo, n.nombre AS nivel, g.nombre AS grado, a.nombreyapellido AS alumno, ap.nombreyapellido AS apoderado
            FROM matricula_detalle md
            INNER JOIN matricula m ON md.matricula_id = m.id
            INNER JOIN institucion_grado g ON m.institucion_grado_id = g.id
            INNER JOIN institucion_nivel n ON g.institucion_nivel_id = n.id
            INNER JOIN institucion_lectivo l ON n.institucion_lectivo_id = l.id
            INNER JOIN alumno a ON md.alumno_id = a.id
            INNER JOIN apoderado ap ON md.apoderado_id = ap.id
            WHERE md.estado = '1'";
        return ejecutarConsulta($sql);
    }

    public function mostrar($id)
    {
        $sql = "SELECT * FROM documento_detalle WHERE matricula_detalle_id = '$id'";
        return ejecutarConsulta($sql);
    }

    public function listarDocumentos()
    {
        $sql = "SELECT * FROM documento WHERE estado = '1'";
        return ejecutarConsulta($sql);
    }

    public function obtenerInformacionMatricula($id)
    {
        $sql = "SELECT md.id, l.nombre AS lectivo, n.nombre AS nivel, g.nombre AS grado, a.nombreyapellido AS alumno, ap.nombreyapellido AS apoderado
            FROM matricula_detalle md
            INNER JOIN matricula m ON md.matricula_id = m.id
            INNER JOIN institucion_grado g ON m.institucion_grado_id = g.id
            INNER JOIN institucion_nivel n ON g.institucion_nivel_id = n.id
            INNER JOIN institucion_lectivo l ON n.institucion_lectivo_id = l.id
            INNER JOIN alumno a ON md.alumno_id = a.id
            INNER JOIN apoderado ap ON md.apoderado_id = ap.id
            WHERE md.id = '$id'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function guardar($matricula_detalle_id, $documentos)
    {
        foreach ($documentos as $documento_id => $detalle) {
            $entregado = $detalle['entregado'];
            $observaciones = $detalle['observaciones'];

            // Verificar si el registro ya existe
            $sql_verificar = "SELECT COUNT(*) as count FROM documento_detalle WHERE matricula_detalle_id = '$matricula_detalle_id' AND documento_id = '$documento_id'";
            $resultado = ejecutarConsultaSimpleFila($sql_verificar);

            if ($resultado['count'] > 0) {
                // Actualizar el registro existente
                $sql = "UPDATE documento_detalle SET entregado='$entregado', observaciones='$observaciones' WHERE matricula_detalle_id='$matricula_detalle_id' AND documento_id='$documento_id'";
            } else {
                // Insertar un nuevo registro
                $sql = "INSERT INTO documento_detalle (matricula_detalle_id, documento_id, entregado, observaciones)
                        VALUES ('$matricula_detalle_id', '$documento_id', '$entregado', '$observaciones')";
            }
            ejecutarConsulta($sql);
        }
        return true;
    }
}
?>