<?php
require_once("../../General/fpdf/fpdf.php");
require_once("../Modelo/ReporteMatricula.php");

class PDF extends FPDF
{
    // Cabecera de página
    function Header()
    {
        // Arial bold 15
        $this->SetFont('Arial','B',15);
        // Movernos a la derecha
        $this->Cell(80);
        // Título
        $this->Cell(30,10,'LISTADO DE MATRICULADOS',0,1,'C');
        // Salto de línea
        $this->Ln(10);
    }

    // Pie de página
    function Footer()
    {
        // Posición a 1.5 cm del final
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Número de página
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }

    // Título de capítulo
    function ChapterTitle($lectivo, $nivel, $grado)
    {
        // Arial 12
        $this->SetFont('Arial','B',12);
        // Color de fondo
        $this->SetFillColor(188,188,188);
        // Ancho total de la página
        $totalWidth = $this->GetPageWidth() - 20; // considerando márgenes de 10 en cada lado
        // Ancho de cada celda
        $cellWidth = $totalWidth / 3;

        // Título
        $this->Cell($cellWidth,9,utf8_decode('AÑO LECTIVO ').utf8_decode($lectivo),1,0,'C',true);
        $this->Cell($cellWidth,9,utf8_decode($nivel),1,0,'C',true);
        $this->Cell($cellWidth,9,utf8_decode($grado),1,1,'C',true);
        // Salto de línea
        $this->Ln(10);
    }

    // Encabezados de la tabla (ahora listado)
    function TableHeader()
    {
        $this->SetFont('Arial','B',12);
        $this->Cell(10,10,'#',0);
        $this->Cell(180,10,'APELLIDO Y NOMBRE',0);
        $this->Ln();
    }

    // Filas de la tabla (ahora listado)
    function TableRow($row, $num)
    {
        $this->SetFont('Arial','',12);
        $this->Cell(10,10,$num,0);
        $this->Cell(180,10,utf8_decode($row['alumno']),0);
        $this->Ln();
    }
}

// Crear un objeto de la clase ReporteMatricula
$reporte = new ReporteMatricula();

// Obtener los datos de la función listar
$datos = $reporte->listar();

// Crear un nuevo objeto PDF
$pdf = new PDF();
$pdf->AliasNbPages();

// Variables para seguimiento del grado actual
$currentGrado = '';
$currentNivel = '';
$currentLectivo = '';
$counter = 1;

while ($row = $datos->fetch_assoc()) {
    // Si el grado cambia, crear una nueva página
    if ($row['grado'] !== $currentGrado || $row['nivel'] !== $currentNivel || $row['lectivo'] !== $currentLectivo) {
        // Añadir una nueva página
        $pdf->AddPage();
        // Actualizar el grado, nivel y lectivo actuales
        $currentGrado = $row['grado'];
        $currentNivel = $row['nivel'];
        $currentLectivo = $row['lectivo'];
        // Añadir título del capítulo
        $pdf->ChapterTitle($row['lectivo'], $row['nivel'], $row['grado']);
        // Añadir encabezados de la tabla
        $pdf->TableHeader();
        // Reiniciar el contador
        $counter = 1;
    }
    // Añadir fila de la tabla
    $pdf->TableRow($row, $counter);
    $counter++;
}

// Salida del documento
$pdf->Output();
?>
