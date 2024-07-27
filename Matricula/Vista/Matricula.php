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
        <h5 class="border-bottom pb-2 mb-0"><b>MATRÍCULAS - LISTADO</b></h5>
        <div class="p-3">
            <table class="table" id="myTable">
                <thead>
                    <tr>
                        <th>GRADO</th>
                        <th>PROFESOR</th>
                        <th>AFORO</th>
                        <th>MATRICULA</th>
                        <th>MENSUALIDAD</th>
                        <th>OTROS</th>
                        <th>ACCIONES</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        <small class="d-block text-end mt-3">
            <button type="button" onclick="MostrarFormulario();cargar_grados();cargar_trabajadores();" class="btn btn-success">Agregar</button>
        </small>
    </div>

    <div class="my-3 p-3 bg-body rounded shadow-sm" id="formulario">
        <h5 class="border-bottom pb-2 mb-0"><b>MATRÍCULAS - FORMULARIO</b></h5>
        <form id="frm_form" name="frm_form" method="post">
            <input type="hidden" id="id" name="id" placeholder="id" class="form-control">

            <div class="p-3">
                <label for="detalle" class="form-label"><b>DETALLE:</b></label>
                <div class="input-group">
                    <textarea id="detalle" name="detalle" placeholder="Detalle" class="form-control"></textarea>
                </div>
            </div>

            <div class="p-3">
                <label for="institucion_grado_id" class="form-label"><b>GRADO:</b></label>
                <div class="input-group">
                    <select id="institucion_grado_id" name="institucion_grado_id" class="form-control selectpicker" data-live-search="true"></select>
                </div>
            </div>

            <div class="p-3">
                <label for="trabajador_id" class="form-label"><b>PROFESOR:</b></label>
                <div class="input-group">
                    <select id="trabajador_id" name="trabajador_id" class="form-control selectpicker" data-live-search="true"></select>
                </div>
            </div>

            <div class="p-3">
                <label for="preciomatricula" class="form-label"><b>COSTOS:</b></label>
                <div class="input-group">
                    <input type="text" id="preciomatricula" name="preciomatricula" placeholder="Precio Matrícula" class="form-control">
                    <input type="text" id="preciomensualidad" name="preciomensualidad" placeholder="Precio Mensualidad" class="form-control">
                    <input type="text" id="preciootros" name="preciootros" placeholder="Precio Otros" class="form-control">
                </div>
            </div>

            <div class="p-3">
                <label for="aforo" class="form-label"><b>AFORO:</b></label>
                <div class="input-group">
                    <input type="text" id="aforo" name="aforo" placeholder="Aforo" class="form-control">
                </div>
            </div>

            <div class="p-3">
                <label for="observaciones" class="form-label"><b>OBSERVACIONES:</b></label>
                <div class="input-group">
                    <textarea id="observaciones" name="observaciones" placeholder="Observaciones" class="form-control"></textarea>
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
<script src="Matricula.js"></script>
<?php
}
ob_end_flush();
?>
