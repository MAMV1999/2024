<?php
include_once("../Modelo/Trabajador.php");

$trabajador = new Trabajador();

$id = isset($_POST["id"]) ? limpiarcadena($_POST["id"]) : "";
$usuario = isset($_POST["usuario"]) ? limpiarcadena($_POST["usuario"]) : "";
$contraseña = isset($_POST["contraseña"]) ? limpiarcadena($_POST["contraseña"]) : "";
$dni = isset($_POST["dni"]) ? limpiarcadena($_POST["dni"]) : "";
$nombre_apellido = isset($_POST["nombre_apellido"]) ? limpiarcadena($_POST["nombre_apellido"]) : "";
$nacimiento = isset($_POST["nacimiento"]) ? limpiarcadena($_POST["nacimiento"]) : "";
$sexo = isset($_POST["sexo"]) ? limpiarcadena($_POST["sexo"]) : "";
$estado_civil = isset($_POST["estado_civil"]) ? limpiarcadena($_POST["estado_civil"]) : "";
$cargo = isset($_POST["cargo"]) ? limpiarcadena($_POST["cargo"]) : "";
$direccion = isset($_POST["direccion"]) ? limpiarcadena($_POST["direccion"]) : "";
$telefono = isset($_POST["telefono"]) ? limpiarcadena($_POST["telefono"]) : "";
$correo = isset($_POST["correo"]) ? limpiarcadena($_POST["correo"]) : "";
$sueldo = isset($_POST["sueldo"]) ? limpiarcadena($_POST["sueldo"]) : "";
$cuenta_bcp = isset($_POST["cuenta_bcp"]) ? limpiarcadena($_POST["cuenta_bcp"]) : "";
$interbancario_bcp = isset($_POST["interbancario_bcp"]) ? limpiarcadena($_POST["interbancario_bcp"]) : "";
$sunat_ruc = isset($_POST["sunat_ruc"]) ? limpiarcadena($_POST["sunat_ruc"]) : "";
$sunat_usuario = isset($_POST["sunat_usuario"]) ? limpiarcadena($_POST["sunat_usuario"]) : "";
$sunat_contraseña = isset($_POST["sunat_contraseña"]) ? limpiarcadena($_POST["sunat_contraseña"]) : "";
$observaciones = isset($_POST["observaciones"]) ? limpiarcadena($_POST["observaciones"]) : "";
$usuariocrea = isset($_POST["usuariocrea"]) ? limpiarcadena($_POST["usuariocrea"]) : "";

switch ($_GET["op"]) {
    case 'guardaryeditar':
        if (empty($id)) {
            $rspta = $trabajador->guardar($usuario, $contraseña, $dni, $nombre_apellido, $nacimiento, $sexo, $estado_civil, $cargo, $direccion, $telefono, $correo, $sueldo, $cuenta_bcp, $interbancario_bcp, $sunat_ruc, $sunat_usuario, $sunat_contraseña, $observaciones, $usuariocrea);
            echo $rspta ? "Trabajador registrado correctamente" : "No se pudo registrar el trabajador";
        } else {
            $rspta = $trabajador->editar($id, $usuario, $contraseña, $dni, $nombre_apellido, $nacimiento, $sexo, $estado_civil, $cargo, $direccion, $telefono, $correo, $sueldo, $cuenta_bcp, $interbancario_bcp, $sunat_ruc, $sunat_usuario, $sunat_contraseña, $observaciones);
            echo $rspta ? "Trabajador actualizado correctamente" : "No se pudo actualizar el trabajador";
        }
        break;

    case 'desactivar':
        $rspta = $trabajador->desactivar($id);
        echo $rspta ? "Trabajador desactivado correctamente" : "No se pudo desactivar el trabajador";
        break;

    case 'activar':
        $rspta = $trabajador->activar($id);
        echo $rspta ? "Trabajador activado correctamente" : "No se pudo activar el trabajador";
        break;

    case 'mostrar':
        $rspta = $trabajador->mostrar($id);
        echo json_encode($rspta);
        break;

    case 'listar':
        $rspta = $trabajador->listar();
        $data = Array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => $reg->dni,
                "1" => $reg->nombre_apellido,
                "2" =>
                '<button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#' . $reg->dni . '">REPORTE PDF</button>
                
                    <div class="modal fade" id="' . $reg->dni . '" tabindex="-1" aria-labelledby="' . $reg->dni . '" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="' . $reg->dni . '">' . $reg->nombre_apellido . '</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <iframe src="../../Reportes/Vista/InformeTrabajador.php?id=' . $reg->id . '" type="application/pdf" width="100%" height="600px"></iframe>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                            </div>
                        </div>
                        </div>',
                "3" => ($reg->estado) ? '
                <button type="button" onclick="mostrar(' . $reg->id . ')" class="btn btn-warning btn-sm">EDITAR</button>
                <button type="button" onclick="desactivar(' . $reg->id . ')" class="btn btn-danger btn-sm">DESACTIVAR</button>
                ' : '
                <button type="button" onclick="mostrar(' . $reg->id . ')" class="btn btn-warning btn-sm">EDITAR</button>
                <button type="button" onclick="activar(' . $reg->id . ')" class="btn btn-success btn-sm">ACTIVAR</button>
                '
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
}
?>
