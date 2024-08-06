<?php
include_once("../Modelo/Competencia.php");

$competencia = new Competencia();
$id = isset($_POST["id"]) ? limpiarcadena($_POST["id"]) : "";
$area_curricular_id = isset($_POST["area_curricular_id"]) ? limpiarcadena($_POST["area_curricular_id"]) : "";
$nombre = isset($_POST["nombre"]) ? limpiarcadena($_POST["nombre"]) : "";

switch ($_GET["op"]) {
    case 'guardaryeditar':
        if (!empty($id)) {
            // Actualizar un registro existente
            $rspta = $competencia->editar($id, $area_curricular_id, $nombre);
            echo $rspta ? "Competencia actualizada correctamente" : "No se pudo actualizar la competencia";
        } else {
            // Crear nuevos registros
            $datos = isset($_POST["datos"]) ? json_decode($_POST["datos"], true) : [];
            if (!empty($datos)) {
                $rspta = $competencia->guardarMultiple($datos);
                echo $rspta ? "Competencias registradas correctamente" : "No se pudieron registrar las competencias";
            } else {
                echo "No se recibieron datos para guardar";
            }
        }
        break;

    case 'desactivar':
        $rspta = $competencia->desactivar($id);
        echo $rspta ? "Competencia desactivada correctamente" : "No se pudo desactivar la competencia";
        break;

    case 'activar':
        $rspta = $competencia->activar($id);
        echo $rspta ? "Competencia activada correctamente" : "No se pudo activar la competencia";
        break;

    case 'mostrar':
        $rspta = $competencia->mostrar($id);
        // Codificar el resultado usando JSON
        echo json_encode($rspta);
        break;

    case 'listar':
        $rspta = $competencia->listar();
        // Vamos a declarar un array
        $data = Array();

        while ($reg = $rspta->fetch_object()) {
            $area_curricular = (strlen($reg->area_curricular) > 40) ? substr($reg->area_curricular, 0, 40) . '...' : $reg->area_curricular;
            $nombre = (strlen($reg->nombre) > 40) ? substr($reg->nombre, 0, 40) . '...' : $reg->nombre;
            $data[] = array(
                "0" => $reg->institucion_lectivo.' - '.$reg->institucion_nivel,
                "1" => $area_curricular,
                "2" => $nombre,
                "3" => ($reg->estado) ?
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

    case 'listar_areas_curriculares_activas':
        $rspta = $competencia->listarAreasCurricularesActivas();
        while ($reg = $rspta->fetch_object()) {
            echo '<option value=' . $reg->id . '>' . $reg->nombre . '</option>';
        }
        break;
}
?>
