<?php
session_start();
include_once("../Modelo/Consulta.php");

$consulta = new Consulta();

switch ($_GET["op"]) {
    case 'listar':
        $rspta = $consulta->listar();
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "lectivo" => $reg->lectivo,
                "nivel" => $reg->nivel,
                "grado" => $reg->grado,
                "alumno" => $reg->alumno
            );
        }

        echo json_encode($data);
        break;

    case 'listarApoderadosAlumnos':
        $rspta = $consulta->listarApoderadosAlumnos();
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "apoderado" => $reg->apoderado,
                "telefono" => $reg->telefono,
                "alumno" => $reg->alumno
            );
        }

        echo json_encode($data);
        break;

    case 'listarAlumnosPorMes':
        $rspta = $consulta->listarAlumnosPorMes();
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "mes" => $reg->mes,
                "dia" => $reg->dia,
                "alumno" => $reg->alumno
            );
        }

        echo json_encode($data);
        break;

    case 'listarMatriculados':
        $rspta = $consulta->listarMatriculados();
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "lectivo" => $reg->lectivo,
                "nivel" => $reg->nivel,
                "grado" => $reg->grado,
                "cantidad_matriculados" => $reg->cantidad_matriculados
            );
        }

        echo json_encode($data);
        break;
}
?>
