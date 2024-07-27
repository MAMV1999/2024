<?php
session_start();
include_once("../Modelo/Matricula.php");

$matricula = new Matricula();
$id = isset($_POST["id"]) ? limpiarcadena($_POST["id"]) : "";
$detalle = isset($_POST["detalle"]) ? limpiarcadena($_POST["detalle"]) : "";
$institucion_grado_id = isset($_POST["institucion_grado_id"]) ? limpiarcadena($_POST["institucion_grado_id"]) : "";
$trabajador_id = isset($_POST["trabajador_id"]) ? limpiarcadena($_POST["trabajador_id"]) : "";
$preciomatricula = isset($_POST["preciomatricula"]) ? limpiarcadena($_POST["preciomatricula"]) : "";
$preciomensualidad = isset($_POST["preciomensualidad"]) ? limpiarcadena($_POST["preciomensualidad"]) : "";
$preciootros = isset($_POST["preciootros"]) ? limpiarcadena($_POST["preciootros"]) : "";
$aforo = isset($_POST["aforo"]) ? limpiarcadena($_POST["aforo"]) : "";
$observaciones = isset($_POST["observaciones"]) ? limpiarcadena($_POST["observaciones"]) : "";
$trabajador_sesion_id = $_SESSION["id"]; // Suponiendo que el ID del trabajador en sesión está almacenado en $_SESSION["id"]

switch ($_GET["op"]) {
    case 'guardaryeditar':
        if (empty($id)) {
            $rspta = $matricula->guardar($detalle, $institucion_grado_id, $trabajador_id, $preciomatricula, $preciomensualidad, $preciootros, $aforo, $observaciones, $trabajador_sesion_id);
            echo $rspta ? "Matrícula registrada correctamente" : "No se pudo registrar la matrícula";
        } else {
            $rspta = $matricula->editar($id, $detalle, $institucion_grado_id, $trabajador_id, $preciomatricula, $preciomensualidad, $preciootros, $aforo, $observaciones, $trabajador_sesion_id);
            echo $rspta ? "Matrícula actualizada correctamente" : "No se pudo actualizar la matrícula";
        }
        break;

    case 'desactivar':
        $rspta = $matricula->desactivar($id);
        echo $rspta ? "Matrícula desactivada correctamente" : "No se pudo desactivar la matrícula";
        break;

    case 'activar':
        $rspta = $matricula->activar($id);
        echo $rspta ? "Matrícula activada correctamente" : "No se pudo activar la matrícula";
        break;

    case 'mostrar':
        $rspta = $matricula->mostrar($id);
        // Codificar el resultado usando JSON
        echo json_encode($rspta);
        break;

    case 'listar':
        $rspta = $matricula->listar();
        // Vamos a declarar un array
        $data = Array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => $reg->institucion_grado,
                "1" => $reg->trabajador,
                "2" => $reg->aforo.' Alumnos',
                "3" => 'S/.'.$reg->preciomatricula,
                "4" => 'S/.'.$reg->preciomensualidad,
                "5" => 'S/.'.$reg->preciootros,
                "6" => $reg->estado == '1' ? 
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

    case 'listar_grados_disponibles':
        $rspta = $matricula->listarGradosDisponibles();
        while ($reg = $rspta->fetch_object()) {
            echo '<option value=' . $reg->id . '>' . $reg->nombre . '</option>';
        }
        break;

    case 'listar_trabajadores_activos':
        $rspta = $matricula->listarTrabajadoresActivos();
        while ($reg = $rspta->fetch_object()) {
            echo '<option value=' . $reg->id . '>' . $reg->nombre_cargo . '</option>';
        }
        break;
}
?>
