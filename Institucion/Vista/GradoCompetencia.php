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
            <h5 class="border-bottom pb-2 mb-0"><b>GRADO-COMPETENCIAS - LISTADO</b></h5>
            <div class="p-3">
                <table class="table" id="myTable">
                    <thead>
                        <tr>
                            <th>LECTIVO</th>
                            <th>NIVEL</th>
                            <th>ÁREA CURRICULAR</th>
                            <th>GRADO</th>
                            <th>COMPETENCIA</th>
                            <th>FECHA CREADO</th>
                            <th>ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            <small class="d-block text-end mt-3">
                <button type="button" onclick="MostrarFormulario();" class="btn btn-success">Agregar</button>
            </small>
        </div>

        <div class="my-3 p-3 bg-body rounded shadow-sm" id="formulario">
            <h5 class="border-bottom pb-2 mb-0"><b>GRADO-COMPETENCIAS - FORMULARIO</b></h5>
            <form id="frm_form" name="frm_form" method="post">
                <input type="hidden" id="id" name="id">
                <div class="p-3">
                    <button type="button" class="btn btn-primary" onclick="agregarFila()">Agregar Fila</button>
                </div>
                <div class="p-3">
                    <table class="table table-bordered" id="detalles">
                        <thead>
                            <tr>
                                <th style="width: 20%;">GRADO</th>
                                <th style="width: auto;">COMPETENCIA</th>
                                <th style="width: 10%;">ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><select name="grado_id[]" class="form-control selectpicker" data-live-search="true" required></select></td>
                                <td><select name="competencia_id[]" class="form-control selectpicker" data-live-search="true" required></select></td>
                                <td><button type="button" class="btn btn-danger" onclick="eliminarFila(this)">Eliminar</button></td>
                            </tr>
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
    <script src="GradoCompetencia.js"></script>
<?php
}
ob_end_flush();
?>
