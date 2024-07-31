<?php
require_once("../../General/fpdf/fpdf.php");
require_once("../Modelo/ReporteMatricula.php");

class PDF extends FPDF
{
    // Cabecera de página
    function Header()
    {
        // Arial bold 15
        $this->SetFont('Arial', 'B', 15);
        // Movernos a la derecha
        $this->Cell(80);
        // Título
        $this->Cell(30, 10, 'CANTIDAD DE MATRICULADOS', 0, 1, 'C');
        // Salto de línea
        $this->Ln(20);
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
}

// Crear un objeto de la clase ReporteMatricula
$reporte = new ReporteMatricula();

// Obtener los datos de la función listarMatriculados
$matriculados = $reporte->listarMatriculados();

// Crear un nuevo objeto PDF
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 12);

// Encabezados de la tabla
$pdf->Cell(50, 10, 'LECTIVO', 1, 0, 'C');
$pdf->Cell(50, 10, 'NIVEL', 1, 0, 'C');
$pdf->Cell(50, 10, 'GRADO', 1, 0, 'C');
$pdf->Cell(40, 10, 'ALUMNOS', 1, 0, 'C');
$pdf->Ln();

// Variable para seguimiento de valores anteriores
$prevLectivo = '';
$prevNivel = '';
$prevGrado = '';
$prevLectivoRowCount = 0;
$prevNivelRowCount = 0;
$prevGradoRowCount = 0;

// Array para almacenar los datos temporales
$data = [];
while ($row = $matriculados->fetch_assoc()) {
    $data[] = $row;
}

// Contar las ocurrencias de cada lectivo, nivel y grado
$lectivoCounts = array_count_values(array_column($data, 'lectivo'));
$nivelCounts = [];
$gradoCounts = [];
foreach ($data as $row) {
    $nivelCounts[$row['lectivo']][$row['nivel']] = isset($nivelCounts[$row['lectivo']][$row['nivel']]) ? $nivelCounts[$row['lectivo']][$row['nivel']] + 1 : 1;
    $gradoCounts[$row['lectivo']][$row['nivel']][$row['grado']] = isset($gradoCounts[$row['lectivo']][$row['nivel']][$row['grado']]) ? $gradoCounts[$row['lectivo']][$row['nivel']][$row['grado']] + 1 : 1;
}

// Variable para el total de matriculados
$totalMatriculados = 0;

foreach ($data as $row) {
    // Unificar celda de lectivo
    if ($row['lectivo'] !== $prevLectivo) {
        $prevLectivo = $row['lectivo'];
        $prevLectivoRowCount = $lectivoCounts[$row['lectivo']];
        $pdf->Cell(50, 10 * $prevLectivoRowCount, utf8_decode($row['lectivo']), 1, 0, 'C');
    } else {
        $pdf->Cell(50, 10, '', 0);
    }

    // Unificar celda de nivel
    if ($row['nivel'] !== $prevNivel || $row['lectivo'] !== $prevLectivo) {
        $prevNivel = $row['nivel'];
        $prevNivelRowCount = $nivelCounts[$row['lectivo']][$row['nivel']];
        $pdf->Cell(50, 10 * $prevNivelRowCount, utf8_decode($row['nivel']), 1, 0, 'C');
    } else {
        $pdf->Cell(50, 10, '', 0);
    }

    // Unificar celda de grado
    if ($row['grado'] !== $prevGrado || $row['nivel'] !== $prevNivel || $row['lectivo'] !== $prevLectivo) {
        $prevGrado = $row['grado'];
        $prevGradoRowCount = $gradoCounts[$row['lectivo']][$row['nivel']][$row['grado']];
        $pdf->Cell(50, 10 * $prevGradoRowCount, utf8_decode($row['grado']), 1, 0, 'C');
    } else {
        $pdf->Cell(50, 10, '', 0);
    }

    $pdf->Cell(40, 10, $row['cantidad_matriculados'], 1, 0, 'C');
    $pdf->Ln();

    // Sumar al total de matriculados
    $totalMatriculados += $row['cantidad_matriculados'];
}

// Fila de total
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(150, 10, 'TOTAL', 1, 0, 'C');
$pdf->Cell(40, 10, $totalMatriculados, 1, 0, 'C');
$pdf->Ln();

// Salida del documento
$pdf->Output();
