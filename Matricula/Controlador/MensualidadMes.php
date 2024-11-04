<?php
include_once("../Modelo/MensualidadMes.php");

$mensualidad = new MensualidadMes();
$id = isset($_POST["id"]) ? limpiarcadena($_POST["id"]) : "";
$nombre = isset($_POST["nombre"]) ? limpiarcadena($_POST["nombre"]) : "";
$observaciones = isset($_POST["observaciones"]) ? limpiarcadena($_POST["observaciones"]) : "";

switch ($_GET["op"]) {
    case 'guardaryeditar':
        if (empty($id)) {
            $rspta = $mensualidad->guardar($nombre, $observaciones);
            echo $rspta ? "Mensualidad registrada correctamente" : "No se pudo registrar la mensualidad";
        } else {
            $rspta = $mensualidad->editar($id, $nombre, $observaciones);
            echo $rspta ? "Mensualidad actualizada correctamente" : "No se pudo actualizar la mensualidad";
        }
        break;

    case 'desactivar':
        $rspta = $mensualidad->desactivar($id);
        echo $rspta ? "Mensualidad desactivada correctamente" : "No se pudo desactivar la mensualidad";
        break;

    case 'activar':
        $rspta = $mensualidad->activar($id);
        echo $rspta ? "Mensualidad activada correctamente" : "No se pudo activar la mensualidad";
        break;

    case 'mostrar':
        $rspta = $mensualidad->mostrar($id);
        // Codificar el resultado usando JSON
        echo json_encode($rspta);
        break;

    case 'listar':
        $rspta = $mensualidad->listar();
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => "N° ".$reg->id,
                "1" => $reg->nombre,
                "2" => $reg->observaciones,
                "3" => $reg->estado == '1' ?
                    '<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg->id . ')">EDITAR</button> <button class="btn btn-danger btn-sm" onclick="desactivar(' . $reg->id . ')">DESACTIVAR</button>' :
                    '<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg->id . ')">EDITAR</button> <button class="btn btn-primary btn-sm" onclick="activar(' . $reg->id . ')">ACTIVAR</button>'
            );
        }
        $results = array(
            "sEcho" => 1, 
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );
        echo json_encode($results);
        break;
}
?>
