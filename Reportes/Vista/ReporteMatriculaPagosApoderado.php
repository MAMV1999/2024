<?php
require_once("../../General/fpdf/fpdf.php");
require_once("../Modelo/ReporteMatricula.php");

class PDF extends FPDF
{
    public $totalesMetodosPorDia = [];
    public $currentApoderado = '';

    // Cabecera de página
    function Header()
    {
        // Arial bold 15
        $this->SetFont('Arial','B',15);
        // Título en mayúsculas
        $this->Cell(277,10,utf8_decode(strtoupper('MATRICULADOS PAGOS POR APODERADO')),0,1,'C');
        // Salto de línea
        $this->Ln(10);
        // Establecer tamaño de fuente para el contenido
        $this->SetFont('Arial','',9);
    }

    // Pie de página
    function Footer()
    {
        // Posición a 1.5 cm del final
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Número de página
        $this->Cell(0,10,utf8_decode('Page '.$this->PageNo().'/{nb}'),0,0,'C');
    }

    // Encabezados de la tabla de datos del apoderado
    function ApoderadoHeader()
    {
        $this->SetFont('Arial','B',9);
        $this->SetFillColor(188, 188, 188);
        $this->Cell(277,10,utf8_decode(strtoupper('Datos del Apoderado')),1,0,'C',true);
        $this->Ln();
    }

    // Encabezados de la tabla de alumnos
    function AlumnosHeader()
    {
        $this->SetFont('Arial','B',9);
        $this->SetFillColor(188, 188, 188);
        $this->Cell(138.5,10,utf8_decode(strtoupper('Nombre')),1,0,'C',true);
        $this->Cell(34.625,10,utf8_decode(strtoupper('Numeracion')),1,0,'C',true);
        $this->Cell(34.625,10,utf8_decode(strtoupper('Fecha Pago')),1,0,'C',true);
        $this->Cell(34.625,10,utf8_decode(strtoupper('Monto')),1,0,'C',true);
        $this->Cell(34.625,10,utf8_decode(strtoupper('Metodo Pago')),1,0,'C',true);
        $this->Ln();
    }

    // Encabezados de la tabla de pagos por método
    function PagosHeader()
    {
        $this->SetFont('Arial','B',9);
        $this->Cell(69.25,10,utf8_decode(strtoupper('Metodo de Pago')),1,0,'C',true);
        $this->Cell(69.25,10,utf8_decode(strtoupper('Total Pagado')),1,0,'C',true);
        $this->Ln();
    }

    // Filas de la tabla de datos del apoderado
    function ApoderadoRow($row)
    {
        $this->SetFont('Arial','',9);
        $this->Cell(277,7,utf8_decode($row['apoderado']),1);
        $this->Ln();
    }

    // Filas de la tabla de alumnos
    function AlumnosRow($row)
    {
        $this->SetFont('Arial','',9);
        $this->Cell(138.5,7,utf8_decode($row['alumno']),1);
        $this->Cell(34.625,7,utf8_decode($row['numeracion']),1);
        $this->Cell(34.625,7,utf8_decode($row['pago_fecha']),1);
        $this->Cell(34.625,7,utf8_decode($row['pago_monto']),1);
        $this->Cell(34.625,7,utf8_decode($row['metodo_pago']),1);
        $this->Ln();
    }

    // Filas de la tabla de pagos por método
    function PagosRow($metodo, $total)
    {
        $this->SetFont('Arial','',9);
        $this->Cell(69.25,7,utf8_decode($metodo),1,0,'L');
        $this->Cell(69.25,7,number_format($total, 2),1,0,'L');
        $this->Ln();
    }

    // Tabla de totales por método de pago
    function TotalesMetodosPago()
    {
        $this->Ln(10); // Salto de línea
        $this->PagosHeader();
        $totalGeneral = 0;
        foreach ($this->totalesMetodosPorDia as $metodo => $total) {
            $this->PagosRow($metodo, $total);
            $totalGeneral += $total;
        }

        // Agregar fila total
        $this->SetFont('Arial','B',9);
        $this->SetFillColor(188, 188, 188);
        $this->Cell(69.25,7,utf8_decode(strtoupper('Total')),1,0,'L',true);
        $this->Cell(69.25,7,number_format($totalGeneral, 2),1,0,'L',true);
        $this->Ln();
    }

    // Función para contar el número de líneas necesarias para el texto
    function NbLines($w, $txt)
    {
        // Calcular el número de líneas necesarias para el texto
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 && $s[$nb - 1] == "\n")
            $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c == ' ')
                $sep = $i;
            $l += $cw[$c];
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j)
                        $i++;
                } else
                    $i = $sep + 1;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else
                $i++;
        }
        return $nl;
    }
}

// Crear un objeto de la clase ReporteMatricula
$reporte = new ReporteMatricula();

// Obtener los datos de la función matriculadospagos_apoderado
$datos = $reporte->matriculadospagos_apoderado();

// Crear un nuevo objeto PDF con orientación horizontal
$pdf = new PDF('L', 'mm', 'A4'); // 'L' para landscape (horizontal)
$pdf->AliasNbPages();

// Inicializar la variable para el apoderado actual
$apoderadoActual = '';

while ($row = $datos->fetch_assoc()) {
    // Si el apoderado cambia, agregar una nueva página
    if ($row['apoderado'] !== $apoderadoActual) {
        if ($pdf->PageNo() > 0) {
            // Agregar la tabla de totales antes de cambiar de apoderado
            $pdf->TotalesMetodosPago();
            $pdf->totalesMetodosPorDia = [];
            $pdf->AddPage();
        } else {
            $pdf->AddPage();
        }
        $pdf->ApoderadoHeader();
        $pdf->ApoderadoRow($row);
        $pdf->Ln(10); // Salto de línea
        $pdf->AlumnosHeader();
    }

    // Añadir fila de la tabla de alumnos
    $pdf->AlumnosRow($row);

    // Sumar el monto al total del método de pago para el apoderado actual
    if (!isset($pdf->totalesMetodosPorDia[$row['metodo_pago']])) {
        $pdf->totalesMetodosPorDia[$row['metodo_pago']] = 0;
    }
    $pdf->totalesMetodosPorDia[$row['metodo_pago']] += $row['pago_monto'];

    $apoderadoActual = $row['apoderado'];
}

// Agregar la tabla de totales para el último apoderado
if (!empty($apoderadoActual)) {
    $pdf->TotalesMetodosPago();
}

// Salida del documento
$pdf->Output();
?>
