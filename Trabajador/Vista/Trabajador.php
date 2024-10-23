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
        <h5 class="border-bottom pb-2 mb-0"><b>TRABAJADOR - LISTADO</b></h5>
        <div class="p-3">
            <table class="table" id="myTable">
                <thead>
                    <tr>
                        <th>DNI</th>
                        <th>NOMBRE Y APELLIDO</th>
                        <th>CARGO</th>
                        <th>INFORMACION</th>
                        <th>OPCIONES</th>
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
        <h5 class="border-bottom pb-2 mb-0"><b>TRABAJADOR - FORMULARIO</b></h5>
        <form id="frm_form" name="frm_form" method="post">
            <input type="hidden" id="id" name="id" placeholder="id" class="form-control">

            <div class="p-3">
                <label for="cargo" class="form-label"><b>CARGO:</b></label>
                <div class="input-group">
                    <select id="cargo" name="cargo" class="form-control">
                        <option value="DIRECTOR">DIRECTOR</option>
                        <option value="SECRETARIO">SECRETARIO</option>
                        <option value="PROFESOR">PROFESOR</option>
                        <option value="AUXILIAR">AUXILIAR</option>
                    </select>
                </div>
            </div>

            <div class="p-3">
                <label for="dni" class="form-label"><b>DATOS PERSONALES:</b></label>
                <div class="input-group">
                    <input type="text" id="dni" name="dni" placeholder="DNI" class="form-control">
                    <input type="text" id="nombre_apellido" name="nombre_apellido" placeholder="Nombre y Apellido" class="form-control" style="width: 50%;">
                </div>
            </div>

            <div class="p-3">
                <label for="telefono" class="form-label"><b>TELÉFONO:</b></label>
                <div class="input-group">
                    <input type="text" id="telefono" name="telefono" placeholder="Teléfono" class="form-control">
                </div>
            </div>

            <div class="p-3">
                <label for="nacimiento" class="form-label"><b>FECHA DE NACIMIENTO:</b></label>
                <div class="input-group">
                    <input type="date" id="nacimiento" name="nacimiento" class="form-control">
                </div>
            </div>

            <div class="p-3">
                <label for="sexo" class="form-label"><b>SEXO:</b></label>
                <div class="input-group">
                    <select id="sexo" name="sexo" class="form-control">
                        <option value="MASCULINO">MASCULINO</option>
                        <option value="FEMENINO">FEMENINO</option>
                    </select>
                </div>
            </div>

            <div class="p-3">
                <label for="estado_civil" class="form-label"><b>ESTADO CIVIL:</b></label>
                <div class="input-group">
                    <select id="estado_civil" name="estado_civil" class="form-control">
                        <option value="SOLTERO">SOLTERO</option>
                        <option value="CASADO">CASADO</option>
                        <option value="DIVORCIADO">DIVORCIADO</option>
                        <option value="VIUDO">VIUDO</option>
                    </select>
                </div>
            </div>

            <div class="p-3">
                <label for="direccion" class="form-label"><b>DIRECCIÓN:</b></label>
                <div class="input-group">
                    <input type="text" id="direccion" name="direccion" placeholder="Dirección" class="form-control">
                </div>
            </div>

            <div class="p-3">
                <label for="correo" class="form-label"><b>CORREO:</b></label>
                <div class="input-group">
                    <input type="email" id="correo" name="correo" placeholder="Correo" class="form-control">
                </div>
            </div>

            <div class="p-3">
                <label for="sueldo" class="form-label"><b>SUELDO:</b></label>
                <div class="input-group">
                    <input type="number" step="0.01" id="sueldo" name="sueldo" placeholder="Sueldo" class="form-control">
                </div>
            </div>

            <div class="p-3">
                <label for="cuenta_bcp" class="form-label"><b>BCP:</b></label>
                <div class="input-group">
                    <input type="text" id="cuenta_bcp" name="cuenta_bcp" placeholder="Cuenta BCP" class="form-control">
                    <input type="text" id="interbancario_bcp" name="interbancario_bcp" placeholder="Interbancario BCP" class="form-control">
                </div>
            </div>

            <div class="p-3">
                <label for="sunat_ruc" class="form-label"><b>SUNAT:</b></label>
                <div class="input-group">
                    <input type="text" id="sunat_ruc" name="sunat_ruc" placeholder="SUNAT RUC" class="form-control">
                    <input type="text" id="sunat_usuario" name="sunat_usuario" placeholder="SUNAT Usuario" class="form-control">
                    <input type="text" id="sunat_contraseña" name="sunat_contraseña" placeholder="SUNAT Contraseña" class="form-control">
                </div>
            </div>

            <div class="p-3">
                <label for="usuario" class="form-label"><b>EBENEZER:</b></label>
                <div class="input-group">
                    <input type="text" id="usuario" name="usuario" placeholder="Usuario" class="form-control">
                    <input type="text" id="contraseña" name="contraseña" placeholder="Contraseña" class="form-control">
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
<script src="Trabajador.js"></script>
<?php
}
ob_end_flush();
?>