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
                    "1" => array("codigo" => "ReportePersonal", "nombre" => "RECIBO DE MATRICULA"),
                    "2" => array("codigo" => "InformacionMatricula", "nombre" => "INFORMACION DE MATRICULA")
                );
                ?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">NOMBRE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $a = 1;
                        while ($a <= count($array)) {
                            echo '
                                <tr>
                                    <th scope="row">' . $a . '</th>
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
                                                        <iframe src="../../Reportes/Vista/' . $array[$a]["codigo"] . '.php?id=' . $_GET['id'] . '" type="application/pdf" width="100%" height="600px"></iframe>
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
        <!-- CUERPO_FIN -->
    </main>
    <?php include "../../General/Include/2_footer.php"; ?>
<?php
}
ob_end_flush();
?>