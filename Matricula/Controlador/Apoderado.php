<?php
include_once("../Modelo/Apoderado.php");

$apoderado = new Apoderado();
$id = isset($_POST["id"]) ? limpiarcadena($_POST["id"]) : "";
$usuario = isset($_POST["usuario"]) ? limpiarcadena($_POST["usuario"]) : "";
$contraseña = isset($_POST["contraseña"]) ? limpiarcadena($_POST["contraseña"]) : "";
$dni = isset($_POST["dni"]) ? limpiarcadena($_POST["dni"]) : "";
$nombreyapellido = isset($_POST["nombreyapellido"]) ? limpiarcadena($_POST["nombreyapellido"]) : "";
$telefono = isset($_POST["telefono"]) ? limpiarcadena($_POST["telefono"]) : "";
$observaciones = isset($_POST["observaciones"]) ? limpiarcadena($_POST["observaciones"]) : "";

switch ($_GET["op"]) {
    case 'guardaryeditar':
        if (empty($id)) {
            $rspta = $apoderado->guardar($usuario, $contraseña, $dni, $nombreyapellido, $telefono, $observaciones);
            echo $rspta ? "Apoderado registrado correctamente" : "No se pudo registrar el apoderado";
        } else {
            $rspta = $apoderado->editar($id, $usuario, $contraseña, $dni, $nombreyapellido, $telefono, $observaciones);
            echo $rspta ? "Apoderado actualizado correctamente" : "No se pudo actualizar el apoderado";
        }
        break;

    case 'desactivar':
        $rspta = $apoderado->desactivar($id);
        echo $rspta ? "Apoderado desactivado correctamente" : "No se pudo desactivar el apoderado";
        break;

    case 'activar':
        $rspta = $apoderado->activar($id);
        echo $rspta ? "Apoderado activado correctamente" : "No se pudo activar el apoderado";
        break;

    case 'mostrar':
        $rspta = $apoderado->mostrar($id);
        // Codificar el resultado usando JSON
        echo json_encode($rspta);
        break;

    case 'listar':
        $rspta = $apoderado->listar();
        // Vamos a declarar un array
        $data = Array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => $reg->usuario,
                "1" => $reg->dni,
                "2" => $reg->nombreyapellido,
                "3" => $reg->telefono,
                "4" => $reg->estado == '1' ? 
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
