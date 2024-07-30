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
            <h5 class="border-bottom pb-2 mb-0"><b>REPORTES PDF</b></h5>
            <div class="p-3">
                <?php
                $array = array(
                    "1" => array("codigo" => "ReporteMatricula", "nombre" => "MATRICULADOS CANTIDAD"),
                    "2" => array("codigo" => "ReporteMatriculaPagos", "nombre" => "MATRICULADOS PAGOS POR FECHA"),
                    "3" => array("codigo" => "ReporteMatriculaPagos", "nombre" => "MATRICULADOS PAGOS POR GRADO"),
                    "4" => array("codigo" => "ReporteMatriculaPagos", "nombre" => "MATRICULADOS PAGOS POR APODERADO"),
                    "5" => array("codigo" => "ReporteMatriculaListado", "nombre" => "MATRICULADOS LISTADO"),
                    "6" => array("codigo" => "ReportePersonal", "nombre" => "MATRICULADOS APODERADO / ALUMNO"),
                    "7" => array("codigo" => "ReportePersonal", "nombre" => "MATRICULADOS CUMPLEAÑOS")
                );
                ?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">NOMBRE</th>
                            <th scope="col">PAGUINA</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $a = 1;
                        while ($a <= count($array)) {
                            echo '
                                <tr>
                                    <th scope="row">' . $a . '</th>
                                    <th scope="row">' . $array[$a]["nombre"] . '</th>
                                    <td>
                                        <!-- Button trigger modal -->
                                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#' . $array[$a]["codigo"] . '">' . $array[$a]["nombre"] . '</button>
                            
                                        <!-- Modal -->
                                        <div class="modal fade" id="' . $array[$a]["codigo"] . '" tabindex="-1" aria-labelledby="' . $array[$a]["codigo"] . 'Label" aria-hidden="true">
                                            <div class="modal-dialog modal-xl">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="' . $array[$a]["codigo"] . 'Label">' . $array[$a]["nombre"] . '</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <iframe src="../../Reportes/Vista/' . $array[$a]["codigo"] . '.php" type="application/pdf" width="100%" height="600px"></iframe>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">SALIR</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            ';
                            $a++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <br>
        <div class="my-3 p-3 bg-body rounded shadow-sm" id="listadoMatriculados">
            <h5 class="border-bottom pb-2 mb-0"><b>INFORMACION</b></h5>
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