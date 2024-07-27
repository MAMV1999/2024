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
        <h5 class="border-bottom pb-2 mb-0"><b>APODERADOS - LISTADO</b></h5>
        <div class="p-3">
            <table class="table" id="myTable">
                <thead>
                    <tr>
                        <th>USUARIO</th>
                        <th>DNI</th>
                        <th>NOMBRE Y APELLIDO</th>
                        <th>TELÉFONO</th>
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
        <h5 class="border-bottom pb-2 mb-0"><b>APODERADOS - FORMULARIO</b></h5>
        <form id="frm_form" name="frm_form" method="post">
            <input type="hidden" id="id" name="id" placeholder="id" class="form-control">

            <div class="p-3">
                <label for="usuario" class="form-label"><b>USUARIO:</b></label>
                <div class="input-group">
                    <input type="text" id="usuario" name="usuario" placeholder="Usuario" class="form-control">
                </div>
            </div>

            <div class="p-3">
                <label for="contraseña" class="form-label"><b>CONTRASEÑA:</b></label>
                <div class="input-group">
                    <input type="text" id="contraseña" name="contraseña" placeholder="Contraseña" class="form-control">
                </div>
            </div>

            <div class="p-3">
                <label for="dni" class="form-label"><b>DNI:</b></label>
                <div class="input-group">
                    <input type="text" id="dni" name="dni" placeholder="DNI" class="form-control">
                </div>
            </div>

            <div class="p-3">
                <label for="nombreyapellido" class="form-label"><b>NOMBRE Y APELLIDO:</b></label>
                <div class="input-group">
                    <input type="text" id="nombreyapellido" name="nombreyapellido" placeholder="Nombre y Apellido" class="form-control">
                </div>
            </div>

            <div class="p-3">
                <label for="telefono" class="form-label"><b>TELÉFONO:</b></label>
                <div class="input-group">
                    <input type="text" id="telefono" name="telefono" placeholder="Teléfono" class="form-control">
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
<script src="Apoderado.js"></script>
<?php
}
ob_end_flush();
?>
