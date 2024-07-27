<?php
include_once("../Modelo/MatriculaMetodo.php");

$matricula_metodo = new MatriculaMetodo();
$id = isset($_POST["id"]) ? limpiarcadena($_POST["id"]) : "";
$nombre = isset($_POST["nombre"]) ? limpiarcadena($_POST["nombre"]) : "";

switch ($_GET["op"]) {
    case 'guardaryeditar':
        if (empty($id)) {
            $rspta = $matricula_metodo->guardar($nombre);
            echo $rspta ? "Método de matrícula registrado correctamente" : "No se pudo registrar el método de matrícula";
        } else {
            $rspta = $matricula_metodo->editar($id, $nombre);
            echo $rspta ? "Método de matrícula actualizado correctamente" : "No se pudo actualizar el método de matrícula";
        }
        break;

    case 'desactivar':
        $rspta = $matricula_metodo->desactivar($id);
        echo $rspta ? "Método de matrícula desactivado correctamente" : "No se pudo desactivar el método de matrícula";
        break;

    case 'activar':
        $rspta = $matricula_metodo->activar($id);
        echo $rspta ? "Método de matrícula activado correctamente" : "No se pudo activar el método de matrícula";
        break;

    case 'mostrar':
        $rspta = $matricula_metodo->mostrar($id);
        // Codificar el resultado usando JSON
        echo json_encode($rspta);
        break;

    case 'listar':
        $rspta = $matricula_metodo->listar();
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
