<?php
include_once("../Modelo/Alumno.php");

$alumno = new Alumno();
$id = isset($_POST["id"]) ? limpiarcadena($_POST["id"]) : "";
$usuario = isset($_POST["usuario"]) ? limpiarcadena($_POST["usuario"]) : "";
$contraseña = isset($_POST["contraseña"]) ? limpiarcadena($_POST["contraseña"]) : "";
$apoderado_id = isset($_POST["apoderado_id"]) ? limpiarcadena($_POST["apoderado_id"]) : "";
$dni = isset($_POST["dni"]) ? limpiarcadena($_POST["dni"]) : "";
$nombreyapellido = isset($_POST["nombreyapellido"]) ? limpiarcadena($_POST["nombreyapellido"]) : "";
$observaciones = isset($_POST["observaciones"]) ? limpiarcadena($_POST["observaciones"]) : "";

switch ($_GET["op"]) {
    case 'guardaryeditar':
        if (empty($id)) {
            $rspta = $alumno->guardar($usuario, $contraseña, $apoderado_id, $dni, $nombreyapellido, $observaciones);
            echo $rspta ? "Alumno registrado correctamente" : "No se pudo registrar el alumno";
        } else {
            $rspta = $alumno->editar($id, $usuario, $contraseña, $apoderado_id, $dni, $nombreyapellido, $observaciones);
            echo $rspta ? "Alumno actualizado correctamente" : "No se pudo actualizar el alumno";
        }
        break;

    case 'desactivar':
        $rspta = $alumno->desactivar($id);
        echo $rspta ? "Alumno desactivado correctamente" : "No se pudo desactivar el alumno";
        break;

    case 'activar':
        $rspta = $alumno->activar($id);
        echo $rspta ? "Alumno activado correctamente" : "No se pudo activar el alumno";
        break;

    case 'mostrar':
        $rspta = $alumno->mostrar($id);
        // Codificar el resultado usando JSON
        echo json_encode($rspta);
        break;

    case 'listar':
        $rspta = $alumno->listar();
        // Vamos a declarar un array
        $data = Array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => $reg->usuario,
                "1" => $reg->dni,
                "2" => $reg->nombreyapellido,
                "3" => $reg->apoderado,
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

    case 'listar_apoderados_activos':
        $rspta = $alumno->listarApoderadosActivos();
        while ($reg = $rspta->fetch_object()) {
            echo '<option value=' . $reg->id . '>' . $reg->nombreyapellido . '</option>';
        }
        break;
}
?>
