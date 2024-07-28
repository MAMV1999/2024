<?php
session_start();
include_once("../Modelo/MatriculaDetalle.php");

$matriculaDetalle = new MatriculaDetalle();

switch ($_GET["op"]) {
    case 'guardaryeditar':
        $apoderado_id = isset($_POST["apoderado_id"]) ? limpiarcadena($_POST["apoderado_id"]) : "";
        $apoderado_dni = isset($_POST["apoderado_dni"]) ? limpiarcadena($_POST["apoderado_dni"]) : "";
        $apoderado_nombreyapellido = isset($_POST["apoderado_nombreyapellido"]) ? limpiarcadena($_POST["apoderado_nombreyapellido"]) : "";
        $apoderado_telefono = isset($_POST["apoderado_telefono"]) ? limpiarcadena($_POST["apoderado_telefono"]) : "";
        $apoderado_observaciones = isset($_POST["apoderado_observaciones"]) ? limpiarcadena($_POST["apoderado_observaciones"]) : "";

        $alumno_dni = isset($_POST["alumno_dni"]) ? limpiarcadena($_POST["alumno_dni"]) : "";
        $alumno_nombreyapellido = isset($_POST["alumno_nombreyapellido"]) ? limpiarcadena($_POST["alumno_nombreyapellido"]) : "";
        $alumno_nacimiento = isset($_POST["alumno_nacimiento"]) ? limpiarcadena($_POST["alumno_nacimiento"]) : "";
        $alumno_sexo = isset($_POST["alumno_sexo"]) ? limpiarcadena($_POST["alumno_sexo"]) : "";
        $alumno_observaciones = isset($_POST["alumno_observaciones"]) ? limpiarcadena($_POST["alumno_observaciones"]) : "";

        $detalle = isset($_POST["detalle"]) ? limpiarcadena($_POST["detalle"]) : "";
        $matricula_id = isset($_POST["matricula_id"]) ? limpiarcadena($_POST["matricula_id"]) : "";
        $matricula_razon_id = isset($_POST["matricula_razon_id"]) ? limpiarcadena($_POST["matricula_razon_id"]) : "";
        $matricula_observaciones = isset($_POST["matricula_observaciones"]) ? limpiarcadena($_POST["matricula_observaciones"]) : "";
        $trabajador_sesion_id = $_SESSION['id'];

        $pago_numeracion = isset($_POST["pago_numeracion"]) ? limpiarcadena($_POST["pago_numeracion"]) : "";
        $pago_fecha = isset($_POST["pago_fecha"]) ? limpiarcadena($_POST["pago_fecha"]) : "";
        $pago_monto = isset($_POST["pago_monto"]) ? limpiarcadena($_POST["pago_monto"]) : "";
        $metodo_pago_id = isset($_POST["metodo_pago_id"]) ? limpiarcadena($_POST["metodo_pago_id"]) : "";
        $pago_observaciones = isset($_POST["pago_observaciones"]) ? limpiarcadena($_POST["pago_observaciones"]) : "";

        $rspta = $matriculaDetalle->guardar(
            $apoderado_id,
            $apoderado_dni,
            $apoderado_nombreyapellido,
            $apoderado_telefono,
            $apoderado_observaciones,
            $alumno_dni,
            $alumno_nombreyapellido,
            $alumno_nacimiento,
            $alumno_sexo,
            $alumno_observaciones,
            $detalle,
            $matricula_id,
            $matricula_razon_id,
            $matricula_observaciones,
            $trabajador_sesion_id,
            $pago_numeracion,
            $pago_fecha,
            $pago_monto,
            $metodo_pago_id,
            $pago_observaciones
        );
        echo $rspta ? "Matrícula registrada correctamente" : "No se pudo registrar la matrícula";
        break;

    case 'listar':
        $rspta = $matriculaDetalle->listar();
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => $reg->lectivo.' - '.$reg->nivel.' - '.$reg->grado,
                "1" => $reg->alumno,
                "2" => $reg->apoderado,
                "3" => 'Nº '.$reg->numeracion,
                "4" => '<a class="btn btn-info btn-sm" href="Reporte.php?id=' . $reg->id . '" role="button">INFO</a> <button class="btn btn-danger btn-sm" onclick="desactivar(' . $reg->id . ')">DESACTIVAR</button>'
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

    case 'listarMetodosPago':
        $rspta = $matriculaDetalle->listarMetodosPago();
        while ($reg = $rspta->fetch_object()) {
            echo '<option value=' . $reg->id . '>' . $reg->nombre . '</option>';
        }
        break;

    case 'listarRazones':
        $rspta = $matriculaDetalle->listarRazones();
        while ($reg = $rspta->fetch_object()) {
            echo '<option value=' . $reg->id . '>' . $reg->nombre . '</option>';
        }
        break;

    case 'listarMatriculas':
        $rspta = $matriculaDetalle->listarMatriculas();
        while ($reg = $rspta->fetch_object()) {
            echo '<option value="' . $reg->id . '" data-preciomatricula="' . $reg->preciomatricula . '">' . $reg->lectivo . ' - ' . $reg->nivel . ' - ' . $reg->grado . ' (Aforo: ' . $reg->aforo . ', Matriculados: ' . $reg->matriculados . ') (Matricula: ' . $reg->preciomatricula . ', Mensualidad: ' . $reg->preciomensualidad . ')</option>';
        }
        break;


    case 'getNextPagoNumeracion':
        $rspta = $matriculaDetalle->getNextPagoNumeracion();
        echo $rspta;
        break;

    case 'buscarApoderado':
        $dni = $_POST['dni'];
        $rspta = $matriculaDetalle->buscarApoderado($dni);
        echo json_encode($rspta);
        break;

    case 'desactivar':
        $id = $_POST['id'];
        $codigo = $_POST['codigo'];
        $rspta = $matriculaDetalle->verificarCodigoDirector($codigo);
        if ($rspta) {
            $result = $matriculaDetalle->desactivar($id);
            echo $result ? "Registro desactivado correctamente" : "No se pudo desactivar el registro";
        } else {
            echo "Código incorrecto";
        }
        break;
}
