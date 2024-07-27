<?php
include_once("../Modelo/InstitucionLectivo.php");

$institucionLectivo = new InstitucionLectivo();
$id = isset($_POST["id"]) ? limpiarcadena($_POST["id"]) : "";
$nombre = isset($_POST["nombre"]) ? limpiarcadena($_POST["nombre"]) : "";
$institucion_id = isset($_POST["institucion_id"]) ? limpiarcadena($_POST["institucion_id"]) : "";

switch ($_GET["op"]) {
    case 'guardaryeditar':
        if (empty($id)) {
            $rspta = $institucionLectivo->guardar($nombre, $institucion_id);
            echo $rspta ? "Institución lectiva registrada correctamente" : "No se pudo registrar la institución lectiva";
        } else {
            $rspta = $institucionLectivo->editar($id, $nombre, $institucion_id);
            echo $rspta ? "Institución lectiva actualizada correctamente" : "No se pudo actualizar la institución lectiva";
        }
        break;

    case 'desactivar':
        $rspta = $institucionLectivo->desactivar($id);
        echo $rspta ? "Institución lectiva desactivada correctamente" : "No se pudo desactivar la institución lectiva";
        break;

    case 'activar':
        $rspta = $institucionLectivo->activar($id);
        echo $rspta ? "Institución lectiva activada correctamente" : "No se pudo activar la institución lectiva";
        break;

    case 'mostrar':
        $rspta = $institucionLectivo->mostrar($id);
        // Codificar el resultado usando JSON
        echo json_encode($rspta);
        break;

    case 'listar':
        $rspta = $institucionLectivo->listar();
        // Vamos a declarar un array
        $data = Array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => 'AÑO LECTIVO '.$reg->nombre,
                "1" => $reg->institucion,
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

    case 'listar_instituciones_activas':
        $rspta = $institucionLectivo->listarInstitucionesActivas();
        while ($reg = $rspta->fetch_object()) {
            echo '<option value=' . $reg->id . '>' . $reg->nombre . '</option>';
        }
        break;
}
?>
