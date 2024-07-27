<?php
include_once("../Modelo/InstitucionGrado.php");

$institucionGrado = new InstitucionGrado();
$id = isset($_POST["id"]) ? limpiarcadena($_POST["id"]) : "";
$nombre = isset($_POST["nombre"]) ? limpiarcadena($_POST["nombre"]) : "";
$institucion_nivel_id = isset($_POST["institucion_nivel_id"]) ? limpiarcadena($_POST["institucion_nivel_id"]) : "";

switch ($_GET["op"]) {
    case 'guardaryeditar':
        if (empty($id)) {
            $rspta = $institucionGrado->guardar($nombre, $institucion_nivel_id);
            echo $rspta ? "Grado de institución nivel registrado correctamente" : "No se pudo registrar el grado de institución nivel";
        } else {
            $rspta = $institucionGrado->editar($id, $nombre, $institucion_nivel_id);
            echo $rspta ? "Grado de institución nivel actualizado correctamente" : "No se pudo actualizar el grado de institución nivel";
        }
        break;

    case 'desactivar':
        $rspta = $institucionGrado->desactivar($id);
        echo $rspta ? "Grado de institución nivel desactivado correctamente" : "No se pudo desactivar el grado de institución nivel";
        break;

    case 'activar':
        $rspta = $institucionGrado->activar($id);
        echo $rspta ? "Grado de institución nivel activado correctamente" : "No se pudo activar el grado de institución nivel";
        break;

    case 'mostrar':
        $rspta = $institucionGrado->mostrar($id);
        // Codificar el resultado usando JSON
        echo json_encode($rspta);
        break;

    case 'listar':
        $rspta = $institucionGrado->listar();
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
        $rspta = $institucionGrado->listarNivelesActivos();
        while ($reg = $rspta->fetch_object()) {
            echo '<option value=' . $reg->id . '>' . $reg->nombre . '</option>';
        }
        break;
}
?>
