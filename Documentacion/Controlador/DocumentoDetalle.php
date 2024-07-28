<?php
include_once("../Modelo/DocumentoDetalle.php");

$documento_detalle = new DocumentoDetalle();

switch ($_GET["op"]) {
    case 'listar':
        $rspta = $documento_detalle->listar();
        $data = Array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "grado" => $reg->lectivo.' - '.$reg->nivel.' - '.$reg->grado,
                "alumno" => $reg->alumno,
                "apoderado" => $reg->apoderado,
                "acciones" => '<button class="btn btn-primary btn-sm" onclick="mostrar(' . $reg->id . ')">Ver/Editar Documentos</button>'
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
        $rspta_documentos = $documento_detalle->listarDocumentos();
        $documentos = Array();
        while ($reg = $rspta_documentos->fetch_object()) {
            $documentos[] = $reg;
        }

        $rspta = $documento_detalle->mostrar($_POST['id']);
        $detalles = Array();
        while ($reg = $rspta->fetch_object()) {
            $detalles[$reg->documento_id] = $reg;
        }

        $informacion_matricula = $documento_detalle->obtenerInformacionMatricula($_POST['id']);

        $data = Array(
            "documentos" => $documentos,
            "detalles" => $detalles,
            "lectivo" => $informacion_matricula['lectivo'],
            "nivel" => $informacion_matricula['nivel'],
            "grado" => $informacion_matricula['grado'],
            "alumno" => $informacion_matricula['alumno'],
            "apoderado" => $informacion_matricula['apoderado']
        );

        echo json_encode($data);
        break;

    case 'guardaryeditar':
        $matricula_detalle_id = $_POST['matricula_detalle_id'];
        $documentos = $_POST['documentos'];
        $rspta = $documento_detalle->guardar($matricula_detalle_id, $documentos);
        echo $rspta ? "Documentos actualizados correctamente" : "No se pudieron actualizar los documentos";
        break;
}
?>
