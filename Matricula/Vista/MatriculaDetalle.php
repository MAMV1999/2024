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
            <h5 class="border-bottom pb-2 mb-0"><b>MATRÍCULA DETALLE - LISTADO</b></h5>
            <div class="p-3">
                <table class="table" id="myTable">
                    <thead>
                        <tr>
                            <th>GRADO</th>
                            <th>ALUMNO</th>
                            <th>APODERADO</th>
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
            <h5 class="border-bottom pb-2 mb-0"><b>MATRÍCULA DETALLE - FORMULARIO</b></h5>
            <form id="frm_form" name="frm_form" method="post">

                <div class="p-3">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">ALUMNO</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">APODERADO</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact-tab-pane" type="button" role="tab" aria-controls="contact-tab-pane" aria-selected="false">MATRICULA</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="mensualidad-tab" data-bs-toggle="tab" data-bs-target="#mensualidad-tab-pane" type="button" role="tab" aria-controls="mensualidad-tab-pane" aria-selected="false">MENSUALIDAD</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="otros-tab" data-bs-toggle="tab" data-bs-target="#otros-tab-pane" type="button" role="tab" aria-controls="otros-tab-pane" aria-selected="false">OTROS</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                            <div class="p-3">
                                <label for="matricula_id" class="form-label"><b>MATRÍCULA:</b></label>
                                <div class="input-group">
                                    <select id="matricula_id" name="matricula_id" class="form-control selectpicker" data-live-search="true" style="width: 50%;"></select>
                                    <select id="matricula_razon_id" name="matricula_razon_id" class="form-control selectpicker" data-live-search="true"></select>
                                </div>
                            </div>

                            <div class="p-3">
                                <label for="alumno_dni" class="form-label"><b>DATOS PERSONALES ALUMNO:</b></label>
                                <div class="input-group">
                                    <input type="text" id="alumno_dni" name="alumno_dni" placeholder="DNI Alumno" class="form-control">
                                    <input type="text" id="alumno_nombreyapellido" name="alumno_nombreyapellido" placeholder="Nombre y Apellido Alumno" class="form-control" style="width: 50%;">
                                </div>
                            </div>

                            <div class="p-3">
                                <label for="alumno_nacimiento" class="form-label"><b>FECHA DE NACIMIENTO ALUMNO:</b></label>
                                <div class="input-group">
                                    <input type="date" id="alumno_nacimiento" name="alumno_nacimiento" class="form-control">
                                </div>
                            </div>

                            <div class="p-3">
                                <label for="alumno_sexo" class="form-label"><b>SEXO ALUMNO:</b></label>
                                <div class="input-group">
                                    <select id="alumno_sexo" name="alumno_sexo" class="form-control">
                                        <option value="MASCULINO">MASCULINO</option>
                                        <option value="FEMENINO">FEMENINO</option>
                                    </select>
                                </div>
                            </div>

                            <div class="p-3">
                                <label for="alumno_observaciones" class="form-label"><b>OBSERVACIONES ALUMNO:</b></label>
                                <div class="input-group">
                                    <input type="text" id="alumno_observaciones" name="alumno_observaciones" placeholder="Observaciones Alumno" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                        <input type="hidden" id="apoderado_id" name="apoderado_id" placeholder="DNI Apoderado" class="form-control">
                            <div class="p-3">
                                <label for="apoderado_dni" class="form-label"><b>DNI APODERADO:</b></label>
                                <div class="input-group">
                                    <input type="text" id="apoderado_dni" name="apoderado_dni" placeholder="DNI Apoderado" class="form-control">
                                    <button type="button" class="btn btn-primary" onclick="buscarApoderado()">BUSCAR</button>
                                </div>
                            </div>

                            <div class="p-3">
                                <label for="apoderado_nombreyapellido" class="form-label"><b>NOMBRE Y APELLIDO APODERADO:</b></label>
                                <div class="input-group">
                                    <input type="text" id="apoderado_nombreyapellido" name="apoderado_nombreyapellido" placeholder="Nombre y Apellido Apoderado" class="form-control">
                                </div>
                            </div>

                            <div class="p-3">
                                <label for="apoderado_telefono" class="form-label"><b>TELÉFONO APODERADO:</b></label>
                                <div class="input-group">
                                    <input type="text" id="apoderado_telefono" name="apoderado_telefono" placeholder="Teléfono Apoderado" class="form-control">
                                </div>
                            </div>

                            <div class="p-3">
                                <label for="apoderado_observaciones" class="form-label"><b>OBSERVACIONES APODERADO:</b></label>
                                <div class="input-group">
                                    <input type="text" id="apoderado_observaciones" name="apoderado_observaciones" placeholder="Observaciones Apoderado" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="contact-tab-pane" role="tabpanel" aria-labelledby="contact-tab" tabindex="0">
                            <div class="p-3">
                                <label for="pago_numeracion" class="form-label"><b>PAGO NUMERACIÓN:</b></label>
                                <div class="input-group">
                                    <input type="text" id="pago_numeracion" name="pago_numeracion" placeholder="Pago Numeración" class="form-control" readonly>
                                </div>
                            </div>

                            <div class="p-3">
                                <label for="pago_fecha" class="form-label"><b>PAGO FECHA:</b></label>
                                <div class="input-group">
                                    <input type="date" id="pago_fecha" name="pago_fecha" class="form-control">
                                </div>
                            </div>

                            <div class="p-3">
                                <label for="pago_monto" class="form-label"><b>MONTO Y METODO:</b></label>
                                <div class="input-group">
                                    <input type="text" id="pago_monto" name="pago_monto" placeholder="Pago Monto" class="form-control">
                                    <select id="metodo_pago_id" name="metodo_pago_id" class="form-control selectpicker" data-live-search="true"></select>
                                </div>
                            </div>

                            <div class="p-3">
                                <label for="pago_observaciones" class="form-label"><b>OBSERVACIONES PAGO:</b></label>
                                <div class="input-group">
                                    <input type="text" id="pago_observaciones" name="pago_observaciones" placeholder="Observaciones Pago" class="form-control">
                                </div>
                            </div>
                        </div>
                        <!-- Nueva Pestaña Mensualidad -->
                        <div class="tab-pane fade" id="mensualidad-tab-pane" role="tabpanel" aria-labelledby="mensualidad-tab" tabindex="0">
                            <div class="p-3">
                                <label for="mensualidad_monto" class="form-label"><b>MONTO MENSUALIDAD:</b></label>
                                <div class="input-group">
                                    <input type="text" id="mensualidad_monto" name="mensualidad_monto" placeholder="Monto Mensualidad" class="form-control">
                                </div>
                            </div>
                            <div class="p-3">
                                <label for="mensualidad_fecha" class="form-label"><b>FECHA MENSUALIDAD:</b></label>
                                <div class="input-group">
                                    <input type="date" id="mensualidad_fecha" name="mensualidad_fecha" class="form-control">
                                </div>
                            </div>
                            <div class="p-3">
                                <label for="mensualidad_observaciones" class="form-label"><b>OBSERVACIONES MENSUALIDAD:</b></label>
                                <div class="input-group">
                                    <input type="text" id="mensualidad_observaciones" name="mensualidad_observaciones" placeholder="Observaciones Mensualidad" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="otros-tab-pane" role="tabpanel" aria-labelledby="otros-tab" tabindex="0">
                            <div class="p-3">
                                <label for="detalle" class="form-label"><b>DETALLE:</b></label>
                                <div class="input-group">
                                    <input type="text" id="detalle" name="detalle" placeholder="Detalle" class="form-control">
                                </div>
                            </div>

                            <div class="p-3">
                                <label for="matricula_observaciones" class="form-label"><b>OBSERVACIONES MATRÍCULA:</b></label>
                                <div class="input-group">
                                    <input type="text" id="matricula_observaciones" name="matricula_observaciones" placeholder="Observaciones Matrícula" class="form-control">
                                </div>
                            </div>
                        </div>
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
    <script src="MatriculaDetalle.js"></script>
<?php
}
ob_end_flush();
?>
