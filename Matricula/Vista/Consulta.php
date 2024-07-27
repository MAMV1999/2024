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
        <div class="my-3 p-3 bg-body rounded shadow-sm" id="listadoMatriculados">
            <h5 class="border-bottom pb-2 mb-0"><b>LISTADO</b></h5>
            <div class="p-3">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">MATRICULADOS</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">ALUMNOS</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact-tab-pane" type="button" role="tab" aria-controls="contact-tab-pane" aria-selected="false">APODERADOS Y ALUMNOS</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="disabled-tab" data-bs-toggle="tab" data-bs-target="#disabled-tab-pane" type="button" role="tab" aria-controls="disabled-tab-pane" aria-selected="false">CUMPLEAÑOS</button>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                        <div class="p-3">
                            <table class="table table-bordered" id="matriculadosTable">
                                <thead>
                                    <tr>
                                        <th>LECTIVO</th>
                                        <th>NIVEL</th>
                                        <th>GRADO</th>
                                        <th>CANTIDAD DE MATRICULADOS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Los datos serán llenados por el JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                        <div class="p-3">
                            <table class="table table-bordered" id="myTable">
                                <thead>
                                    <tr>
                                        <th>LECTIVO</th>
                                        <th>NIVEL</th>
                                        <th>GRADO</th>
                                        <th>ALUMNOS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Los datos serán llenados por el JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="contact-tab-pane" role="tabpanel" aria-labelledby="contact-tab" tabindex="0">
                        <div class="p-3">
                            <table class="table table-bordered" id="apoderadosTable">
                                <thead>
                                    <tr>
                                        <th>APODERADO</th>
                                        <th>ALUMNO</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Los datos serán llenados por el JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="disabled-tab-pane" role="tabpanel" aria-labelledby="disabled-tab" tabindex="0">
                        <div class="p-3">
                            <table class="table table-bordered" id="mesesTable">
                                <thead>
                                    <tr>
                                        <th>MES</th>
                                        <th>DÍA</th>
                                        <th>ALUMNOS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Los datos serán llenados por el JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- CUERPO_FIN -->
    </main>
    <?php include "../../General/Include/2_footer.php"; ?>
    <script src="Consulta.js"></script>
<?php
}
ob_end_flush();
?>