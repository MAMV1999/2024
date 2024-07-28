<?php
include_once("../Modelo/Documento.php");

$documento = new Documento();

$id = isset($_POST["id"]) ? limpiarcadena($_POST["id"]) : "";
$detalle = isset($_POST["detalle"]) ? limpiarcadena($_POST["detalle"]) : "";
$nombre = isset($_POST["nombre"]) ? limpiarcadena($_POST["nombre"]) : "";
$obligatorio = isset($_POST["obligatorio"]) ? limpiarcadena($_POST["obligatorio"]) : "";
$observaciones = isset($_POST["observaciones"]) ? limpiarcadena($_POST["observaciones"]) : "";

switch ($_GET["op"]) {
    case 'guardaryeditar':
        if (empty($id)) {
            $rspta = $documento->guardar($detalle, $nombre, $obligatorio, $observaciones);
            echo $rspta ? "Documento registrado correctamente" : "No se pudo registrar el documento";
        } else {
            $rspta = $documento->editar($id, $detalle, $nombre, $obligatorio, $observaciones);
            echo $rspta ? "Documento actualizado correctamente" : "No se pudo actualizar el documento";
        }
        break;

    case 'desactivar':
        $rspta = $documento->desactivar($id);
        echo $rspta ? "Documento desactivado correctamente" : "No se pudo desactivar el documento";
        break;

    case 'activar':
        $rspta = $documento->activar($id);
        echo $rspta ? "Documento activado correctamente" : "No se pudo activar el documento";
        break;

    case 'mostrar':
        $rspta = $documento->mostrar($id);
        echo json_encode($rspta);
        break;

    case 'listar':
        $rspta = $documento->listar();
        $data = Array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => $reg->detalle,
                "1" => $reg->nombre,
                "2" => $reg->obligatorio,
                "3" => $reg->observaciones,
                "4" => ($reg->estado) ?
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
