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
                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">RECIBO MATRICULA</button>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                        <div class="p-3">
                            <iframe src="../../Reportes/Vista/ReportePersonal.php?id=<?php echo $_GET['id'];?>" type="application/pdf" width="100%" height="1000px"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- CUERPO_FIN -->
    </main>
    <?php include "../../General/Include/2_footer.php"; ?>
<?php
}
ob_end_flush();
?>