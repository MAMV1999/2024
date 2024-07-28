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
            <h5 class="border-bottom pb-2 mb-0"><b>DOCUMENTACION</b></h5>
            <div class="d-flex text-body-secondary pt-3">
                <br>
                <?php
                $array = array(
                    "1" => array("nombre" => "ENTREGA DE DOCUMENTOS", "link" => "DocumentoDetalle.php")
                );
                ?>
                <table class="table">
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
                            echo '<tr>
                                <th scope="row">' . $a . '</th>
                                <td>' . $array[$a]["nombre"] . '</td>
                                <td><a class="btn btn-primary" href="' . $array[$a]["link"] . '" role="button">Ir a ' . $array[$a]["nombre"] . '</a></td>
                            </tr>';
                            $a++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="my-3 p-3 bg-body rounded shadow-sm" id="listado">
            <h5 class="border-bottom pb-2 mb-0"><b>CONFIGURACION</b></h5>
            <div class="d-flex text-body-secondary pt-3">
                <br>
                <?php
                $array = array(
                    "1" => array("nombre" => "CONFIG. DOCUMENTACION", "link" => "Documento.php")
                );
                ?>
                <table class="table">
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
                            echo '<tr>
                                <th scope="row">' . $a . '</th>
                                <td>' . $array[$a]["nombre"] . '</td>
                                <td><a class="btn btn-primary" href="' . $array[$a]["link"] . '" role="button">Ir a ' . $array[$a]["nombre"] . '</a></td>
                            </tr>';
                            $a++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    <?php include "../../General/Include/2_footer.php"; ?>
    <script>
        function init() {
            actualizarFechaHora();
            setInterval(actualizarFechaHora, 1000);
        }

        init();
    </script>
<?php
}
ob_end_flush();
?>