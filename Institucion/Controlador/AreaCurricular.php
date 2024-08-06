<?php
include_once("../Modelo/AreaCurricular.php");

$areaCurricular = new AreaCurricular();
$id = isset($_POST["id"]) ? limpiarcadena($_POST["id"]) : "";
$nombre = isset($_POST["nombre"]) ? limpiarcadena($_POST["nombre"]) : "";
$institucion_nivel_id = isset($_POST["institucion_nivel_id"]) ? limpiarcadena($_POST["institucion_nivel_id"]) : "";

switch ($_GET["op"]) {
    case 'guardaryeditar':
        if (!empty($id)) {
            // Actualizar un registro existente
            $rspta = $areaCurricular->editar($id, $nombre, $institucion_nivel_id);
            echo $rspta ? "Área Curricular actualizada correctamente" : "No se pudo actualizar el Área Curricular";
        } else {
            // Crear nuevos registros
            $datos = isset($_POST["datos"]) ? json_decode($_POST["datos"], true) : [];
            if (!empty($datos)) {
                $rspta = $areaCurricular->guardarMultiple($datos);
                echo $rspta ? "Áreas Curriculares registradas correctamente" : "No se pudieron registrar las Áreas Curriculares";
            } else {
                echo "No se recibieron datos para guardar";
            }
        }
        break;

    case 'desactivar':
        $rspta = $areaCurricular->desactivar($id);
        echo $rspta ? "Área Curricular desactivada correctamente" : "No se pudo desactivar el Área Curricular";
        break;

    case 'activar':
        $rspta = $areaCurricular->activar($id);
        echo $rspta ? "Área Curricular activada correctamente" : "No se pudo activar el Área Curricular";
        break;

    case 'mostrar':
        $rspta = $areaCurricular->mostrar($id);
        // Codificar el resultado usando JSON
        echo json_encode($rspta);
        break;

    case 'listar':
        $rspta = $areaCurricular->listar();
        // Vamos a declarar un array
        $data = Array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => $reg->nombre,
                "1" => $reg->institucion_nivel,
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

    case 'listar_niveles_activos':
        $rspta = $areaCurricular->listarNivelesActivos();
        while ($reg = $rspta->fetch_object()) {
            echo '<option value=' . $reg->id . '>' . $reg->nombre . '</option>';
        }
        break;
}
?>
