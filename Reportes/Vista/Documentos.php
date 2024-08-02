<?php
require_once("../../General/fpdf/fpdf.php");
require_once("../Modelo/Documentos.php");

class PDF extends FPDF
{
    // Cabecera de página
    function Header()
    {
        // Arial bold 15
        $this->SetFont('Arial', 'B', 15);
        // Título
        $this->Cell(0, 10, 'Reporte de Documentos', 0, 1, 'C');
        // Salto de línea
        $this->Ln(10);
    }

    // Pie de página
    function Footer()
    {
        // Posición a 1.5 cm del final
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Número de página
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    // Tabla simple
    function BasicTable($header, $data)
    {
        // Cabecera
        foreach ($header as $col) {
            $this->Cell(40, 7, $col, 1);
        }
        $this->Ln();
        // Datos
        foreach ($data as $row) {
            foreach ($row as $col) {
                $this->Cell(40, 6, utf8_decode($col), 1);
            }
            $this->Ln();
        }
    }
}

// Crear un objeto de la clase Documentos
$documentos = new Documentos();

// Obtener el ID del reporte
$id = $_GET['id'];

// Obtener el reporte dinámico
$reporte = $documentos->obtenerReporteDinamico($id);

// Crear un nuevo objeto PDF en orientación vertical
$pdf = new PDF('P', 'mm', 'A4'); // 'P' para orientación vertical
$pdf->AliasNbPages();
$pdf->AddPage();

// Encabezados de la tabla
$header = ['ID', 'Lectivo', 'Nivel', 'Grado', 'Razon', 'Apoderado ID', 'Apoderado Nombre', 'Apoderado Telefono', 'Alumno ID', 'Alumno Nombre'];

// Obtener las columnas dinámicas
$columnasDinamicas = [];
if ($reporte) {
    while ($fila = $reporte->fetch_assoc()) {
        foreach ($fila as $columna => $valor) {
            if (!in_array($columna, $header)) {
                $columnasDinamicas[] = $columna;
            }
        }
        break;
    }
}

// Combinar encabezados fijos y dinámicos
$header = array_merge($header, $columnasDinamicas);

// Reiniciar el puntero del resultado
$reporte->data_seek(0);

// Datos de la tabla
$data = [];
if ($reporte) {
    while ($fila = $reporte->fetch_assoc()) {
        $data[] = $fila;
    }
}

// Generar la tabla en el PDF
$pdf->BasicTable($header, $data);

// Salida del documento
$pdf->Output();
?>
