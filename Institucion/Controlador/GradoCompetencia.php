<?php
include_once("../Modelo/GradoCompetencia.php");

$gradoCompetencia = new GradoCompetencia();
$id = isset($_POST["id"]) ? limpiarcadena($_POST["id"]) : "";
$grado_id = isset($_POST["grado_id"]) ? limpiarcadena($_POST["grado_id"]) : "";
$competencia_id = isset($_POST["competencia_id"]) ? limpiarcadena($_POST["competencia_id"]) : "";

switch ($_GET["op"]) {
    case 'guardaryeditar':
        if (!empty($id)) {
            // Actualizar un registro existente
            $rspta = $gradoCompetencia->editar($id, $grado_id, $competencia_id);
            echo $rspta ? "Relación grado-competencia actualizada correctamente" : "No se pudo actualizar la relación grado-competencia";
        } else {
            // Crear nuevos registros
            $datos = isset($_POST["datos"]) ? json_decode($_POST["datos"], true) : [];
            if (!empty($datos)) {
                $rspta = $gradoCompetencia->guardarMultiple($datos);
                echo $rspta === true ? "Relaciones grado-competencia registradas correctamente" : $rspta;
            } else {
                echo "No se recibieron datos para guardar";
            }
        }
        break;

    case 'listar_grados_disponibles':
        $rspta = $gradoCompetencia->listarGradosDisponibles();
        while ($reg = $rspta->fetch_object()) {
            echo '<option value=' . $reg->id . '>' . $reg->nombre . '</option>';
        }
        break;

    case 'listar_competencias_disponibles':
        $rspta = $gradoCompetencia->listarCompetenciasDisponibles();
        while ($reg = $rspta->fetch_object()) {
            echo '<option value=' . $reg->id . '>' . $reg->nombre . '</option>';
        }
        break;

    case 'desactivar':
        $rspta = $gradoCompetencia->desactivar($id);
        echo $rspta ? "Relación grado-competencia desactivada correctamente" : "No se pudo desactivar la relación grado-competencia";
        break;

    case 'activar':
        $rspta = $gradoCompetencia->activar($id);
        echo $rspta ? "Relación grado-competencia activada correctamente" : "No se pudo activar la relación grado-competencia";
        break;

    case 'mostrar':
        $rspta = $gradoCompetencia->mostrar($id);
        // Codificar el resultado usando JSON
        echo json_encode($rspta);
        break;

    case 'listar':
        $rspta = $gradoCompetencia->listar();
        // Vamos a declarar un array
        $data = Array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => $reg->institucion_lectivo.' / '.$reg->institucion_nivel.' / '.$reg->grado.' - '.$reg->area_curricular,
                "1" => $reg->competencia,
                "2" => ($reg->estado) ?
                    '<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg->id . ')">EDITAR</button> <button class="btn btn-danger btn-sm" onclick="desactivar(' . $reg->id . ')">DESACTIVAR</button>'
                    :
                    '<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg->id . ')">EDITAR</button> <button class="btn btn-primary btn-sm" onclick="activar(' . $reg->id . ')">ACTIVAR</button>'
            );
        }
        $results = array(
            "sEcho" => 1, // Información para el datatables
            "iTotalRecords" => count($data), // Enviamos el total de registros al datatable
            "iTotalDisplayRecords" => count($data), // Enviamos el total de registros a visualizar
            "aaData" => $data
        );
        echo json_encode($results);
        break;

    case 'listar_grados_activos':
        $rspta = $gradoCompetencia->listarGradosActivos();
        while ($reg = $rspta->fetch_object()) {
            echo '<option value=' . $reg->id . '>' . $reg->nombre . '</option>';
        }
        break;

    case 'listar_competencias_activas':
        $rspta = $gradoCompetencia->listarCompetenciasActivas();
        while ($reg = $rspta->fetch_object()) {
            echo '<option value=' . $reg->id . '>' . $reg->nombre . '</option>';
        }
        break;
}
?>
