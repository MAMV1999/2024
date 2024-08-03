<?php
ob_start();
session_start();

if (!isset($_SESSION['nombre'])) {
    header("Location: ../../Inicio/Controlador/Acceso.php?op=salir");
} else {
?>
    <?php include "../../General/Include/1_header.php"; ?>
    <main class="container">
        <!-- TITULO -->
        <?php include "../../General/Include/3_body.php"; ?>

        <!-- CUERPO_INICIO -->
        <div class="my-3 p-3 bg-body rounded shadow-sm" id="listado">
            <h5 class="border-bottom pb-2 mb-0"><b>DOCUMENTOS DETALLE - LISTADO</b></h5>
            <div class="p-3">
                <table class="table" id="myTable">
                    <thead>
                        <tr>
                            <th>GRADO</th>
                            <th>ALUMNO</th>
                            <th>APODERADO</th>
                            <th>INFO</th>
                            <th>ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

        <div class="my-3 p-3 bg-body rounded shadow-sm" id="formulario" style="display: none;">
            <h5 class="border-bottom pb-2 mb-0"><b>DOCUMENTOS DETALLE - FORMULARIO</b></h5>

            <!-- Información adicional -->
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td>LECTIVO / NIVEL / GRADO</td>
                            <td><span id="lectivo"></span> / <span id="nivel"></span> / <span id="grado"></span></td>
                        </tr>
                        <tr>
                            <td>APODERADO</td>
                            <td><span id="apoderado"></span></td>
                        </tr>
                        <tr>
                            <td>ALUMNO</td>
                            <td><span id="alumno"></span></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <form id="frm_form" name="frm_form" method="post">
                <input type="hidden" id="id" name="id" placeholder="id" class="form-control">
                <input type="hidden" id="matricula_detalle_id" name="matricula_detalle_id" class="form-control">

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nº</th>
                                <th>DETALLE</th>
                                <th>DOCUMENTOS</th>
                                <th>***</th>
                                <th>ENTREGADO (SI / NO)</th>
                                <th>OBSERVACIONES</th>
                            </tr>
                        </thead>
                        <tbody id="documento_list">
                            <!-- Los documentos dinámicos se cargarán aquí -->
                        </tbody>
                    </table>
                </div>

                <div class="p-3">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <button type="button" onclick="MostrarListado();" class="btn btn-secondary">Cancelar</button>
                </div>
            </form>
        </div>
        <!-- CUERPO_FIN -->
    </main>
    <?php include "../../General/Include/2_footer.php"; ?>
    <script src="DocumentoDetalle.js"></script>
    <style>
        .custom-radio {
            margin-right: 10px;
        }
        .custom-radio input[type="radio"] {
            transform: scale(1.5);
            margin-right: 5px;
        }
    </style>
<?php
}
ob_end_flush();
?>
