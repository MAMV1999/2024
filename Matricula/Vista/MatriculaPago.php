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
            <h5 class="border-bottom pb-2 mb-0"><b>MATRÍCULA PAGO - LISTADO</b></h5>
            <div class="p-3">
                <table class="table" id="myTable">
                    <thead>
                        <tr>
                            <th>NUMERACIÓN</th>
                            <th>APODERADO</th>
                            <th>FECHA</th>
                            <th>MONTO</th>
                            <th>ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            <small class=" d-block text-end mt-3">
                <button type="button" onclick="MostrarFormulario();" class="btn btn-success">Agregar</button>
            </small>
        </div>

        <div class="my-3 p-3 bg-body rounded shadow-sm" id="formulario">
            <h5 class="border-bottom pb-2 mb-0"><b>MATRÍCULA PAGO - FORMULARIO</b></h5>
            <form id="frm_form" name="frm_form" method="post">

                <div class="p-3">
                    <label for="fecha" class="form-label"><b>FECHA:</b></label>
                    <div class="input-group">
                        <input type="date" id="fecha" name="fecha" class="form-control">
                    </div>
                </div>

                <div class="p-3">
                    <label for="matricula_detalle_id" class="form-label"><b>MATRÍCULA DETALLE:</b></label>
                    <div class="input-group">
                        <input type="hidden" id="id" name="id">
                        <select id="matricula_detalle_id" name="matricula_detalle_id" class="form-control" data-live-search="true">
                            <!-- Options will be loaded dynamically -->
                        </select>
                    </div>
                </div>

                <div class="p-3">
                    <label for="numeracion" class="form-label"><b>NUMERACIÓN:</b></label>
                    <div class="input-group">
                        <input type="text" id="numeracion" name="numeracion" placeholder="Numeración" class="form-control" readonly>
                    </div>
                </div>
                
                <div class="p-3">
                    <label for="monto" class="form-label"><b>MONTO / METODO</b></label>
                    <div class="input-group">
                        <input type="text" id="monto" name="monto" placeholder="Monto" class="form-control">
                        <select id="matricula_metodo_id" name="matricula_metodo_id" class="form-control"></select>
                    </div>
                </div>

                <div class="p-3">
                    <label for="observaciones" class="form-label"><b>OBSERVACIONES:</b></label>
                    <div class="input-group">
                        <input type="text" id="observaciones" name="observaciones" placeholder="Observaciones" class="form-control">
                    </div>
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
    <script src="MatriculaPago.js"></script>
<?php
}
ob_end_flush();
?>