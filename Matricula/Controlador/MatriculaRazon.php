<?php
include_once("../Modelo/MatriculaRazon.php");

$matricula_razon = new MatriculaRazon();
$id = isset($_POST["id"]) ? limpiarcadena($_POST["id"]) : "";
$nombre = isset($_POST["nombre"]) ? limpiarcadena($_POST["nombre"]) : "";

switch ($_GET["op"]) {
    case 'guardaryeditar':
        if (empty($id)) {
            $rspta = $matricula_razon->guardar($nombre);
            echo $rspta ? "Razón de matrícula registrada correctamente" : "No se pudo registrar la razón de matrícula";
        } else {
            $rspta = $matricula_razon->editar($id, $nombre);
            echo $rspta ? "Razón de matrícula actualizada correctamente" : "No se pudo actualizar la razón de matrícula";
        }
        break;

    case 'desactivar':
        $rspta = $matricula_razon->desactivar($id);
        echo $rspta ? "Razón de matrícula desactivada correctamente" : "No se pudo desactivar la razón de matrícula";
        break;

    case 'activar':
        $rspta = $matricula_razon->activar($id);
        echo $rspta ? "Razón de matrícula activada correctamente" : "No se pudo activar la razón de matrícula";
        break;

    case 'mostrar':
        $rspta = $matricula_razon->mostrar($id);
        // Codificar el resultado usando JSON
        echo json_encode($rspta);
        break;

    case 'listar':
        $rspta = $matricula_razon->listar();
        // Vamos a declarar un array
        $data = Array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => $reg->nombre,
                "1" => $reg->estado == '1' ? 
                    '<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg->id . ')">EDITAR</button> <button class="btn btn-danger btn-sm" onclick="desactivar(' . $reg->id . ')">DESACTIVAR</button>' :
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
}
?>
