<?php
include_once("../Modelo/Institucion.php");

$institucion = new Institucion();
$id = isset($_POST["id"]) ? limpiarcadena($_POST["id"]) : "";
$nombre = isset($_POST["nombre"]) ? limpiarcadena($_POST["nombre"]) : "";
$id_trabajador = isset($_POST["id_trabajador"]) ? limpiarcadena($_POST["id_trabajador"]) : "";
$telefono = isset($_POST["telefono"]) ? limpiarcadena($_POST["telefono"]) : "";
$correo = isset($_POST["correo"]) ? limpiarcadena($_POST["correo"]) : "";
$direccion = isset($_POST["direccion"]) ? limpiarcadena($_POST["direccion"]) : "";

switch ($_GET["op"]) {
    case 'guardaryeditar':
        if (empty($id)) {
            $rspta = $institucion->guardar($nombre, $id_trabajador, $telefono, $correo, $direccion);
            echo $rspta ? "Institución registrada correctamente" : "No se pudo registrar la institución";
        } else {
            $rspta = $institucion->editar($id, $nombre, $id_trabajador, $telefono, $correo, $direccion);
            echo $rspta ? "Institución actualizada correctamente" : "No se pudo actualizar la institución";
        }
        break;

    case 'desactivar':
        $rspta = $institucion->desactivar($id);
        echo $rspta ? "Institución desactivada correctamente" : "No se pudo desactivar la institución";
        break;

    case 'activar':
        $rspta = $institucion->activar($id);
        echo $rspta ? "Institución activada correctamente" : "No se pudo activar la institución";
        break;

    case 'mostrar':
        $rspta = $institucion->mostrar($id);
        // Codificar el resultado usando JSON
        echo json_encode($rspta);
        break;

    case 'listar':
        $rspta = $institucion->listar();
        // Vamos a declarar un array
        $data = Array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => $reg->nombre,
                "1" => $reg->trabajador,
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

    case 'listar_directores_activos':
        $rspta = $institucion->listarDirectoresActivos();
        while ($reg = $rspta->fetch_object()) {
            echo '<option value=' . $reg->id . '>' . $reg->nombre_apellido . '</option>';
        }
        break;
}
?>
