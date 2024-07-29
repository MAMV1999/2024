<?php
require('../../General/fpdf/fpdf.php');
require_once("../Modelo/InformeTrabajador.php");

class PDF extends FPDF
{
    protected $fecha_hora_actual;

    function __construct($orientation = 'P', $unit = 'mm', $size = 'A4', $fecha_hora_actual = null)
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
        $this->Cell(0, 5, utf8_decode('Fecha y Hora de generación: ' . $this->fecha_hora_actual), 0, 1, 'C');
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

    function Recibo($data)
    {
        $this->AddPage();

        // Encabezado con información del colegio
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(0, 10, utf8_decode($data['nombre_apellido']), 1, 1, 'C');
        $this->SetFont('Arial', '', 11);
        $this->SetFillColor(188, 188, 188);
        $this->Cell(0, 7, utf8_decode('DATOS PERSONALES'), 1, 1, 'C', true);
        $this->Ln(5);

        // INFORMACION
        $this->SectionTitle('ACCESO AL SISTEMA');
        $this->SectionData('USUARIO', $data['usuario']);
        $this->SectionData('CONTRASEÑA', $data['contraseña']);
        $this->Ln(5);

        // INFORMACION
        $this->SectionTitle('INFORMACION PERSONAL');
        $this->SectionData('Nº DNI', $data['dni']);
        $this->SectionData('NOMBRE Y APELLIDO', $data['nombre_apellido']);
        $this->SectionData('NACIMIENTO', $data['nacimiento'] . ' - ' . $data['edad'] . ' AÑOS');
        $this->SectionData('SEXO', $data['sexo']);
        $this->SectionData('ESTADO CIVIL', $data['estado_civil']);
        $this->Ln(5);

        // INFORMACION
        $this->SectionTitle('INFORMACION DE CONTACTO');
        $this->SectionData('TELEFONO', $data['telefono']);
        $this->SectionData('CORREO', $data['correo']);
        $this->SectionData('DIRECCION', $data['direccion']);
        $this->Ln(5);

        // INFORMACION
        $this->SectionTitle('INFORMACION BANCARIA BCP');
        $this->SectionData('Nº DE CUENTA', $data['cuenta_bcp']);
        $this->SectionData('Nº INTERBANCARIO', $data['interbancario_bcp']);
        $this->Ln(5);

        // INFORMACION
        $this->SectionTitle('INFORMACION TRIBUTARIA SUNAT');
        $this->SectionData('RUC', $data['sunat_ruc']);
        $this->SectionData('USUARIO', $data['sunat_usuario']);
        $this->SectionData('CONTRASEÑA', $data['sunat_contraseña']);
        $this->Ln(5);
    }
}

// Obtener el ID de la matrícula desde algún lugar (por ejemplo, una solicitud GET)
$id = $_GET['id'];

// Crear una instancia del modelo y obtener los datos
$modelo = new Trabajador();
$result = $modelo->listarTrabajadorPorId($id);
$data = $result->fetch_assoc();

date_default_timezone_set('America/Lima');
$fecha_hora_actual = date('d/m/Y H:i:s');

$pdf = new PDF('P', 'mm', 'A4', $fecha_hora_actual); // 'P' para orientación vertical (portrait), 'mm' para unidades en milímetros, 'A4' para tamaño de papel A4
$pdf->AliasNbPages();
$pdf->Recibo($data);

$filename = utf8_decode($data['nombre_apellido']) . '.pdf';

header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="' . $filename . '"');
header('Cache-Control: private, max-age=0, must-revalidate');
header('Pragma: public');

$pdf->Output('I', $filename);
