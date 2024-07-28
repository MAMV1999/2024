<?php
require('../../General/fpdf/fpdf.php');
require_once("../Modelo/ReportePersonal.php");

class PDF extends FPDF
{
    // Encabezado
    function Header()
    {
        // Título
        // $this->SetFont('Arial', 'B', 20);
        // $this->Cell(0, 9, utf8_decode('RECIBO DE PAGO'), 0, 1, 'C');
        // $this->Ln(2);
    }

    // Pie de página
    function Footer()
    {
        // Posición a 1.5 cm del final
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 5, utf8_decode('(*) Una vez realizado el pago, NO HAY DEVOLUCIONES.'), 0, 1, 'L');
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    function ChapterTitle($label)
    {
        // Título
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, utf8_decode($label), 0, 1, 'C');
        $this->Ln(1);
    }

    function SectionTitle($label)
    {
        // Título de sección
        $this->SetFont('Arial', 'B', 11);
        $this->SetFillColor(188, 188, 188);
        $this->Cell(0, 10, utf8_decode($label), 1, 1, 'L', true);
        $this->Ln(0);
    }

    function SectionData($label, $data)
    {
        // Datos de sección
        $this->SetFont('Arial', '', 11);
        $this->Cell(50, 10, utf8_decode($label), 1);
        $this->Cell(0, 10, utf8_decode($data), 1, 1);
    }

    function Recibo($data)
    {
        $this->AddPage();

        // Encabezado con información del colegio
        $this->SetFont('Arial', 'B', 25);
        $this->Cell(0, 9, utf8_decode($data['institucion_nombre']), 0, 1, 'C');
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 9, utf8_decode('N.º RECIBO ' . $data['numeracion']), 0, 1, 'C');
        $this->SetFont('Arial', '', 11);
        $this->Cell(0, 5, utf8_decode($data['institucion_direccion']), 0, 1, 'C');
        $this->Cell(0, 5, utf8_decode('CORREO: '.$data['institucion_correo']), 0, 1, 'C');
        $this->Cell(0, 5, utf8_decode('TELEFONO: '.$data['institucion_telefono']), 0, 1, 'C');
        $this->Ln(5);

        $this->SetFont('Arial', 'B', 13);
        $this->Cell(0, 7, utf8_decode('MATRÍCULA ' . $data['lectivo']), 0, 1, 'C');
        $this->Cell(0, 7, utf8_decode($data['lectivo'].' / '.$data['nivel'].' / '.$data['grado']), 0, 1, 'C');
        $this->Ln(5);

        // Datos generales
        $this->SectionTitle('MATRÍCULA');
        $this->SectionData('MATRICULADO', $data['nivel'].' / '.$data['grado']);
        $this->SectionData('TIPO DE ALUMNO', $data['razon']);
        $this->Ln(5);

        // Datos del apoderado
        $this->SectionTitle('APODERADO');
        $this->SectionData('DNI', $data['apoderado_dni']);
        $this->SectionData('NOMBRE Y APELLIDO', $data['apoderado']);
        $this->SectionData('TELÉFONO', $data['apoderado_telefono']);
        $this->Ln(5);

        // Datos del alumno
        $this->SectionTitle('ALUMNO');
        $this->SectionData('DNI', $data['alumno_dni']);
        $this->SectionData('NOMBRE Y APELLIDO', $data['alumno']);
        $this->SectionData('NACIMIENTO', $data['alumno_nacimiento']);
        $this->Ln(5);

        // Datos del pago
        $this->SectionTitle('PAGO');
        $this->SectionData('Nº RECIBO', $data['numeracion']);
        $this->SectionData('FECHA', $data['fecha']);
        $this->SectionData('MONTO', 'S/. '.$data['monto'].' - '.$data['metodo']);
        $this->SectionData('OBSERVACIONES', $data['observaciones']);
    }
}

// Obtener el ID de la matrícula desde algún lugar (por ejemplo, una solicitud GET)
$id = $_GET['id'];

// Crear una instancia del modelo y obtener los datos
$modelo = new ReportePersonal();
$result = $modelo->ReciboDePagoMatricula($id);
$data = $result->fetch_assoc();

$pdf = new PDF('P', 'mm', 'A4'); // 'P' para orientación vertical (portrait), 'mm' para unidades en milímetros, 'A4' para tamaño de papel A4
$pdf->AliasNbPages();
$pdf->Recibo($data);

if (isset($_GET['download']) && $_GET['download'] == 'true') {
    // Generar el nombre del archivo utilizando el nombre del alumno
    $filename = utf8_decode($data['alumno']) . '.pdf';
    $pdf->Output('D', $filename);
} else {
    // Visualizar el PDF en el navegador
    $pdf->Output();
}
?>
