<?php
session_start();
include_once("../Modelo/MatriculaPago.php");

$matriculaPago = new MatriculaPago();

switch ($_GET["op"]) {
    case 'guardaryeditar':
        $id = isset($_POST["id"]) ? limpiarcadena($_POST["id"]) : "";
        $matricula_detalle_id = isset($_POST["matricula_detalle_id"]) ? limpiarcadena($_POST["matricula_detalle_id"]) : "";
        $matricula_metodo_id = isset($_POST["matricula_metodo_id"]) ? limpiarcadena($_POST["matricula_metodo_id"]) : "";
        $numeracion = isset($_POST["numeracion"]) ? limpiarcadena($_POST["numeracion"]) : "";
        $fecha = isset($_POST["fecha"]) ? limpiarcadena($_POST["fecha"]) : "";
        $monto = isset($_POST["monto"]) ? limpiarcadena($_POST["monto"]) : "";
        $observaciones = isset($_POST["observaciones"]) ? limpiarcadena($_POST["observaciones"]) : "";

        if (empty($id)) {
            $rspta = $matriculaPago->guardar($matricula_detalle_id, $matricula_metodo_id, $numeracion, $fecha, $monto, $observaciones);
            echo $rspta ? "Pago registrado correctamente" : "No se pudo registrar el pago";
        } else {
            $rspta = $matriculaPago->editar($id, $matricula_detalle_id, $matricula_metodo_id, $numeracion, $fecha, $monto, $observaciones);
            echo $rspta ? "Pago actualizado correctamente" : "No se pudo actualizar el pago";
        }
        break;

    case 'listar':
        $rspta = $matriculaPago->listar();
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => 'Nº ' . $reg->numeracion,
                "1" => $reg->apoderado,
                "2" => $reg->fecha,
                "3" => 'S/.' . $reg->monto . ' - ' . $reg->metodo_pago,
                "4" => '<button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#' . $reg->numeracion . '">VER RECIBO</button>
                        <div class="modal fade" id="' . $reg->numeracion . '" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">' . $reg->apoderado . '</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <br>
                                    <h4 class="text-center">RECIBO DE PAGO Nº ' . $reg->numeracion . '</h4>
                                    <h5 class="text-center">' . $reg->lectivo . ' / ' . $reg->nivel . ' / ' . $reg->grado . '</h5>
                                    <br>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td>APODERADO</td>
                                            <td>' . $reg->apoderado . '</td>
                                        </tr>
                                        <tr>
                                            <td>ALUMNO</td>
                                            <td>' . $reg->alumno . '</td>
                                        </tr>
                                        <tr>
                                            <td>TIPO DE ALUMNO</td>
                                            <td>' . $reg->razon . '</td>
                                        </tr>
                                        <tr>
                                            <td>FECHA</td>
                                            <td>' . $reg->fecha . '</td>
                                        </tr>
                                        <tr>
                                            <td>MONTO</td>
                                            <td>S/. ' . $reg->monto . ' - ' . $reg->metodo_pago . '</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">CERRAR</button>
                                    <button type="button" class="btn btn-warning btn-sm" data-bs-dismiss="modal" onclick="mostrar(' . $reg->id . ')">EDITAR</button>
                                    <a class="btn btn-info btn-sm" href="../../Reportes/Vista/ReportePersonal.php?id=' . $reg->id . '" role="button">RECIBO PDF</a>
                                </div>
                            </div>
                        </div>
                    </div>'
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

    case 'mostrar':
        $id = $_POST['id'];
        $rspta = $matriculaPago->mostrar($id);
        echo json_encode($rspta);
        break;

    case 'desactivar':
        $id = $_POST['id'];
        $rspta = $matriculaPago->desactivar($id);
        echo $rspta ? "Pago desactivado correctamente" : "No se pudo desactivar el pago";
        break;

    case 'activar':
        $id = $_POST['id'];
        $rspta = $matriculaPago->activar($id);
        echo $rspta ? "Pago activado correctamente" : "No se pudo activar el pago";
        break;

    case 'listarMatriculaDetalles':
        $rspta = $matriculaPago->listarMatriculaDetalles();
        while ($reg = $rspta->fetch_object()) {
            echo '<option value=' . $reg->id . '>' . $reg->lectivo . ' - ' . $reg->nivel . ' - ' . $reg->grado . ' - ' . $reg->apoderado . ' - ' . $reg->alumno . ' - ' . $reg->razon . '</option>';
        }
        break;

    case 'listarMatriculaDetallesPorId':
        $id = $_POST['id'];
        $rspta = $matriculaPago->listarMatriculaDetallesPorId($id);
        while ($reg = $rspta->fetch_object()) {
            echo '<option value=' . $reg->id . '>' . $reg->lectivo . ' - ' . $reg->nivel . ' - ' . $reg->grado . ' - ' . $reg->apoderado . ' - ' . $reg->alumno . ' - ' . $reg->razon . '</option>';
        }
        break;

    case 'listarMetodosPago':
        $rspta = $matriculaPago->listarMetodosPago();
        while ($reg = $rspta->fetch_object()) {
            echo '<option value=' . $reg->id . '>' . $reg->nombre . '</option>';
        }
        break;
}
?>
