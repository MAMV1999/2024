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
                "info" => '<!-- Button trigger modal -->
                            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#' . $reg->id . '">REPORTE</button>
                            
                            <!-- Modal -->
                            <div class="modal fade" id="' . $reg->id . '" tabindex="-1" aria-labelledby="' . $reg->id . 'Label" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="' . $reg->id . 'Label">' . $reg->alumno . '</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <iframe src="../../Reportes/Vista/Documentos.php?id=' . $reg->id . '" type="application/pdf" width="100%" height="600px"></iframe>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CERRAR</button>
                                </div>
                                </div>
                            </div>
                            </div>',
                "acciones" => '<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg->id . ')">EDITAR</button>'
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
