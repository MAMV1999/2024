<?php
include_once("../Modelo/InstitucionNivel.php");

$institucionNivel = new InstitucionNivel();
$id = isset($_POST["id"]) ? limpiarcadena($_POST["id"]) : "";
$nombre = isset($_POST["nombre"]) ? limpiarcadena($_POST["nombre"]) : "";
$institucion_lectivo_id = isset($_POST["institucion_lectivo_id"]) ? limpiarcadena($_POST["institucion_lectivo_id"]) : "";

switch ($_GET["op"]) {
    case 'guardaryeditar':
        if (empty($id)) {
            $rspta = $institucionNivel->guardar($nombre, $institucion_lectivo_id);
            echo $rspta ? "Nivel de institución lectiva registrado correctamente" : "No se pudo registrar el nivel de institución lectiva";
        } else {
            $rspta = $institucionNivel->editar($id, $nombre, $institucion_lectivo_id);
            echo $rspta ? "Nivel de institución lectiva actualizado correctamente" : "No se pudo actualizar el nivel de institución lectiva";
        }
        break;

    case 'desactivar':
        $rspta = $institucionNivel->desactivar($id);
        echo $rspta ? "Nivel de institución lectiva desactivado correctamente" : "No se pudo desactivar el nivel de institución lectiva";
        break;

    case 'activar':
        $rspta = $institucionNivel->activar($id);
        echo $rspta ? "Nivel de institución lectiva activado correctamente" : "No se pudo activar el nivel de institución lectiva";
        break;

    case 'mostrar':
        $rspta = $institucionNivel->mostrar($id);
        // Codificar el resultado usando JSON
        echo json_encode($rspta);
        break;

    case 'listar':
        $rspta = $institucionNivel->listar();
        // Vamos a declarar un array
        $data = Array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => $reg->nombre,
                "1" => $reg->institucion_lectivo,
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

    case 'listar_instituciones_lectivas_activas':
        $rspta = $institucionNivel->listarInstitucionesLectivasActivas();
        while ($reg = $rspta->fetch_object()) {
            echo '<option value=' . $reg->id . '>' . $reg->nombre . '</option>';
        }
        break;
}
?>
