<?php
require('../../General/fpdf/fpdf.php');
require_once("../Modelo/ReportePersonal.php");

class PDF extends FPDF
{
    protected $fecha_hora_actual;

    function __construct($orientation='P', $unit='mm', $size='A4', $fecha_hora_actual=null)
    {
        parent::__construct($orientation, $unit, $size);
        $this->fecha_hora_actual = $fecha_hora_actual;
    }

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
        $this->SetY(-23);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 5, utf8_decode('(*) Una vez realizado el pago, NO HAY DEVOLUCIONES.'), 0, 1, 'L');
        $this->Cell(0, 5, utf8_decode('Fecha y Hora de generación: ' . $this->fecha_hora_actual), 0, 1, 'L');
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
        $this->SetFont('Arial', '', 10);
        $this->Cell(50, 8, utf8_decode($label), 1);
        $this->Cell(0, 8, utf8_decode($data), 1, 1);
    }

    function SectionDataDOS($label, $data)
    {
        // Datos de sección
        $width = $this->GetPageWidth() - $this->lMargin - $this->rMargin;
        $halfWidth = $width / 2;
        $this->SetFont('Arial', 'B', 11);
        $this->Cell($halfWidth, 8, utf8_decode($label), 0,0,'R');
        $this->SetFont('Arial', '', 11);
        $this->Cell($halfWidth, 8, utf8_decode($data), 0, 1);
    }

    function Recibo($data)
    {
        $this->AddPage();

        // Encabezado con información del colegio
        $this->SetFont('Arial', 'B', 25);
        $this->Cell(0, 9, utf8_decode($data['institucion_nombre']), 0, 1, 'C');
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 4, utf8_decode(''), 0, 1, 'C');
        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 5, utf8_decode($data['institucion_direccion']), 0, 1, 'C');
        $this->Cell(0, 5, utf8_decode('CORREO: '.$data['institucion_correo']), 0, 1, 'C');
        $this->Cell(0, 5, utf8_decode('TELÉFONO: '.$data['institucion_telefono']), 0, 1, 'C');
        $this->Ln(5);

        $this->SetFont('Arial', 'B', 13);
        $this->Cell(0, 7, utf8_decode('MATRÍCULA ALUMNO ' . $data['razon']), 0, 1, 'C');
        $this->Ln(5);

        $this->SetFont('Arial', '', 12);
        $this->Cell(0, 5, utf8_decode('Por medio del siguiente documento yo:'), 0, 1, 'L');
        $this->Ln(4);

        $this->SectionTitle('NOMBRE Y APELLIDO APODERADO(A)');
        $this->SectionData('Nº DNI', $data['apoderado_dni']);
        $this->SectionData('NOMBRE Y APELLIDO', $data['apoderado']);
        $this->SectionData('TELEFONO', $data['apoderado_telefono']);
        $this->Ln(4);

        $this->SetFont('Arial', '', 12);
        $this->Cell(0, 5, utf8_decode('Doy consentimiento a matricular a mi menor hijo(a):'), 0, 1, 'L');
        $this->Ln(4);

        $this->SectionTitle('NOMBRE Y APELLIDO ALUMNO(A)');
        $this->SectionData('Nº DNI', $data['alumno_dni']);
        $this->SectionData('NOMBRE Y APELLIDO', $data['alumno']);
        $this->SectionData('NACIMIENTO', $data['alumno_nacimiento']);
        $this->Ln(4);

        $this->SetFont('Arial', '', 12);
        $this->Cell(0, 5, utf8_decode('En el nivel / grado:'), 0, 1, 'L');
        $this->Ln(4);

        $this->SectionTitle('NIVEL Y GRADO (QUE PASA EN EL '.$data['lectivo'].')');
        $this->SectionData('AÑO LECTIVO', $data['lectivo']);
        $this->SectionData('NIVEL', $data['nivel']);
        $this->SectionData('GRADO', $data['grado']);
        $this->SectionData('TIPO DE ALUMNO', $data['razon']);
        $this->Ln(4);

        
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 30, '', 0, 1, 'C');
        $this->SetY($this->GetY() - 5);
        $this->Cell(0, 5, utf8_decode('_______________________________'), 0, 1, 'C');
        $this->Cell(0, 9, utf8_decode('FIRMA DEL APODERADO(A)'), 0, 1, 'C');
        $this->SetFont('Arial', '', 11);
        $this->Cell(0, 6, utf8_decode($data['apoderado']), 0, 1, 'C');
        $this->Cell(0, 6, utf8_decode($data['apoderado_dni']), 0, 1, 'C');

    }
}

// Obtener el ID de la matrícula desde algún lugar (por ejemplo, una solicitud GET)
$id = $_GET['id'];

// Crear una instancia del modelo y obtener los datos
$modelo = new ReportePersonal();
$result = $modelo->ReciboDePagoMatricula($id);
$data = $result->fetch_assoc();

date_default_timezone_set('America/Lima');
$fecha_hora_actual = date('d/m/Y H:i:s');

$pdf = new PDF('P', 'mm', 'A4', $fecha_hora_actual); // 'P' para orientación vertical (portrait), 'mm' para unidades en milímetros, 'A4' para tamaño de papel A4
$pdf->AliasNbPages();
$pdf->Recibo($data);

$filename = utf8_decode($data['alumno']) . '.pdf';

header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="' . $filename . '"');
header('Cache-Control: private, max-age=0, must-revalidate');
header('Pragma: public');

$pdf->Output('I', $filename);
?>
