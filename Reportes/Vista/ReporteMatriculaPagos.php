<?php
require_once("../../General/fpdf/fpdf.php");
require_once("../Modelo/ReporteMatricula.php");

class PDF extends FPDF
{
    public $totalesMetodosPorDia = [];
    public $currentFecha = '';

    // Cabecera de página
    function Header()
    {
        // Arial bold 15
        $this->SetFont('Arial','B',15);
        // Título en mayúsculas
        $this->Cell(277,10,strtoupper('MATRICULADOS PAGOS POR FECHA'),0,1,'C');
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
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }

    // Encabezados de la tabla
    function TableHeader()
    {
        $this->SetFont('Arial','B',9);
        $this->Cell(74.79,10,strtoupper('Apoderado'),1,0,'C');
        $this->Cell(97,10,strtoupper('Alumno'),1,0,'C');
        $this->Cell(25.2,10,strtoupper('Numeracion'),1,0,'C');
        $this->Cell(25.2,10,strtoupper('Fecha Pago'),1,0,'C');
        $this->Cell(16.94,10,strtoupper('Monto'),1,0,'C');
        $this->Cell(37.5,10,strtoupper('Metodo Pago'),1,0,'C');
        $this->Ln();
    }

    // Filas de la tabla
    function TableRow($row)
    {
        $this->SetFont('Arial','',9);

        // Obtener la altura máxima de la fila
        $maxHeight = max(
            $this->NbLines(74.79, utf8_decode($row['apoderado'])),
            $this->NbLines(97, utf8_decode($row['alumno'])),
            $this->NbLines(25.2, utf8_decode($row['numeracion'])),
            $this->NbLines(25.2, $row['pago_fecha']),
            $this->NbLines(16.94, $row['pago_monto']),
            $this->NbLines(37.5, utf8_decode($row['metodo_pago']))
        ) * 5;

        // Asegurarnos de que cada celda tenga la misma altura
        $x = $this->GetX();
        $y = $this->GetY();
        $this->MultiCell(74.79,5,utf8_decode($row['apoderado']),1,'L',false);
        $y1 = $this->GetY();
        $this->SetXY($x + 74.79, $y);

        $this->MultiCell(97,5,utf8_decode($row['alumno']),1,'L',false);
        $y2 = $this->GetY();
        $this->SetXY($x + 171.79, $y);

        $this->MultiCell(25.2,5,utf8_decode($row['numeracion']),1,'L',false);
        $y3 = $this->GetY();
        $this->SetXY($x + 196.99, $y);

        $this->MultiCell(25.2,5,$row['pago_fecha'],1,'L',false);
        $y4 = $this->GetY();
        $this->SetXY($x + 222.19, $y);

        $this->MultiCell(16.94,5,$row['pago_monto'],1,'L',false);
        $y5 = $this->GetY();
        $this->SetXY($x + 239.13, $y);

        $this->MultiCell(37.5,5,utf8_decode($row['metodo_pago']),1,'L',false);
        $y6 = $this->GetY();
        $this->SetXY($x + 276.63, $y);

        $this->Ln(max($y1, $y2, $y3, $y4, $y5, $y6) - $y);

        // Sumar el monto al total del método de pago para la fecha actual
        if (!isset($this->totalesMetodosPorDia[$this->currentFecha])) {
            $this->totalesMetodosPorDia[$this->currentFecha] = [];
        }
        if (!isset($this->totalesMetodosPorDia[$this->currentFecha][$row['metodo_pago']])) {
            $this->totalesMetodosPorDia[$this->currentFecha][$row['metodo_pago']] = 0;
        }
        $this->totalesMetodosPorDia[$this->currentFecha][$row['metodo_pago']] += $row['pago_monto'];
    }

    // Función para agregar una tabla de totales por método de pago
    function TotalesMetodosPago()
    {
        $this->Ln(10); // Salto de línea
        $this->SetFont('Arial','B',9);
        $this->Cell(138.5,10,strtoupper('Totales por Metodo de Pago'),1,1,'C');
        $this->SetFont('Arial','',9);

        $totalGeneral = 0;
        if (isset($this->totalesMetodosPorDia[$this->currentFecha])) {
            foreach ($this->totalesMetodosPorDia[$this->currentFecha] as $metodo => $total) {
                $this->Cell(101.5,7,utf8_decode($metodo),1,0,'L');
                $this->Cell(37,7,number_format($total, 2),1,1,'R');
                $totalGeneral += $total;
            }
        }

        // Agregar fila total
        $this->SetFont('Arial','B',9);
        $this->Cell(101.5,7,strtoupper('Total'),1,0,'L');
        $this->Cell(37,7,number_format($totalGeneral, 2),1,1,'R');
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

// Obtener los datos de la función matriculadospagos
$datos = $reporte->matriculadospagos();

// Crear un nuevo objeto PDF con orientación horizontal
$pdf = new PDF('L', 'mm', 'A4'); // 'L' para landscape (horizontal)
$pdf->AliasNbPages();

// Inicializar la variable para la fecha actual
$fechaActual = '';

while ($row = $datos->fetch_assoc()) {
    // Asegurarse de que haya una página antes de agregar contenido
    if ($pdf->PageNo() == 0) {
        $pdf->AddPage();
        $pdf->TableHeader();
    }

    // Si la fecha de pago cambia y no es la primera iteración, agregar la tabla de totales y luego iniciar una nueva página
    if ($row['pago_fecha'] !== $fechaActual && $fechaActual !== '') {
        $pdf->TotalesMetodosPago();
        $pdf->totalesMetodosPorDia = [];
        $pdf->AddPage();
        $pdf->TableHeader();
    }

    // Añadir fila de la tabla
    $pdf->TableRow($row);

    $fechaActual = $row['pago_fecha'];
}

// Agregar la tabla de totales para el último día
if (!empty($fechaActual)) {
    $pdf->TotalesMetodosPago();
}

// Salida del documento
$pdf->Output();
?>
