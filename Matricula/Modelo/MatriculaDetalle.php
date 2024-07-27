<?php
require_once("../../database.php");

class MatriculaDetalle
{
    public function __construct()
    {
    }

    public function guardar($apoderado_id, $apoderado_dni, $apoderado_nombreyapellido, $apoderado_telefono, $apoderado_observaciones, $alumno_dni, $alumno_nombreyapellido, $alumno_nacimiento, $alumno_sexo, $alumno_observaciones, $detalle, $matricula_id, $matricula_razon_id, $matricula_observaciones, $trabajador_sesion_id, $pago_numeracion, $pago_fecha, $pago_monto, $metodo_pago_id, $pago_observaciones)
    {
        // Verificar si el apoderado existe
        if (empty($apoderado_id)) {
            // Guardar apoderado
            $sql_apoderado = "INSERT INTO apoderado (usuario, contraseña, dni, nombreyapellido, telefono, observaciones, estado) VALUES ('$apoderado_dni', '$apoderado_dni', '$apoderado_dni', '$apoderado_nombreyapellido', '$apoderado_telefono', '$apoderado_observaciones', '1')";
            $apoderado_id = ejecutarConsulta_retornarID($sql_apoderado);
        }

        if ($apoderado_id) {
            // Guardar alumno
            $sql_alumno = "INSERT INTO alumno (usuario, contraseña, apoderado_id, dni, nombreyapellido, nacimiento, sexo, observaciones, estado) VALUES ('$alumno_dni', '$alumno_dni', '$apoderado_id', '$alumno_dni', '$alumno_nombreyapellido', '$alumno_nacimiento', '$alumno_sexo', '$alumno_observaciones', '1')";
            $alumno_id = ejecutarConsulta_retornarID($sql_alumno);

            if ($alumno_id) {
                // Guardar matricula_detalle
                $sql_matricula_detalle = "INSERT INTO matricula_detalle (detalle, matricula_id, apoderado_id, alumno_id, matricula_razon_id, observaciones, fechacreado, trabajador_sesion_id, estado) VALUES ('$detalle', '$matricula_id', '$apoderado_id', '$alumno_id', '$matricula_razon_id', '$matricula_observaciones', CURRENT_TIMESTAMP, '$trabajador_sesion_id', '1')";
                $matricula_detalle_id = ejecutarConsulta_retornarID($sql_matricula_detalle);

                if ($matricula_detalle_id) {
                    // Guardar matricula_pago
                    $sql_matricula_pago = "INSERT INTO matricula_pago (matricula_detalle_id, numeracion, fecha, monto, matricula_metodo_id, observaciones, fechacreado, estado) VALUES ('$matricula_detalle_id', '$pago_numeracion', '$pago_fecha', '$pago_monto', '$metodo_pago_id', '$pago_observaciones', CURRENT_TIMESTAMP, '1')";
                    $pago_id = ejecutarConsulta_retornarID($sql_matricula_pago);

                    if ($pago_id) {
                        return true;
                    } else {
                        // Eliminar matricula_detalle si falla el pago
                        $sql_eliminar_matricula_detalle = "DELETE FROM matricula_detalle WHERE id='$matricula_detalle_id'";
                        ejecutarConsulta($sql_eliminar_matricula_detalle);
                    }
                } else {
                    // Eliminar alumno si falla la matricula_detalle
                    $sql_eliminar_alumno = "DELETE FROM alumno WHERE id='$alumno_id'";
                    ejecutarConsulta($sql_eliminar_alumno);
                }
            } else {
                // Eliminar apoderado si falla el alumno
                if (empty($apoderado_id)) {
                    $sql_eliminar_apoderado = "DELETE FROM apoderado WHERE id='$apoderado_id'";
                    ejecutarConsulta($sql_eliminar_apoderado);
                }
            }
        }

        return false;
    }

    public function listar()
    {
        $sql = "SELECT 
                md.id, 
                l.nombre AS lectivo, 
                n.nombre AS nivel, 
                g.nombre AS grado, 
                a.nombreyapellido AS alumno, 
                ap.nombreyapellido AS apoderado, 
                mp.numeracion
            FROM matricula_detalle md
            INNER JOIN matricula m ON md.matricula_id = m.id
            INNER JOIN institucion_grado g ON m.institucion_grado_id = g.id
            INNER JOIN institucion_nivel n ON g.institucion_nivel_id = n.id
            INNER JOIN institucion_lectivo l ON n.institucion_lectivo_id = l.id
            INNER JOIN alumno a ON md.alumno_id = a.id
            INNER JOIN apoderado ap ON md.apoderado_id = ap.id
            INNER JOIN matricula_pago mp ON md.id = mp.matricula_detalle_id
            WHERE md.estado = '1' AND m.estado = '1' AND g.estado = '1' AND n.estado = '1' AND l.estado = '1' AND a.estado = '1' AND ap.estado = '1' AND mp.estado = '1'";
        return ejecutarConsulta($sql);
    }

    public function listarMetodosPago()
    {
        $sql = "SELECT * FROM matricula_metodo WHERE estado = '1'";
        return ejecutarConsulta($sql);
    }

    public function listarRazones()
    {
        $sql = "SELECT * FROM matricula_razon WHERE estado = '1'";
        return ejecutarConsulta($sql);
    }

    public function listarMatriculas()
    {
        $sql = "SELECT m.id, l.nombre AS lectivo, n.nombre AS nivel, g.nombre AS grado, m.aforo, m.preciomatricula, m.preciomensualidad, COUNT(md.id) AS matriculados
                FROM matricula m
                LEFT JOIN matricula_detalle md ON m.id = md.matricula_id AND md.estado = '1'
                INNER JOIN institucion_grado g ON m.institucion_grado_id = g.id
                INNER JOIN institucion_nivel n ON g.institucion_nivel_id = n.id
                INNER JOIN institucion_lectivo l ON n.institucion_lectivo_id = l.id
                WHERE m.estado = '1'
                GROUP BY m.id, l.nombre, n.nombre, g.nombre, m.aforo, m.preciomatricula, m.preciomensualidad
                HAVING COUNT(md.id) < m.aforo";
        return ejecutarConsulta($sql);
    }

    public function getNextPagoNumeracion()
    {
        $sql = "SELECT LPAD(IFNULL(MAX(CAST(numeracion AS UNSIGNED)) + 1, 1), 6, '0') AS numeracion FROM matricula_pago";
        $result = ejecutarConsultaSimpleFila($sql);
        return $result ? $result['numeracion'] : '000001';
    }

    public function buscarApoderado($dni)
    {
        $sql = "SELECT * FROM apoderado WHERE dni = '$dni' AND estado = '1'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function verificarCodigoDirector($codigo)
    {
        $sql = "SELECT id FROM trabajador WHERE dni = '$codigo' AND cargo = 'DIRECTOR' AND estado = '1'";
        $result = ejecutarConsultaSimpleFila($sql);
        return !empty($result);
    }

    public function desactivar($id)
    {
        $sql_matricula_detalle = "UPDATE matricula_detalle SET estado = '0' WHERE id = '$id'";
        $sql_matricula_pago = "UPDATE matricula_pago SET estado = '0' WHERE matricula_detalle_id = '$id'";
        ejecutarConsulta($sql_matricula_detalle);
        ejecutarConsulta($sql_matricula_pago);

        // Desactivar apoderado y alumno si no tienen más registros activos
        $sql_apoderado = "UPDATE apoderado SET estado = '0' WHERE id = (SELECT apoderado_id FROM matricula_detalle WHERE id = '$id' AND NOT EXISTS (SELECT 1 FROM matricula_detalle WHERE apoderado_id = apoderado.id AND estado = '1'))";
        $sql_alumno = "UPDATE alumno SET estado = '0' WHERE id = (SELECT alumno_id FROM matricula_detalle WHERE id = '$id' AND NOT EXISTS (SELECT 1 FROM matricula_detalle WHERE alumno_id = alumno.id AND estado = '1'))";
        ejecutarConsulta($sql_apoderado);
        ejecutarConsulta($sql_alumno);

        return true;
    }
}
